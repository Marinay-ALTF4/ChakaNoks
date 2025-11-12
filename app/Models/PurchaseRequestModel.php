<?php

namespace App\Models;

use CodeIgniter\Model;

class PurchaseRequestModel extends Model
{
    protected $table = 'purchase_requests';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'branch_id', 'item_name', 'quantity', 'status', 'approved_by',
        'created_at', 'updated_at'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Relations
    public function branch()
    {
        return $this->belongsTo('App\Models\BranchModel', 'branch_id');
    }

    public function approver()
    {
        return $this->belongsTo('App\Models\UserModel', 'approved_by');
    }

    // Methods
    public function getPendingRequests()
    {
        return $this->where('status', 'pending')->findAll();
    }

    public function approveRequest($requestId, $approverId)
    {
        return $this->update($requestId, [
            'status' => 'approved',
            'approved_by' => $approverId
        ]);
    }

    public function rejectRequest($requestId, $approverId)
    {
        return $this->update($requestId, [
            'status' => 'rejected',
            'approved_by' => $approverId
        ]);
    }

    public function getRequestsByBranch($branchId)
    {
        return $this->where('branch_id', $branchId)->findAll();
    }
}
