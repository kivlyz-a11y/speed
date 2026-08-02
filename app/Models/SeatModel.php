<?php

namespace App\Models;

use CodeIgniter\Model;

class SeatModel extends Model
{
    protected $table            = 'seats';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['speed_boat_id', 'seat_number', 'row_num', 'col_num', 'seat_class', 'is_active'];
    protected $useTimestamps    = true;
}
