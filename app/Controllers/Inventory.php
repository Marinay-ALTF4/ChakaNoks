<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Inventory extends BaseController
{
    public function dashboard()
    {
        return view('inventory/dashboard');
    }

    public function addStock()
    {
        return view('inventory/add_stock');
    }

    public function editStock()
    {
        return view('inventory/edit_stock');
    }

    public function stockList()
    {
        return view('inventory/stock_list');
    }

    public function alerts()
    {
        return view('inventory/alerts');
    }
}
