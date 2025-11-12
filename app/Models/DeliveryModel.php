<?php

namespace App\Models;

use CodeIgniter\Model;

class DeliveryModel extends Model
{
    protected $table = 'deliveries';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'order_id', 'status', 'tracking_number', 'route', 'scheduled_date',
        'actual_date', 'logistics_coordinator_id'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Relations
    public function purchaseOrder()
    {
        return $this->belongsTo('App\Models\PurchaseOrderModel', 'order_id');
    }

    public function coordinator()
    {
        return $this->belongsTo('App\Models\UserModel', 'logistics_coordinator_id');
    }

    // Methods
    public function getPendingDeliveries()
    {
        return $this->where('status', 'pending')->findAll();
    }

    public function updateStatus($deliveryId, $status, $actualDate = null)
    {
        $data = ['status' => $status];
        if ($actualDate) {
            $data['actual_date'] = $actualDate;
        }
        return $this->update($deliveryId, $data);
    }

    public function generateTrackingNumber()
    {
        return 'TRK' . date('Ymd') . rand(1000, 9999);
    }

    public function getDeliveriesByRoute($route)
    {
        return $this->where('route', $route)->findAll();
    }

    public function optimizeRoute($deliveries)
    {
        // Simple route optimization logic (can be enhanced)
        usort($deliveries, function($a, $b) {
            return strtotime($a['scheduled_date']) - strtotime($b['scheduled_date']);
        });
        return $deliveries;
    }
}
