<?php

namespace App\Models;

use CodeIgniter\Model;

class InventoryModel extends Model
{
    protected $table      = 'inventory';
    protected $primaryKey = 'id';

    protected $allowedFields = ['item_name', 'quantity', 'status', 'created_at', 'updated_at'];

    protected $useTimestamps = true;
}
