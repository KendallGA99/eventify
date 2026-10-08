#!/usr/bin/env bash
set -euo pipefail
source "$(dirname -- "${BASH_SOURCE[0]}")/common-linux.sh"
require_docker
prepare_env
# Stack separado y efímero: nunca conectar con los volúmenes de la aplicación.
cleanup() { "${EVENTIFY_DOCKER[@]}" compose -f docker-compose.test.yml down; }
trap cleanup EXIT
"${EVENTIFY_DOCKER[@]}" compose -f docker-compose.test.yml up --build --abort-on-container-exit --exit-code-from tests
