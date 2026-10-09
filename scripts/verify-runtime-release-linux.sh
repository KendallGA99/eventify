#!/usr/bin/env bash
set -euo pipefail
source "$(dirname -- "${BASH_SOURCE[0]}")/common-linux.sh"
require_docker
umask 077
record="$(mktemp -d "$EVENTIFY_ROOT/storage/runtime-release-XXXXXXXX")"
git rev-parse HEAD > "$record/commit.txt"
git status --porcelain > "$record/working-tree.txt"
date -u +%FT%TZ > "$record/status.txt"
# Imágenes finales y arranque público en destinos efímeros, sin instalación local.
set +e
(
    set -e
    bash scripts/test-image-linux.sh
    bash scripts/test-public-linux.sh
    bash scripts/test-public-tls-linux.sh
    bash scripts/test-public-no-tls-linux.sh
) > "$record/test.log" 2>&1
result=$?
set -e
printf 'exit_code=%s\n' "$result" >> "$record/status.txt"
tail -n 20 "$record/test.log"
printf 'Registro privado: %s\n' "$record"
exit "$result"
