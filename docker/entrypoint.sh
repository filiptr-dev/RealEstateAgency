#!/bin/bash
set -e

export PORT="${PORT:-8080}"
envsubst '${PORT}' < /etc/nginx/http.d/default.conf.template > /etc/nginx/http.d/default.conf

php artisan package:discover --ansi
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan migrate --force

exec "$@"
