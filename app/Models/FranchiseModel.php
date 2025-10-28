<?php

namespace App\Models;

use CodeIgniter\Model;

class FranchiseModel extends Model
{
    protected $table = 'franchises';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'franchise_name', 'owner', 'location', 'contact', 'status', 'created_at', 'updated_at'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}

