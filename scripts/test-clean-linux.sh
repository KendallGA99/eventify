#!/usr/bin/env bash
# Ensayo desde fuente Git limpia; conserva todos los destinos creados.
set -euo pipefail
source "$(dirname -- "${BASH_SOURCE[0]}")/common-linux.sh"
umask 077
[[ $# -ge 1 && $# -le 2 ]] || { echo 'Uso: bash scripts/test-clean-linux.sh <commit-o-etiqueta> [puerto]' >&2; exit 1; }
EVENTIFY_CLEAN_PORT="${2:-8083}"
[[ "$EVENTIFY_CLEAN_PORT" =~ ^[1-9][0-9]{3,4}$ ]] && (( EVENTIFY_CLEAN_PORT >= 1024 && EVENTIFY_CLEAN_PORT <= 65535 )) || { echo 'Puerto inválido: use un número entre 1024 y 65535.' >&2; exit 1; }
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
python3 - "$EVENTIFY_CLEAN_DIR" "$EVENTIFY_CLEAN_PORT" <<'PY'
import json, pathlib, zipfile
import secrets
import sys
root = pathlib.Path(sys.argv[1])
packages = list((root / 'package').glob('*/manifest.json'))
if len(packages) != 1:
    raise SystemExit('Paquete de ensayo ambiguo.')
manifest = json.loads(packages[0].read_text())
with zipfile.ZipFile(packages[0].parent / manifest['archive']) as archive:
    extraction = root / 'Copia limpia con espacios'
    archive.extractall(extraction)
    # extractall hereda umask 077 e ignora los permisos Git del ZIP.
    # El padre privado sigue 700; el código copiado a Docker debe ser legible por Apache.
    for directory in [extraction, *[p for p in extraction.rglob('*') if p.is_dir()]]:
        directory.chmod(0o755)
    for info in archive.infolist():
        if not info.is_dir():
            (extraction / info.filename).chmod(0o755 if (info.external_attr >> 16) & 0o111 else 0o644)
copy = root / 'Copia limpia con espacios' / 'Eventify'
# Solo claves nuevas. Nunca leer/copiar .env ni datos del desarrollador.
port = sys.argv[2] if len(sys.argv) > 2 else '8083'
env = ('APP_ENV=local\nAPP_PORT=' + port + '\nAPP_TIMEZONE=America/Costa_Rica\n'
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
EVENTIFY_CLEAN_PROJECT="eventify-clean-$EVENTIFY_CLEAN_ID"
# El arranque del manual se ejecuta desde el ZIP, sin Git, vendor ni bind de código.
EVENTIFY_CLEAN_COMPOSE=("${EVENTIFY_DOCKER[@]}" compose --env-file "$EVENTIFY_CLEAN_COPY/.env" -p "$EVENTIFY_CLEAN_PROJECT" -f "$EVENTIFY_CLEAN_COPY/docker-compose.yml")
# sudo puede eliminar variables de entorno: pasar -p como argumento explícito.
# Rechazar ZIP anteriores que no admiten el arranque aislado antes de cualquier up.
grep -q -- '--project' "$EVENTIFY_CLEAN_COPY/scripts/start-linux.sh" || { echo 'Referencia antigua sin soporte de proyecto explícito; no se iniciará.' >&2; exit 1; }
"${EVENTIFY_CLEAN_COMPOSE[@]}" config --format json | python3 -c '
import json,sys
c=json.load(sys.stdin); expected=sys.argv[1]
assert c["name"]==expected, "Proyecto inesperado"
assert all(v["name"].startswith(expected+"_") and not v.get("external",False) for v in c["volumes"].values()), "Volúmenes ajenos"
assert str(c["services"]["web"]["ports"][0]["published"])==sys.argv[2], "Puerto inesperado"
assert all(v["type"]=="volume" for service in c["services"].values() for v in service.get("volumes",[])), "Montaje no aislado"
' "$EVENTIFY_CLEAN_PROJECT" "$EVENTIFY_CLEAN_PORT"
bash "$EVENTIFY_CLEAN_COPY/scripts/start-linux.sh" --project "$EVENTIFY_CLEAN_PROJECT"
"${EVENTIFY_CLEAN_COMPOSE[@]}" exec -T web php bin/seed-demo.php
"${EVENTIFY_CLEAN_COMPOSE[@]}" exec -T web php bin/qa-persistence.php before
"${EVENTIFY_CLEAN_COMPOSE[@]}" up --force-recreate --detach --wait --wait-timeout 180
"${EVENTIFY_CLEAN_COMPOSE[@]}" exec -T web php bin/qa-persistence.php after
"${EVENTIFY_CLEAN_COMPOSE[@]}" exec -T web cat storage/demo-credentials.json > "$EVENTIFY_CLEAN_DIR/demo-credentials.json"
printf '%s\n' "$EVENTIFY_CLEAN_PROJECT" > "$EVENTIFY_CLEAN_DIR/project.txt"
echo "Copia limpia iniciada en http://localhost:$EVENTIFY_CLEAN_PORT; falta comprobar el flujo en navegador."
echo "Resultado/credenciales privados: storage/clean-$EVENTIFY_CLEAN_ID"
echo 'Destino conservado. Si el puerto estaba ocupado, no detenga otras instancias: revise el fallo.'
