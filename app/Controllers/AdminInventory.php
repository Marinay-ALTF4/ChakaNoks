<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class AdminInventory extends Controller
{
    public function dashboard()
    {
        // ✅ Restrict to admins only
        if (!session()->get('logged_in') || session()->get('role') !== 'admin') {
            return redirect()->to('/');
        }

        // ✅ Load your actual file app/Views/managers/inventory_AD.php
        return view('managers/inventory_AD');
    }


    public function addStock()
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'admin') {
            return redirect()->to('/');
        }

        return view('admin_inventory/add_stock');
    }

    public function saveStock()
    {
        // Here you’ll later add logic to save stock into DB
        return redirect()->to('/admin/inventory')->with('success', 'Stock added!');
    }

    public function editStock($id)
    {
        return view('admin_inventory/edit_stock', ['id' => $id]);
    }

    public function updateStock($id)
    {
        return redirect()->to('/admin/inventory')->with('success', 'Stock updated!');
    }

    public function deleteStock($id)
    {
        return redirect()->to('/admin/inventory')->with('success', 'Stock deleted!');
    }

    public function alerts()
    {
        return view('admin_inventory/alerts');
    }

    public function branchStocks()
    {
        return view('admin_inventory/branches');
    }
}
