<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\SupplierModel;
use App\Models\InventoryModel;

class Central_AD extends Controller
{
    protected $supplierModel;
    protected $inventoryModel;

    public function __construct()
    {
        $this->supplierModel = new SupplierModel();
        $this->inventoryModel = new InventoryModel();
    }

    // Dashboard
    public function dashboard()
    {
        return view('managers/Central_AD');
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

    // Other pages
    public function orders() { return view('managers/orders'); }
    public function franchising() { return view('managers/franchising'); }
    public function reports() { return view('managers/reports'); }
    public function settings() { return view('managers/settings'); }
}
