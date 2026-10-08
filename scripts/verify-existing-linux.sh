#!/usr/bin/env bash
set -euo pipefail
source "$(dirname -- "${BASH_SOURCE[0]}")/common-linux.sh"
umask 077
[[ $# == 2 && "$1" =~ ^[0-9]{14}-[a-f0-9]{8}$ && "$2" =~ ^[0-9]{14}-[a-f0-9]{8}$ ]] || {
    echo 'Uso: verify-existing-linux.sh <ID-copia-limpia> <ID-restauracion>' >&2; exit 1;
}
EVENTIFY_CLEAN_ID="$1"
EVENTIFY_RESTORE_ID="$2"
EVENTIFY_CLEAN_DIR="$EVENTIFY_ROOT/storage/clean-$EVENTIFY_CLEAN_ID"
EVENTIFY_RESTORE_DIR="$EVENTIFY_ROOT/storage/restore-$EVENTIFY_RESTORE_ID"
EVENTIFY_COPY="$EVENTIFY_CLEAN_DIR/Copia limpia con espacios/Eventify"
EVENTIFY_CLEAN_PROJECT="eventify-clean-$EVENTIFY_CLEAN_ID"
EVENTIFY_RESTORE_PROJECT="eventify-restore-$EVENTIFY_RESTORE_ID"
for EVENTIFY_FILE in "$EVENTIFY_CLEAN_DIR/status.txt" "$EVENTIFY_RESTORE_DIR/status.txt"; do
    [[ -f "$EVENTIFY_FILE" ]] && grep -qx 'exit_code=0' "$EVENTIFY_FILE" || { echo 'Ensayo previo no aprobado.' >&2; exit 1; }
done
[[ -f "$EVENTIFY_COPY/.env" && -f "$EVENTIFY_COPY/docker-compose.yml" && -f "$EVENTIFY_RESTORE_DIR/restore.env" && -f "$EVENTIFY_RESTORE_DIR/after.json" ]] || { echo 'Faltan archivos del destino existente.' >&2; exit 1; }
[[ "$(cat "$EVENTIFY_CLEAN_DIR/project.txt")" == "$EVENTIFY_CLEAN_PROJECT" ]] || { echo 'Proyecto de copia inesperado.' >&2; exit 1; }
command -v python3 >/dev/null
command -v curl >/dev/null
require_docker
unset APP_ENV APP_PORT APP_TIMEZONE APP_SECRET MYSQL_DATABASE MYSQL_USER MYSQL_PASSWORD MYSQL_ROOT_PASSWORD WEATHER_ENABLED COMPOSE_FILE COMPOSE_PROJECT_NAME
EVENTIFY_CLEAN=("${EVENTIFY_DOCKER[@]}" compose --env-file "$EVENTIFY_COPY/.env" -p "$EVENTIFY_CLEAN_PROJECT" -f "$EVENTIFY_COPY/docker-compose.yml")
EVENTIFY_RESTORE=("${EVENTIFY_DOCKER[@]}" compose --env-file "$EVENTIFY_RESTORE_DIR/restore.env" -p "$EVENTIFY_RESTORE_PROJECT" -f "$EVENTIFY_ROOT/docker-compose.yml")
# Validar configuración efectiva y existencia/propiedad de ambos volúmenes antes de mutar.
check_existing() {
    local project="$1" database="$2" port="$3"; shift 3
    "$@" config --format json | python3 -c '
import json,sys
c=json.load(sys.stdin); project,database,port=sys.argv[1:]
assert c["name"]==project
assert c["services"]["web"]["environment"]["APP_ENV"]=="local"
assert c["services"]["web"]["environment"]["DB_DATABASE"]==database
assert c["services"]["db"]["environment"]["MYSQL_DATABASE"]==database
p=c["services"]["web"]["ports"]
assert len(p)==1 and str(p[0]["published"])==port and p[0]["host_ip"]=="127.0.0.1"
assert set(c["volumes"])=={"mysql_data","app_storage"}
assert all(v["name"]==project+"_"+k and not v.get("external",False) for k,v in c["volumes"].items())
assert all(v["type"]=="volume" for s in c["services"].values() for v in s.get("volumes",[]))
' "$project" "$database" "$port"
    local suffix owner
    for suffix in mysql_data app_storage; do
        owner="$("${EVENTIFY_DOCKER[@]}" volume inspect --format '{{index .Labels "com.docker.compose.project"}}' "${project}_${suffix}")"
        [[ "$owner" == "$project" ]] || { echo 'Volumen ausente o de otro proyecto.' >&2; return 1; }
    done
}
check_existing "$EVENTIFY_CLEAN_PROJECT" eventify_qa 8083 "${EVENTIFY_CLEAN[@]}"
check_existing "$EVENTIFY_RESTORE_PROJECT" eventify_restore 8082 "${EVENTIFY_RESTORE[@]}"
EVENTIFY_RESULT_DIR="$(mktemp -d "$EVENTIFY_ROOT/storage/existing-XXXXXXXX")"
date -u +'%Y-%m-%dT%H:%M:%SZ' > "$EVENTIFY_RESULT_DIR/status.txt"
printf '%s\n%s\n' "$EVENTIFY_CLEAN_PROJECT" "$EVENTIFY_RESTORE_PROJECT" > "$EVENTIFY_RESULT_DIR/projects.txt"
exec > >(tee "$EVENTIFY_RESULT_DIR/test.log") 2>&1
EVENTIFY_CLEAN_STOPPED=false
finish_existing() {
    local result=$?
    trap - EXIT
    if [[ "$EVENTIFY_CLEAN_STOPPED" == true ]]; then
        "${EVENTIFY_CLEAN[@]}" up --detach --wait --wait-timeout 180 web || result=1
    fi
    printf 'exit_code=%s\n' "$result" >> "$EVENTIFY_RESULT_DIR/status.txt"
    echo "Registro privado: ${EVENTIFY_RESULT_DIR#"$EVENTIFY_ROOT/"}"
    exit "$result"
}
trap finish_existing EXIT
# Snapshot actual incluye las reservas/cancelaciones hechas después del primer ensayo.
EVENTIFY_CLEAN_STOPPED=true
"${EVENTIFY_CLEAN[@]}" stop web
"${EVENTIFY_CLEAN[@]}" run --rm --no-deps -T --entrypoint php web bin/qa-transfer.php snapshot > "$EVENTIFY_RESULT_DIR/clean-before.json"
"${EVENTIFY_CLEAN[@]}" up --force-recreate --detach --wait --wait-timeout 180
"${EVENTIFY_CLEAN[@]}" stop web
"${EVENTIFY_CLEAN[@]}" run --rm --no-deps -T --entrypoint php web bin/qa-transfer.php snapshot > "$EVENTIFY_RESULT_DIR/clean-after.json"
cmp --silent "$EVENTIFY_RESULT_DIR/clean-before.json" "$EVENTIFY_RESULT_DIR/clean-after.json" || { echo 'Persistencia de copia limpia distinta.' >&2; exit 1; }
"${EVENTIFY_CLEAN[@]}" up --detach --wait --wait-timeout 180 web
EVENTIFY_CLEAN_STOPPED=false
# Solo arrancar el destino restaurado: no importar, migrar, sembrar ni reconstruir imagen.
"${EVENTIFY_RESTORE[@]}" up --detach --wait --wait-timeout 180
"${EVENTIFY_RESTORE[@]}" exec -T web php bin/qa-transfer.php snapshot > "$EVENTIFY_RESULT_DIR/restore-current.json"
cmp --silent "$EVENTIFY_RESTORE_DIR/after.json" "$EVENTIFY_RESULT_DIR/restore-current.json" || { echo 'Destino restaurado cambió antes del recorrido HTTP.' >&2; exit 1; }
"${EVENTIFY_RESTORE[@]}" exec -T web cat storage/demo-credentials.json > "$EVENTIFY_RESULT_DIR/restore-credentials.json"
for EVENTIFY_PORT in 8082 8083; do
    for EVENTIFY_ROUTE in / /salud /ingresar; do
        [[ "$(curl --silent --show-error --max-time 15 --output /dev/null --write-out '%{http_code}' "http://localhost:$EVENTIFY_PORT$EVENTIFY_ROUTE")" == 200 ]] || { echo 'HTTP del destino de ensayo falló.' >&2; exit 1; }
    done
done
echo 'Persistencia posterior al flujo y seis comprobaciones HTTP correctas. Falta recorrido autenticado del destino restaurado.'
echo 'Destinos conservados: restaurado8082 y copia limpia8083. Instancia normal/QA y volúmenes no se borraron.'
