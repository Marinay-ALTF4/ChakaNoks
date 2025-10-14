<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\InventoryModel;
use App\Models\SupplierModel;
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

            $data['metrics'] = [
                'totalItems' => $inventoryModel->countAll(),
                'lowStock' => $inventoryModel->where('quantity <', 5)->countAllResults(),
                'suppliers' => $supplierModel->countAll(),
            ];
            $data['recentItems'] = $inventoryModel->orderBy('updated_at', 'DESC')->findAll(5);
        } elseif ($role === 'branch_manager') {
            $db = Database::connect();
            $branchId = session()->get('branch_id');

            $data['metrics'] = [
                'branchInventoryCount' => $db->table('branch_inventory')->where('branch_id', $branchId)->countAllResults(),
                'pendingTransfers' => $db->table('transfers')->where('to_branch', $branchId)->where('status', 'pending')->countAllResults(),
                'purchaseRequests' => $db->table('purchase_requests')->where('branch_id', $branchId)->countAllResults(),
            ];
            $data['inventory'] = $db->table('branch_inventory')
                ->where('branch_id', $branchId)
                ->orderBy('updated_at', 'DESC')
                ->limit(5)
                ->get()->getResultArray();
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


