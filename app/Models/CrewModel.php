<?php

namespace App\Models;

use CodeIgniter\Model;

class CrewModel extends Model
{
    protected $table            = 'crews';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $allowedFields    = ['company_id', 'name', 'role_title', 'phone', 'status'];
    protected $useTimestamps    = true;
}
