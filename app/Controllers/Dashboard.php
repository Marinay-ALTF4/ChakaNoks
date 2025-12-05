<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\InventoryModel;
use App\Models\SupplierModel;
use App\Models\BranchModel;
use App\Models\DeliveryModel;
use App\Models\VehicleModel;
use App\Models\DriverModel;
use App\Models\UserModel;
use App\Models\PurchaseOrderModel;
use Config\Database;

class Dashboard extends BaseController
{
    public function index()
    {
        if (! session()->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'You must be logged in.');
        }

        $role = session()->get('role');
        $userId = session()->get('user_id');

        $data = [
            'role' => $role,
            'username' => session()->get('username'),
            'alerts' => [
                'success' => session()->getFlashdata('success'),
                'error' => session()->getFlashdata('error'),
                'warning' => session()->getFlashdata('warning'),
                'info' => session()->getFlashdata('info'),
            ],
        ];

        if ($role === 'admin') {
            $inventoryModel = new InventoryModel();
            $supplierModel = new SupplierModel();
            $branchModel = new BranchModel();
            $db = Database::connect();

            $purchaseOrderModel = new PurchaseOrderModel();
            $deliveryModel = new DeliveryModel();
            
            // Count low stock items using the same logic as InventoryModel
            $lowStockItems = $inventoryModel->getLowStockItems(5);
            $lowStockCount = count($lowStockItems);
            
            $data['metrics'] = [
                'totalItems' => $inventoryModel->countAll(),
                'lowStock' => $lowStockCount,
                'suppliers' => $supplierModel->countAll(),
                'totalBranches' => $branchModel->countAll(),
                'pendingPurchaseRequests' => $db->table('purchase_requests')->where('status', 'pending')->countAllResults(),
                'pendingSupplierOrders' => (clone $purchaseOrderModel)
                    ->whereIn('status', PurchaseOrderModel::SUPPLIER_PENDING_STATUSES)
                    ->countAllResults(),
                'confirmedOrders' => $purchaseOrderModel->where('status', 'confirmed')->countAllResults(),
                'preparingOrders' => $purchaseOrderModel->where('status', 'preparing')->countAllResults(),
                'readyForDelivery' => $purchaseOrderModel->where('status', 'ready_for_delivery')->countAllResults(),
                'scheduledDeliveries' => $deliveryModel->where('status', 'scheduled')->countAllResults(),
            ];
            $data['recentItems'] = $inventoryModel->orderBy('updated_at', 'DESC')->findAll(5);
            $data['pendingPurchaseRequests'] = $db->table('purchase_requests')
                ->select('purchase_requests.*, branches.name as branch_name')
                ->join('branches', 'branches.id = purchase_requests.branch_id')
                ->where('purchase_requests.status', 'pending')
                ->orderBy('purchase_requests.created_at', 'DESC')
                ->limit(5)
                ->get()->getResultArray();
        } elseif ($role === 'supplier') {
            $supplierId = session()->get('supplier_id');

            if (empty($supplierId)) {
                $userModel = new UserModel();
                $userRow   = $userModel->select('supplier_id')->find($userId);
                if (!empty($userRow['supplier_id'])) {
                    $supplierId = (int) $userRow['supplier_id'];
                    session()->set('supplier_id', $supplierId);
                }
            }

            $data['metrics'] = [
                'pendingOrders'    => 0,
                'confirmedOrders'  => 0,
                'readyForDelivery' => 0,
                'totalOrders'      => 0,
                'activeDeliveries' => 0,
                'submittedInvoices'=> 0,
            ];
            $data['recentOrders']    = [];
            $data['activeDeliveries'] = [];
            $data['recentInvoices']   = [];

            if (empty($supplierId)) {
                $data['alerts']['warning'] = $data['alerts']['warning'] ?? 'Supplier account is not linked to a supplier profile yet. Please contact an administrator.';
            } else {
                $purchaseOrderModel = new PurchaseOrderModel();
                $deliveryModel      = new DeliveryModel();

                $data['metrics']['pendingOrders'] = (clone $purchaseOrderModel)
                    ->where('supplier_id', $supplierId)
                    ->whereIn('status', PurchaseOrderModel::SUPPLIER_PENDING_STATUSES)
                    ->countAllResults();

                $data['metrics']['confirmedOrders'] = (clone $purchaseOrderModel)
                    ->where('supplier_id', $supplierId)
                    ->whereIn('status', ['confirmed', 'preparing'])
                    ->countAllResults();

                $data['metrics']['readyForDelivery'] = (clone $purchaseOrderModel)
                    ->where('supplier_id', $supplierId)
                    ->where('status', 'ready_for_delivery')
                    ->countAllResults();

                $data['metrics']['totalOrders'] = (clone $purchaseOrderModel)
                    ->where('supplier_id', $supplierId)
                    ->countAllResults();

                $data['recentOrders'] = (new PurchaseOrderModel())
                    ->select('purchase_orders.*, branches.name as branch_name')
                    ->join('branches', 'branches.id = purchase_orders.branch_id', 'left')
                    ->where('purchase_orders.supplier_id', $supplierId)
                    ->orderBy('purchase_orders.order_date', 'DESC')
                    ->limit(5)
                    ->findAll();

                $deliveries = $deliveryModel
                    ->select('deliveries.*, purchase_orders.item_name, purchase_orders.quantity, purchase_orders.unit, branches.name as destination_branch, purchase_orders.status as order_status')
                    ->join('purchase_orders', 'purchase_orders.id = deliveries.order_id', 'left')
                    ->join('branches', 'branches.id = deliveries.destination_branch_id', 'left')
                    ->where('purchase_orders.supplier_id', $supplierId)
                    ->orderBy('deliveries.updated_at', 'DESC')
                    ->limit(5)
                    ->findAll();

                foreach ($deliveries as &$delivery) {
                    $delivery['timeline'] = $deliveryModel->getStatusTimeline((int) ($delivery['id'] ?? 0));
                }
                unset($delivery);

                $data['activeDeliveries']            = $deliveries;
                $data['metrics']['activeDeliveries'] = count($deliveries);

                if (class_exists('App\\Models\\SupplierInvoiceModel')) {
                    $invoiceModel = new \App\Models\SupplierInvoiceModel();

                    $data['metrics']['submittedInvoices'] = $invoiceModel
                        ->where('supplier_id', $supplierId)
                        ->countAllResults();

                    $data['recentInvoices'] = (new \App\Models\SupplierInvoiceModel())
                        ->where('supplier_id', $supplierId)
                        ->orderBy('submitted_at', 'DESC')
                        ->limit(5)
                        ->findAll();
                }
            }
        } elseif ($role === 'logistics_coordinator') {
            $deliveryModel = new DeliveryModel();
            $vehicleModel  = new VehicleModel();
            $driverModel   = new DriverModel();

            $data['stats'] = [
                'pending'      => (clone $deliveryModel)->where('status', DeliveryModel::STATUS_PENDING)->countAllResults(),
                'dispatched'   => (clone $deliveryModel)->where('status', DeliveryModel::STATUS_DISPATCHED)->countAllResults(),
                'inTransit'    => (clone $deliveryModel)->where('status', DeliveryModel::STATUS_IN_TRANSIT)->countAllResults(),
                'delivered'    => (clone $deliveryModel)->where('status', DeliveryModel::STATUS_DELIVERED)->countAllResults(),
                'acknowledged' => (clone $deliveryModel)->where('status', DeliveryModel::STATUS_ACKNOWLEDGED)->countAllResults(),
            ];

            $data['upcomingDeliveries'] = (clone $deliveryModel)
                ->whereIn('status', [
                    DeliveryModel::STATUS_PENDING,
                    DeliveryModel::STATUS_DISPATCHED,
                    DeliveryModel::STATUS_IN_TRANSIT,
                ])
                ->orderBy('scheduled_at', 'ASC')
                ->findAll(20);

            $data['vehicles'] = $vehicleModel->available();
            $data['drivers']  = $driverModel->findAll();
        } elseif ($role === 'branch_manager') {
            $db = Database::connect();
            $branchId = $this->resolveBranchContext($userId);

            $data['metrics'] = [
                'branchInventoryCount' => $branchId
                    ? $db->table('branch_inventory')->where('branch_id', $branchId)->countAllResults()
                    : 0,
                'pendingTransfers' => $branchId
                    ? $db->table('transfer_requests')->where('to_branch_id', $branchId)->where('status', 'pending')->countAllResults()
                    : 0,
                'purchaseRequests' => $branchId
                    ? $db->table('purchase_requests')->where('branch_id', $branchId)->where('status', 'pending')->countAllResults()
                    : 0,
            ];

            $data['inventory'] = $branchId
                ? $db->table('branch_inventory')
                    ->where('branch_id', $branchId)
                    ->orderBy('updated_at', 'DESC')
                    ->limit(5)
                    ->get()
                    ->getResultArray()
                : [];

            $data['lowStockAlerts'] = $branchId
                ? $db->table('branch_inventory')
                    ->select('item_name, quantity')
                    ->where('branch_id', $branchId)
                    ->where('quantity <', 5)
                    ->get()
                    ->getResultArray()
                : [];

            $transfers = $branchId
                ? $db->table('transfer_requests')
                    ->select("'Transfer' as type, CONCAT('Transfer request from branch ', from_branch_id, ' to ', to_branch_id) as description, created_at")
                    ->groupStart()
                        ->where('to_branch_id', $branchId)
                        ->orWhere('from_branch_id', $branchId)
                    ->groupEnd()
                    ->orderBy('created_at', 'DESC')
                    ->limit(3)
                    ->get()
                    ->getResultArray()
                : [];

            $requests = $branchId
                ? $db->table('purchase_requests')
                    ->select("'Purchase Request' as type, CONCAT('Requested ', quantity, ' ', COALESCE(unit, 'unit/s'), ' of ', item_name) as description, created_at")
                    ->where('branch_id', $branchId)
                    ->orderBy('created_at', 'DESC')
                    ->limit(2)
                    ->get()
                    ->getResultArray()
                : [];

            $data['activityLog'] = array_merge($transfers, $requests);
            usort($data['activityLog'], static fn($a, $b) => strtotime($b['created_at']) <=> strtotime($a['created_at']));
            $data['activityLog'] = array_slice($data['activityLog'], 0, 5);

            $data['salesTrend'] = [
                ['month' => 'Jan', 'sales' => 120],
                ['month' => 'Feb', 'sales' => 150],
                ['month' => 'Mar', 'sales' => 180],
                ['month' => 'Apr', 'sales' => 200],
                ['month' => 'May', 'sales' => 170],
                ['month' => 'Jun', 'sales' => 220],
            ];
        } elseif ($role === 'inventory') {
            $inventoryModel = new InventoryModel();
            
            // Count low stock items using the same logic as InventoryModel
            $lowStockItems = $inventoryModel->getLowStockItems(5);
            $lowStockCount = count($lowStockItems);
            
            $data['metrics'] = [
                'stockCount' => $inventoryModel->countAll(),
                'lowStock' => $lowStockCount,
            ];
            $data['recentItems'] = $inventoryModel->orderBy('updated_at', 'DESC')->findAll(5);
        } elseif ($role === 'system_administrator') {
            $db = Database::connect();
            $userModel = new \App\Models\UserModel();
            $logModel = new \App\Models\LogModel();
            
            // System Administrator metrics
            $data['metrics'] = [
                'totalUsers' => $userModel->countAll(),
                'activeUsers' => $db->table('users')->where('role !=', 'system_administrator')->countAllResults(),
                'totalLogs' => $logModel->countAll(),
                'recentLogs' => $logModel->getRecentLogs(10),
                'systemHealth' => [
                    'database' => 'healthy',
                    'storage' => 'normal',
                    'security' => 'secure'
                ]
            ];
            
            // Recent user activity
            $data['recentUsers'] = $userModel->orderBy('updated_at', 'DESC')->limit(5)->findAll();
            
            // System logs
            $data['systemLogs'] = $logModel->orderBy('timestamp', 'DESC')->limit(10)->findAll();
        }

        return view('dashboard/index', $data);
    }

    private function resolveBranchContext(?int $userId): ?int
    {
        $branchId = session()->get('branch_id');
        if (!empty($branchId)) {
            return (int) $branchId;
        }

        if (empty($userId)) {
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


