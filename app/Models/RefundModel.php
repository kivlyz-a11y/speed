<?php

namespace App\Models;

use CodeIgniter\Model;

class RefundModel extends Model
{
    protected $table            = 'refunds';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'booking_id', 'refund_code', 'reason', 'total_paid', 'deduction_percentage',
        'deduction_amount', 'refund_amount', 'bank_name', 'account_number',
        'account_holder', 'status', 'approved_by_user_id', 'processed_at'
    ];
    protected $useTimestamps    = true;
}
