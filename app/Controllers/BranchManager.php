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
        
        // Get pending purchase requests count
        $data['pendingPurchaseRequests'] = $this->purchaseRequestModel
            ->where('branch_id', $branchId)
            ->where('status', 'pending')
            ->countAllResults();

        return view('branch_managers/dashboard', $data);
    }

    // Create Purchase Request
    public function createPurchaseRequest()
    {
        // Check if POST request (case-insensitive)
        $method = strtolower($this->request->getMethod());
        
        if ($method === 'post') {
            helper(['form', 'url']);
            
            $branchId = session()->get('branch_id');
            $items = $this->request->getPost('items');

            // Check if branch_id is set in session
            if (empty($branchId)) {
                return redirect()->back()->withInput()->with('error', 'Branch ID not found. Please log out and log back in.');
            }

            if (empty($items) || !is_array($items)) {
                return redirect()->back()->withInput()->with('error', 'At least one item is required');
            }

            $db = \Config\Database::connect();
            $db->transStart();

            $savedCount = 0;
            $errors = [];

            try {
                foreach ($items as $index => $item) {
                    // Skip empty items
                    if (empty($item) || (!isset($item['item_name']) && !isset($item['quantity']))) {
                        continue;
                    }

                    // Validation for each item
                    if (empty($item['item_name']) || empty($item['quantity'])) {
                        $errors[] = "Item #" . ($index + 1) . ": Item name and quantity are required";
                        continue;
                    }

                    $data = [
                        'branch_id' => $branchId,
                        'supplier_id' => !empty($item['supplier_id']) ? (int)$item['supplier_id'] : null,
                        'item_name' => trim($item['item_name']),
                        'quantity' => (int)$item['quantity'],
                        'unit' => !empty($item['unit']) ? trim($item['unit']) : null,
                        'unit_price' => !empty($item['unit_price']) ? (float)$item['unit_price'] : null,
                        'description' => !empty($item['description']) ? trim($item['description']) : null,
                        'status' => 'pending'
                    ];

                    // Get model errors if insert fails
                    if (!$this->purchaseRequestModel->insert($data)) {
                        $modelErrors = $this->purchaseRequestModel->errors();
                        $errorMsg = !empty($modelErrors) ? implode(', ', $modelErrors) : 'Failed to insert purchase request item';
                        $errors[] = "Item #" . ($index + 1) . ": " . $errorMsg;
                        continue;
                    }

                    $savedCount++;
                }

                if (empty($savedCount) && !empty($errors)) {
                    $db->transRollback();
                    return redirect()->back()->withInput()->with('error', implode('<br>', $errors));
                }

                if (empty($savedCount)) {
                    $db->transRollback();
                    return redirect()->back()->withInput()->with('error', 'No valid items to save. Please check your form data.');
                }

                $db->transComplete();

                if ($db->transStatus() === false) {
                    throw new \Exception('Transaction failed. Please try again.');
                }

                // Log the action (only if logModel is available)
                if ($this->logModel) {
                    try {
                        $this->logModel->logAction(session()->get('user_id'), 'created_purchase_request', "Created purchase request with " . $savedCount . " items");
                    } catch (\Exception $e) {
                        // Don't fail if logging fails
                    }
                }

                $successMsg = $savedCount . ' item(s) saved successfully';
                if (!empty($errors)) {
                    $successMsg .= '. Some items had errors: ' . implode(', ', $errors);
                }

                return redirect()->to('/branch/dashboard')->with('success', $successMsg);
            } catch (\Exception $e) {
                $db->transRollback();
                return redirect()->back()->withInput()->with('error', 'Error: ' . $e->getMessage());
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

        // Return empty array if no items found (instead of null)
        if (empty($items)) {
            return $this->response->setJSON(['items' => []]);
        }

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
