#!/usr/bin/env bash
set -euo pipefail
source "$(dirname -- "${BASH_SOURCE[0]}")/common-linux.sh"
require_docker
prepare_env
"${EVENTIFY_DOCKER[@]}" compose config --quiet
"${EVENTIFY_DOCKER[@]}" compose up --build --detach --wait --wait-timeout 180
"${EVENTIFY_DOCKER[@]}" compose exec -T web php bin/migrate.php
EVENTIFY_PORT="$(sed -n 's/^APP_PORT=//p' .env | head -n 1)"
if [[ ! "$EVENTIFY_PORT" =~ ^[0-9]+$ ]]; then
    echo 'Compruebe APP_PORT en .env.' >&2
    exit 1
fi
echo "Eventify disponible en http://localhost:$EVENTIFY_PORT"
