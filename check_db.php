<?php

$mysqli = new mysqli('127.0.0.1', 'root', '', 'speed_boat_db');
if ($mysqli->connect_error) {
    die('Error: ' . $mysqli->connect_error);
}

echo "=== LOCATIONS ===\n";
$res = $mysqli->query('SELECT id, code, name, city, status FROM locations');
while ($row = $res->fetch_assoc()) {
    echo "ID: {$row['id']} | Code: {$row['code']} | Name: {$row['name']} | City: {$row['city']} | Status: {$row['status']}\n";
}

echo "\n=== ROUTES ===\n";
$res2 = $mysqli->query('SELECT r.id, loc1.name as origin, loc2.name as destination, r.base_price, r.status FROM routes r JOIN locations loc1 ON loc1.id=r.origin_location_id JOIN locations loc2 ON loc2.id=r.destination_location_id');
while ($row = $res2->fetch_assoc()) {
    echo "Route ID: {$row['id']} | {$row['origin']} -> {$row['destination']} | Price: {$row['base_price']} | Status: {$row['status']}\n";
}
