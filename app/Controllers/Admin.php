<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use Config\Database;

class Admin extends Controller
{
    /* ===================== AUTH ===================== */

    public function login()
    {
        helper(['form', 'url']);
        return view('auth/login');
    }

    public function loginAuth()
    {
        helper(['form', 'url']);
        $db = Database::connect();

        $username = trim($this->request->getPost('username'));
        $password = trim($this->request->getPost('password'));

        $user = $db->table('users')
            ->where('username', $username)
            ->orWhere('email', $username)
            ->get()
            ->getRow();

        if (!$user || !password_verify($password, $user->password)) {
            return redirect()->back()->with('error', 'Invalid login credentials.');
        }

        session()->set([
            'logged_in' => true,
            'user_id'   => $user->id,
            'username'  => $user->username,
            'role'      => $user->role,
            'branch_id' => $user->branch_id ?? null,
            'supplier_id' => $user->supplier_id ?? null,
        ]);

        return redirect()->to('/dashboard');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/');
    }

    /* ===================== ADMIN DASHBOARD ===================== */

    private function adminOnly()
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'admin') {
            return redirect()->to('/')->send();
            exit;
        }
    }

    public function dashboard()
    {
        $this->adminOnly();
        return view('managers/Central_AD', ['section' => 'dashboard']);
    }

    public function otherBranches()
    {
        $this->adminOnly();
        return view('managers/Central_AD', ['section' => 'otherBranches']);
    }

    public function inventory()
    {
        $this->adminOnly();
        return view('managers/inventory_AD');
    }

    public function suppliers()
    {
        $this->adminOnly();
        return view('managers/suppliers');
    }

    public function orders()
    {
        $this->adminOnly();
        return view('managers/orders');
    }

    public function reports()
    {
        $this->adminOnly();
        return view('managers/reports');
    }

    public function settings()
    {
        $this->adminOnly();
        return view('managers/settings');
    }

    /* ===================== USERS ===================== */

    public function users()
    {
        $this->adminOnly();
        $db = Database::connect();

        $status = $this->request->getGet('status') ?? 'active';

        $builder = $db->table('users')
            ->select('users.*, branches.name as branch_name')
            ->join('branches', 'branches.id = users.branch_id', 'left');

        if ($status !== 'all') {
            $builder->where('users.status', $status);
        }

        return view('managers/users', [
            'users' => $builder->get()->getResultArray(),
            'branches' => $db->table('branches')->get()->getResultArray(),
            'selectedStatus' => $status
        ]);
    }

    public function createUser()
    {
        $this->adminOnly();
        $db = Database::connect();

        return view('managers/create_user', [
            'branches' => $db->table('branches')->get()->getResultArray(),
            'suppliers' => $db->table('suppliers')->get()->getResultArray()
        ]);
    }

    public function storeUser()
    {
        $this->adminOnly();

        if (!$this->validate([
            'username' => 'required|is_unique[users.username]',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'role'     => 'required'
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        Database::connect()->table('users')->insert([
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'     => $this->request->getPost('role'),
            'branch_id' => $this->request->getPost('branch_id') ?: null,
            'supplier_id' => $this->request->getPost('supplier_id') ?: null,
            'status'   => 'active',
            'created_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->to('/admin/users')->with('success', 'User created successfully.');
    }

    public function editUser($id)
    {
        $this->adminOnly();
        $db = Database::connect();

        return view('managers/edit_user', [
            'user' => $db->table('users')->where('id', $id)->get()->getRowArray(),
            'branches' => $db->table('branches')->get()->getResultArray()
        ]);
    }

    public function updateUser($id)
    {
        $this->adminOnly();
        $db = Database::connect();

        $data = [
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
            'role'     => $this->request->getPost('role'),
            'branch_id'=> $this->request->getPost('branch_id') ?: null,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if ($this->request->getPost('password')) {
            $data['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
        }

        $db->table('users')->where('id', $id)->update($data);
        return redirect()->to('/admin/users')->with('success', 'User updated.');
    }

    public function deactivateUser($id)
    {
        $this->adminOnly();

        if (session()->get('user_id') == $id) {
            return $this->response->setJSON(['success' => false, 'message' => 'You cannot deactivate yourself']);
        }

        Database::connect()->table('users')->where('id', $id)->update([
            'status' => 'inactive',
            'deleted_at' => date('Y-m-d H:i:s')
        ]);

        return $this->response->setJSON(['success' => true]);
    }

    public function restoreUser($id)
    {
        $this->adminOnly();

        Database::connect()->table('users')->where('id', $id)->update([
            'status' => 'active',
            'deleted_at' => null
        ]);

        return $this->response->setJSON(['success' => true]);
    }
}
