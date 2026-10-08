#!/usr/bin/env bash
set -euo pipefail
source "$(dirname -- "${BASH_SOURCE[0]}")/common-linux.sh"
umask 077
EVENTIFY_QA_DIR="$EVENTIFY_ROOT/storage/qa"
[[ -f "$EVENTIFY_QA_DIR/qa.env" ]] || { echo 'Primero inicie QA.' >&2; exit 1; }
require_docker
command -v openssl >/dev/null
EVENTIFY_RESTORE_ID="$(date -u +'%Y%m%d%H%M%S')-$(openssl rand -hex 4)"
EVENTIFY_RESTORE_DIR="$EVENTIFY_ROOT/storage/restore-$EVENTIFY_RESTORE_ID"
mkdir -m 700 "$EVENTIFY_RESTORE_DIR"
date -u +'%Y-%m-%dT%H:%M:%SZ' > "$EVENTIFY_RESTORE_DIR/status.txt"
exec > >(tee "$EVENTIFY_RESTORE_DIR/test.log") 2>&1
unset APP_ENV APP_PORT APP_TIMEZONE APP_SECRET MYSQL_DATABASE MYSQL_USER MYSQL_PASSWORD MYSQL_ROOT_PASSWORD WEATHER_ENABLED
EVENTIFY_SOURCE=("${EVENTIFY_DOCKER[@]}" compose --env-file "$EVENTIFY_QA_DIR/qa.env" -p eventify-qa -f "$EVENTIFY_ROOT/docker-compose.yml")
{
    printf 'APP_ENV=local\nAPP_PORT=8082\nAPP_TIMEZONE=America/Costa_Rica\nMYSQL_DATABASE=eventify_restore\nMYSQL_USER=eventify_restore\nWEATHER_ENABLED=false\n'
    printf 'APP_SECRET=%s\n' "$(openssl rand -hex 32)"
    printf 'MYSQL_PASSWORD=%s\n' "$(openssl rand -hex 32)"
    printf 'MYSQL_ROOT_PASSWORD=%s\n' "$(openssl rand -hex 32)"
} > "$EVENTIFY_RESTORE_DIR/restore.env"
EVENTIFY_DEST=("${EVENTIFY_DOCKER[@]}" compose --env-file "$EVENTIFY_RESTORE_DIR/restore.env" -p "eventify-restore-$EVENTIFY_RESTORE_ID" -f "$EVENTIFY_ROOT/docker-compose.yml")
EVENTIFY_SOURCE_STOPPED=false
EVENTIFY_DEST_STARTED=false
finish_restore() {
    local result=$?
    trap - EXIT
    if [[ "$EVENTIFY_SOURCE_STOPPED" == true ]]; then
        if ! "${EVENTIFY_SOURCE[@]}" up --detach --wait --wait-timeout 180 web; then result=1; fi
    fi
    if [[ "$EVENTIFY_DEST_STARTED" == true ]]; then
        if ! "${EVENTIFY_DEST[@]}" stop db; then result=1; fi
    fi
    printf 'exit_code=%s\n' "$result" >> "$EVENTIFY_RESTORE_DIR/status.txt"
    echo "Registro privado del ensayo: storage/restore-$EVENTIFY_RESTORE_ID"
    echo 'Datos/volúmenes de origen y destino conservados; no se borraron.'
    exit "$result"
}
trap finish_restore EXIT
"${EVENTIFY_SOURCE[@]}" config --quiet
"${EVENTIFY_DEST[@]}" config --quiet
# Solo QA queda temporalmente fuera de servicio para exportar datos/archivos coherentes.
# No iniciar un ensayo desde una instancia de trabajo normal.
"${EVENTIFY_SOURCE[@]}" exec -T web php bin/qa-transfer.php snapshot > "$EVENTIFY_RESTORE_DIR/preflight.json"
echo 'Se detendrá temporalmente web de QA; la instancia normal no se toca.'
EVENTIFY_SOURCE_STOPPED=true
"${EVENTIFY_SOURCE[@]}" stop web
"${EVENTIFY_SOURCE[@]}" run --rm --no-deps -T --entrypoint php web bin/qa-transfer.php snapshot > "$EVENTIFY_RESTORE_DIR/before.json"
"${EVENTIFY_SOURCE[@]}" exec -T db sh -c 'test "$MYSQL_DATABASE" = eventify_qa && MYSQL_PWD="$MYSQL_PASSWORD" mysqldump --user="$MYSQL_USER" --single-transaction --quick --no-tablespaces --set-gtid-purged=OFF --skip-comments --skip-add-drop-table --skip-add-locks "$MYSQL_DATABASE"' > "$EVENTIFY_RESTORE_DIR/database.sql"
[[ -s "$EVENTIFY_RESTORE_DIR/database.sql" ]] || { echo 'Respaldo vacío; no se restaurará.' >&2; exit 1; }
# Solo archivos duraderos del alcance actual. No transferir sesiones ni caché/locks.
"${EVENTIFY_SOURCE[@]}" run --rm --no-deps -T --entrypoint tar web -cf - -C /var/www/html/storage demo-credentials.json qa-persistence-marker.bin > "$EVENTIFY_RESTORE_DIR/storage.tar"
"${EVENTIFY_SOURCE[@]}" up --detach --wait --wait-timeout 180 web
EVENTIFY_SOURCE_STOPPED=false
# Cada ejecución usa proyecto/volúmenes nuevos. No importar sobre una base existente.
EVENTIFY_DEST_STARTED=true
"${EVENTIFY_DEST[@]}" up --detach --wait --wait-timeout 180 db
"${EVENTIFY_DEST[@]}" build web
"${EVENTIFY_DEST[@]}" run --rm --no-deps -T --entrypoint php web bin/qa-transfer.php empty
"${EVENTIFY_DEST[@]}" exec -T db sh -c 'test "$MYSQL_DATABASE" = eventify_restore && MYSQL_PWD="$MYSQL_PASSWORD" mysql --user="$MYSQL_USER" --database="$MYSQL_DATABASE" --binary-mode=1' < "$EVENTIFY_RESTORE_DIR/database.sql"
"${EVENTIFY_DEST[@]}" run --rm --no-deps -T --entrypoint tar web -xf - -C /var/www/html/storage < "$EVENTIFY_RESTORE_DIR/storage.tar"
"${EVENTIFY_DEST[@]}" run --rm --no-deps -T --entrypoint php web bin/qa-transfer.php snapshot > "$EVENTIFY_RESTORE_DIR/after.json"
cmp --silent "$EVENTIFY_RESTORE_DIR/before.json" "$EVENTIFY_RESTORE_DIR/after.json" || { echo 'El contenido restaurado difiere; ensayo rechazado.' >&2; exit 1; }
echo 'Restauración QA: nueve tablas, credenciales, marcador e imágenes coinciden con origen.'
echo 'No se transfirieron sesiones, caché ni claves del entorno original.'
