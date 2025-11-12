<?php

namespace App\Models;

use CodeIgniter\Model;

class BranchModel extends Model
{
    protected $table = 'branches';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'location', 'manager_id', 'status'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Relations
    public function manager()
    {
        return $this->belongsTo('App\Models\UserModel', 'manager_id');
    }

    public function inventory()
    {
        return $this->hasMany('App\Models\InventoryModel', 'branch_id');
    }

    public function purchaseRequests()
    {
        return $this->hasMany('App\Models\PurchaseRequestModel', 'branch_id');
    }

    // Methods
    public function getActiveBranches()
    {
        return $this->where('status', 'active')->findAll();
    }

    public function getBranchInventory($branchId)
    {
        return $this->db->table('branch_inventory')
                        ->where('branch_id', $branchId)
                        ->get()
                        ->getResultArray();
    }
}
