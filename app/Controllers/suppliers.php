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

    public function dashboard()
    {
        return view('managers/Central_AD'); // na edit
    }

    public function inventory()
    {
        return view('managers/inventory_AD');
    }

    public function suppliers()
    {
        // na dungag
        $data['suppliers'] = $this->supplierModel->findAll();

        // na dungag
        return view('managers/suppliers', $data);
    }

    public function orders()
    {
        return view('managers/orders');
    }

    public function franchising()
    {
        return view('managers/franchising');
    }

    public function reports()
    {
        return view('managers/reports');
    }

    public function settings()
    {
        return view('managers/settings');
    }
}
