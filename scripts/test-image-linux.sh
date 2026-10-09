#!/usr/bin/env bash
set -euo pipefail
source "$(dirname -- "${BASH_SOURCE[0]}")/common-linux.sh"
require_docker
umask 077
record="$(mktemp -d "$EVENTIFY_ROOT/storage/image-check-XXXXXXXX")"
version="$(cat VERSION)"
[[ "$version" =~ ^[0-9]+\.[0-9]+\.[0-9]+-dev\.[0-9]+$ ]] || { echo 'Versión de desarrollo inválida.' >&2; exit 1; }
name="$(basename "$record")"
git rev-parse HEAD > "$record/commit.txt"
printf '%s\n' "$version" > "$record/version.txt"
git status --porcelain > "$record/working-tree.txt"
date -u +%FT%TZ > "$record/status.txt"

# Solo construye imágenes y ejecuta procesos sin red, puertos, montajes ni bases.
verify_images() {
    local kind dockerfile image actual package
    for kind in local public; do
        dockerfile='Dockerfile'
        [[ "$kind" != public ]] || dockerfile='Dockerfile.public'
        image="eventify-${name}-${kind}:${version}"
        "${EVENTIFY_DOCKER[@]}" build -f "$dockerfile" -t "$image" .
        for package in liblzma5 xz-utils; do
            actual="$("${EVENTIFY_DOCKER[@]}" run --rm --network none --entrypoint dpkg-query "$image" -W '-f=${Version}' "$package")"
            [[ "$actual" == '5.8.1-1+deb13u2' ]] || { echo "Versión corregida no comprobada: $package" >&2; return 1; }
        done
        "${EVENTIFY_DOCKER[@]}" run --rm --network none --entrypoint php "$image" bin/lint.php
        for test in tests/unit/*.php; do
            "${EVENTIFY_DOCKER[@]}" run --rm --network none --entrypoint php "$image" "$test"
        done
        "${EVENTIFY_DOCKER[@]}" image inspect --format '{{.Id}}' "$image" > "$record/${kind}-image-id.txt"
        "${EVENTIFY_DOCKER[@]}" image save "$image" > "$record/${kind}.tar"
        echo "Imagen $kind construida, versiones corregidas y pruebas aprobadas; exportación conservada."
    done
}

set +e
( set -e; verify_images ) > "$record/test.log" 2>&1
result=$?
set -e
printf 'exit_code=%s\n' "$result" >> "$record/status.txt"
tail -n 18 "$record/test.log"
printf 'Registro privado: %s\n' "$record"
exit "$result"
