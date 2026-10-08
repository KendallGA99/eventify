#!/usr/bin/env bash
set -euo pipefail
source "$(dirname -- "${BASH_SOURCE[0]}")/common-linux.sh"
umask 077
require_docker
EVENTIFY_PUBLIC_TEST_DIR="$(mktemp -d "$EVENTIFY_ROOT/storage/public-start-XXXXXXXX")"
date -u +'%Y-%m-%dT%H:%M:%SZ' > "$EVENTIFY_PUBLIC_TEST_DIR/status.txt"
trap 'EVENTIFY_PUBLIC_RESULT=$?; printf "exit_code=%s\n" "$EVENTIFY_PUBLIC_RESULT" >> "$EVENTIFY_PUBLIC_TEST_DIR/status.txt"' EXIT
exec > >(tee "$EVENTIFY_PUBLIC_TEST_DIR/test.log") 2>&1
EVENTIFY_PUBLIC_TEST_IMAGE="eventify-public-test:$(cat VERSION)"
"${EVENTIFY_DOCKER[@]}" build -f Dockerfile.public -t "$EVENTIFY_PUBLIC_TEST_IMAGE" .
"${EVENTIFY_DOCKER[@]}" run --rm --network none --entrypoint php "$EVENTIFY_PUBLIC_TEST_IMAGE" -l bin/public-preflight.php
# Sin puertos, volúmenes, secretos reales ni base de aplicación.
for EVENTIFY_CASE in missing local port; do
    EVENTIFY_OPTIONS=()
    EVENTIFY_EXPECTED='Arranque público rechazado'
    if [[ "$EVENTIFY_CASE" == local ]]; then EVENTIFY_OPTIONS=(--env APP_ENV=local); fi
    if [[ "$EVENTIFY_CASE" == port ]]; then EVENTIFY_OPTIONS=(--env 'PORT=80;invalido'); EVENTIFY_EXPECTED='Puerto público no válido'; fi
    if "${EVENTIFY_DOCKER[@]}" run --rm --network none "${EVENTIFY_OPTIONS[@]}" "$EVENTIFY_PUBLIC_TEST_IMAGE" > "$EVENTIFY_PUBLIC_TEST_DIR/$EVENTIFY_CASE.log" 2>&1; then
        echo 'El arranque público aceptó configuración inválida.' >&2; exit 1
    fi
    grep -q "$EVENTIFY_EXPECTED" "$EVENTIFY_PUBLIC_TEST_DIR/$EVENTIFY_CASE.log" || { echo 'Fallo distinto al rechazo previsto.' >&2; exit 1; }
done
echo 'Imagen pública: sintaxis y tres rechazos reales correctos. Arranque válido/TLS externo aún pendientes.'
echo "Registro privado: ${EVENTIFY_PUBLIC_TEST_DIR#"$EVENTIFY_ROOT/"}"
