<?php

namespace App\Models;

use CodeIgniter\Model;

class RouteModel extends Model
{
    protected $table         = 'routes';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $allowedFields = [
        'delivery_id',
        'integration_provider',
        'geojson',
        'polyline',
        'distance_m',
        'eta_minutes',
    ];

    public function latestForDelivery(int $deliveryId): ?array
    {
        return $this->where('delivery_id', $deliveryId)
            ->orderBy('created_at', 'DESC')
            ->first();
    }
}
