<?php

namespace App\Models;

use CodeIgniter\Model;

class SeatLockModel extends Model
{
    protected $table            = 'seat_locks';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['trip_id', 'seat_id', 'session_id', 'user_id', 'locked_until'];
    protected $useTimestamps    = false;
}
