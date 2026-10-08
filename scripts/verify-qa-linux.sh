#!/usr/bin/env bash
set -euo pipefail
EVENTIFY_VERIFY_ROOT="$(cd -- "$(dirname -- "${BASH_SOURCE[0]}")/.." && pwd)"
cd -- "$EVENTIFY_VERIFY_ROOT"
umask 077
mkdir -p storage/qa
date -u +'%Y-%m-%dT%H:%M:%SZ' > storage/qa/verification-status.txt
trap 'EVENTIFY_QA_EXIT=$?; printf "exit_code=%s\n" "$EVENTIFY_QA_EXIT" >> storage/qa/verification-status.txt' EXIT
exec > >(tee storage/qa/verification.log) 2>&1
bash scripts/start-qa-linux.sh
bash scripts/test-restore-qa-linux.sh
echo 'Actualización, persistencia y restauración QA terminadas correctamente.'
