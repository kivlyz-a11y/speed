#!/bin/bash
set -e

# Ensure writable directories exist & set permissions
mkdir -p /var/www/html/writable/cache \
         /var/www/html/writable/logs \
         /var/www/html/writable/session \
         /var/www/html/writable/uploads \
         /var/www/html/writable/debugbar

chown -R www-data:www-data /var/www/html/writable
chmod -R 777 /var/www/html/writable

# Execute migrations if DB_AUTO_MIGRATE is true
if [ "$DB_AUTO_MIGRATE" = "true" ]; then
    echo "Running database migrations..."
    php spark migrate --all || true
    echo "Database migrations completed."
fi

exec "$@"
