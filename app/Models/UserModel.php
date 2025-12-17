<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $useSoftDeletes = true;
    protected $allowedFields = [
        'username', 'email', 'password', 'role', 'branch_id', 'supplier_id', 'status',
        'created_at', 'updated_at', 'deleted_at'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Relations
    public function branch()
    {
        return $this->belongsTo('App\Models\BranchModel', 'branch_id');
    }

    // Methods
    public function getUsersByRole($role)
    {
        return $this->where('role', $role)->findAll();
    }

    public function getActiveUsers()
    {
        return $this->where('status', 'active')->findAll();
    }

    public function authenticate($username, $password)
    {
        $user = $this->where('username', $username)
                     ->orWhere('email', $username)
                     ->first();

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }

        return false;
    }
}
