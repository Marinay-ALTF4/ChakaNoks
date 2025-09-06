<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use Config\Database;

class InventoryStaff extends Controller
{
    public function dashboard()
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'inventory') {
            return redirect()->to('/');
        }

        return view('inventory/dashboard');
    }

    public function stockList()
    {
        $db = Database::connect();
        $builder = $db->table('inventory');
        $data['stocks'] = $builder->get()->getResult();

        return view('inventory/stock_list', $data);
    }

    public function addStock()
    {
        return view('inventory/add_stock');
    }

    public function saveStock()
    {
        $db = Database::connect();
        $builder = $db->table('inventory');

        $builder->insert([
            'item_name'     => $this->request->getPost('item_name'),
            'quantity'      => $this->request->getPost('quantity'),
            'branch'        => $this->request->getPost('branch'),
            'expiry_date'   => $this->request->getPost('expiry_date'),
            'barcode'       => $this->request->getPost('barcode'),
        ]);

        return redirect()->to('/inventory/stock-list')->with('success', '✅ Stock added successfully!');
    }

    public function stockAlerts()
    {
        $db = Database::connect();
        $builder = $db->table('inventory');
        $data['alerts'] = $builder->where('quantity <', 10)->get()->getResult();

        return view('inventory/alerts', $data);
    }
}
