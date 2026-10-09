#!/usr/bin/env bash
set -euo pipefail
source "$(dirname -- "${BASH_SOURCE[0]}")/common-linux.sh"
require_docker
umask 077
record="$(mktemp -d "$EVENTIFY_ROOT/storage/image-check-XXXXXXXX")"
version="$(cat VERSION)"
[[ "$version" =~ ^[0-9]+\.[0-9]+\.[0-9]+-dev\.[0-9]+$ ]] || { echo 'Versión de desarrollo inválida.' >&2; exit 1; }
name="$(basename "$record")"
# mktemp puede generar mayúsculas; Docker solo las permite en la etiqueta.
image_name="${name,,}"
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
        image="eventify-${image_name}-${kind}:${version}"
        "${EVENTIFY_DOCKER[@]}" build -f "$dockerfile" -t "$image" .
        for package in liblzma5 xz-utils; do
            actual="$("${EVENTIFY_DOCKER[@]}" run --rm --network none --entrypoint dpkg-query "$image" -W '-f=${Version}' "$package")"
            [[ "$actual" == '5.8.1-1+deb13u2' ]] || { echo "Versión corregida no comprobada: $package" >&2; return 1; }
        done
        # La imagen final no debe conservar compiladores o cabeceras retirados.
        "${EVENTIFY_DOCKER[@]}" run --rm --network none --entrypoint sh "$image" -c '
            for package in binutils gcc g++ make autoconf m4 patch re2c libc6-dev linux-libc-dev; do
                status=$(dpkg-query -W -f="\${db:Status-Status}" "$package" 2>/dev/null || true)
                if [ "$status" = installed ]; then
                    echo "Herramienta de construcción aún instalada: $package" >&2
                    exit 1
                fi
            done
        '
        "${EVENTIFY_DOCKER[@]}" run --rm --network none --entrypoint apache2ctl "$image" -M > "$record/${kind}-apache-modules.txt" 2>&1
        if grep -Eq ' (heartmonitor|charset_lite|vhost_alias|xml2enc|proxy_uwsgi|auth_digest)_module ' "$record/${kind}-apache-modules.txt"; then
            echo 'Un módulo de Apache pendiente de corrección está habilitado; revisar el registro privado.' >&2
            return 1
        fi
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
