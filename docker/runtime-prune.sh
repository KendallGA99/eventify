#!/bin/sh
set -eu
# Solo elimina paquetes instalados de construcción; no usa autoremove.
# El ensayo falla si apt retira una biblioteca o servicio requerido.
dpkg-query -W -f='${binary:Package} ${db:Status-Status}\n' \
    | awk '$2 == "installed" { sub(/:.*/, "", $1); print $1 }' \
    | grep -E '^(autoconf|automake|binutils(-.*)?|cpp(-[0-9]+(-.*)?)?|gcc(-[0-9]+(-[a-z0-9-]+-linux-gnu)?)?|g\+\+(-[0-9]+(-[a-z0-9-]+-linux-gnu)?)?|dpkg-dev|libc6-dev|linux-libc-dev|make|m4|patch|pkg-config|pkgconf|pkgconf-bin|re2c|libbinutils|libctf0|libctf-nobfd0|libgprofng0|libsframe1)$' \
    > /tmp/eventify-build-packages.txt
[ -s /tmp/eventify-build-packages.txt ]
# Los nombres proceden de dpkg y del patrón cerrado, nunca de datos web.
apt-get -s purge --no-auto-remove $(cat /tmp/eventify-build-packages.txt) > /tmp/eventify-purge-plan.txt
if grep -Eq '^(Remv|Purg) (apache2|apache2-bin|libxml2|libsqlite3-0|libcurl4t64|libssl3t64|liblzma5|xz-utils)(:| |$)' /tmp/eventify-purge-plan.txt; then
    echo 'El plan elimina un componente requerido; ensayo rechazado.' >&2
    exit 1
fi
apt-get purge -y --no-auto-remove $(cat /tmp/eventify-build-packages.txt)
for package in apache2 apache2-bin libxml2 libsqlite3-0 libcurl4t64 libssl3t64 liblzma5 xz-utils; do
    [ "$(dpkg-query -W -f='${db:Status-Status}' "$package")" = installed ]
done
php -r 'if (!extension_loaded("pdo_mysql")) { exit(1); }'
apache2ctl -t
rm /tmp/eventify-build-packages.txt /tmp/eventify-purge-plan.txt
