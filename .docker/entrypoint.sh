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
    echo "Checking database connection & ensuring database exists..."
    for i in {1..30}; do
        if php -r '
            $host = getenv("database.default.hostname") ?: getenv("database_default_hostname") ?: getenv("DB_HOST") ?: "db";
            $port = (int)(getenv("database.default.port") ?: getenv("database_default_port") ?: getenv("DB_PORT") ?: 3306);
            $user = getenv("database.default.username") ?: getenv("database_default_username") ?: getenv("DB_USER") ?: "root";
            $pass = getenv("database.default.password") ?: getenv("database_default_password") ?: getenv("DB_PASS") ?: "";
            $db   = getenv("database.default.database") ?: getenv("database_default_database") ?: getenv("DB_NAME") ?: "speed_boat_db";

            $conn = @fsockopen($host, $port, $errno, $errstr, 2);
            if (!$conn) { exit(1); }
            fclose($conn);

            $mysqli = @new mysqli($host, $user, $pass, "", $port);
            if ($mysqli->connect_error) {
                exit(1);
            }
            $dbEscaped = $mysqli->real_escape_string($db);
            if (!$mysqli->query("CREATE DATABASE IF NOT EXISTS `$dbEscaped` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci")) {
                echo "Warning: Could not create database `$dbEscaped`: " . $mysqli->error . "\n";
            }
            $mysqli->close();
            exit(0);
        '; then
            echo "Database connection established and database verified/created!"
            php spark migrate --all || true
            break
        fi
        echo "Waiting for database container... ($i/30)"
        sleep 2
    done
fi

exec "$@"
