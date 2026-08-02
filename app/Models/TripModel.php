<?php

namespace App\Models;

use CodeIgniter\Model;

class TripModel extends Model
{
    protected $table            = 'trips';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $allowedFields    = ['schedule_id', 'trip_code', 'trip_date', 'speed_boat_id', 'captain_id', 'departure_time', 'arrival_time', 'adult_price', 'child_price', 'available_seats', 'status'];
    protected $useTimestamps    = true;
}
