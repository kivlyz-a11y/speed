<?php
$host = '127.0.0.1';
$user = 'root';
$pass = '';

$mysqli = @new mysqli($host, $user, $pass);
if ($mysqli->connect_error) {
    die("Database connection failed: " . $mysqli->connect_error . "\n");
}

$dbName = 'speed_boat_db';
$query = "CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";

if ($mysqli->query($query)) {
    echo "Database '{$dbName}' checked/created successfully.\n";
} else {
    echo "Error creating database: " . $mysqli->error . "\n";
}
$mysqli->close();
