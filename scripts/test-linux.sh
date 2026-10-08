#!/usr/bin/env bash
set -euo pipefail
source "$(dirname -- "${BASH_SOURCE[0]}")/common-linux.sh"
require_docker
prepare_env
"${EVENTIFY_DOCKER[@]}" compose run --build --rm --no-deps web php bin/lint.php
"${EVENTIFY_DOCKER[@]}" compose run --rm --no-deps web php tests/unit/config.php
"${EVENTIFY_DOCKER[@]}" compose run --rm --no-deps web php tests/unit/reservation-policy.php
"${EVENTIFY_DOCKER[@]}" compose run --rm --no-deps web php tests/unit/registration.php
"${EVENTIFY_DOCKER[@]}" compose run --rm --no-deps web php tests/unit/reservation-input.php
"${EVENTIFY_DOCKER[@]}" compose run --rm --no-deps web php tests/unit/ticket-qr.php
# Si el stack no está listo, esta prueba falla; no se confunde con éxito de sintaxis.
"${EVENTIFY_DOCKER[@]}" compose exec -T web php bin/health.php
echo 'Sintaxis, configuración y conexión MySQL verificadas.'
