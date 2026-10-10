#!/usr/bin/env bash
set -euo pipefail
source "$(dirname -- "${BASH_SOURCE[0]}")/common-linux.sh"
require_docker
umask 077
record="$(mktemp -d "$EVENTIFY_ROOT/storage/mysql-image-check-XXXXXXXX")"
version="$(cat VERSION)"
[[ "$version" =~ ^[0-9]+\.[0-9]+\.[0-9]+-dev\.[0-9]+$ ]] || exit 1
name="$(basename "$record")"
image="eventify-${name,,}:${version}"
date -u +%FT%TZ > "$record/status.txt"
git rev-parse HEAD > "$record/commit.txt"
git status --porcelain > "$record/working-tree.txt"
printf '%s\n' "$version" > "$record/version.txt"

verify_mysql() {
    "${EVENTIFY_DOCKER[@]}" build -f Dockerfile.mysql -t "$image" .
    actual="$("${EVENTIFY_DOCKER[@]}" run --rm --network none --entrypoint rpm "$image" -q --qf '%{VERSION}-%{RELEASE}' gawk)"
    [[ "$actual" == '5.1.0-6.el9_8.1' ]] || { echo 'No se comprobó la versión corregida de gawk.' >&2; return 1; }
    printf '%s\n' "$actual" > "$record/gawk-version.txt"
    for package in openssl openssl-libs; do
        actual="$("${EVENTIFY_DOCKER[@]}" run --rm --network none --entrypoint rpm "$image" -q --qf '%{VERSION}-%{RELEASE}' "$package")"
        [[ "$actual" == '3.5.8-2.0.1.el9_8' ]] || { echo "No se comprobó OpenSSL corregido: $package" >&2; return 1; }
    done
    "${EVENTIFY_DOCKER[@]}" run --rm --network none --entrypoint gosu "$image" --version > "$record/gosu-version.txt"
    grep -q '^1.19 (go1.27.2 on linux/' "$record/gosu-version.txt"
    "${EVENTIFY_DOCKER[@]}" run --rm --network none --entrypoint sh "$image" -c '
        [ "$(gosu mysql id -u)" = "$(id -u mysql)" ]
        [ "$(gosu mysql id -g)" = "$(id -g mysql)" ]
        ! command -v go
    '

    "${EVENTIFY_DOCKER[@]}" run --rm --network none --entrypoint mysqld "$image" --version > "$record/mysql-version.txt"
    "${EVENTIFY_DOCKER[@]}" image inspect --format '{{.Id}}' "$image" > "$record/image-id.txt"
    "${EVENTIFY_DOCKER[@]}" image save "$image" > "$record/mysql.tar"
    # Los tres ensayos usan bases efímeras; no actualizan la instalación local.
    COMPOSE_PROJECT_NAME="eventify-mysql-check-$(openssl rand -hex 6)" bash scripts/test-database-linux.sh
    bash scripts/test-public-tls-linux.sh
    bash scripts/test-public-no-tls-linux.sh
}

unset COMPOSE_FILE COMPOSE_PROJECT_NAME
set +e
( set -e; verify_mysql ) > "$record/test.log" 2>&1
result=$?
set -e
printf 'exit_code=%s\n' "$result" >> "$record/status.txt"
tail -n 18 "$record/test.log"
printf 'Registro privado: %s\n' "$record"
exit "$result"
