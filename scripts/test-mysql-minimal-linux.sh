#!/usr/bin/env bash
set -euo pipefail
source "$(dirname -- "${BASH_SOURCE[0]}")/common-linux.sh"
command -v python3 >/dev/null
require_docker
umask 077
record="$(mktemp -d "$EVENTIFY_ROOT/storage/mysql-minimal-XXXXXXXX")"
name="eventify-$(basename "$record" | tr '[:upper:]' '[:lower:]')"
version="$(cat VERSION)"
[[ "$version" =~ ^[0-9]+\.[0-9]+\.[0-9]+-dev\.[0-9]+$ ]] || exit 1
base="$name-base:$version"
candidate="$name-candidate:$version"
web="$name-tests:$version"
unset COMPOSE_FILE COMPOSE_PROJECT_NAME COMPOSE_PROFILES
printf '' > "$record/empty.env"
git rev-parse HEAD > "$record/commit.txt"
git status --porcelain > "$record/working-tree.txt"
date -u +%FT%TZ > "$record/status.txt"
compose=("${EVENTIFY_DOCKER[@]}" compose --project-name "$name" --env-file "$record/empty.env" -f "$record/compose.json")

finish() {
    local result=$?
    trap - EXIT
    if [[ -f "$record/compose.json" ]]; then
        "${compose[@]}" down --timeout 20 >> "$record/cleanup.log" 2>&1 || result=1
    fi
    printf 'exit_code=%s\n' "$result" >> "$record/status.txt"
    tail -n 20 "$record/test.log" 2>/dev/null || true
    printf 'Registro privado: %s\n' "$record"
    exit "$result"
}
trap finish EXIT

verify_candidate() {
    "${EVENTIFY_DOCKER[@]}" build -f Dockerfile.mysql --target mysql-with-shell -t "$base" .
    "${EVENTIFY_DOCKER[@]}" run --rm --network none --entrypoint rpm "$base" -qa --qf '%{NAME}\n' | sort > "$record/before.txt"
    "${EVENTIFY_DOCKER[@]}" build -f docker/Dockerfile.mysql-minimal-check --build-arg "EVENTIFY_MYSQL_BASE=$base" -t "$candidate" docker
    "${EVENTIFY_DOCKER[@]}" run --rm --network none --entrypoint rpm "$candidate" -qa --qf '%{NAME}\n' | sort > "$record/after.txt"
    python3 - "$record" <<'PY'
from pathlib import Path
import sys
record = Path(sys.argv[1])
before = (record / 'before.txt').read_text().splitlines()
after = (record / 'after.txt').read_text().splitlines()
assert before.count('mysql-shell') == 1
assert after == [name for name in before if name != 'mysql-shell']
print('Inventario RPM: solo se retiró mysql-shell.')
PY
    "${EVENTIFY_DOCKER[@]}" build -f Dockerfile -t "$web" .
    python3 - "$record" "$name" "$candidate" "$web" <<'PY'
import json, secrets, sys
from pathlib import Path
record, project, database_image, test_image = sys.argv[1:]
password = secrets.token_hex(32)
config = {
    'name': project,
    'services': {
        'db': {
            'image': database_image,
            'environment': {
                'MYSQL_DATABASE': 'eventify_test', 'MYSQL_USER': 'eventify_test',
                'MYSQL_PASSWORD': password, 'MYSQL_ROOT_PASSWORD': secrets.token_hex(32),
            },
            'command': ['--character-set-server=utf8mb4', '--collation-server=utf8mb4_0900_ai_ci', '--default-time-zone=+00:00'],
            'tmpfs': ['/var/lib/mysql'],
            'healthcheck': {
                'test': ['CMD-SHELL', 'MYSQL_PWD="$${MYSQL_PASSWORD}" mysql --user="$${MYSQL_USER}" --database="$${MYSQL_DATABASE}" --execute="SELECT 1" --silent >/dev/null 2>&1'],
                'interval': '5s', 'timeout': '5s', 'retries': 30, 'start_period': '30s',
            },
            'networks': ['isolated'],
        },
        'tests': {
            'image': test_image, 'entrypoint': ['php'],
            'command': ['tests/integration/database.php'],
            'environment': {
                'APP_ENV': 'test', 'WEATHER_ENABLED': 'false',
                'APP_SECRET': secrets.token_hex(32), 'DB_HOST': 'db', 'DB_PORT': '3306',
                'DB_DATABASE': 'eventify_test', 'DB_USERNAME': 'eventify_test', 'DB_PASSWORD': password,
            },
            'depends_on': {'db': {'condition': 'service_healthy'}},
            'networks': ['isolated'],
        },
    },
    'networks': {'isolated': {'internal': True}},
}
assert project.startswith('eventify-mysql-minimal-')
assert all(not service.get('ports') and not service.get('volumes') and not service.get('privileged') for service in config['services'].values())
Path(record, 'compose.json').write_text(json.dumps(config))
PY
    # --env-file y -f explícitos aíslan el ensayo de .env y del proyecto normal.
    "${compose[@]}" config --quiet
    "${compose[@]}" up --no-build --abort-on-container-exit --exit-code-from tests
    "${compose[@]}" up --no-build --detach --wait --wait-timeout 240 db
    # Respaldo/restauración de una tabla ficticia en la base efímera del ensayo.
    "${compose[@]}" exec -T db sh -c 'MYSQL_PWD="$MYSQL_PASSWORD" mysql --user="$MYSQL_USER" --database="$MYSQL_DATABASE"' <<'SQL'
CREATE TABLE eventify_backup_probe (id INT PRIMARY KEY, label VARCHAR(40) NOT NULL);
INSERT INTO eventify_backup_probe VALUES (1, 'respaldo'), (2, 'restauracion');
SQL
    "${compose[@]}" exec -T db sh -c 'MYSQL_PWD="$MYSQL_PASSWORD" mysqldump --user="$MYSQL_USER" --single-transaction --quick --no-tablespaces --set-gtid-purged=OFF "$MYSQL_DATABASE" eventify_backup_probe' > "$record/probe.sql"
    "${compose[@]}" exec -T db sh -c 'MYSQL_PWD="$MYSQL_PASSWORD" mysql --user="$MYSQL_USER" --database="$MYSQL_DATABASE" --execute="DROP TABLE eventify_backup_probe"'
    "${compose[@]}" exec -T db sh -c 'MYSQL_PWD="$MYSQL_PASSWORD" mysql --user="$MYSQL_USER" --database="$MYSQL_DATABASE"' < "$record/probe.sql"
    local restored
    restored="$("${compose[@]}" exec -T db sh -c 'MYSQL_PWD="$MYSQL_PASSWORD" mysql --user="$MYSQL_USER" --database="$MYSQL_DATABASE" --batch --skip-column-names --execute="SELECT id, label FROM eventify_backup_probe ORDER BY id"')"
    [[ "$restored" == $'1\trespaldo\n2\trestauracion' ]] || { echo 'No se comprobó el respaldo restaurado.' >&2; return 1; }
    "${EVENTIFY_DOCKER[@]}" image inspect --format '{{.Id}}' "$candidate" > "$record/image-id.txt"
    "${EVENTIFY_DOCKER[@]}" image save "$candidate" > "$record/mysql.tar"
    echo 'Ensayo MySQL sin Shell: inventario, integración y respaldo/restauración aprobados.'
    echo 'Pendientes: escaneo, TLS y aceptación antes de modificar la imagen de entrega.'
}

# El registro conserva errores sin imprimir configuraciones o contraseñas.
verify_candidate > "$record/test.log" 2>&1
