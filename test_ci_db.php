<?php

require __DIR__ . '/vendor/autoload.php';

// Bootstrap CI4
$app = \Config\Services::codeigniter();
$app->initialize();

$locationModel = new \App\Models\LocationModel();
$locations = $locationModel->where('status', 'active')->findAll();

echo "=== CURRENT LOCATIONS IN DATABASE ===\n";
foreach ($locations as $loc) {
    echo "ID: {$loc['id']} | Code: {$loc['code']} | Name: {$loc['name']} | City: {$loc['city']}\n";
}
