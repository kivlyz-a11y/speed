<?php

namespace App\Models;

use CodeIgniter\Model;

class VoucherModel extends Model
{
    protected $table            = 'vouchers';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'code', 'title', 'discount_type', 'discount_value', 'min_transaction',
        'max_discount', 'quota', 'used_quota', 'start_date', 'end_date', 'is_active'
    ];
    protected $useTimestamps    = true;
}
