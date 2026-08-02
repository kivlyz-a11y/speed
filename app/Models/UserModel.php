<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $allowedFields    = ['uuid', 'role_id', 'name', 'email', 'phone', 'password_hash', 'avatar', 'is_active'];
    protected $useTimestamps    = true;
}
