<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\InventoryModel;
use App\Models\PurchaseOrderModel;
use App\Models\PurchaseRequestModel;
use App\Models\BranchModel;
use App\Models\LogModel;
use App\Models\SupplierModel;
use App\Models\TransferRequestModel;
use App\Models\UserModel;

class BranchManager extends Controller
{
    protected $inventoryModel;
    protected $purchaseOrderModel;
    protected $purchaseRequestModel;
    protected $branchModel;
    protected $logModel;
    protected $supplierModel;
    protected $transferRequestModel;

    public function __construct()
    {
        $this->inventoryModel = new InventoryModel();
        $this->purchaseOrderModel = new PurchaseOrderModel();
        $this->purchaseRequestModel = new PurchaseRequestModel();
        $this->branchModel = new BranchModel();
        $this->logModel = new LogModel();
        $this->supplierModel = new SupplierModel();
        $this->transferRequestModel = new TransferRequestModel();
    }

    public function dashboard()
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'branch_manager') {
            return redirect()->to('/');
        }

        $branchId = $this->resolveBranchId();

        if (empty($branchId)) {
            return redirect()->to('/dashboard')->with('error', 'Branch context could not be determined for your account. Please contact an administrator.');
        }

        $data['branchInventory'] = $this->inventoryModel->getByBranch($branchId);
        $data['lowStockItems'] = $this->inventoryModel->where('branch_id', $branchId)->where('quantity <=', 5)->findAll();
        $data['pendingTransfers'] = $this->transferRequestModel
            ->where('to_branch_id', $branchId)
            ->where('status', TransferRequestModel::STATUS_PENDING)
            ->orderBy('created_at', 'DESC')
            ->findAll();
        
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
        
        $resolvedBranchId = $this->resolveBranchId();

        if ($method === 'post') {
            helper(['form', 'url']);
            
            $branchIdInput = (int) ($this->request->getPost('branch_id') ?? 0);
            $branchId = $branchIdInput > 0 ? $branchIdInput : ($resolvedBranchId ?? 0);
            $items = $this->request->getPost('items');

            // Check if branch_id is set in session
            if (empty($branchId)) {
                return redirect()->back()->withInput()->with('error', 'Branch context could not be determined for your account. Please contact an administrator.');
            }

            if (! $this->branchModel->find($branchId)) {
                return redirect()->back()->withInput()->with('error', 'Selected branch could not be found.');
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
                    if (empty($item)) {
                        continue;
                    }

                    $supplierId = isset($item['supplier_id']) ? (int) $item['supplier_id'] : 0;
                    $itemName   = isset($item['item_name']) ? trim((string) $item['item_name']) : '';
                    $quantity   = isset($item['quantity']) ? (int) $item['quantity'] : 0;

                    if ($supplierId <= 0) {
                        $errors[] = 'Item #' . ($index + 1) . ': Supplier is required';
                        continue;
                    }

                    if (! $this->supplierModel->find($supplierId)) {
                        $errors[] = 'Item #' . ($index + 1) . ': Selected supplier was not found';
                        continue;
                    }

                    if ($itemName === '') {
                        $errors[] = 'Item #' . ($index + 1) . ': Item name is required';
                        continue;
                    }

                    if ($quantity <= 0) {
                        $errors[] = 'Item #' . ($index + 1) . ': Quantity must be greater than zero';
                        continue;
                    }

                    $unit = isset($item['unit']) ? trim((string) $item['unit']) : null;
                    $unitPriceValue = isset($item['unit_price']) && $item['unit_price'] !== ''
                        ? (float) $item['unit_price']
                        : null;

                    if ($unitPriceValue !== null && $unitPriceValue < 0) {
                        $errors[] = 'Item #' . ($index + 1) . ': Unit price must be zero or higher';
                        continue;
                    }

                    $data = [
                        'branch_id'   => $branchId,
                        'supplier_id' => $supplierId,
                        'item_name'   => $itemName,
                        'quantity'    => $quantity,
                        'unit'        => $unit ?: null,
                        'unit_price'  => $unitPriceValue,
                        'description' => isset($item['description']) ? trim((string) $item['description']) : null,
                        'status'      => 'pending',
                    ];

                    if (! $this->purchaseRequestModel->insert($data)) {
                        $modelErrors = $this->purchaseRequestModel->errors();
                        $errorMsg = !empty($modelErrors) ? implode(', ', $modelErrors) : 'Failed to insert purchase request item';
                        $errors[] = 'Item #' . ($index + 1) . ': ' . $errorMsg;
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

                return redirect()->to('/branch/purchase-request')->with('success', $successMsg);
            } catch (\Exception $e) {
                $db->transRollback();
                return redirect()->back()->withInput()->with('error', 'Error: ' . $e->getMessage());
            }
        }

        $branches = $this->branchModel->findAll();
        $branchMap = [];
        foreach ($branches as $branch) {
            $branchMap[$branch['id']] = $branch['name'] ?? ('Branch ' . $branch['id']);
        }

        $data['suppliers'] = $this->supplierModel->getActiveSuppliers();
        $data['branches'] = $branches;
        $data['branchMap'] = $branchMap;
        $data['selectedBranchId'] = $resolvedBranchId;

        $data['requestSummary'] = [
            'total'    => 0,
            'pending'  => 0,
            'approved' => 0,
            'rejected' => 0,
        ];
        $data['recentRequests'] = [];

        if (!empty($resolvedBranchId)) {
            $requests = $this->purchaseRequestModel
                ->select('purchase_requests.*, suppliers.supplier_name')
                ->join('suppliers', 'suppliers.id = purchase_requests.supplier_id', 'left')
                ->where('purchase_requests.branch_id', $resolvedBranchId)
                ->orderBy('purchase_requests.created_at', 'DESC')
                ->findAll(50);

            $summary = [
                'total'    => count($requests),
                'pending'  => 0,
                'approved' => 0,
                'rejected' => 0,
            ];

            foreach ($requests as $request) {
                $status = $request['status'] ?? 'pending';
                if (! isset($summary[$status])) {
                    $summary[$status] = 0;
                }
                $summary[$status]++;
            }

            $data['requestSummary'] = $summary;
            $data['recentRequests'] = $requests;
        }

        return view('branch_managers/create_purchase_request', $data);
    }

    private function resolveBranchId(): ?int
    {
        $branchId = session()->get('branch_id');
        if (!empty($branchId)) {
            return (int) $branchId;
        }

        $userId = session()->get('user_id');
        if ($userId) {
            $user = (new UserModel())->find($userId);
            if (!empty($user['branch_id'])) {
                $branchId = (int) $user['branch_id'];
                session()->set('branch_id', $branchId);
                return $branchId;
            }

            $managedBranch = $this->branchModel
                ->where('manager_id', $userId)
                ->orderBy('id', 'ASC')
                ->first();

            if (!empty($managedBranch['id'])) {
                $branchId = (int) $managedBranch['id'];
                (new UserModel())->update($userId, ['branch_id' => $branchId]);
                session()->set('branch_id', $branchId);
                return $branchId;
            }
        }

        return null;
    }

    // Get supplier items via AJAX
    public function getSupplierItems($supplierId)
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'branch_manager') {
            return $this->response->setJSON(['error' => 'Unauthorized'])->setStatusCode(401);
        }

        $names = [];

        $inventoryItems = $this->inventoryModel
            ->select('item_name')
            ->where('supplier_id', $supplierId)
            ->where('item_name !=', '')
            ->groupBy('item_name')
            ->orderBy('item_name', 'ASC')
            ->findAll();

        foreach ($inventoryItems as $item) {
            $name = isset($item['item_name']) ? trim((string) $item['item_name']) : '';
            if ($name !== '' && ! in_array($name, $names, true)) {
                $names[] = $name;
            }
        }

        if (empty($names)) {
            $orderItems = $this->purchaseOrderModel
                ->select('item_name')
                ->where('supplier_id', $supplierId)
                ->where('item_name IS NOT NULL', null, false)
                ->groupBy('item_name')
                ->orderBy('item_name', 'ASC')
                ->findAll();

            foreach ($orderItems as $item) {
                $name = isset($item['item_name']) ? trim((string) $item['item_name']) : '';
                if ($name !== '' && ! in_array($name, $names, true)) {
                    $names[] = $name;
                }
            }
        }

        $items = array_map(static fn ($name) => ['item_name' => $name], $names);

        return $this->response->setJSON(['items' => $items]);
    }

    // Approve Intra-branch Transfer
    public function approveTransfer(int $transferId = null)
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'branch_manager') {
            return redirect()->to('/');
        }

        $branchId = $this->resolveBranchId();
        if (empty($branchId)) {
            return redirect()->to('/dashboard')->with('error', 'Branch context could not be determined for your account. Please contact an administrator.');
        }

        if ($transferId === null) {
            $requests = $this->transferRequestModel
                ->where('to_branch_id', $branchId)
                ->orderBy('created_at', 'DESC')
                ->findAll();

            $branchIds = [];
            foreach ($requests as $request) {
                $branchIds[] = (int) ($request['from_branch_id'] ?? 0);
                $branchIds[] = (int) ($request['to_branch_id'] ?? 0);
            }

            $branchIds = array_values(array_unique(array_filter($branchIds)));
            $branchMap = [];
            if (!empty($branchIds)) {
                foreach ($this->branchModel->whereIn('id', $branchIds)->findAll() as $branch) {
                    $branchMap[$branch['id']] = $branch['name'] ?? ('Branch ' . $branch['id']);
                }
            }

            return view('branch_managers/approve_transfers', [
                'requests'  => $requests,
                'branchMap' => $branchMap,
                'branchId'  => $branchId,
            ]);
        }

        $transfer = $this->transferRequestModel->find($transferId);
        if (! $transfer || (int) ($transfer['to_branch_id'] ?? 0) !== (int) $branchId) {
            return redirect()->to('/branch/approve-transfers')->with('error', 'Transfer request not found for your branch.');
        }

        if ($transfer['status'] !== TransferRequestModel::STATUS_PENDING) {
            return redirect()->to('/branch/approve-transfers')->with('error', 'Only pending transfer requests can be approved.');
        }

        if (! $this->transferRequestModel->update($transferId, [
            'status'      => TransferRequestModel::STATUS_APPROVED,
            'approved_by' => session()->get('user_id'),
        ])) {
            $errors = $this->transferRequestModel->errors();
            $message = !empty($errors) ? implode(', ', $errors) : 'Unable to approve transfer request. Please try again.';
            return redirect()->to('/branch/approve-transfers')->with('error', $message);
        }

        if ($this->logModel) {
            try {
                $this->logModel->logAction(session()->get('user_id'), 'approved_transfer', 'Approved transfer request #' . $transferId);
            } catch (\Throwable $e) {
                // Ignore logging errors
            }
        }

        return redirect()->to('/branch/approve-transfers')->with('success', 'Transfer request approved successfully.');
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
