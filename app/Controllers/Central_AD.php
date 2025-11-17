<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\SupplierModel;
use App\Models\InventoryModel;
use App\Models\FranchiseModel;

class Central_AD extends Controller
{
    protected $supplierModel;
    protected $inventoryModel;
    protected $franchiseModel;

    public function __construct()
    {
        $this->supplierModel = new SupplierModel();
        $this->inventoryModel = new InventoryModel();
        $this->franchiseModel = new FranchiseModel();
    }



    // Inventory
    public function inventory()
    {
        $data['inventory'] = $this->inventoryModel->findAll();
        return view('managers/inventory_AD', $data);
    }

    // List Suppliers
    public function suppliers()
    {
        $data['suppliers'] = $this->supplierModel->findAll();
        return view('managers/suppliers', $data);
    }

    // Add Supplier view
    public function addSupplier()
    {
        return view('managers/createsupplier'); // Updated to match your filename
    }

    // Store Supplier
    public function storeSupplier()
    {
        $supplierModel = new \App\Models\SupplierModel();

        // Check for duplicate email
        $existingSupplier = $supplierModel->where('email', $this->request->getPost('email'))->first();
        if ($existingSupplier) {
            return redirect()->back()->withInput()->with('error', 'A supplier with this email already exists.');
        }

        // Validation rules
        $rules = [
            'supplier_name' => 'required|min_length[2]|max_length[100]',
            'contact'       => 'required|min_length[5]|max_length[100]',
            'email'         => 'required|valid_email|max_length[100]',
            'address'       => 'required|min_length[5]|max_length[255]',
            'branch_serve'  => 'required|min_length[2]|max_length[100]',
            'status'        => 'required|in_list[Active,Inactive]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Get form data
        $data = [
            'supplier_name' => $this->request->getPost('supplier_name'),
            'contact'       => $this->request->getPost('contact'),
            'email'         => $this->request->getPost('email'),
            'address'       => $this->request->getPost('address'),
            'branch_serve'  => $this->request->getPost('branch_serve'),
            'status'        => $this->request->getPost('status')
        ];

        // Insert new supplier
        if ($supplierModel->insert($data)) {
            return redirect()->to(base_url('Central_AD/suppliers'))->with('success', 'Supplier added successfully.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to add supplier. Please try again.');
        }
    }

    // Edit Supplier view
    public function editSupplier($id = null)
    {
        $data['supplier'] = $this->supplierModel->find($id);

        if (!$data['supplier']) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Supplier not found');
        }

        return view('managers/editsupplier', $data); // Updated to match your filename
    }

    // Update Supplier
    public function updateSupplier($id)
    {
        $supplierModel = new \App\Models\SupplierModel();

        // Check for duplicate email (excluding current supplier)
        $existingSupplier = $supplierModel->where('email', $this->request->getPost('email'))
                                        ->where('id !=', $id)
                                        ->first();
        if ($existingSupplier) {
            return redirect()->back()->withInput()->with('error', 'A supplier with this email already exists.');
        }

        // Validation rules
        $rules = [
            'supplier_name' => 'required|min_length[2]|max_length[100]',
            'contact'       => 'required|min_length[5]|max_length[100]',
            'email'         => 'required|valid_email|max_length[100]',
            'address'       => 'required|min_length[5]|max_length[255]',
            'branch_serve'  => 'required|min_length[2]|max_length[100]',
            'status'        => 'required|in_list[Active,Inactive]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Get form data
        $data = [
            'supplier_name' => $this->request->getPost('supplier_name'),
            'contact'       => $this->request->getPost('contact'),
            'email'         => $this->request->getPost('email'),
            'address'       => $this->request->getPost('address'),
            'branch_serve'  => $this->request->getPost('branch_serve'),
            'status'        => $this->request->getPost('status')
        ];

        // Update the supplier
        if ($supplierModel->update($id, $data)) {
            return redirect()->to(base_url('Central_AD/suppliers'))->with('success', 'Supplier updated successfully.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to update supplier. Please try again.');
        }
    }


    // Delete Supplier
    public function deleteSupplier($id = null)
    {
        if ($id !== null) {
            $this->supplierModel->delete($id);
        }
        return redirect()->to('/Central_AD/suppliers')->with('success', 'Supplier deleted successfully.');
    }

    //  INVENTORY MANAGEMENT METHODS 
    // Add Item view
    public function addItem()
    {
        return view('managers/add_item');
    }

    // Store Item
    public function storeItem()
    {
        // Validation rules
        $rules = [
            'item_name' => 'required|min_length[2]|max_length[255]',
            'type'       => 'required|min_length[2]|max_length[100]',
            'quantity'   => 'required|integer|greater_than[0]',
            'barcode'    => 'permit_empty|max_length[100]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Generate barcode if not provided
        $barcode = $this->request->getPost('barcode');
        if (empty($barcode)) {
            $barcode = 'BC' . time() . rand(100, 999);
        }

        // Get form data
        $data = [
            'item_name' => $this->request->getPost('item_name'),
            'type'       => $this->request->getPost('type'),
            'quantity'   => $this->request->getPost('quantity'),
            'barcode'    => $barcode,
            'status'     => 'available'
        ];

        // Insert new item
        if ($this->inventoryModel->insert($data)) {
            return redirect()->to(base_url('Central_AD/inventory'))->with('success', 'Item added successfully.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to add item. Please try again.');
        }
    }

    // Edit Item view
    public function editItem($id = null)
    {
        $data['item'] = $this->inventoryModel->find($id);

        if (!$data['item']) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Item not found');
        }

        return view('managers/edit_item', $data);
    }

    // Update Item
    public function updateItem($id)
    {
        // Validation rules
        $rules = [
            'item_name' => 'required|min_length[2]|max_length[255]',
            'type'       => 'required|min_length[2]|max_length[100]',
            'quantity'   => 'required|integer|greater_than[0]',
            'barcode'    => 'permit_empty|max_length[100]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Get form data
        $data = [
            'item_name' => $this->request->getPost('item_name'),
            'type'       => $this->request->getPost('type'),
            'quantity'   => $this->request->getPost('quantity'),
            'barcode'    => $this->request->getPost('barcode'),
            'status'     => $this->request->getPost('status')
        ];

        // Update the item
        if ($this->inventoryModel->update($id, $data)) {
            return redirect()->to(base_url('Central_AD/inventory'))->with('success', 'Item updated successfully.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to update item. Please try again.');
        }
    }

    // Delete Item
    public function deleteItem($id = null)
    {
        if ($id !== null) {
            $this->inventoryModel->delete($id);
        }
        return redirect()->to('/Central_AD/inventory')->with('success', 'Item deleted successfully.');
    }

    // Orders Management
    public function orders()
    {
        $purchaseOrderModel = new \App\Models\PurchaseOrderModel();
        $data['orders'] = $purchaseOrderModel->select('purchase_orders.*, suppliers.supplier_name, branches.name as branch_name')
            ->join('suppliers', 'suppliers.id = purchase_orders.supplier_id')
            ->join('branches', 'branches.id = purchase_orders.branch_id')
            ->findAll();
        return view('managers/orders', $data);
    }

    // Create Order view
    public function createOrder()
    {
        $branchModel = new \App\Models\BranchModel();
        $supplierModel = new \App\Models\SupplierModel();

        $data['branches'] = $branchModel->findAll();
        $data['suppliers'] = $supplierModel->findAll();

        return view('managers/create_order', $data);
    }

    // Store Order
    public function storeOrder()
    {
        $purchaseOrderModel = new \App\Models\PurchaseOrderModel();

        // Validation rules
        $rules = [
            'branch_id'   => 'required|integer',
            'supplier_id' => 'required|integer',
            'item_name'   => 'required|min_length[2]|max_length[255]',
            'quantity'    => 'required|integer|greater_than[0]',
            'unit_price'  => 'required|decimal|greater_than[0]',
            'order_date'  => 'required|valid_date',
            'goods_type'  => 'required|min_length[2]|max_length[100]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Calculate total amount
        $quantity = $this->request->getPost('quantity');
        $unitPrice = $this->request->getPost('unit_price');
        $totalAmount = $quantity * $unitPrice;

        // Get form data
        $data = [
            'branch_id'   => $this->request->getPost('branch_id'),
            'supplier_id' => $this->request->getPost('supplier_id'),
            'item_name'   => $this->request->getPost('item_name'),
            'quantity'    => $quantity,
            'unit_price'  => $unitPrice,
            'total_price' => $totalAmount,
            'order_date'  => $this->request->getPost('order_date'),
            'goods_type'  => $this->request->getPost('goods_type'),
            'notes'       => $this->request->getPost('notes'),
            'status'      => 'pending'
        ];

        // Insert new order
        if ($purchaseOrderModel->insert($data)) {
            return redirect()->to(base_url('Central_AD/orders'))->with('success', 'Order created successfully and is pending approval.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to create order. Please try again.');
        }
    }
    
    // FRANCHISING MANAGEMENT METHODS
    public function franchising()
    {
        $data['franchises'] = $this->franchiseModel->orderBy('created_at', 'DESC')->findAll();
        return view('managers/franchising', $data);
    }

    // Add Franchise view
    public function addFranchise()
    {
        return view('managers/createfranchise');
    }

    // Store Franchise
    public function storeFranchise()
    {
        $franchiseModel = new \App\Models\FranchiseModel();

        // Validation rules
        $rules = [
            'franchise_name' => 'required|min_length[2]|max_length[100]',
            'owner'          => 'required|min_length[2]|max_length[100]',
            'location'       => 'required|min_length[2]|max_length[100]',
            'contact'        => 'required|min_length[5]|max_length[100]',
            'status'         => 'required|in_list[active,inactive]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Get form data
        $data = [
            'franchise_name' => $this->request->getPost('franchise_name'),
            'owner'          => $this->request->getPost('owner'),
            'location'       => $this->request->getPost('location'),
            'contact'        => $this->request->getPost('contact'),
            'status'         => $this->request->getPost('status')
        ];

        // Insert new franchise
        if ($franchiseModel->insert($data)) {
            return redirect()->to(base_url('Central_AD/franchising'))->with('success', 'Franchise added successfully.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to add franchise. Please try again.');
        }
    }

    // Edit Franchise view
    public function editFranchise($id = null)
    {
        $data['franchise'] = $this->franchiseModel->find($id);

        if (!$data['franchise']) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Franchise not found');
        }

        return view('managers/editfranchise', $data);
    }

    // Update Franchise
    public function updateFranchise($id)
    {
        $franchiseModel = new \App\Models\FranchiseModel();

        // Validation rules
        $rules = [
            'franchise_name' => 'required|min_length[2]|max_length[100]',
            'owner'          => 'required|min_length[2]|max_length[100]',
            'location'       => 'required|min_length[2]|max_length[100]',
            'contact'        => 'required|min_length[5]|max_length[100]',
            'status'         => 'required|in_list[active,inactive]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Get form data
        $data = [
            'franchise_name' => $this->request->getPost('franchise_name'),
            'owner'          => $this->request->getPost('owner'),
            'location'       => $this->request->getPost('location'),
            'contact'        => $this->request->getPost('contact'),
            'status'         => $this->request->getPost('status')
        ];

        // Update the franchise
        if ($franchiseModel->update($id, $data)) {
            return redirect()->to(base_url('Central_AD/franchising'))->with('success', 'Franchise updated successfully.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to update franchise. Please try again.');
        }
    }

    // Delete Franchise
    public function deleteFranchise($id = null)
    {
        if ($id !== null) {
            $this->franchiseModel->delete($id);
        }
        return redirect()->to('/Central_AD/franchising')->with('success', 'Franchise deleted successfully.');
    }

    // Reports
    public function reports()
    {
        $inventoryModel = new \App\Models\InventoryModel();
        $supplierModel = new \App\Models\SupplierModel();
        $purchaseOrderModel = new \App\Models\PurchaseOrderModel();

        $data['totalInventory'] = $inventoryModel->countAll();
        $data['lowStockItems'] = $inventoryModel->getLowStockItems();
        $data['expiredItems'] = $inventoryModel->getExpiredItems();
        $data['supplierPerformance'] = [];

        $suppliers = $supplierModel->findAll();
        foreach ($suppliers as $supplier) {
            $data['supplierPerformance'][] = array_merge(
                ['supplier_name' => $supplier['supplier_name']],
                $supplierModel->getSupplierPerformance($supplier['id'])
            );
        }

        $data['purchaseOrders'] = $purchaseOrderModel->findAll();

        return view('managers/reports', $data);
    }

    public function branches()
    {
        $branchModel = new \App\Models\BranchModel();
        $data['branches'] = $branchModel->findAll();
        return view('managers/branches', $data);
    }

    // Add Branch view
    public function addBranch()
    {
        return view('managers/add_branch');
    }

    // Store Branch
    public function storeBranch()
    {
        $branchModel = new \App\Models\BranchModel();

        // Validation rules
        $rules = [
            'name' => 'required|min_length[2]|max_length[100]',
            'location' => 'required|min_length[2]|max_length[255]',
            'manager_name' => 'permit_empty|max_length[100]',
            'status' => 'required|in_list[active,inactive]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Get form data
        $data = [
            'name' => $this->request->getPost('name'),
            'location' => $this->request->getPost('location'),
            'manager_name' => $this->request->getPost('manager_name'),
            'status' => $this->request->getPost('status')
        ];

        // Insert new branch
        if ($branchModel->insert($data)) {
            return redirect()->to(base_url('Central_AD/branches'))->with('success', 'Branch added successfully.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to add branch. Please try again.');
        }
    }

    // Edit Branch view
    public function editBranch($id = null)
    {
        $branchModel = new \App\Models\BranchModel();
        $data['branch'] = $branchModel->find($id);

        if (!$data['branch']) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Branch not found');
        }

        return view('managers/edit_branch', $data);
    }

    // Update Branch
    public function updateBranch($id)
    {
        $branchModel = new \App\Models\BranchModel();

        // Validation rules
        $rules = [
            'name' => 'required|min_length[2]|max_length[100]',
            'location' => 'required|min_length[2]|max_length[255]',
            'manager_name' => 'permit_empty|max_length[100]',
            'status' => 'required|in_list[active,inactive]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Get form data
        $data = [
            'name' => $this->request->getPost('name'),
            'location' => $this->request->getPost('location'),
            'manager_name' => $this->request->getPost('manager_name'),
            'status' => $this->request->getPost('status')
        ];

        // Update the branch
        if ($branchModel->update($id, $data)) {
            return redirect()->to(base_url('Central_AD/branches'))->with('success', 'Branch updated successfully.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to update branch. Please try again.');
        }
    }

    // Delete Branch
    public function deleteBranch($id = null)
    {
        $branchModel = new \App\Models\BranchModel();
        if ($id !== null) {
            $branchModel->delete($id);
        }
        return redirect()->to('/Central_AD/branches')->with('success', 'Branch deleted successfully.');
    }

    public function settings() { return view('managers/settings'); }

    // Dashboard with consolidated reports
    public function dashboard()
    {
        $inventoryModel = new \App\Models\InventoryModel();
        $supplierModel = new \App\Models\SupplierModel();
        $purchaseOrderModel = new \App\Models\PurchaseOrderModel();
        $branchModel = new \App\Models\BranchModel();
        $franchiseModel = new \App\Models\FranchiseModel();

        $data['totalInventory'] = $inventoryModel->countAll();
        $data['lowStockAlerts'] = count($inventoryModel->getLowStockItems());
        $data['expiredItems'] = count($inventoryModel->getExpiredItems());
        $data['totalSuppliers'] = $supplierModel->countAll();
        $data['pendingOrders'] = $purchaseOrderModel->where('status', 'pending')->countAllResults();
        $data['activeBranches'] = $branchModel->where('status', 'active')->countAllResults();
        $data['activeFranchises'] = $franchiseModel->where('status', 'active')->countAllResults();

        // Recent activities (simplified)
        $data['recentOrders'] = $purchaseOrderModel->orderBy('created_at', 'DESC')->limit(5)->findAll();

        // Pending purchase orders with details for approval
        $data['pendingPurchaseOrders'] = $purchaseOrderModel->select('purchase_orders.*, suppliers.supplier_name, branches.name as branch_name')
            ->join('suppliers', 'suppliers.id = purchase_orders.supplier_id')
            ->join('branches', 'branches.id = purchase_orders.branch_id')
            ->where('purchase_orders.status', 'pending')
            ->findAll();

        // Inventory alerts
        $alerts = $inventoryModel->getAlerts();
        $data['inventoryAlerts'] = array_merge(
            array_slice($alerts['low_stock'], 0, 5),
            array_slice($alerts['expiring_soon'], 0, 3)
        );

        return view('managers/Central_AD', $data);
    }

    // Approve Purchase Order
    public function approvePurchaseOrder($orderId)
    {
        $purchaseOrderModel = new \App\Models\PurchaseOrderModel();
        $logModel = new \App\Models\LogModel();

        $purchaseOrderModel->approveOrder($orderId, session()->get('user_id'));

        // Log the action
        $logModel->logAction(session()->get('user_id'), 'approved_purchase_order', "Approved purchase order #$orderId");

        return redirect()->to('/Central_AD/dashboard')->with('success', 'Purchase order approved');
    }

    // Reject Purchase Order
    public function rejectPurchaseOrder($orderId)
    {
        $purchaseOrderModel = new \App\Models\PurchaseOrderModel();
        $logModel = new \App\Models\LogModel();

        $purchaseOrderModel->update($orderId, ['status' => 'rejected']);

        // Log the action
        $logModel->logAction(session()->get('user_id'), 'rejected_purchase_order', "Rejected purchase order #$orderId");

        return redirect()->to('/Central_AD/dashboard')->with('success', 'Purchase order rejected');
    }

    // Approve Purchase Request
    public function approvePurchaseRequest($requestId)
    {
        $purchaseRequestModel = new \App\Models\PurchaseRequestModel();
        $logModel = new \App\Models\LogModel();

        $purchaseRequestModel->approveRequest($requestId, session()->get('user_id'));

        // Log the action
        $logModel->logAction(session()->get('user_id'), 'approved_purchase_request', "Approved purchase request #$requestId");

        return redirect()->to('/dashboard')->with('success', 'Purchase request approved');
    }

    // Reject Purchase Request
    public function rejectPurchaseRequest($requestId)
    {
        $purchaseRequestModel = new \App\Models\PurchaseRequestModel();
        $logModel = new \App\Models\LogModel();

        $purchaseRequestModel->rejectRequest($requestId, session()->get('user_id'));

        // Log the action
        $logModel->logAction(session()->get('user_id'), 'rejected_purchase_request', "Rejected purchase request #$requestId");

        return redirect()->to('/dashboard')->with('success', 'Purchase request rejected');
    }
}
