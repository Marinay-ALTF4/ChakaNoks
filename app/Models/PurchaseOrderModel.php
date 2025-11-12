<?php

namespace App\Models;

use CodeIgniter\Model;

class PurchaseOrderModel extends Model
{
    protected $table = 'purchase_orders';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'supplier_id', 'branch_id', 'item_name', 'quantity', 'unit_price',
        'total_price', 'status', 'order_date', 'delivery_date', 'approved_by'
    ];
    protected $useTimestamps = false;

    // Relations
    public function supplier()
    {
        return $this->belongsTo('App\Models\SupplierModel', 'supplier_id');
    }

    public function branch()
    {
        return $this->belongsTo('App\Models\BranchModel', 'branch_id');
    }

    public function approver()
    {
        return $this->belongsTo('App\Models\UserModel', 'approved_by');
    }

    public function delivery()
    {
        return $this->hasOne('App\Models\DeliveryModel', 'order_id');
    }

    // Methods
    public function getPendingOrders()
    {
        return $this->where('status', 'pending')->findAll();
    }

    public function approveOrder($orderId, $approverId)
    {
        return $this->update($orderId, [
            'status' => 'approved',
            'approved_by' => $approverId
        ]);
    }

    public function getOrdersByBranch($branchId)
    {
        return $this->where('branch_id', $branchId)->findAll();
    }

    public function calculateTotalPrice($quantity, $unitPrice)
    {
        return $quantity * $unitPrice;
    }
}
