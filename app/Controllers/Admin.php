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
                $sessionData = [
                    'logged_in' => true,
                    'user_id'   => $user->id,
                    'username'  => $user->username,
                    'role'      => $user->role // admin, inventory, branch_manager
                ];

                // Add branch_id to session if user has one
                if (isset($user->branch_id) && !empty($user->branch_id)) {
                    $sessionData['branch_id'] = $user->branch_id;
                }

                // Add supplier_id to session if user has one (for supplier role)
                if (isset($user->supplier_id) && !empty($user->supplier_id)) {
                    $sessionData['supplier_id'] = $user->supplier_id;
                }

                session()->set($sessionData);

                // ✅ Unified redirect
                return redirect()->to('/dashboard');
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

        $db = Database::connect();
        $branches = $db->table('branches')->get()->getResultArray();

        return view('managers/request_stock', ['branches' => $branches]);
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
    return view('managers/suppliers'); // looks for app/Views/managers/suppliers.php
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

    public function storeStockRequest()
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'admin') {
            return redirect()->to('/');
        }

        $db = Database::connect();

        // Validation rules
        $rules = [
            'branch_id' => 'required|integer',
            'item_name' => 'required|min_length[2]|max_length[255]',
            'quantity'  => 'required|integer|greater_than[0]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Get form data
        $data = [
            'branch_id' => $this->request->getPost('branch_id'),
            'item_name' => $this->request->getPost('item_name'),
            'quantity'  => $this->request->getPost('quantity'),
            'status'    => 'pending',
            'created_at' => date('Y-m-d H:i:s')
        ];

        // Insert into purchase_requests table
        if ($db->table('purchase_requests')->insert($data)) {
            return redirect()->to(base_url('Central_AD/request_stock'))->with('success', 'Stock request submitted successfully.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to submit stock request. Please try again.');
        }
    }

    public function forgotPasswordSubmit()
    {
        return redirect()->back()->with('success', 'If this email exists, a reset link was sent.');
    }

    // User Management Methods
    public function users()
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'admin') {
            return redirect()->to('/');
        }

        $db = Database::connect();
        $selectedBranch = $this->request->getGet('branch');

        $builder = $db->table('users')
            ->select('users.*, branches.name as branch_name')
            ->join('branches', 'branches.id = users.branch_id', 'left');

        if ($selectedBranch) {
            $builder->where('users.branch_id', $selectedBranch);
        }

        $data['users'] = $builder->get()->getResultArray();
        $data['branches'] = $db->table('branches')->get()->getResultArray();
        $data['selectedBranch'] = $selectedBranch;

        return view('managers/users', $data);
    }

    public function createUser()
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'admin') {
            return redirect()->to('/');
        }

        $db = Database::connect();
        $data['branches'] = $db->table('branches')->get()->getResultArray();
        $data['suppliers'] = $db->table('suppliers')->get()->getResultArray();

        return view('managers/create_user', $data);
    }

    public function storeUser()
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'admin') {
            return redirect()->to('/');
        }

        $rules = [
            'username' => 'required|min_length[3]|max_length[50]|is_unique[users.username]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'role' => 'required|in_list[admin,branch_manager,inventory,supplier,logistics_coordinator]',
            'branch_id' => 'permit_empty|integer',
            'supplier_id' => 'permit_empty|integer'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $db = Database::connect();
        $data = [
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role' => $this->request->getPost('role'),
            'branch_id' => $this->request->getPost('branch_id') ?: null,
            'supplier_id' => $this->request->getPost('supplier_id') ?: null,
            'created_at' => date('Y-m-d H:i:s')
        ];

        if ($db->table('users')->insert($data)) {
            return redirect()->to('/admin/users')->with('success', 'User created successfully.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to create user.');
        }
    }

    public function editUser($id)
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'admin') {
            return redirect()->to('/');
        }

        $db = Database::connect();
        $data['user'] = $db->table('users')->where('id', $id)->get()->getRowArray();
        $data['branches'] = $db->table('branches')->get()->getResultArray();

        if (!$data['user']) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('User not found');
        }

        return view('managers/edit_user', $data);
    }

    public function updateUser($id)
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'admin') {
            return redirect()->to('/');
        }

        $rules = [
            'username' => 'required|min_length[3]|max_length[50]|is_unique[users.username,id,' . $id . ']',
            'email' => 'required|valid_email|is_unique[users.email,id,' . $id . ']',
            'role' => 'required|in_list[admin,branch_manager,inventory]',
            'branch_id' => 'permit_empty|integer'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $db = Database::connect();
        $data = [
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'role' => $this->request->getPost('role'),
            'branch_id' => $this->request->getPost('branch_id') ?: null,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // Update password only if provided
        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        if ($db->table('users')->where('id', $id)->update($data)) {
            return redirect()->to('/admin/users')->with('success', 'User updated successfully.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to update user.');
        }
    }

    public function deleteUser($id)
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'admin') {
            return redirect()->to('/');
        }

        $db = Database::connect();
        if ($db->table('users')->where('id', $id)->delete()) {
            return redirect()->to('/admin/users')->with('success', 'User deleted successfully.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to delete user.');
        }
    }
}
