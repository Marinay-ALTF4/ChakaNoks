<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\InventoryModel;
use App\Models\SupplierModel;
use App\Models\BranchModel;
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

            $data['metrics'] = [
                'totalItems' => $inventoryModel->countAll(),
                'lowStock' => $inventoryModel->where('quantity <', 5)->countAllResults(),
                'suppliers' => $supplierModel->countAll(),
                'totalBranches' => $branchModel->countAll(),
                'pendingPurchaseRequests' => $db->table('purchase_requests')->where('status', 'pending')->countAllResults(),
            ];
            $data['recentItems'] = $inventoryModel->orderBy('updated_at', 'DESC')->findAll(5);
            $data['pendingPurchaseRequests'] = $db->table('purchase_requests')
                ->select('purchase_requests.*, branches.name as branch_name')
                ->join('branches', 'branches.id = purchase_requests.branch_id')
                ->where('purchase_requests.status', 'pending')
                ->orderBy('purchase_requests.created_at', 'DESC')
                ->limit(5)
                ->get()->getResultArray();
        } elseif ($role === 'branch_manager') {
            $db = Database::connect();
            $branchId = session()->get('branch_id');

            $data['metrics'] = [
                'branchInventoryCount' => $db->table('branch_inventory')->where('branch_id', $branchId)->countAllResults(),
                'pendingTransfers' => $db->table('transfers')->where('to_branch', $branchId)->where('status', 'pending')->countAllResults(),
                'purchaseRequests' => $db->table('purchase_requests')->where('branch_id', $branchId)->where('status', 'pending')->countAllResults(),
            ];
            $data['inventory'] = $db->table('branch_inventory')
                ->where('branch_id', $branchId)
                ->orderBy('updated_at', 'DESC')
                ->limit(5)
                ->get()->getResultArray();

            // Low Stock Alerts: items with quantity < 5
            $data['lowStockAlerts'] = $db->table('branch_inventory')
                ->select('item_name, quantity')
                ->where('branch_id', $branchId)
                ->where('quantity <', 5)
                ->get()->getResultArray();

            // Activity Log: recent transfers and purchase requests
            $transfers = $db->table('transfers')
                ->select("'Transfer' as type, CONCAT('Transfer of ', quantity, ' ', item_name, ' from branch ', from_branch) as description, created_at")
                ->where('to_branch', $branchId)
                ->orWhere('from_branch', $branchId)
                ->orderBy('created_at', 'DESC')
                ->limit(3)
                ->get()->getResultArray();

            $requests = $db->table('purchase_requests')
                ->select("'Purchase Request' as type, CONCAT('Requested ', quantity, ' ', item_name) as description, created_at")
                ->where('branch_id', $branchId)
                ->orderBy('created_at', 'DESC')
                ->limit(2)
                ->get()->getResultArray();

            $data['activityLog'] = array_merge($transfers, $requests);
            usort($data['activityLog'], fn($a, $b) => strtotime($b['created_at']) <=> strtotime($a['created_at']));
            $data['activityLog'] = array_slice($data['activityLog'], 0, 5);

            // Sales Trend: simulated monthly data
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
            $data['metrics'] = [
                'stockCount' => $inventoryModel->countAll(),
                'lowStock' => $inventoryModel->where('quantity <', 5)->countAllResults(),
            ];
            $data['recentItems'] = $inventoryModel->orderBy('updated_at', 'DESC')->findAll(5);
        }

        return view('dashboard/index', $data);
    }
}


