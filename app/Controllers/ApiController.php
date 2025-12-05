<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\BranchModel;
use App\Models\PurchaseRequestModel;
use App\Models\PurchaseOrderModel;
use App\Models\DeliveryModel;
use App\Models\UserModel;

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

        $role = session()->get('role');

        if ($role === 'branch_manager') {
            $branchId = $this->resolveBranchContext();
            $count = $branchId
                ? (clone $this->purchaseRequestModel)
                    ->where('branch_id', $branchId)
                    ->where('status', 'pending')
                    ->countAllResults()
                : 0;
        } else {
            $count = (clone $this->purchaseRequestModel)
                ->where('status', 'pending')
                ->countAllResults();
        }

        return $this->response->setJSON(['count' => $count]);
    }

    // Get real-time purchase requests
    public function getPurchaseRequests()
    {
        if (!session()->get('logged_in')) {
            return $this->response->setJSON(['error' => 'Unauthorized'])->setStatusCode(401);
        }

        $db = \Config\Database::connect();
        $role = session()->get('role');

        if ($role !== 'admin' && $role !== 'branch_manager') {
            return $this->response->setJSON(['error' => 'Unauthorized'])->setStatusCode(401);
        }

        $builder = $db->table('purchase_requests')
            ->select('purchase_requests.*, branches.name as branch_name')
            ->join('branches', 'branches.id = purchase_requests.branch_id', 'left')
            ->where('purchase_requests.status', 'pending')
            ->orderBy('purchase_requests.created_at', 'DESC')
            ->limit(10);

        if ($role === 'branch_manager') {
            $branchId = $this->resolveBranchContext();
            if ($branchId) {
                $builder->where('purchase_requests.branch_id', $branchId);
            } else {
                return $this->response->setJSON(['requests' => []]);
            }
        }

        $requests = $builder->get()->getResultArray();

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

        if ($role === 'supplier' && empty($supplierId)) {
            $userId = (int) (session()->get('user_id') ?? 0);
            if ($userId > 0) {
                $user = (new UserModel())->select('supplier_id')->find($userId);
                if (!empty($user['supplier_id'])) {
                    $supplierId = (int) $user['supplier_id'];
                    session()->set('supplier_id', $supplierId);
                }
            }
        }

        $pendingStatuses = PurchaseOrderModel::SUPPLIER_PENDING_STATUSES;

        if ($role === 'supplier' && $supplierId) {
            $orders = $this->purchaseOrderModel
                ->select('purchase_orders.*, branches.name as branch_name')
                ->join('branches', 'branches.id = purchase_orders.branch_id', 'left')
                ->where('purchase_orders.supplier_id', $supplierId)
                ->whereIn('purchase_orders.status', $pendingStatuses)
                ->orderBy('purchase_orders.order_date', 'ASC')
                ->findAll();
        } else {
            $orders = $this->purchaseOrderModel
                ->select('purchase_orders.*, suppliers.supplier_name, branches.name as branch_name')
                ->join('suppliers', 'suppliers.id = purchase_orders.supplier_id', 'left')
                ->join('branches', 'branches.id = purchase_orders.branch_id', 'left')
                ->whereIn('purchase_orders.status', $pendingStatuses)
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
        $pendingStatuses = PurchaseOrderModel::SUPPLIER_PENDING_STATUSES;
        $role = session()->get('role');

        if ($role === 'branch_manager') {
            $branchId = $this->resolveBranchContext();

            $stats = [
                'pendingPurchaseRequests' => $branchId
                    ? (clone $this->purchaseRequestModel)
                        ->where('branch_id', $branchId)
                        ->where('status', 'pending')
                        ->countAllResults()
                    : 0,
                'pendingSupplierOrders' => $branchId
                    ? (clone $this->purchaseOrderModel)
                        ->where('branch_id', $branchId)
                        ->whereIn('status', $pendingStatuses)
                        ->countAllResults()
                    : 0,
                'confirmedOrders' => $branchId
                    ? (clone $this->purchaseOrderModel)
                        ->where('branch_id', $branchId)
                        ->where('status', 'confirmed')
                        ->countAllResults()
                    : 0,
                'preparingOrders' => $branchId
                    ? (clone $this->purchaseOrderModel)
                        ->where('branch_id', $branchId)
                        ->where('status', 'preparing')
                        ->countAllResults()
                    : 0,
                'readyForDelivery' => $branchId
                    ? (clone $this->purchaseOrderModel)
                        ->where('branch_id', $branchId)
                        ->where('status', 'ready_for_delivery')
                        ->countAllResults()
                    : 0,
                'scheduledDeliveries' => $branchId
                    ? (clone $this->deliveryModel)
                        ->where('destination_branch_id', $branchId)
                        ->where('status', 'scheduled')
                        ->countAllResults()
                    : 0,
                'deliveredToday' => $branchId
                    ? $db->table('deliveries')
                        ->where('destination_branch_id', $branchId)
                        ->where('status', 'delivered')
                        ->where('DATE(delivered_at)', date('Y-m-d'))
                        ->countAllResults()
                    : 0,
            ];
        } else {
            $stats = [
                'pendingPurchaseRequests' => (clone $this->purchaseRequestModel)
                    ->where('status', 'pending')
                    ->countAllResults(),
                'pendingSupplierOrders' => (clone $this->purchaseOrderModel)
                    ->whereIn('status', $pendingStatuses)
                    ->countAllResults(),
                'confirmedOrders' => (clone $this->purchaseOrderModel)
                    ->where('status', 'confirmed')
                    ->countAllResults(),
                'preparingOrders' => (clone $this->purchaseOrderModel)
                    ->where('status', 'preparing')
                    ->countAllResults(),
                'readyForDelivery' => (clone $this->purchaseOrderModel)
                    ->where('status', 'ready_for_delivery')
                    ->countAllResults(),
                'scheduledDeliveries' => (clone $this->deliveryModel)
                    ->where('status', 'scheduled')
                    ->countAllResults(),
                'deliveredToday' => $db->table('deliveries')
                    ->where('status', 'delivered')
                    ->where('DATE(delivered_at)', date('Y-m-d'))
                    ->countAllResults(),
            ];
        }

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

    private function resolveBranchContext(): ?int
    {
        $branchId = session()->get('branch_id');
        if (!empty($branchId)) {
            return (int) $branchId;
        }

        $userId = (int) (session()->get('user_id') ?? 0);
        if ($userId <= 0) {
            return null;
        }

        $userModel = new UserModel();
        $user = $userModel->select('branch_id')->find($userId);
        if (!empty($user['branch_id'])) {
            $branchId = (int) $user['branch_id'];
            session()->set('branch_id', $branchId);
            return $branchId;
        }

        $branchModel = new BranchModel();
        $managedBranch = $branchModel->where('manager_id', $userId)->orderBy('id', 'ASC')->first();
        if (!empty($managedBranch['id'])) {
            $branchId = (int) $managedBranch['id'];
            session()->set('branch_id', $branchId);
            $userModel->update($userId, ['branch_id' => $branchId]);
            return $branchId;
        }

        return null;
    }
}

