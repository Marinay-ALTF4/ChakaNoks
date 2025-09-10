<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class BranchManager extends Controller
{
    public function dashboard()
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'branch_manager') {
            return redirect()->to('/');
        }

        return view('branch_managers/dashboard');
    }
}
