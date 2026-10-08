#!/usr/bin/env bash
set -euo pipefail
source "$(dirname -- "${BASH_SOURCE[0]}")/common-linux.sh"
require_docker
prepare_env
"${EVENTIFY_DOCKER[@]}" compose -f docker-compose.yml -f docker-compose.dev.yml config --quiet
"${EVENTIFY_DOCKER[@]}" compose -f docker-compose.yml -f docker-compose.dev.yml up --build --detach --wait --wait-timeout 180
"${EVENTIFY_DOCKER[@]}" compose -f docker-compose.yml -f docker-compose.dev.yml exec -T web php bin/migrate.php
echo 'Modo de desarrollo listo. PHP/HTML/CSS se leen desde la carpeta del proyecto; los datos se conservan.'
