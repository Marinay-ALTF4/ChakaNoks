<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\InventoryModel;
use App\Models\PurchaseOrderModel;
use App\Models\PurchaseRequestModel;
use App\Models\BranchModel;
use App\Models\LogModel;

class BranchManager extends Controller
{
    protected $inventoryModel;
    protected $purchaseOrderModel;
    protected $purchaseRequestModel;
    protected $branchModel;
    protected $logModel;

    public function __construct()
    {
        $this->inventoryModel = new InventoryModel();
        $this->purchaseOrderModel = new PurchaseOrderModel();
        $this->purchaseRequestModel = new PurchaseRequestModel();
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
            $items = $this->request->getPost('items');

            if (empty($items)) {
                return redirect()->back()->withInput()->with('error', 'At least one item is required');
            }

            $db = \Config\Database::connect();
            $db->transStart();

            try {
                foreach ($items as $item) {
                    // Validation for each item
                    if (empty($item['item_name']) || empty($item['quantity'])) {
                        throw new \Exception('Item name and quantity are required');
                    }

                    $data = [
                        'branch_id' => $branchId,
                        'item_name' => $item['item_name'],
                        'quantity' => $item['quantity'],
                        'status' => 'pending'
                    ];

                    if (!$this->purchaseRequestModel->insert($data)) {
                        throw new \Exception('Failed to insert purchase request item');
                    }
                }

                $db->transComplete();

                if ($db->transStatus() === false) {
                    throw new \Exception('Transaction failed');
                }

                // Log the action
                $this->logModel->logAction(session()->get('user_id'), 'created_purchase_request', "Created purchase request with " . count($items) . " items");

                return redirect()->to('/branch/dashboard')->with('success', 'Purchase request submitted successfully');
            } catch (\Exception $e) {
                $db->transRollback();
                return redirect()->back()->withInput()->with('error', $e->getMessage());
            }
        }

        $data['suppliers'] = (new \App\Models\SupplierModel())->getActiveSuppliers();
        return view('branch_managers/create_purchase_request', $data);
    }

    // Get supplier items via AJAX
    public function getSupplierItems($supplierId)
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'branch_manager') {
            return $this->response->setJSON(['error' => 'Unauthorized'])->setStatusCode(401);
        }

        $inventoryModel = new InventoryModel();
        $items = $inventoryModel->where('supplier_id', $supplierId)->findAll();

        return $this->response->setJSON(['items' => $items]);
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
