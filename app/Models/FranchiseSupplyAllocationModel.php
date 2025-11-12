<?php

namespace App\Models;

use CodeIgniter\Model;

class FranchiseSupplyAllocationModel extends Model
{
    protected $table = 'franchise_supply_allocations';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'franchise_id', 'item_name', 'allocated_quantity', 'period',
        'royalty_percentage', 'created_at', 'updated_at'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Relations
    public function franchise()
    {
        return $this->belongsTo('App\Models\FranchiseModel', 'franchise_id');
    }

    // Methods
    public function getAllocationsByFranchise($franchiseId)
    {
        return $this->where('franchise_id', $franchiseId)->findAll();
    }

    public function getAllocationsByPeriod($period)
    {
        return $this->where('period', $period)->findAll();
    }

    public function updateAllocation($allocationId, $quantity)
    {
        return $this->update($allocationId, ['allocated_quantity' => $quantity]);
    }

    public function calculateTotalAllocation($franchiseId)
    {
        $allocations = $this->where('franchise_id', $franchiseId)->findAll();
        return array_sum(array_column($allocations, 'allocated_quantity'));
    }

    public function getMonthlyAllocations($year, $month)
    {
        $period = $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT);
        return $this->like('period', $period)->findAll();
    }
}
