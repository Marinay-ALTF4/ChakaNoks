<?php

namespace App\Models;

use CodeIgniter\Model;

class SupplierModel extends Model
{
    protected $table = 'suppliers';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'supplier_name', 'contact', 'email', 'address', 'branch_serve', 'status',
        'created_at', 'updated_at'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Relations
    public function purchaseOrders()
    {
        return $this->hasMany('App\Models\PurchaseOrderModel', 'supplier_id');
    }

    // Methods
    public function getActiveSuppliers()
    {
        return $this->where('status', 'Active')->findAll();
    }

    public function getSupplierPerformance($supplierId)
    {
        $orders = $this->db->table('purchase_orders')
                           ->where('supplier_id', $supplierId)
                           ->get()
                           ->getResultArray();

        $totalOrders = count($orders);
        $onTimeDeliveries = 0;

        foreach ($orders as $order) {
            if ($order['status'] === 'delivered' && $order['delivery_date'] <= date('Y-m-d')) {
                $onTimeDeliveries++;
            }
        }

        return [
            'total_orders' => $totalOrders,
            'on_time_delivery_rate' => $totalOrders > 0 ? ($onTimeDeliveries / $totalOrders) * 100 : 0
        ];
    }

    public function getSuppliersByBranch($branch)
    {
        return $this->where('branch_serve', $branch)->findAll();
    }

    public function updateStatus($supplierId, $status)
    {
        return $this->update($supplierId, ['status' => $status]);
    }
}
