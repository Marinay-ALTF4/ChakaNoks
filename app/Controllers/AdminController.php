<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use Config\Database;

class AdminController extends Controller
{
    // ... (keep all existing methods until the users method)

    // User Management Methods
    public function users()
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'admin') {
            return redirect()->to('/');
        }

        $db = Database::connect();
        $selectedBranch = $this->request->getGet('branch');
        $status = $this->request->getGet('status') ?? 'active';

        $builder = $db->table('users')
            ->select('users.*, branches.name as branch_name')
            ->join('branches', 'branches.id = users.branch_id', 'left');

        if ($selectedBranch) {
            $builder->where('users.branch_id', $selectedBranch);
        }

        // Filter by status
        if ($status !== 'all') {
            $builder->where('users.status', $status);
        } else {
            $builder->where('users.status IS NOT NULL');
        }

        $data['users'] = $builder->get()->getResultArray();
        $data['branches'] = $db->table('branches')->get()->getResultArray();
        $data['selectedBranch'] = $selectedBranch;
        $data['selectedStatus'] = $status;

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
            'role' => 'required|in_list[admin,branch_manager,inventory,supplier,logistics_coordinator,franchise_manager,system_administrator]',
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
            'status' => 'active',
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
            'role' => 'required|in_list[admin,branch_manager,inventory,supplier,logistics_coordinator,franchise_manager,system_administrator]',
            'branch_id' => 'permit_empty|integer',
            'status' => 'required|in_list[active,inactive]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $db = Database::connect();
        $data = [
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'role' => $this->request->getPost('role'),
            'status' => $this->request->getPost('status'),
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

        // Prevent admin from deactivating their own account
        if (session()->get('user_id') == $id) {
            return redirect()->back()->with('error', 'You cannot deactivate your own account while logged in.');
        }

        $db = Database::connect();
        $data = [
            'status' => 'inactive',
            'deleted_at' => date('Y-m-d H:i:s')
        ];

        if ($db->table('users')->where('id', $id)->update($data)) {
            return redirect()->to('/admin/users')->with('success', 'User deactivated successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to deactivate user.');
        }
    }

    public function restoreUser($id)
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'admin') {
            return redirect()->to('/');
        }

        $db = Database::connect();
        $data = [
            'status' => 'active',
            'deleted_at' => null
        ];

        if ($db->table('users')->where('id', $id)->update($data)) {
            return redirect()->to('/admin/users?status=inactive')->with('success', 'User restored successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to restore user.');
        }
    }
}
