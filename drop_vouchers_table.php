<?php

$mysqli = new mysqli('127.0.0.1', 'root', '', 'speed_boat_db');
if ($mysqli->connect_error) {
    die('Error: ' . $mysqli->connect_error);
}

$mysqli->query("SET FOREIGN_KEY_CHECKS = 0;");
$mysqli->query("DROP TABLE IF EXISTS vouchers;");
$mysqli->query("SET FOREIGN_KEY_CHECKS = 1;");

echo "Vouchers table dropped successfully.\n";
