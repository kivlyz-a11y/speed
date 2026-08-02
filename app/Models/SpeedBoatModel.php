<?php

namespace App\Models;

use CodeIgniter\Model;

class SpeedBoatModel extends Model
{
    protected $table            = 'speed_boats';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $allowedFields    = ['company_id', 'name', 'code', 'capacity', 'total_rows', 'total_cols', 'seat_layout_json', 'status'];
    protected $useTimestamps    = true;
}
