<?php

namespace App\Models;

use CodeIgniter\Model;

class DeliveryItemModel extends Model
{
    protected $table            = 'delivery_items';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
    protected $allowedFields    = [
        'delivery_id',
        'product_id',
        'quantity',
        'unit',
        'unit_cost',
        'expiry_date',
    ];

    public function forDelivery(int $deliveryId): array
    {
        return $this->where('delivery_id', $deliveryId)->findAll();
    }
}
