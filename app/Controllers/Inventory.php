<?php  

namespace App\Controllers;  
use App\Controllers\BaseController;  
use App\Models\InventoryModel;  

class Inventory extends BaseController  
{  
    // 📊 Dashboard  
    public function dashboard()  
    {  
        $inventoryModel = new InventoryModel();  

        $data['stockCount']  = $inventoryModel->countAll();  
        $data['lowStock']    = $inventoryModel->where('quantity <', 5)->countAllResults();  
        $data['recentItems'] = $inventoryModel->orderBy('updated_at', 'DESC')->findAll(5);  

        return view('inventory/dashboard', $data);  
    }  

    // ➕ Add stock  
    public function addStock()  
    {
        $inventoryModel = new InventoryModel();
        $branchModel = new \App\Models\BranchModel();

        if ($this->request->getMethod() === 'post') {
            // Validation rules
            $rules = [
                'item_name' => 'required|min_length[2]|max_length[255]',
                'type'      => 'permit_empty|max_length[100]',
                'quantity'  => 'required|integer|greater_than_equal_to[0]',
                'expiry_date' => 'permit_empty|valid_date',
                'branch_id' => 'permit_empty|integer',
                'barcode'   => 'permit_empty|max_length[100]'
            ];

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            // Generate barcode if not provided
            $barcode = $this->request->getPost('barcode');
            if (empty($barcode)) {
                $barcode = $inventoryModel->generateBarcode();
            }

            // Get quantity and auto-determine status
            $quantity = (int)$this->request->getPost('quantity');
            $status = 'available';
            if ($quantity <= 0) {
                $status = 'out_of_stock';
            } elseif ($quantity <= 5) {
                $status = 'low_stock';
            }

            // Prepare data
            $data = [
                'item_name'  => $this->request->getPost('item_name'),
                'type'       => $this->request->getPost('type') ?: null,
                'quantity'   => $quantity,
                'barcode'    => $barcode,
                'expiry_date'=> $this->request->getPost('expiry_date') ?: null,
                'branch_id'  => $this->request->getPost('branch_id') ?: null,
                'status'     => $status
            ];

            // Insert new item
            if ($inventoryModel->save($data)) {
                return redirect()->to(site_url('inventory/stock-list'))->with('success', 'Item added successfully!');
            } else {
                return redirect()->back()->withInput()->with('error', 'Failed to add item. Please try again.');
            }
        }

        // Load branches for dropdown
        $data['branches'] = $branchModel->getActiveBranches();
        return view('inventory/add_stock', $data);  
    }

    // ✏️ Edit stock  
    public function editStock($id = null)  
    {
        $inventoryModel = new InventoryModel();
        $branchModel = new \App\Models\BranchModel();

        if ($this->request->getMethod() === 'post') {
            $id = $this->request->getPost('id') ?: $id;
            
            // Validation rules
            $rules = [
                'item_name' => 'required|min_length[2]|max_length[255]',
                'type'      => 'permit_empty|max_length[100]',
                'quantity'  => 'required|integer|greater_than_equal_to[0]',
                'expiry_date' => 'permit_empty|valid_date',
                'branch_id' => 'permit_empty|integer',
                'barcode'   => 'permit_empty|max_length[100]'
            ];

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            // Get quantity and auto-determine status based on quantity
            $quantity = (int)$this->request->getPost('quantity');
            $requestedStatus = $this->request->getPost('status');
            
            // Always auto-update status based on quantity (override manual selection for consistency)
            if ($quantity <= 0) {
                $status = 'out_of_stock';
            } elseif ($quantity <= 5) {
                $status = 'low_stock';
            } else {
                $status = 'available';
            }
            
            // Only allow manual status override for special cases (damaged, unavailable)
            if (in_array($requestedStatus, ['damaged', 'unavailable'])) {
                $status = $requestedStatus;
            }

            // Prepare data
            $data = [
                'item_name'  => $this->request->getPost('item_name'),
                'type'       => $this->request->getPost('type') ?: null,
                'quantity'   => $quantity,
                'barcode'    => $this->request->getPost('barcode') ?: null,
                'expiry_date'=> $this->request->getPost('expiry_date') ?: null,
                'branch_id'  => $this->request->getPost('branch_id') ?: null,
                'status'     => $status
            ];

            // Update item
            if ($inventoryModel->update($id, $data)) {
                return redirect()->to(site_url('inventory/stock-list'))->with('success', 'Item updated successfully!');
            } else {
                return redirect()->back()->withInput()->with('error', 'Failed to update item. Please try again.');
            }
        }

        // Load item data
        if (!$id) {
            return redirect()->to(site_url('inventory/stock-list'))->with('error', 'Item ID is required.');
        }

        $item = $inventoryModel->find($id);
        if (!$item) {
            return redirect()->to(site_url('inventory/stock-list'))->with('error', 'Item not found.');
        }

        $data['item'] = $item;
        $data['branches'] = $branchModel->getActiveBranches();
        return view('inventory/edit_stock', $data);  
    }

    // 📋 Stock list  
    public function stockList()  
    {
        $inventoryModel = new InventoryModel();
        $data['items'] = $inventoryModel->orderBy('updated_at', 'DESC')->findAll();
        return view('inventory/stock_list', $data);  
    }

    // ⚡ Quick Update (for inline editing)
    public function quickUpdate()
    {
        $inventoryModel = new InventoryModel();
        
        if ($this->request->getMethod() !== 'post') {
            return redirect()->to(site_url('inventory/stock-list'))->with('error', 'Invalid request method.');
        }

        $id = $this->request->getPost('id');
        if (!$id) {
            return redirect()->to(site_url('inventory/stock-list'))->with('error', 'Item ID is required.');
        }

        $item = $inventoryModel->find($id);
        if (!$item) {
            return redirect()->to(site_url('inventory/stock-list'))->with('error', 'Item not found.');
        }

        $data = [];
        $quantity = $this->request->getPost('quantity');
        $status = $this->request->getPost('status');

        // Update quantity if provided
        if ($quantity !== null) {
            $quantity = (int)$quantity;
            $data['quantity'] = $quantity;
            
            // Auto-update status based on quantity
            if ($quantity <= 0) {
                $data['status'] = 'out_of_stock';
            } elseif ($quantity <= 5) {
                $data['status'] = 'low_stock';
            } else {
                $data['status'] = 'available';
            }
        }

        // Update status if provided (and quantity not provided)
        if ($status !== null && $quantity === null) {
            $data['status'] = $status;
        }

        if (empty($data)) {
            return redirect()->to(site_url('inventory/stock-list'))->with('error', 'No data to update.');
        }

        // Update the item
        if ($inventoryModel->update($id, $data)) {
            return redirect()->to(site_url('inventory/stock-list'))->with('success', 'Item updated successfully!');
        } else {
            return redirect()->to(site_url('inventory/stock-list'))->with('error', 'Failed to update item. Please try again.');
        }
    }

    // ⚠️ Alerts  
    public function alerts()  
    {  
        $inventoryModel = new InventoryModel();
        $branchModel = new \App\Models\BranchModel();
        
        // Get all alert types
        $alerts = $inventoryModel->getAlerts();
        
        // Prepare data with alert types
        $data['low_stock'] = $alerts['low_stock'];
        $data['out_of_stock'] = $alerts['out_of_stock'];
        $data['expiring_soon'] = $alerts['expiring_soon'];
        $data['expired'] = $alerts['expired'];
        $data['damaged'] = $alerts['damaged'];
        
        // Get all branches for display
        $branches = $branchModel->findAll();
        $data['branches'] = [];
        foreach ($branches as $branch) {
            $data['branches'][$branch['id']] = $branch['name'];
        }
        
        // Calculate totals
        $data['total_alerts'] = count($data['low_stock']) + 
                               count($data['out_of_stock']) + 
                               count($data['expiring_soon']) + 
                               count($data['expired']) +
                               count($data['damaged']);
        
        return view('inventory/alerts', $data);  
    }  

    // 🔄 Update stock levels (when items are used/sold)  
    public function updateStock()  
    {  
        $inventoryModel = new InventoryModel();  

        if ($this->request->getMethod() === 'post') {  
            $id       = $this->request->getPost('id');  
            $quantity = $this->request->getPost('quantity');  

            $inventoryModel->update($id, [  
                'quantity'   => $quantity,  
                'updated_at' => date('Y-m-d H:i:s')  
            ]);  
            return redirect()->to(site_url('inventory'))->with('success', 'Stock updated!');  
        }  

        $data['items'] = $inventoryModel->findAll();  
        return view('inventory/update_stock', $data);  
    }  

    // 📦 Receive deliveries (new items come in)
    public function receive_delivery()

    {
        $inventoryModel = new InventoryModel();

        if ($this->request->getMethod() === 'post') {
            $data = [
                'item_name' => $this->request->getPost('item_name'),
                'quantity'  => $this->request->getPost('quantity'),
                'type'      => $this->request->getPost('type'),
                'barcode'   => $this->request->getPost('barcode') ?: $inventoryModel->generateBarcode(),
                'expiry_date' => $this->request->getPost('expiry_date'),
                'branch_id' => $this->request->getPost('branch_id'),
                'status'    => 'available',
                'updated_at'=> date('Y-m-d H:i:s')
            ];

            $inventoryModel->save($data);
            return redirect()->to(site_url('inventory'))->with('success', 'Delivery added!');
        }

        return view('inventory/receive_delivery');
    }

    // 🛑 Report damaged/expired goods  
    public function report_damage()  
    {  
        $inventoryModel = new InventoryModel();  

        if ($this->request->getMethod() === 'post') {  
            $id     = $this->request->getPost('id');  
            $status = $this->request->getPost('status') ?? 'damaged';  

            $inventoryModel->update($id, [  
                'status'     => $status,  
                'updated_at' => date('Y-m-d H:i:s')  
            ]);  
            return redirect()->to(site_url('inventory'))->with('success', 'Item marked damaged!');  
        }  

        $data['items'] = $inventoryModel->findAll();  
        return view('inventory/report_damage', $data);  
    }  
}  