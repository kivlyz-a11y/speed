<?php

namespace App\Models;

use CodeIgniter\Model;

class PaymentModel extends Model
{
    protected $table            = 'payments';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'uuid', 'booking_id', 'payment_code', 'gateway_type', 'payment_method',
        'gross_amount', 'transaction_status', 'transaction_time', 'snap_token',
        'redirect_url', 'raw_response'
    ];
    protected $useTimestamps    = true;
}
