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
        // Get all inventory items ordered by most recently updated
        $data['inventory'] = $this->inventoryModel
            ->orderBy('updated_at', 'DESC')
            ->findAll();
        
        // Get summary statistics
        $db = \Config\Database::connect();
        $totalQuantityResult = $db->table('inventory')->selectSum('quantity')->get()->getRow();
        
        // Count available items (including low stock items as available, but excluding expired items)
        $today = date('Y-m-d');
        $availableItems = $db->table('inventory')
            ->groupStart()
                ->where('status', 'available')
                ->orWhere('status', 'low_stock')
            ->groupEnd()
            ->groupStart()
                ->where('expiry_date >=', $today)
                ->orWhere('expiry_date IS NULL', null, false)
                ->orWhere('expiry_date', '')
            ->groupEnd()
            ->countAllResults();
        
        $data['stats'] = [
            'totalItems' => $this->inventoryModel->countAll(),
            'totalQuantity' => $totalQuantityResult->quantity ?? 0,
            'availableItems' => $availableItems,
            'expiringSoon' => count($this->inventoryModel->getExpiringSoon(7)),
        ];
        
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
            'quantity'   => 'required|integer|greater_than_equal_to[0]',
            'barcode'    => 'permit_empty|max_length[100]',
            'expiry_date' => 'permit_empty|valid_date',
            'branch_id' => 'permit_empty|integer'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Generate barcode if not provided
        $barcode = $this->request->getPost('barcode');
        if (empty($barcode)) {
            $barcode = $this->inventoryModel->generateBarcode();
        }

        // Get quantity and auto-determine status
        $quantity = (int)$this->request->getPost('quantity');
        $status = 'available';
        if ($quantity <= 0) {
            $status = 'out_of_stock';
        } elseif ($quantity <= 5) {
            $status = 'low_stock';
        }

        // Get form data
        $data = [
            'item_name' => $this->request->getPost('item_name'),
            'type'       => $this->request->getPost('type'),
            'quantity'   => $quantity,
            'barcode'    => $barcode,
            'expiry_date'=> $this->request->getPost('expiry_date') ?: null,
            'branch_id'  => $this->request->getPost('branch_id') ?: null,
            'status'     => $status
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
            'quantity'   => 'required|integer|greater_than_equal_to[0]',
            'barcode'    => 'permit_empty|max_length[100]',
            'expiry_date' => 'permit_empty|valid_date',
            'branch_id' => 'permit_empty|integer'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Get quantity and determine status
        $quantity = (int)$this->request->getPost('quantity');
        $requestedStatus = $this->request->getPost('status');
        
        // Calculate what status should be based on quantity
        $autoStatus = 'available';
        if ($quantity <= 0) {
            $autoStatus = 'out_of_stock';
        } elseif ($quantity <= 5) {
            $autoStatus = 'low_stock';
        }
        
        // Respect manual status selection if it's a valid choice
        // Allow manual override for: low_stock, out_of_stock, available, damaged, unavailable
        if (in_array($requestedStatus, ['low_stock', 'out_of_stock', 'available', 'damaged', 'unavailable'])) {
            $status = $requestedStatus;
        } else {
            // If no valid status selected, use auto-calculated status
            $status = $autoStatus;
        }
        
        // Special handling: if status is damaged or unavailable, keep it regardless of quantity
        // Otherwise, if quantity suggests a different status and user selected a standard status,
        // we still respect their choice but log it (they might want to mark it low_stock even if quantity is higher)

        // Get form data
        $data = [
            'item_name' => $this->request->getPost('item_name'),
            'type'       => $this->request->getPost('type'),
            'quantity'   => $quantity,
            'barcode'    => $this->request->getPost('barcode') ?: null,
            'expiry_date'=> $this->request->getPost('expiry_date') ?: null,
            'branch_id'  => $this->request->getPost('branch_id') ?: null,
            'status'     => $status
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
        $data['orders'] = $purchaseOrderModel
            ->select('purchase_orders.*, suppliers.supplier_name, branches.name as branch_name')
            ->join('suppliers', 'suppliers.id = purchase_orders.supplier_id')
            ->join('branches', 'branches.id = purchase_orders.branch_id')
            ->orderBy('purchase_orders.created_at', 'DESC')
            ->findAll();
        return view('managers/orders', $data);
    }

    // Supplier Order Management - View pending orders for supplier
    public function supplierOrders()
    {
        $purchaseOrderModel = new \App\Models\PurchaseOrderModel();
        $data['pendingOrders'] = $purchaseOrderModel
            ->select('purchase_orders.*, suppliers.supplier_name, branches.name as branch_name')
            ->join('suppliers', 'suppliers.id = purchase_orders.supplier_id')
            ->join('branches', 'branches.id = purchase_orders.branch_id')
            ->where('purchase_orders.status', 'pending_supplier')
            ->orderBy('purchase_orders.order_date', 'ASC')
            ->findAll();
        
        $data['confirmedOrders'] = $purchaseOrderModel
            ->select('purchase_orders.*, suppliers.supplier_name, branches.name as branch_name')
            ->join('suppliers', 'suppliers.id = purchase_orders.supplier_id')
            ->join('branches', 'branches.id = purchase_orders.branch_id')
            ->where('purchase_orders.status', 'confirmed')
            ->orderBy('purchase_orders.supplier_confirmed_at', 'DESC')
            ->findAll();
        
        $data['preparingOrders'] = $purchaseOrderModel
            ->select('purchase_orders.*, suppliers.supplier_name, branches.name as branch_name')
            ->join('suppliers', 'suppliers.id = purchase_orders.supplier_id')
            ->join('branches', 'branches.id = purchase_orders.branch_id')
            ->where('purchase_orders.status', 'preparing')
            ->orderBy('purchase_orders.prepared_at', 'DESC')
            ->findAll();
        
        return view('managers/supplier_orders', $data);
    }

    // Supplier confirms order
    public function confirmSupplierOrder($orderId)
    {
        $purchaseOrderModel = new \App\Models\PurchaseOrderModel();
        $logModel = new \App\Models\LogModel();

        if ($purchaseOrderModel->confirmBySupplier($orderId)) {
            $logModel->logAction(session()->get('user_id'), 'supplier_confirmed_order', "Supplier confirmed order #$orderId");
            return redirect()->to('/Central_AD/supplier-orders')->with('success', 'Order confirmed successfully');
        }
        
        return redirect()->back()->with('error', 'Failed to confirm order');
    }

    // Supplier marks order as preparing
    public function markOrderPreparing($orderId)
    {
        $purchaseOrderModel = new \App\Models\PurchaseOrderModel();
        $logModel = new \App\Models\LogModel();

        if ($purchaseOrderModel->markAsPreparing($orderId)) {
            $logModel->logAction(session()->get('user_id'), 'order_preparing', "Order #$orderId marked as preparing");
            return redirect()->to('/Central_AD/supplier-orders')->with('success', 'Order marked as preparing');
        }
        
        return redirect()->back()->with('error', 'Failed to update order status');
    }

    // Supplier marks order as ready for delivery
    public function markOrderReadyForDelivery($orderId)
    {
        $purchaseOrderModel = new \App\Models\PurchaseOrderModel();
        $logModel = new \App\Models\LogModel();

        if ($purchaseOrderModel->markAsReadyForDelivery($orderId)) {
            $logModel->logAction(session()->get('user_id'), 'order_ready_delivery', "Order #$orderId marked as ready for delivery");
            return redirect()->to('/Central_AD/supplier-orders')->with('success', 'Order marked as ready for delivery');
        }
        
        return redirect()->back()->with('error', 'Failed to update order status');
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
        $db = \Config\Database::connect();
        $purchaseOrderModel = new \App\Models\PurchaseOrderModel();
        $userModel = new \App\Models\UserModel();
        $inventoryModel = new \App\Models\InventoryModel();

        // Total Sales - Sum of total_price from all delivered/completed purchase orders
        $totalSales = $db->table('purchase_orders')
            ->selectSum('total_price', 'total')
            ->whereIn('status', ['delivered', 'confirmed', 'ready_for_delivery'])
            ->get()
            ->getRowArray();
        $data['totalSales'] = $totalSales['total'] ?? 0;

        // Total Orders - Count of all purchase orders
        $data['totalOrders'] = $purchaseOrderModel->countAllResults();

        // New Customers - Users created in the last 30 days
        $thirtyDaysAgo = date('Y-m-d H:i:s', strtotime('-30 days'));
        $data['newCustomers'] = $userModel->where('created_at >=', $thirtyDaysAgo)->countAllResults();

        // Top Product - Most ordered item
        $topProduct = $db->table('purchase_orders')
            ->select('item_name, SUM(quantity) as total_quantity')
            ->groupBy('item_name')
            ->orderBy('total_quantity', 'DESC')
            ->limit(1)
            ->get()
            ->getRowArray();
        $data['topProduct'] = $topProduct['item_name'] ?? 'N/A';

        // Sales Trend - Last 7 days
        $salesTrend = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $dayName = date('D', strtotime("-$i days"));
            $nextDate = date('Y-m-d', strtotime("-$i days +1 day"));
            $daySales = $db->table('purchase_orders')
                ->selectSum('total_price', 'total')
                ->where('order_date >=', $date . ' 00:00:00')
                ->where('order_date <', $nextDate . ' 00:00:00')
                ->whereIn('status', ['delivered', 'confirmed', 'ready_for_delivery'])
                ->get()
                ->getRowArray();
            $salesTrend[] = [
                'day' => $dayName,
                'sales' => floatval($daySales['total'] ?? 0)
            ];
        }
        $data['salesTrend'] = $salesTrend;

        // Top 5 Products - Best selling items by quantity
        $topProducts = $db->table('purchase_orders')
            ->select('item_name, SUM(quantity) as total_quantity')
            ->groupBy('item_name')
            ->orderBy('total_quantity', 'DESC')
            ->limit(5)
            ->get()
            ->getResultArray();
        $data['topProducts'] = $topProducts;

        // Recent Reports - Generated reports (using purchase orders as report data)
        $recentReports = $db->table('purchase_orders')
            ->select('purchase_orders.*, users.username as generated_by, branches.name as branch_name')
            ->join('users', 'users.id = purchase_orders.approved_by', 'left')
            ->join('branches', 'branches.id = purchase_orders.branch_id', 'left')
            ->whereIn('purchase_orders.status', ['delivered', 'confirmed'])
            ->orderBy('purchase_orders.order_date', 'DESC')
            ->limit(10)
            ->get()
            ->getResultArray();
        $data['recentReports'] = $recentReports;

        // Additional data for context
        $data['totalInventory'] = $inventoryModel->countAll();
        $data['lowStockItems'] = $inventoryModel->getLowStockItems();

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
        $data['pendingSupplierOrders'] = $purchaseOrderModel->where('status', 'pending_supplier')->countAllResults();
        $data['confirmedOrders'] = $purchaseOrderModel->where('status', 'confirmed')->countAllResults();
        $data['preparingOrders'] = $purchaseOrderModel->where('status', 'preparing')->countAllResults();
        $data['readyForDelivery'] = $purchaseOrderModel->where('status', 'ready_for_delivery')->countAllResults();
        $data['activeBranches'] = $branchModel->where('status', 'active')->countAllResults();
        $data['activeFranchises'] = $franchiseModel->where('status', 'active')->countAllResults();

        // Recent activities (simplified)
        $data['recentOrders'] = $purchaseOrderModel->orderBy('order_date', 'DESC')->limit(5)->findAll();

        // Get orders by status for workflow tracking
        $data['pendingSupplierOrdersList'] = $purchaseOrderModel
            ->select('purchase_orders.*, suppliers.supplier_name, branches.name as branch_name')
            ->join('suppliers', 'suppliers.id = purchase_orders.supplier_id')
            ->join('branches', 'branches.id = purchase_orders.branch_id')
            ->where('purchase_orders.status', 'pending_supplier')
            ->orderBy('purchase_orders.order_date', 'ASC')
            ->findAll();
        
        $data['readyForDeliveryOrders'] = $purchaseOrderModel
            ->select('purchase_orders.*, suppliers.supplier_name, branches.name as branch_name')
            ->join('suppliers', 'suppliers.id = purchase_orders.supplier_id')
            ->join('branches', 'branches.id = purchase_orders.branch_id')
            ->where('purchase_orders.status', 'ready_for_delivery')
            ->orderBy('purchase_orders.prepared_at', 'ASC')
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
        // Check if user is logged in and is admin
        if (!session()->get('logged_in') || session()->get('role') !== 'admin') {
            return redirect()->to('/')->with('error', 'Unauthorized access');
        }

        $purchaseRequestModel = new \App\Models\PurchaseRequestModel();
        $purchaseOrderModel = new \App\Models\PurchaseOrderModel();
        $logModel = new \App\Models\LogModel();

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Get the purchase request
            $request = $purchaseRequestModel->find($requestId);
            
            if (!$request) {
                throw new \Exception('Purchase request not found');
            }

            // Check if request is already approved or rejected
            if ($request['status'] !== 'pending') {
                return redirect()->to('/dashboard')->with('error', 'Purchase request has already been ' . $request['status']);
            }

            // Validate required fields
            if (empty($request['branch_id'])) {
                throw new \Exception('Branch ID is missing in purchase request');
            }

            if (empty($request['supplier_id'])) {
                throw new \Exception('Supplier ID is missing. Please ensure the purchase request has a supplier assigned.');
            }

            // Approve the request
            if (!$purchaseRequestModel->approveRequest($requestId, session()->get('user_id'))) {
                $errors = $purchaseRequestModel->errors();
                throw new \Exception('Failed to approve request: ' . (!empty($errors) ? implode(', ', $errors) : 'Unknown error'));
            }

            // Create Purchase Order from approved request
            $totalPrice = ($request['quantity'] ?? 0) * ($request['unit_price'] ?? 0);
            
            $orderData = [
                'purchase_request_id' => $requestId,
                'supplier_id' => $request['supplier_id'],
                'branch_id' => $request['branch_id'],
                'item_name' => $request['item_name'],
                'quantity' => $request['quantity'],
                'unit' => $request['unit'] ?? null,
                'unit_price' => $request['unit_price'] ?? null,
                'total_price' => $totalPrice,
                'description' => $request['description'] ?? null,
                'status' => 'pending_supplier', // Goes to supplier for confirmation
                'order_date' => date('Y-m-d H:i:s'),
                'approved_by' => session()->get('user_id')
            ];

            if (!$purchaseOrderModel->insert($orderData)) {
                $errors = $purchaseOrderModel->errors();
                throw new \Exception('Failed to create purchase order: ' . (!empty($errors) ? implode(', ', $errors) : 'Unknown error'));
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Transaction failed. Please try again.');
            }

            // Log the action
            try {
                $logModel->logAction(session()->get('user_id'), 'approved_purchase_request', "Approved purchase request #$requestId and created purchase order");
            } catch (\Exception $e) {
                // Log error but don't fail the approval
                log_message('warning', 'Failed to log approval action: ' . $e->getMessage());
            }

            return redirect()->to('/dashboard')->with('success', 'Purchase request approved and sent to supplier for confirmation');
        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Purchase Request Approval Error: ' . $e->getMessage());
            return redirect()->to('/dashboard')->with('error', 'Error: ' . $e->getMessage());
        }
    }

    // Reject Purchase Request
    public function rejectPurchaseRequest($requestId)
    {
        // Check if user is logged in and is admin
        if (!session()->get('logged_in') || session()->get('role') !== 'admin') {
            return redirect()->to('/')->with('error', 'Unauthorized access');
        }

        $purchaseRequestModel = new \App\Models\PurchaseRequestModel();
        $logModel = new \App\Models\LogModel();

        try {
            // Get the purchase request
            $request = $purchaseRequestModel->find($requestId);
            
            if (!$request) {
                return redirect()->to('/dashboard')->with('error', 'Purchase request not found');
            }

            // Check if request is already processed
            if ($request['status'] !== 'pending') {
                return redirect()->to('/dashboard')->with('error', 'Purchase request has already been ' . $request['status']);
            }

            if (!$purchaseRequestModel->rejectRequest($requestId, session()->get('user_id'))) {
                $errors = $purchaseRequestModel->errors();
                return redirect()->to('/dashboard')->with('error', 'Failed to reject request: ' . (!empty($errors) ? implode(', ', $errors) : 'Unknown error'));
            }

            // Log the action
            try {
                $logModel->logAction(session()->get('user_id'), 'rejected_purchase_request', "Rejected purchase request #$requestId");
            } catch (\Exception $e) {
                log_message('warning', 'Failed to log rejection action: ' . $e->getMessage());
            }

            return redirect()->to('/dashboard')->with('success', 'Purchase request rejected');
        } catch (\Exception $e) {
            log_message('error', 'Purchase Request Rejection Error: ' . $e->getMessage());
            return redirect()->to('/dashboard')->with('error', 'Error: ' . $e->getMessage());
        }
    }

    // Settings
    public function settings()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/');
        }

        $userModel = new \App\Models\UserModel();
        $userId = session()->get('user_id');
        $user = $userModel->find($userId);

        if (!$user) {
            return redirect()->to('/dashboard')->with('error', 'User not found');
        }

        $data['user'] = $user;
        $data['preferences'] = [
            'theme' => session()->get('theme') ?? 'light',
            'language' => session()->get('language') ?? 'english',
            'notifications' => session()->get('notifications') ?? true
        ];

        return view('managers/settings', $data);
    }

    // Update Profile
    public function updateProfile()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/');
        }

        $userModel = new \App\Models\UserModel();
        $userId = session()->get('user_id');

        $rules = [
            'username' => "required|min_length[3]|max_length[50]|is_unique[users.username,id,{$userId}]",
            'email' => "required|valid_email|is_unique[users.email,id,{$userId}]"
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email')
        ];

        if ($userModel->update($userId, $data)) {
            // Update session username
            session()->set('username', $data['username']);
            return redirect()->to('Central_AD/settings')->with('success', 'Profile updated successfully.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to update profile. Please try again.');
        }
    }

    // Update Password
    public function updatePassword()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/');
        }

        $userModel = new \App\Models\UserModel();
        $userId = session()->get('user_id');
        $user = $userModel->find($userId);

        $currentPassword = $this->request->getPost('current_password');
        $newPassword = $this->request->getPost('new_password');
        $confirmPassword = $this->request->getPost('confirm_password');

        // Validate current password
        if (!password_verify($currentPassword, $user['password'])) {
            return redirect()->back()->with('error', 'Current password is incorrect.');
        }

        // Validate new password
        if (strlen($newPassword) < 6) {
            return redirect()->back()->with('error', 'New password must be at least 6 characters long.');
        }

        // Validate password confirmation
        if ($newPassword !== $confirmPassword) {
            return redirect()->back()->with('error', 'New password and confirmation do not match.');
        }

        // Update password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        if ($userModel->update($userId, ['password' => $hashedPassword])) {
            return redirect()->to('Central_AD/settings')->with('success', 'Password updated successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to update password. Please try again.');
        }
    }

    // Update Preferences
    public function updatePreferences()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/');
        }

        $theme = $this->request->getPost('theme') ?? 'light';
        $language = $this->request->getPost('language') ?? 'english';
        $notifications = $this->request->getPost('notifications') === 'on' ? true : false;

        session()->set([
            'theme' => $theme,
            'language' => $language,
            'notifications' => $notifications
        ]);

        return redirect()->to('Central_AD/settings')->with('success', 'Preferences updated successfully.');
    }
}
