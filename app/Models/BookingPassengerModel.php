<?php

namespace App\Models;

use CodeIgniter\Model;

class BookingPassengerModel extends Model
{
    protected $table            = 'booking_passengers';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'booking_id', 'seat_id', 'passenger_name', 'passenger_phone',
        'passenger_type', 'seat_number', 'price'
    ];
    protected $useTimestamps    = true;
}
