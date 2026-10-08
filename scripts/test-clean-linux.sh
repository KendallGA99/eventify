#!/usr/bin/env bash
# Ensayo desde fuente Git limpia; conserva todos los destinos creados.
set -euo pipefail
source "$(dirname -- "${BASH_SOURCE[0]}")/common-linux.sh"
umask 077
[[ $# == 1 ]] || { echo 'Uso: bash scripts/test-clean-linux.sh <commit-o-etiqueta>' >&2; exit 1; }
command -v python3 >/dev/null
command -v openssl >/dev/null
EVENTIFY_CLEAN_REF="$1"
require_docker
EVENTIFY_CLEAN_ID="$(date -u +'%Y%m%d%H%M%S')-$(openssl rand -hex 4)"
EVENTIFY_CLEAN_DIR="$EVENTIFY_ROOT/storage/clean-$EVENTIFY_CLEAN_ID"
mkdir -m 700 "$EVENTIFY_CLEAN_DIR"
date -u +'%Y-%m-%dT%H:%M:%SZ' > "$EVENTIFY_CLEAN_DIR/status.txt"
trap 'EVENTIFY_CLEAN_RESULT=$?; printf "exit_code=%s\n" "$EVENTIFY_CLEAN_RESULT" >> "$EVENTIFY_CLEAN_DIR/status.txt"' EXIT
exec > >(tee "$EVENTIFY_CLEAN_DIR/test.log") 2>&1
python3 "$EVENTIFY_ROOT/scripts/package-source.py" --ref "$EVENTIFY_CLEAN_REF" --output-dir "$EVENTIFY_CLEAN_DIR/package"
python3 - "$EVENTIFY_CLEAN_DIR" <<'PY'
import json, pathlib, zipfile
import secrets
import sys
root = pathlib.Path(sys.argv[1])
packages = list((root / 'package').glob('*/manifest.json'))
if len(packages) != 1:
    raise SystemExit('Paquete de ensayo ambiguo.')
manifest = json.loads(packages[0].read_text())
with zipfile.ZipFile(packages[0].parent / manifest['archive']) as archive:
    archive.extractall(root / 'Copia limpia con espacios')
copy = root / 'Copia limpia con espacios' / 'Eventify'
# Solo claves nuevas. Nunca leer/copiar .env ni datos del desarrollador.
env = ('APP_ENV=local\nAPP_PORT=8083\nAPP_TIMEZONE=America/Costa_Rica\n'
       'MYSQL_DATABASE=eventify_qa\nMYSQL_USER=eventify_qa\nWEATHER_ENABLED=false\n'
       + ''.join(name + '=' + secrets.token_hex(32) + '\n' for name in
                 ['APP_SECRET', 'MYSQL_PASSWORD', 'MYSQL_ROOT_PASSWORD']))
(copy / '.env').write_text(env)
(copy / '.env').chmod(0o600)
(root / 'reference.json').write_text(json.dumps({'commit':manifest['commit'],
    'archive_sha256':manifest['sha256'], 'files':len(manifest['files'])}) + '\n')
PY
EVENTIFY_CLEAN_COPY="$EVENTIFY_CLEAN_DIR/Copia limpia con espacios/Eventify"
# Evitar que variables del host cambien puerto, secretos o proyecto de prueba.
unset APP_ENV APP_PORT APP_TIMEZONE APP_SECRET MYSQL_DATABASE MYSQL_USER MYSQL_PASSWORD MYSQL_ROOT_PASSWORD WEATHER_ENABLED COMPOSE_FILE COMPOSE_PROJECT_NAME
export COMPOSE_PROJECT_NAME="eventify-clean-$EVENTIFY_CLEAN_ID"
# El arranque del manual se ejecuta desde el ZIP, sin Git, vendor ni bind de código.
bash "$EVENTIFY_CLEAN_COPY/scripts/start-linux.sh"
EVENTIFY_CLEAN_COMPOSE=("${EVENTIFY_DOCKER[@]}" compose --env-file "$EVENTIFY_CLEAN_COPY/.env" -p "$COMPOSE_PROJECT_NAME" -f "$EVENTIFY_CLEAN_COPY/docker-compose.yml")
"${EVENTIFY_CLEAN_COMPOSE[@]}" exec -T web php bin/seed-demo.php
"${EVENTIFY_CLEAN_COMPOSE[@]}" exec -T web php bin/qa-persistence.php before
"${EVENTIFY_CLEAN_COMPOSE[@]}" up --force-recreate --detach --wait --wait-timeout 180
"${EVENTIFY_CLEAN_COMPOSE[@]}" exec -T web php bin/qa-persistence.php after
"${EVENTIFY_CLEAN_COMPOSE[@]}" exec -T web cat storage/demo-credentials.json > "$EVENTIFY_CLEAN_DIR/demo-credentials.json"
printf '%s\n' "$COMPOSE_PROJECT_NAME" > "$EVENTIFY_CLEAN_DIR/project.txt"
echo 'Copia limpia iniciada en http://localhost:8083; falta comprobar el flujo en navegador.'
echo "Resultado/credenciales privados: storage/clean-$EVENTIFY_CLEAN_ID"
echo 'Destino conservado. Si 8083 estaba ocupado, no detenga otras instancias: revise el fallo.'
