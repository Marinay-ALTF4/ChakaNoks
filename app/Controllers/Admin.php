<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use Config\Database;

class Admin extends Controller
{
    public function login()
    {
        helper(['form', 'url']);
        return view('Login');
    }

    public function loginAuth()
    {
        helper(['form', 'url']);
        $db = Database::connect();

        $username = trim($this->request->getPost('username'));
        $password = trim($this->request->getPost('password'));

        $builder = $db->table('admins');
        $user = $builder->where('username', $username)
                        ->orWhere('email', $username)
                        ->get()
                        ->getRow();

        if ($user) {
            if (password_verify($password, $user->password)) {
                session()->set([
                    'logged_in' => true,
                    'admin_id'  => $user->id,
                    'username'  => $user->username
                ]);
                return redirect()->to('/Central_AD'); // ✅ redirects to dashboard
            }
            return redirect()->back()->with('error', '❌ Invalid password!');
        }
        return redirect()->back()->with('error', '❌ User not found!');
    }

    public function dashboard()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/');
        }
        return view('Central_AD'); // ✅ must be in app/Views/
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/');
    }

    public function forgotPassword()
    {
        helper(['form', 'url']);
        return view('forgot_password');
    }

    public function forgotPasswordSubmit()
    {
        return redirect()->back()->with('success', 'If this email exists, a reset link was sent.');
    }
}
