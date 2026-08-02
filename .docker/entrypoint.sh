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

# Wait for DB connection gracefully if DB_AUTO_MIGRATE is true
if [ "$DB_AUTO_MIGRATE" = "true" ]; then
    echo "Checking database connection..."
    for i in {1..30}; do
        if php -r "
            \$host = getenv('database.default.hostname') ?: getenv('database_default_hostname') ?: getenv('DB_HOST') ?: 'db';
            \$port = getenv('database.default.port') ?: getenv('database_default_port') ?: getenv('DB_PORT') ?: 3306;
            \$conn = @fsockopen(\$host, \$port, \$errno, \$errstr, 2);
            if (\$conn) { fclose(\$conn); exit(0); } else { exit(1); }
        "; then
            echo "Database connection established!"
            php spark migrate --all || true
            break
        fi
        echo "Waiting for database container... ($i/30)"
        sleep 2
    done
fi

exec "$@"
