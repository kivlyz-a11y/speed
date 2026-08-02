<?php

namespace App\Models;

use CodeIgniter\Model;

class BoardingManifestModel extends Model
{
    protected $table            = 'boarding_manifests';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['trip_id', 'total_checked_in', 'total_absent', 'manifest_pdf_path', 'finalized_at', 'finalized_by_user_id'];
    protected $useTimestamps    = true;
}
