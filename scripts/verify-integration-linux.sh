#!/usr/bin/env bash
set -euo pipefail
EVENTIFY_INTEGRATION_ROOT="$(cd -- "$(dirname -- "${BASH_SOURCE[0]}")/.." && pwd)"
umask 077
mkdir -p "$EVENTIFY_INTEGRATION_ROOT/storage"
date -u +'%Y-%m-%dT%H:%M:%SZ' > "$EVENTIFY_INTEGRATION_ROOT/storage/integration-status.txt"
trap 'EVENTIFY_INTEGRATION_RESULT=$?; printf "exit_code=%s\n" "$EVENTIFY_INTEGRATION_RESULT" >> "$EVENTIFY_INTEGRATION_ROOT/storage/integration-status.txt"' EXIT
exec > >(tee "$EVENTIFY_INTEGRATION_ROOT/storage/integration.log") 2>&1
bash "$EVENTIFY_INTEGRATION_ROOT/scripts/test-database-linux.sh"
echo 'Verificaciones de integración terminadas correctamente.'
