<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\UserModel;
use App\Models\LogModel;
use Config\Database;

class SystemAdministratorController extends Controller
{
    protected $userModel;
    protected $logModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->logModel = new LogModel();
    }

    // Check if user is system administrator
    private function checkAccess()
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'system_administrator') {
            return redirect()->to('/')->with('error', 'Access denied. System Administrator only.');
        }
    }

    // User Management
    public function users()
    {
        $this->checkAccess();
        
        $db = Database::connect();
        $data['users'] = $db->table('users')
            ->select('users.*, branches.name as branch_name')
            ->join('branches', 'branches.id = users.branch_id', 'left')
            ->orderBy('users.created_at', 'DESC')
            ->get()
            ->getResultArray();
        
        $data['branches'] = $db->table('branches')->get()->getResultArray();
        
        return view('system/users', $data);
    }

    // Create User
    public function createUser()
    {
        $this->checkAccess();
        
        $db = Database::connect();
        $data['branches'] = $db->table('branches')->get()->getResultArray();
        $data['suppliers'] = $db->table('suppliers')->get()->getResultArray();
        $data['roles'] = ['admin', 'inventory', 'branch_manager', 'supplier', 'logistics_coordinator', 'franchise_manager'];
        
        return view('system/create_user', $data);
    }

    // Store User
    public function storeUser()
    {
        $this->checkAccess();
        
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

        $data = [
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role' => $this->request->getPost('role'),
            'branch_id' => $this->request->getPost('branch_id') ?: null,
            'supplier_id' => $this->request->getPost('supplier_id') ?: null
        ];

        if ($this->userModel->insert($data)) {
            $this->logModel->logAction(
                session()->get('user_id'),
                'created_user',
                "Created user: {$data['username']} with role: {$data['role']}"
            );
            return redirect()->to('system/users')->with('success', 'User created successfully.');
        }

        return redirect()->back()->withInput()->with('error', 'Failed to create user.');
    }

    // Edit User
    public function editUser($id)
    {
        $this->checkAccess();
        
        $db = Database::connect();
        $data['user'] = $this->userModel->find($id);
        $data['branches'] = $db->table('branches')->get()->getResultArray();
        $data['suppliers'] = $db->table('suppliers')->get()->getResultArray();
        $data['roles'] = ['admin', 'inventory', 'branch_manager', 'supplier', 'logistics_coordinator', 'franchise_manager', 'system_administrator'];
        
        if (!$data['user']) {
            return redirect()->to('system/users')->with('error', 'User not found.');
        }
        
        return view('system/edit_user', $data);
    }

    // Update User
    public function updateUser($id)
    {
        $this->checkAccess();
        
        $rules = [
            'username' => "required|min_length[3]|max_length[50]|is_unique[users.username,id,{$id}]",
            'email' => "required|valid_email|is_unique[users.email,id,{$id}]",
            'role' => 'required|in_list[admin,branch_manager,inventory,supplier,logistics_coordinator,franchise_manager,system_administrator]',
            'branch_id' => 'permit_empty|integer',
            'supplier_id' => 'permit_empty|integer'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'role' => $this->request->getPost('role'),
            'branch_id' => $this->request->getPost('branch_id') ?: null,
            'supplier_id' => $this->request->getPost('supplier_id') ?: null
        ];

        // Update password if provided
        if ($this->request->getPost('password')) {
            $data['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
        }

        if ($this->userModel->update($id, $data)) {
            $this->logModel->logAction(
                session()->get('user_id'),
                'updated_user',
                "Updated user: {$data['username']}"
            );
            return redirect()->to('system/users')->with('success', 'User updated successfully.');
        }

        return redirect()->back()->withInput()->with('error', 'Failed to update user.');
    }

    // Delete User
    public function deleteUser($id)
    {
        $this->checkAccess();
        
        $user = $this->userModel->find($id);
        
        if (!$user) {
            return redirect()->to('system/users')->with('error', 'User not found.');
        }

        // Prevent deleting yourself
        if ($id == session()->get('user_id')) {
            return redirect()->to('system/users')->with('error', 'You cannot delete your own account.');
        }

        if ($this->userModel->delete($id)) {
            $this->logModel->logAction(
                session()->get('user_id'),
                'deleted_user',
                "Deleted user: {$user['username']}"
            );
            return redirect()->to('system/users')->with('success', 'User deleted successfully.');
        }

        return redirect()->to('system/users')->with('error', 'Failed to delete user.');
    }

    // View System Logs
    public function logs()
    {
        $this->checkAccess();
        
        $data['logs'] = $this->logModel
            ->select('logs.*, users.username')
            ->join('users', 'users.id = logs.user_id', 'left')
            ->orderBy('logs.timestamp', 'DESC')
            ->findAll(100);
        
        return view('system/logs', $data);
    }

    // Backup System
    public function backup()
    {
        $this->checkAccess();
        
        // Log backup attempt
        $this->logModel->logAction(
            session()->get('user_id'),
            'backup_initiated',
            'System backup initiated'
        );
        
        $data['message'] = 'Backup functionality will be implemented here. This would typically export database and files.';
        
        return view('system/backup', $data);
    }

    // Security Settings
    public function security()
    {
        $this->checkAccess();
        
        $data['settings'] = [
            'password_policy' => 'enabled',
            'session_timeout' => '30 minutes',
            'two_factor_auth' => 'disabled',
            'ip_whitelist' => 'disabled'
        ];
        
        return view('system/security', $data);
    }
}
