<?php

namespace App\Models;

use CodeIgniter\Model;

class RescheduleModel extends Model
{
    protected $table            = 'reschedules';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'old_booking_id', 'new_booking_id', 'reschedule_code', 'old_trip_id',
        'new_trip_id', 'fee_amount', 'price_diff_amount', 'total_additional_pay',
        'status', 'processed_by_user_id'
    ];
    protected $useTimestamps    = true;
}
