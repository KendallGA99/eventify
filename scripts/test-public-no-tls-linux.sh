#!/usr/bin/env bash
set -euo pipefail
source "$(dirname -- "${BASH_SOURCE[0]}")/common-linux.sh"
umask 077
command -v python3 >/dev/null
command -v openssl >/dev/null
command -v timeout >/dev/null
require_docker
EVENTIFY_NO_TLS_RUN="$(mktemp -d "$EVENTIFY_ROOT/storage/public-no-tls-XXXXXXXX")"
EVENTIFY_NO_TLS_PROJECT="eventify-no-tls-$(openssl rand -hex 6)"
EVENTIFY_NO_TLS_STARTED=false
date -u +'%Y-%m-%dT%H:%M:%SZ' > "$EVENTIFY_NO_TLS_RUN/status.txt"
printf '%s\n' "$EVENTIFY_NO_TLS_PROJECT" > "$EVENTIFY_NO_TLS_RUN/project.txt"
trap 'EVENTIFY_NO_TLS_RESULT=$?; printf "exit_code=%s\n" "$EVENTIFY_NO_TLS_RESULT" >> "$EVENTIFY_NO_TLS_RUN/status.txt"' EXIT
exec > >(tee "$EVENTIFY_NO_TLS_RUN/test.log") 2>&1
python3 scripts/make-tls-fixture.py "$EVENTIFY_NO_TLS_RUN/certs"
{
    printf 'APP_SECRET=%s\nMYSQL_PASSWORD=%s\nMYSQL_ROOT_PASSWORD=%s\n' "$(openssl rand -hex 32)" "$(openssl rand -hex 32)" "$(openssl rand -hex 32)"
    printf 'EVENTIFY_TLS_DIR="%s"\n' "$EVENTIFY_NO_TLS_RUN/certs"
} > "$EVENTIFY_NO_TLS_RUN/fixture.env"
unset APP_SECRET MYSQL_PASSWORD MYSQL_ROOT_PASSWORD EVENTIFY_TLS_DIR COMPOSE_FILE COMPOSE_PROJECT_NAME
EVENTIFY_NO_TLS_COMPOSE=("${EVENTIFY_DOCKER[@]}" compose --env-file "$EVENTIFY_NO_TLS_RUN/fixture.env" -p "$EVENTIFY_NO_TLS_PROJECT" -f "$EVENTIFY_ROOT/docker-compose.no-tls-test.yml")
finish_no_tls() {
    local result=$?
    trap - EXIT
    if [[ "$EVENTIFY_NO_TLS_STARTED" == true ]]; then
        "${EVENTIFY_NO_TLS_COMPOSE[@]}" down || result=1
    fi
    printf 'exit_code=%s\n' "$result" >> "$EVENTIFY_NO_TLS_RUN/status.txt"
    echo "Registro privado: ${EVENTIFY_NO_TLS_RUN#"$EVENTIFY_ROOT/"}"
    exit "$result"
}
trap finish_no_tls EXIT
"${EVENTIFY_NO_TLS_COMPOSE[@]}" config --format json | python3 -c '
import json,sys
c=json.load(sys.stdin)
assert c["name"]==sys.argv[1]
assert set(c["services"])=={"tests","db"}
assert not c.get("volumes")
assert all(not s.get("ports") for s in c["services"].values())
assert c["networks"]["isolated"]["internal"]
assert c["services"]["db"]["tmpfs"]==["/var/lib/mysql"]
assert "--tls-version=" in c["services"]["db"]["command"]
assert not c["services"]["db"].get("volumes")
assert c["services"]["tests"]["environment"]["DB_DATABASE"]=="eventify_no_tls_test"
assert c["services"]["tests"]["environment"]["APP_ENV"]=="production"
assert c["services"]["tests"]["environment"]["DB_TLS_MODE"]=="verify_identity"
assert all(v["type"]=="bind" and v["source"].startswith(sys.argv[2]+"/") and v["read_only"] for s in c["services"].values() for v in s.get("volumes",[]))
' "$EVENTIFY_NO_TLS_PROJECT" "$EVENTIFY_NO_TLS_RUN"
"${EVENTIFY_NO_TLS_COMPOSE[@]}" build tests
EVENTIFY_NO_TLS_STARTED=true
"${EVENTIFY_NO_TLS_COMPOSE[@]}" up --detach --wait --wait-timeout 240 db
"${EVENTIFY_NO_TLS_COMPOSE[@]}" run --rm --no-deps -T tests -l tests/integration/public-no-tls.php
"${EVENTIFY_NO_TLS_COMPOSE[@]}" run --rm --no-deps -T tests tests/integration/public-no-tls.php
# El arranque real debe fallar en migración y nunca llegar a ejecutar Apache.
EVENTIFY_NO_TLS_REJECTION=0
timeout 60s "${EVENTIFY_NO_TLS_COMPOSE[@]}" run --rm --no-deps -T --entrypoint /usr/local/bin/eventify-public-entrypoint tests apache2-foreground > "$EVENTIFY_NO_TLS_RUN/rejection.log" 2>&1 || EVENTIFY_NO_TLS_REJECTION=$?
if [[ "$EVENTIFY_NO_TLS_REJECTION" != 1 ]]; then
    echo 'El arranque no produjo el rechazo esperado (o agotó el tiempo).' >&2
    exit 1
fi
python3 - "$EVENTIFY_NO_TLS_RUN/rejection.log" <<'PY'
from pathlib import Path
import sys
text = Path(sys.argv[1]).read_text()
assert 'Configuración pública validada' in text
assert 'Syntax OK' in text
assert ('No fue posible conectar o preparar la base de datos.' in text
        or 'La conexión de base de datos requiere TLS.' in text)
assert 'Migraciones aplicadas:' not in text
assert 'AH00489' not in text and 'resuming normal operations' not in text
print('Arranque público rechazado antes de servir o aplicar migraciones.')
PY
"${EVENTIFY_NO_TLS_COMPOSE[@]}" run --rm --no-deps -T tests tests/integration/public-no-tls.php
echo 'Servidor sin TLS rechazado; controles TCP y base vacía aprobados antes y después del arranque.'
echo 'No certifica proveedor externo, HTTPS ni URL pública.'
