<?php

namespace App\Models;

use CodeIgniter\Model;

class CaptainModel extends Model
{
    protected $table            = 'captains';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $allowedFields    = ['company_id', 'name', 'license_number', 'phone', 'status'];
    protected $useTimestamps    = true;
}
