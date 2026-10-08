#!/usr/bin/env bash
set -euo pipefail
umask 077
[[ $# == 1 && "$1" == apache2-foreground ]] || { echo 'Comando público no válido.' >&2; exit 1; }
EVENTIFY_PUBLIC_PORT="${PORT:-10000}"
[[ "$EVENTIFY_PUBLIC_PORT" =~ ^[1-9][0-9]{0,4}$ && "$EVENTIFY_PUBLIC_PORT" -le 65535 ]] || { echo 'Puerto público no válido.' >&2; exit 1; }
cd /var/www/html
# No abrir Apache ni ejecutar migraciones con configuración insegura/incompleta.
php bin/public-preflight.php
mkdir -p storage
chown www-data:www-data storage
printf 'Listen %s\n' "$EVENTIFY_PUBLIC_PORT" > /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:80>/<VirtualHost *:$EVENTIFY_PUBLIC_PORT>/" /etc/apache2/sites-available/000-default.conf
# La configuración de Apache es pública; Apache debe poder leerla.
chmod 644 /etc/apache2/ports.conf
apache2ctl configtest
# MigrationRunner conserva checksums/progreso y coordina por GET_LOCK MySQL.
# Sin seeder, importación, reset, endpoint instalador ni reintento infinito.
php bin/migrate.php
exec docker-php-entrypoint "$@"
