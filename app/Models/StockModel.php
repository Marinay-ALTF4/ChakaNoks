<?php

namespace App\Models;

use CodeIgniter\Model;

class StockModel extends Model
{
    protected $table = 'stocks';
    protected $primaryKey = 'id';
    protected $allowedFields = ['barcode', 'name', 'type', 'quantity', 'updated_at'];
    protected $useTimestamps = false;
}
