<?php

namespace App\Models;

use CodeIgniter\Model;

class CheckInLogModel extends Model
{
    protected $table            = 'check_in_logs';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['ticket_id', 'trip_id', 'scanned_by_user_id', 'scan_time', 'device_info', 'status'];
    protected $useTimestamps    = false;
}
