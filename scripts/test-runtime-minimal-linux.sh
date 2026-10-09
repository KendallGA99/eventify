#!/usr/bin/env bash
set -euo pipefail
source "$(dirname -- "${BASH_SOURCE[0]}")/common-linux.sh"
require_docker
command -v python3 >/dev/null
umask 077
record="$(mktemp -d "$EVENTIFY_ROOT/storage/runtime-check-XXXXXXXX")"
name="eventify-$(basename "$record" | tr '[:upper:]' '[:lower:]')"
version="$(cat VERSION)"
[[ "$version" =~ ^[0-9]+\.[0-9]+\.[0-9]+-dev\.[0-9]+$ ]] || exit 1
unset COMPOSE_FILE COMPOSE_PROJECT_NAME COMPOSE_PROFILES
printf '' > "$record/empty.env"
git rev-parse HEAD > "$record/commit.txt"
git status --porcelain > "$record/working-tree.txt"
date -u +%FT%TZ > "$record/status.txt"
compose=("${EVENTIFY_DOCKER[@]}" compose --project-name "$name" --env-file "$record/empty.env" -f "$record/compose.json")
cleanup() {
    if [[ -f "$record/compose.json" ]]; then
        "${compose[@]}" down --timeout 20 >> "$record/cleanup.log" 2>&1
    fi
}
trap cleanup EXIT
verify_runtime() {
    local kind file base reduced test
    "${EVENTIFY_DOCKER[@]}" build -f Dockerfile.mysql -t "$name-db:$version" .
    for kind in local public; do
        file=Dockerfile
        [[ "$kind" != public ]] || file=Dockerfile.public
        base="$name-$kind-base:$version"
        reduced="$name-$kind-minimal:$version"
        "${EVENTIFY_DOCKER[@]}" build -f "$file" -t "$base" .
        "${EVENTIFY_DOCKER[@]}" run --rm --network none --entrypoint dpkg-query "$base" -W > "$record/$kind-before.txt"
        "${EVENTIFY_DOCKER[@]}" build -f docker/Dockerfile.runtime-check --build-arg "EVENTIFY_BASE_IMAGE=$base" -t "$reduced" docker
        "${EVENTIFY_DOCKER[@]}" run --rm --network none --entrypoint dpkg-query "$reduced" -W > "$record/$kind-after.txt"
        "${EVENTIFY_DOCKER[@]}" run --rm --network none --entrypoint apache2ctl "$reduced" -M > "$record/$kind-modules.txt" 2>&1
        "${EVENTIFY_DOCKER[@]}" run --rm --network none --entrypoint php "$reduced" bin/lint.php
        for test in tests/unit/*.php; do
            "${EVENTIFY_DOCKER[@]}" run --rm --network none --entrypoint php "$reduced" "$test"
        done
        python3 - "$record" "$name" "$version" "$reduced" <<'PY'
import json, secrets, sys
from pathlib import Path
record, name, version, image = sys.argv[1:]
password = secrets.token_hex(32)
config = {
    'services': {
        'tests': {
            'image': image, 'entrypoint': ['php'],
            'command': ['tests/integration/database.php'],
            'environment': {
                'APP_ENV': 'test', 'WEATHER_ENABLED': 'false',
                'APP_SECRET': secrets.token_hex(32), 'DB_HOST': 'db',
                'DB_PORT': '3306', 'DB_DATABASE': 'eventify_test',
                'DB_USERNAME': 'eventify_test', 'DB_PASSWORD': password,
            },
            'depends_on': {'db': {'condition': 'service_healthy'}},
            'networks': ['isolated'],
        },
        'db': {
            'image': name + '-db:' + version,
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
    },
    'networks': {'isolated': {'internal': True}},
}
assert 'volumes' not in config
assert all('ports' not in service and 'volumes' not in service and 'privileged' not in service for service in config['services'].values())
Path(record, 'compose.json').write_text(json.dumps(config))
PY
        "${compose[@]}" up --no-build --abort-on-container-exit --exit-code-from tests
        "${compose[@]}" down --timeout 20
        "${EVENTIFY_DOCKER[@]}" image inspect --format '{{.Id}}' "$reduced" > "$record/$kind-image-id.txt"
        "${EVENTIFY_DOCKER[@]}" image save "$reduced" > "$record/$kind.tar"
    done
}
set +e
( set -e; verify_runtime ) > "$record/test.log" 2>&1
result=$?
set -e
printf 'exit_code=%s\n' "$result" >> "$record/status.txt"
tail -n 20 "$record/test.log"
printf 'Registro privado: %s\n' "$record"
exit "$result"
