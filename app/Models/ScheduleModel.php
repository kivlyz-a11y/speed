<?php

namespace App\Models;

use CodeIgniter\Model;

class ScheduleModel extends Model
{
    protected $table            = 'schedules';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $allowedFields    = ['route_id', 'speed_boat_id', 'captain_id', 'departure_time', 'arrival_time', 'operational_days_mask', 'adult_price', 'child_price', 'status'];
    protected $useTimestamps    = true;
}
