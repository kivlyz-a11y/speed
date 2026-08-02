<?php

namespace App\Models;

use CodeIgniter\Model;

class TicketModel extends Model
{
    protected $table            = 'tickets';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'uuid', 'ticket_code', 'booking_id', 'passenger_id', 'trip_id',
        'seat_number', 'qr_code_path', 'status', 'checked_in_at'
    ];
    protected $useTimestamps    = true;
}
