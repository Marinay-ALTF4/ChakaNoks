<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\SupplierModel;

class Central_AD extends Controller
{
    protected $supplierModel;

    public function __construct()
    {
        $this->supplierModel = new SupplierModel();
    }

    // Dashboard
    public function dashboard()
    {
        return view('managers/Central_AD');
    }

    // Inventory
    public function inventory()
    {
        return view('managers/inventory_AD');
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

    // Other pages
    public function orders() { return view('managers/orders'); }
    public function franchising() { return view('managers/franchising'); }
    public function reports() { return view('managers/reports'); }
    public function settings() { return view('managers/settings'); }
}
