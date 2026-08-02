<?php

$mysqli = new mysqli('127.0.0.1', 'root', '', 'speed_boat_db');
if ($mysqli->connect_error) {
    die('Error: ' . $mysqli->connect_error);
}

$now = date('Y-m-d H:i:s');

$mysqli->query("SET FOREIGN_KEY_CHECKS = 0;");
$mysqli->query("TRUNCATE TABLE locations;");
$mysqli->query("TRUNCATE TABLE routes;");

// Insert ONLY Bulungan & Tarakan
$locations = [
    [1, 'BLNG', 'Bulungan', 'Bulungan', 'Kalimantan Utara'],
    [2, 'TRK', 'Tarakan', 'Tarakan', 'Kalimantan Utara'],
];

foreach ($locations as $l) {
    $stmt = $mysqli->prepare("INSERT INTO locations (id, code, name, city, province, description, status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, 'Dermaga Penyeberangan', 'active', ?, ?)");
    $stmt->bind_param("issssss", $l[0], $l[1], $l[2], $l[3], $l[4], $now, $now);
    $stmt->execute();
}

// Insert ONLY Bulungan <-> Tarakan routes
$routes = [
    [1, 2, 15.00, 45, 150000.00], // Bulungan -> Tarakan
    [2, 1, 15.00, 45, 150000.00], // Tarakan -> Bulungan
];

foreach ($routes as $r) {
    $stmt = $mysqli->prepare("INSERT INTO routes (origin_location_id, destination_location_id, distance_nautical_miles, estimated_duration_minutes, base_price, status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, 'active', ?, ?)");
    $stmt->bind_param("iididss", $r[0], $r[1], $r[2], $r[3], $r[4], $now, $now);
    $stmt->execute();
}

$mysqli->query("SET FOREIGN_KEY_CHECKS = 1;");

echo "Database cleaned: Only Bulungan & Tarakan locations and routes remain!\n";
