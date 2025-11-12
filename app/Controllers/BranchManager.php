<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\InventoryModel;
use App\Models\PurchaseOrderModel;
use App\Models\BranchModel;
use App\Models\LogModel;

class BranchManager extends Controller
{
    protected $inventoryModel;
    protected $purchaseOrderModel;
    protected $branchModel;
    protected $logModel;

    public function __construct()
    {
        $this->inventoryModel = new InventoryModel();
        $this->purchaseOrderModel = new PurchaseOrderModel();
        $this->branchModel = new BranchModel();
        $this->logModel = new LogModel();
    }

    public function dashboard()
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'branch_manager') {
            return redirect()->to('/');
        }

        $branchId = session()->get('branch_id'); // Assuming branch_id is stored in session

        $data['branchInventory'] = $this->inventoryModel->getByBranch($branchId);
        $data['lowStockItems'] = $this->inventoryModel->where('branch_id', $branchId)->where('quantity <=', 5)->findAll();
        $data['pendingTransfers'] = []; // Placeholder for transfer requests

        return view('branch_managers/dashboard', $data);
    }

    // Create Purchase Request
    public function createPurchaseRequest()
    {
        if ($this->request->getMethod() === 'post') {
            $branchId = session()->get('branch_id');

            // Validation
            $rules = [
                'supplier_id' => 'required|integer',
                'item_name' => 'required|min_length[2]|max_length[255]',
                'quantity' => 'required|integer|greater_than[0]',
                'unit_price' => 'required|numeric|greater_than[0]'
            ];

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            $data = [
                'supplier_id' => $this->request->getPost('supplier_id'),
                'branch_id' => $branchId,
                'item_name' => $this->request->getPost('item_name'),
                'quantity' => $this->request->getPost('quantity'),
                'unit_price' => $this->request->getPost('unit_price'),
                'total_price' => $this->request->getPost('quantity') * $this->request->getPost('unit_price'),
                'status' => 'pending',
                'order_date' => date('Y-m-d')
            ];

            if ($this->purchaseOrderModel->insert($data)) {
                // Log the action
                $this->logModel->logAction(session()->get('user_id'), 'created_purchase_request', "Created purchase request for {$data['item_name']}");

                return redirect()->to('/branch/dashboard')->with('success', 'Purchase request submitted successfully');
            } else {
                return redirect()->back()->withInput()->with('error', 'Failed to submit purchase request');
            }
        }

        $data['suppliers'] = (new \App\Models\SupplierModel())->getActiveSuppliers();
        return view('branch_managers/create_purchase_request', $data);
    }

    // Approve Intra-branch Transfer
    public function approveTransfer($transferId)
    {
        // Placeholder for transfer approval logic
        // Assuming a TransferModel exists

        // Log the action
        $this->logModel->logAction(session()->get('user_id'), 'approved_transfer', "Approved transfer #$transferId");

        return redirect()->to('/branch/dashboard')->with('success', 'Transfer approved');
    }

    // View Branch Inventory
    public function inventory()
    {
        $branchId = session()->get('branch_id');
        $data['inventory'] = $this->inventoryModel->getByBranch($branchId);
        return view('branch_managers/inventory', $data);
    }

    // Monitor Branch Performance
    public function performance()
    {
        $branchId = session()->get('branch_id');

        $data['totalItems'] = $this->inventoryModel->where('branch_id', $branchId)->countAllResults();
        $data['lowStockCount'] = count($this->inventoryModel->where('branch_id', $branchId)->where('quantity <=', 5)->findAll());
        $data['pendingRequests'] = $this->purchaseOrderModel->where('branch_id', $branchId)->where('status', 'pending')->countAllResults();

        return view('branch_managers/performance', $data);
    }
}
