<?php

$mysqli = new mysqli('127.0.0.1', 'root', '', 'speed_boat_db');
if ($mysqli->connect_error) {
    die('Connect Error: ' . $mysqli->connect_error);
}

$queries = [
    "UPDATE locations SET name = 'Pelabuhan Sanur', city = 'Denpasar' WHERE code = 'SANUR'",
    "UPDATE locations SET name = 'Banjar Nyuh', city = 'Nusa Penida' WHERE code = 'NPN'",
    "UPDATE locations SET name = 'Jungut Batu', city = 'Nusa Lembongan' WHERE code = 'NLB'",
    "UPDATE locations SET name = 'Pelabuhan Padangbai', city = 'Karangasem' WHERE code = 'PDB'",
    "UPDATE locations SET name = 'Gili Trawangan', city = 'Lombok Utara' WHERE code = 'GTI'",
];

foreach ($queries as $q) {
    $mysqli->query($q);
}

echo "Database locations updated successfully!\n";
