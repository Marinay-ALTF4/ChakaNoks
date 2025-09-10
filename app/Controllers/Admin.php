<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use Config\Database;

class Admin extends Controller
{
    public function login()
    {
        helper(['form', 'url']);
        // Load the login form from app/Views/auth/login.php
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
                    'role'      => $user->role // "admin" or "inventory"
                ]);

                // Redirect based on role
                if ($user->role === 'admin') {
                    return redirect()->to('/Central_AD');
                } elseif ($user->role === 'inventory') {
                    return redirect()->to('/inventory');
                }
            }

            return redirect()->back()->with('error', '❌ Invalid password!');
        }

        return redirect()->back()->with('error', '❌ User not found!');
    }

public function dashboard()
{
    if (!session()->get('logged_in') || session()->get('role') !== 'admin') {
        return redirect()->to('/');
    }

    // Default dashboard content
    return view('managers/Central_AD', ['section' => 'dashboard']);
}

public function otherBranches()
{
    if (!session()->get('logged_in') || session()->get('role') !== 'admin') {
        return redirect()->to('/');
    }

    // Load same dashboard but with 'otherBranches' section
    return view('managers/Central_AD', ['section' => 'otherBranches']);
}
public function request_stock()
{
    if (!session()->get('logged_in') || session()->get('role') !== 'admin') {
        return redirect()->to('/');
    }

    // Load request stock view (make sure the file exists under app/Views/managers/)
    return view('managers/request_stock');
}





    // ✅ New: Admin inventory dashboard
    public function inventory()
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'admin') {
            return redirect()->to('/');
        }

        // Load from app/Views/managers/inventory_AD.php
        return view('managers/inventory_AD');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/');
    }

    public function forgotPassword()
    {
        helper(['form', 'url']);
        // Load from app/Views/auth/forgot_password.php
        return view('auth/forgot_password');
    }

    public function forgotPasswordSubmit()
    {
        // You can add email sending logic here later
        return redirect()->back()->with('success', 'If this email exists, a reset link was sent.');
    }
}
