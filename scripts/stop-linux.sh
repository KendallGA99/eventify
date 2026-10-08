#!/usr/bin/env bash
set -euo pipefail
source "$(dirname -- "${BASH_SOURCE[0]}")/common-linux.sh"
require_docker
"${EVENTIFY_DOCKER[@]}" compose stop
echo 'Eventify detenido. Los datos se conservan.'
