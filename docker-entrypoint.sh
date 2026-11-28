#!/bin/sh
set -e

# Render memberi port lewat env PORT (default 10000)
PORT="${PORT:-10000}"
sed -ri "s/Listen 80/Listen ${PORT}/" /etc/apache2/ports.conf
sed -ri "s/<VirtualHost \*:80>/<VirtualHost *:${PORT}>/" /etc/apache2/sites-available/000-default.conf

cd /var/www/html
rm -rf public/storage
php artisan storage:link --force || true

php artisan config:cache
php artisan route:cache
php artisan view:cache

if [ "${RUN_MIGRATIONS:-true}" = "true" ]; then
  php artisan migrate --force
fi

# Isi data awal HANYA sekali: set RUN_SEED=true di deploy pertama, lalu ubah ke false
if [ "${RUN_SEED:-false}" = "true" ]; then
  php artisan db:seed --force
fi

chown -R www-data:www-data storage bootstrap/cache
exec apache2-foreground
