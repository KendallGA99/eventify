#!/usr/bin/env bash
set -euo pipefail
EVENTIFY_VERIFY_ROOT="$(cd -- "$(dirname -- "${BASH_SOURCE[0]}")/.." && pwd)"
bash "$EVENTIFY_VERIFY_ROOT/scripts/start-linux.sh"
bash "$EVENTIFY_VERIFY_ROOT/scripts/test-linux.sh"
bash "$EVENTIFY_VERIFY_ROOT/scripts/test-database-linux.sh"
echo 'Actualización y verificaciones terminadas correctamente.'
