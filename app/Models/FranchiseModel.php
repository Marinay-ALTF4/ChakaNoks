<?php

namespace App\Models;

use CodeIgniter\Model;

class FranchiseModel extends Model
{
    protected $table = 'franchises';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'franchise_name', 'owner', 'location', 'contact', 'status', 'created_at', 'updated_at'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Relations
    public function supplyAllocations()
    {
        return $this->hasMany('App\Models\FranchiseSupplyAllocationModel', 'franchise_id');
    }

    // Methods
    public function getActiveFranchises()
    {
        return $this->where('status', 'active')->findAll();
    }

    public function getPendingApplications()
    {
        return $this->where('status', 'pending')->findAll();
    }

    public function approveFranchise($franchiseId)
    {
        return $this->update($franchiseId, ['status' => 'active']);
    }

    public function rejectFranchise($franchiseId)
    {
        return $this->update($franchiseId, ['status' => 'rejected']);
    }

    public function getFranchiseAllocations($franchiseId)
    {
        return $this->db->table('franchise_supply_allocations')
                        ->where('franchise_id', $franchiseId)
                        ->get()
                        ->getResultArray();
    }

    public function allocateSupply($franchiseId, $itemName, $quantity, $period)
    {
        $allocationModel = new \App\Models\FranchiseSupplyAllocationModel();
        return $allocationModel->insert([
            'franchise_id' => $franchiseId,
            'item_name' => $itemName,
            'allocated_quantity' => $quantity,
            'period' => $period
        ]);
    }

    public function calculateRoyalty($franchiseId, $salesAmount)
    {
        // Assuming 5% royalty rate
        return $salesAmount * 0.05;
    }
}

