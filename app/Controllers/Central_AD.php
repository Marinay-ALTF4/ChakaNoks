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
    $supplierModel->insert($data);

    // Redirect to suppliers list page with success message
    return redirect()->to(base_url('Central_AD/suppliers'))->with('success', 'Supplier added successfully.');
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
    $supplierModel->update($id, $data);

    // Redirect to the suppliers list page
    return redirect()->to(base_url('Central_AD/suppliers'))->with('success', 'Supplier updated successfully.');
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
