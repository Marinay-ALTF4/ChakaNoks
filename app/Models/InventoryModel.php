<?php

namespace App\Models;

use CodeIgniter\Model;

class InventoryModel extends Model
{
    protected $table      = 'inventory';
    protected $primaryKey = 'id';

    protected $returnType     = 'array';
    protected $allowedFields  = ['item_name', 'quantity', 'status', 'created_at', 'updated_at'];

    // timestamps
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
