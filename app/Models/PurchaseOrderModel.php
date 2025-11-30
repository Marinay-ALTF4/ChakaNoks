<?php

namespace App\Models;

use CodeIgniter\Model;

class PurchaseOrderModel extends Model
{
    protected $table = 'purchase_orders';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'purchase_request_id', 'supplier_id', 'branch_id', 'item_name', 'quantity', 'unit', 'unit_price',
        'total_price', 'description', 'status', 'order_date', 'supplier_confirmed_at', 'prepared_at',
        'delivery_date', 'approved_by'
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

    public function purchaseRequest()
    {
        return $this->belongsTo('App\Models\PurchaseRequestModel', 'purchase_request_id');
    }

    // Methods
    public function getPendingOrders()
    {
        return $this->where('status', 'pending_supplier')->findAll();
    }

    public function getSupplierPendingOrders()
    {
        return $this->where('status', 'pending_supplier')->findAll();
    }

    public function getConfirmedOrders()
    {
        return $this->where('status', 'confirmed')->findAll();
    }

    public function getPreparingOrders()
    {
        return $this->where('status', 'preparing')->findAll();
    }

    public function getReadyForDeliveryOrders()
    {
        return $this->where('status', 'ready_for_delivery')->findAll();
    }

    public function approveOrder($orderId, $approverId)
    {
        return $this->update($orderId, [
            'status' => 'approved',
            'approved_by' => $approverId
        ]);
    }

    public function confirmBySupplier($orderId)
    {
        return $this->update($orderId, [
            'status' => 'confirmed',
            'supplier_confirmed_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function markAsPreparing($orderId)
    {
        return $this->update($orderId, [
            'status' => 'preparing',
            'prepared_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function markAsReadyForDelivery($orderId)
    {
        return $this->update($orderId, [
            'status' => 'ready_for_delivery'
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
