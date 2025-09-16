<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Central_AD extends Controller
{
    public function dashboard()
    {
        return view('managers/Central_AD'); // or your actual dashboard view
    }

    public function inventory()
    {
        return view('managers/inventory_AD');
    }

    public function suppliers()
    {
        return view('managers/suppliers');
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
