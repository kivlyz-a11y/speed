<?php

namespace App\Models;

use CodeIgniter\Model;

class RouteModel extends Model
{
    protected $table            = 'routes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $allowedFields    = ['origin_location_id', 'destination_location_id', 'distance_nautical_miles', 'estimated_duration_minutes', 'base_price', 'status'];
    protected $useTimestamps    = true;
}
