#!/usr/bin/env bash
set -euo pipefail
source "$(dirname -- "${BASH_SOURCE[0]}")/common-linux.sh"
umask 077
EVENTIFY_QA_DIR="$EVENTIFY_ROOT/storage/qa"
mkdir -p "$EVENTIFY_QA_DIR"
date -u +'%Y-%m-%dT%H:%M:%SZ' > "$EVENTIFY_QA_DIR/status.txt"
trap 'EVENTIFY_QA_RESULT=$?; printf "exit_code=%s\n" "$EVENTIFY_QA_RESULT" >> "$EVENTIFY_QA_DIR/status.txt"' EXIT
exec > >(tee "$EVENTIFY_QA_DIR/start.log") 2>&1
require_docker
if [[ ! -f "$EVENTIFY_QA_DIR/qa.env" ]]; then
    command -v openssl >/dev/null || { echo 'Falta openssl para claves QA.' >&2; exit 1; }
    {
        printf 'APP_ENV=local\nAPP_PORT=8081\nAPP_TIMEZONE=America/Costa_Rica\nMYSQL_DATABASE=eventify_qa\nMYSQL_USER=eventify_qa\nWEATHER_ENABLED=true\n'
        printf 'APP_SECRET=%s\n' "$(openssl rand -hex 32)"
        printf 'MYSQL_PASSWORD=%s\n' "$(openssl rand -hex 32)"
        printf 'MYSQL_ROOT_PASSWORD=%s\n' "$(openssl rand -hex 32)"
    } > "$EVENTIFY_QA_DIR/qa.env"
fi
# El entorno del host no debe redirigir QA al puerto o base de la instancia normal.
unset APP_ENV APP_PORT APP_TIMEZONE APP_SECRET MYSQL_DATABASE MYSQL_USER MYSQL_PASSWORD MYSQL_ROOT_PASSWORD WEATHER_ENABLED
EVENTIFY_QA_COMPOSE=("${EVENTIFY_DOCKER[@]}" compose --env-file "$EVENTIFY_QA_DIR/qa.env" -p eventify-qa -f "$EVENTIFY_ROOT/docker-compose.yml")
"${EVENTIFY_QA_COMPOSE[@]}" config --quiet
"${EVENTIFY_QA_COMPOSE[@]}" up --build --detach --wait --wait-timeout 180
"${EVENTIFY_QA_COMPOSE[@]}" exec -T web php bin/migrate.php
"${EVENTIFY_QA_COMPOSE[@]}" exec -T web php bin/seed-demo.php
"${EVENTIFY_QA_COMPOSE[@]}" exec -T web php bin/qa-persistence.php before
# Proyecto QA: conserva volúmenes; nunca down -v ni reset de datos.
"${EVENTIFY_QA_COMPOSE[@]}" up --force-recreate --detach --wait --wait-timeout 180
"${EVENTIFY_QA_COMPOSE[@]}" exec -T web php bin/qa-persistence.php after
# Redirección a archivo privado del host; las claves nunca aparecen en pantalla/log.
"${EVENTIFY_QA_COMPOSE[@]}" exec -T web cat storage/demo-credentials.json > "$EVENTIFY_QA_DIR/demo-credentials.json"
chmod 600 "$EVENTIFY_QA_DIR/demo-credentials.json"
echo 'QA listo en http://localhost:8081. Proyecto/volúmenes separados: eventify-qa.'
echo 'Credenciales locales en storage/qa/demo-credentials.json; no compartir ni subir a Git.'
