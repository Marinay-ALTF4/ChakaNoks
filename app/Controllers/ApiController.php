<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\PurchaseRequestModel;
use App\Models\PurchaseOrderModel;
use App\Models\DeliveryModel;

class ApiController extends Controller
{
    protected $purchaseRequestModel;
    protected $purchaseOrderModel;
    protected $deliveryModel;

    public function __construct()
    {
        $this->purchaseRequestModel = new PurchaseRequestModel();
        $this->purchaseOrderModel = new PurchaseOrderModel();
        $this->deliveryModel = new DeliveryModel();
    }

    // Get real-time purchase requests count
    public function getPurchaseRequestsCount()
    {
        if (!session()->get('logged_in')) {
            return $this->response->setJSON(['error' => 'Unauthorized'])->setStatusCode(401);
        }

        $count = $this->purchaseRequestModel->where('status', 'pending')->countAllResults();
        return $this->response->setJSON(['count' => $count]);
    }

    // Get real-time purchase requests
    public function getPurchaseRequests()
    {
        // Allow access for admin users
        if (!session()->get('logged_in') || session()->get('role') !== 'admin') {
            return $this->response->setJSON(['error' => 'Unauthorized'])->setStatusCode(401);
        }

        $db = \Config\Database::connect();
        $requests = $db->table('purchase_requests')
            ->select('purchase_requests.*, branches.name as branch_name')
            ->join('branches', 'branches.id = purchase_requests.branch_id', 'left')
            ->where('purchase_requests.status', 'pending')
            ->orderBy('purchase_requests.created_at', 'DESC')
            ->limit(10)
            ->get()
            ->getResultArray();

        return $this->response->setJSON(['requests' => $requests]);
    }

    // Get real-time supplier orders
    public function getSupplierOrders()
    {
        if (!session()->get('logged_in')) {
            return $this->response->setJSON(['error' => 'Unauthorized'])->setStatusCode(401);
        }

        $role = session()->get('role');
        $supplierId = session()->get('supplier_id');

        if ($role === 'supplier' && $supplierId) {
            $orders = $this->purchaseOrderModel
                ->select('purchase_orders.*, branches.name as branch_name')
                ->join('branches', 'branches.id = purchase_orders.branch_id', 'left')
                ->where('purchase_orders.supplier_id', $supplierId)
                ->where('purchase_orders.status', 'pending_supplier')
                ->orderBy('purchase_orders.order_date', 'ASC')
                ->findAll();
        } else {
            $orders = $this->purchaseOrderModel
                ->select('purchase_orders.*, suppliers.supplier_name, branches.name as branch_name')
                ->join('suppliers', 'suppliers.id = purchase_orders.supplier_id', 'left')
                ->join('branches', 'branches.id = purchase_orders.branch_id', 'left')
                ->where('purchase_orders.status', 'pending_supplier')
                ->orderBy('purchase_orders.order_date', 'ASC')
                ->findAll();
        }

        return $this->response->setJSON(['orders' => $orders]);
    }

    // Get real-time ready for delivery orders
    public function getReadyForDeliveryOrders()
    {
        if (!session()->get('logged_in')) {
            return $this->response->setJSON(['error' => 'Unauthorized'])->setStatusCode(401);
        }

        $orders = $this->purchaseOrderModel
            ->select('purchase_orders.*, suppliers.supplier_name, branches.name as branch_name')
            ->join('suppliers', 'suppliers.id = purchase_orders.supplier_id', 'left')
            ->join('branches', 'branches.id = purchase_orders.branch_id', 'left')
            ->where('purchase_orders.status', 'ready_for_delivery')
            ->orderBy('purchase_orders.prepared_at', 'ASC')
            ->findAll();

        return $this->response->setJSON(['orders' => $orders]);
    }

    // Get real-time workflow stats
    public function getWorkflowStats()
    {
        if (!session()->get('logged_in')) {
            return $this->response->setJSON(['error' => 'Unauthorized'])->setStatusCode(401);
        }

        $db = \Config\Database::connect();
        
        $stats = [
            'pendingPurchaseRequests' => $this->purchaseRequestModel->where('status', 'pending')->countAllResults(),
            'pendingSupplierOrders' => $this->purchaseOrderModel->where('status', 'pending_supplier')->countAllResults(),
            'confirmedOrders' => $this->purchaseOrderModel->where('status', 'confirmed')->countAllResults(),
            'preparingOrders' => $this->purchaseOrderModel->where('status', 'preparing')->countAllResults(),
            'readyForDelivery' => $this->purchaseOrderModel->where('status', 'ready_for_delivery')->countAllResults(),
            'scheduledDeliveries' => $this->deliveryModel->where('status', 'scheduled')->countAllResults(),
            'deliveredToday' => $db->table('deliveries')
                ->where('status', 'delivered')
                ->where('DATE(actual_date)', date('Y-m-d'))
                ->countAllResults(),
        ];

        return $this->response->setJSON(['stats' => $stats]);
    }

    // Get order status updates
    public function getOrderStatus($orderId)
    {
        if (!session()->get('logged_in')) {
            return $this->response->setJSON(['error' => 'Unauthorized'])->setStatusCode(401);
        }

        $order = $this->purchaseOrderModel->find($orderId);
        if (!$order) {
            return $this->response->setJSON(['error' => 'Order not found'])->setStatusCode(404);
        }

        return $this->response->setJSON(['order' => $order]);
    }

    // Get delivery status updates
    public function getDeliveryStatus($deliveryId)
    {
        if (!session()->get('logged_in')) {
            return $this->response->setJSON(['error' => 'Unauthorized'])->setStatusCode(401);
        }

        $delivery = $this->deliveryModel->find($deliveryId);
        if (!$delivery) {
            return $this->response->setJSON(['error' => 'Delivery not found'])->setStatusCode(404);
        }

        return $this->response->setJSON(['delivery' => $delivery]);
    }
}

