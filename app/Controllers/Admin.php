<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use Config\Database;

class Admin extends Controller
{
    public function login()
    {
        helper(['form', 'url']);
        // Load the login form
        return view('auth/login');
    }

    public function loginAuth()
    {
        helper(['form', 'url']);
        $db = Database::connect();

        $username = trim($this->request->getPost('username'));
        $password = trim($this->request->getPost('password'));

        // Look for the user in the users table
        $builder = $db->table('users');
        $user = $builder->where('username', $username)
                        ->orWhere('email', $username)
                        ->get()
                        ->getRow();

        if ($user) {
            if (password_verify($password, $user->password)) {
                // Save user session
                session()->set([
                    'logged_in' => true,
                    'user_id'   => $user->id,
                    'username'  => $user->username,
                    'role'      => $user->role // admin, inventory, branch_manager
                ]);

                // ✅ Redirect based on role
                if ($user->role === 'admin') {
                    return redirect()->to('/Central_AD');
                } elseif ($user->role === 'inventory') {
                    return redirect()->to('/inventory');
                } elseif ($user->role === 'branch_manager') {
                    return redirect()->to('/branch/dashboard');
                }
            }

            return redirect()->back()->with('error', '❌ Invalid password!');
        }

        return redirect()->back()->with('error', '❌ User not found!');
    }

    // ✅ Admin-only dashboards
    public function dashboard()
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'admin') {
            return redirect()->to('/');
        }

        return view('managers/Central_AD', ['section' => 'dashboard']);
    }

    public function otherBranches()
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'admin') {
            return redirect()->to('/');
        }

        return view('managers/Central_AD', ['section' => 'otherBranches']);
    }

    public function request_stock()
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'admin') {
            return redirect()->to('/');
        }

        return view('managers/request_stock');
    }

    public function inventory()
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'admin') {
            return redirect()->to('/');
        }

        return view('managers/inventory_AD');
    }
    public function suppliers()
{
    if (!session()->get('logged_in') || session()->get('role') !== 'admin') {
        return redirect()->to('/');
    }
    return view('managers/suppliers');
}

public function orders()
{
    if (!session()->get('logged_in') || session()->get('role') !== 'admin') {
        return redirect()->to('/');
    }
    return view('managers/orders');
}

public function franchising()
{
    if (!session()->get('logged_in') || session()->get('role') !== 'admin') {
        return redirect()->to('/');
    }
    return view('managers/franchising');
}

public function reports()
{
    if (!session()->get('logged_in') || session()->get('role') !== 'admin') {
        return redirect()->to('/');
    }
    return view('managers/reports');
}

public function settings()
{
    if (!session()->get('logged_in') || session()->get('role') !== 'admin') {
        return redirect()->to('/');
    }
    return view('managers/settings');
}


    // ✅ Branch Manager dashboard
    public function branchDashboard()
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'branch_manager') {
            return redirect()->to('/');
        }

        return view('branch_managers/dashboard'); // app/Views/branch_managers/dashboard.php
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/');
    }

    public function forgotPassword()
    {
        helper(['form', 'url']);
        return view('auth/forgot_password');
    }

    public function forgotPasswordSubmit()
    {
        return redirect()->back()->with('success', 'If this email exists, a reset link was sent.');
    }
}
