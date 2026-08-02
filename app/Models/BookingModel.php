<?php

namespace App\Models;

use CodeIgniter\Model;

class BookingModel extends Model
{
    protected $table            = 'bookings';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $allowedFields    = [
        'uuid', 'booking_code', 'user_id', 'trip_id', 'booking_type',
        'customer_name', 'customer_email', 'customer_phone',
        'total_passengers', 'total_amount', 'discount_amount', 'final_amount',
        'voucher_code', 'status', 'payment_status', 'expired_at'
    ];
    protected $useTimestamps    = true;
}
