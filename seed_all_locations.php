<?php

$mysqli = new mysqli('127.0.0.1', 'root', '', 'speed_boat_db');
if ($mysqli->connect_error) {
    die('Error: ' . $mysqli->connect_error);
}

$now = date('Y-m-d H:i:s');

$mysqli->query("SET FOREIGN_KEY_CHECKS = 0;");
$mysqli->query("TRUNCATE TABLE locations;");
$mysqli->query("TRUNCATE TABLE routes;");

$locations = [
    [1, 'BLNG', 'Bulungan', 'Bulungan', 'Kalimantan Utara'],
    [2, 'TRK', 'Tarakan', 'Tarakan', 'Kalimantan Utara'],
    [3, 'SANUR', 'Pelabuhan Sanur', 'Denpasar', 'Bali'],
    [4, 'NPN', 'Banjar Nyuh', 'Nusa Penida', 'Bali'],
    [5, 'NLB', 'Jungut Batu', 'Nusa Lembongan', 'Bali'],
    [6, 'PDB', 'Pelabuhan Padangbai', 'Karangasem', 'Bali'],
    [7, 'GTI', 'Gili Trawangan', 'Lombok Utara', 'Nusa Tenggara Barat'],
];

foreach ($locations as $l) {
    $stmt = $mysqli->prepare("INSERT INTO locations (id, code, name, city, province, description, status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, 'Dermaga Penyeberangan', 'active', ?, ?)");
    $stmt->bind_param("issssss", $l[0], $l[1], $l[2], $l[3], $l[4], $now, $now);
    $stmt->execute();
}

$routes = [
    [1, 2, 15.00, 45, 150000.00], // Bulungan -> Tarakan
    [2, 1, 15.00, 45, 150000.00], // Tarakan -> Bulungan
    [3, 4, 15.00, 45, 150000.00], // Sanur -> Nusa Penida
    [4, 3, 15.00, 45, 150000.00], // Nusa Penida -> Sanur
    [3, 5, 12.00, 35, 135000.00], // Sanur -> Nusa Lembongan
    [6, 7, 35.00, 90, 350000.00], // Padangbai -> Gili Trawangan
];

foreach ($routes as $r) {
    $stmt = $mysqli->prepare("INSERT INTO routes (origin_location_id, destination_location_id, distance_nautical_miles, estimated_duration_minutes, base_price, status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, 'active', ?, ?)");
    $stmt->bind_param("iididss", $r[0], $r[1], $r[2], $r[3], $r[4], $now, $now);
    $stmt->execute();
}

$mysqli->query("SET FOREIGN_KEY_CHECKS = 1;");

echo "All 7 locations and routes seeded successfully!\n";
