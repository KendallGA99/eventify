#!/usr/bin/env bash
set -euo pipefail
EVENTIFY_VERIFY_ROOT="$(cd -- "$(dirname -- "${BASH_SOURCE[0]}")/.." && pwd)"
umask 077
mkdir -p "$EVENTIFY_VERIFY_ROOT/storage"
date -u +'%Y-%m-%dT%H:%M:%SZ' > "$EVENTIFY_VERIFY_ROOT/storage/verification-status.txt"
trap 'EVENTIFY_VERIFY_RESULT=$?; printf "exit_code=%s\n" "$EVENTIFY_VERIFY_RESULT" >> "$EVENTIFY_VERIFY_ROOT/storage/verification-status.txt"' EXIT
exec > >(tee "$EVENTIFY_VERIFY_ROOT/storage/verification.log") 2>&1
bash "$EVENTIFY_VERIFY_ROOT/scripts/start-dev-linux.sh"
bash "$EVENTIFY_VERIFY_ROOT/scripts/test-linux.sh"
bash "$EVENTIFY_VERIFY_ROOT/scripts/test-database-linux.sh"
echo 'Actualización y verificaciones de desarrollo terminadas correctamente.'
