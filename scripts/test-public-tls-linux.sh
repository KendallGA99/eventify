#!/usr/bin/env bash
set -euo pipefail
source "$(dirname -- "${BASH_SOURCE[0]}")/common-linux.sh"
umask 077
command -v python3 >/dev/null
command -v openssl >/dev/null
require_docker
EVENTIFY_TLS_RUN="$(mktemp -d "$EVENTIFY_ROOT/storage/public-tls-XXXXXXXX")"
EVENTIFY_TLS_PROJECT="eventify-tls-$(openssl rand -hex 6)"
date -u +'%Y-%m-%dT%H:%M:%SZ' > "$EVENTIFY_TLS_RUN/status.txt"
trap 'EVENTIFY_TLS_RESULT=$?; printf "exit_code=%s\n" "$EVENTIFY_TLS_RESULT" >> "$EVENTIFY_TLS_RUN/status.txt"' EXIT
printf '%s\n' "$EVENTIFY_TLS_PROJECT" > "$EVENTIFY_TLS_RUN/project.txt"
exec > >(tee "$EVENTIFY_TLS_RUN/test.log") 2>&1
python3 scripts/make-tls-fixture.py "$EVENTIFY_TLS_RUN/certs"
{
    printf 'APP_SECRET=%s\nMYSQL_PASSWORD=%s\nMYSQL_ROOT_PASSWORD=%s\n' "$(openssl rand -hex 32)" "$(openssl rand -hex 32)" "$(openssl rand -hex 32)"
    printf 'EVENTIFY_TLS_DIR="%s"\n' "$EVENTIFY_TLS_RUN/certs"
} > "$EVENTIFY_TLS_RUN/fixture.env"
unset APP_SECRET MYSQL_PASSWORD MYSQL_ROOT_PASSWORD EVENTIFY_TLS_DIR COMPOSE_FILE COMPOSE_PROJECT_NAME
EVENTIFY_TLS_COMPOSE=("${EVENTIFY_DOCKER[@]}" compose --env-file "$EVENTIFY_TLS_RUN/fixture.env" -p "$EVENTIFY_TLS_PROJECT" -f "$EVENTIFY_ROOT/docker-compose.tls-test.yml")
finish_tls() {
    local result=$?
    trap - EXIT
    # Solo proyecto efímero creado por este ensayo, sin volúmenes de aplicación.
    "${EVENTIFY_TLS_COMPOSE[@]}" down || result=1
    printf 'exit_code=%s\n' "$result" >> "$EVENTIFY_TLS_RUN/status.txt"
    echo "Registro privado: ${EVENTIFY_TLS_RUN#"$EVENTIFY_ROOT/"}"
    exit "$result"
}
trap finish_tls EXIT
"${EVENTIFY_TLS_COMPOSE[@]}" config --format json | python3 -c '
import json,sys
c=json.load(sys.stdin)
assert c["name"]==sys.argv[1]
assert not c.get("volumes")
assert all(not s.get("ports") for s in c["services"].values())
assert c["networks"]["isolated"]["internal"]
assert c["services"]["db"]["tmpfs"]==["/var/lib/mysql"]
assert c["services"]["web"]["environment"]["DB_DATABASE"]=="eventify_tls_test"
assert c["services"]["web"]["environment"]["APP_ENV"]=="production"
assert all(v["type"]=="bind" and v["source"].startswith(sys.argv[2]+"/") and v["read_only"] for s in c["services"].values() for v in s.get("volumes",[]))
' "$EVENTIFY_TLS_PROJECT" "$EVENTIFY_TLS_RUN"
"${EVENTIFY_TLS_COMPOSE[@]}" up --build --detach --wait --wait-timeout 240
"${EVENTIFY_TLS_COMPOSE[@]}" exec -T web php -l tests/integration/public-tls.php
"${EVENTIFY_TLS_COMPOSE[@]}" exec -T web php tests/integration/public-tls.php allow
"${EVENTIFY_TLS_COMPOSE[@]}" exec -T -e DB_TLS_CA=/run/secrets/wrong-ca.pem web php tests/integration/public-tls.php deny
"${EVENTIFY_TLS_COMPOSE[@]}" exec -T -e DB_HOST=wrong-host web php tests/integration/public-tls.php deny
for EVENTIFY_PATH in /salud /ingresar; do
    [[ "$("${EVENTIFY_TLS_COMPOSE[@]}" exec -T web curl --silent --show-error --max-time 15 --output /dev/null --write-out '%{http_code}' "http://127.0.0.1:10000$EVENTIFY_PATH")" == 200 ]] || { echo 'Apache público no responde correctamente.' >&2; exit 1; }
done
"${EVENTIFY_TLS_COMPOSE[@]}" up --force-recreate --no-deps --detach --wait --wait-timeout 180 web
"${EVENTIFY_TLS_COMPOSE[@]}" exec -T web php tests/integration/public-tls.php allow
"${EVENTIFY_TLS_COMPOSE[@]}" logs --no-color web > "$EVENTIFY_TLS_RUN/web.log"
echo 'Arranque público TLS aislado, CA/hostname negativos, HTTP interno y recreación aprobados.'
echo 'No certifica TLS del proveedor externo, HTTPS público, cuentas ni URL desplegada.'
