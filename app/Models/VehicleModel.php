<?php

namespace App\Models;

use CodeIgniter\Model;

class VehicleModel extends Model
{
    protected $table         = 'vehicles';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $allowedFields = [
        'plate_no',
        'capacity',
        'driver_id',
        'status',
    ];

    public function available(): array
    {
        return $this->where('status', 'available')->findAll();
    }
}
