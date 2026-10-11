#!/usr/bin/env bash
set -euo pipefail
source "$(dirname -- "${BASH_SOURCE[0]}")/common-linux.sh"
EVENTIFY_START_PROJECT=eventify
if [[ $# != 0 ]]; then
    [[ $# == 2 && "$1" == --project && "$2" =~ ^[a-z0-9][a-z0-9_-]+$ ]] || { echo 'Uso: start-linux.sh [--project nombre]' >&2; exit 1; }
    EVENTIFY_START_PROJECT="$2"
fi
require_docker
prepare_env
EVENTIFY_START_COMPOSE=("${EVENTIFY_DOCKER[@]}" compose -p "$EVENTIFY_START_PROJECT")
"${EVENTIFY_START_COMPOSE[@]}" config --quiet
"${EVENTIFY_START_COMPOSE[@]}" up --build --detach --wait --wait-timeout 180
"${EVENTIFY_START_COMPOSE[@]}" exec -T web php bin/migrate.php
"${EVENTIFY_START_COMPOSE[@]}" exec -T web php bin/prepare-examples.php
EVENTIFY_PORT="$(sed -n 's/^APP_PORT=//p' .env | head -n 1)"
if [[ ! "$EVENTIFY_PORT" =~ ^[0-9]+$ ]]; then
    echo 'Compruebe APP_PORT en .env.' >&2
    exit 1
fi
echo "Eventify disponible en http://localhost:$EVENTIFY_PORT"
