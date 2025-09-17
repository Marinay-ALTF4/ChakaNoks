<?php

namespace App\Models;

use CodeIgniter\Model;

class SupplierModel extends Model
{
    protected $table = 'suppliers'; 
    protected $primaryKey = 'id';   
    protected $allowedFields = [
        'supplier_name', 'contact', 'email', 'address', 'branch_serve', 'status'
    ];
}
