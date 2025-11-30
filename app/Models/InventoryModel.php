<?php

namespace App\Models;

use CodeIgniter\Model;

class InventoryModel extends Model
{
    protected $table      = 'inventory';
    protected $primaryKey = 'id';

    protected $returnType     = 'array';
    protected $allowedFields  = [
        'item_name', 'quantity', 'status', 'created_at', 'updated_at',
        'type', 'barcode', 'expiry_date', 'branch_id', 'supplier_id'
    ];

    // timestamps
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Relations
    public function branch()
    {
        return $this->belongsTo('App\Models\BranchModel', 'branch_id');
    }

    public function supplier()
    {
        return $this->belongsTo('App\Models\SupplierModel', 'supplier_id');
    }

    // Methods for alerts and expiry
    public function getLowStockItems($threshold = 5)
    {
        return $this->where('quantity <=', $threshold)->findAll();
    }

    public function getExpiredItems()
    {
        return $this->where('expiry_date <', date('Y-m-d'))->findAll();
    }

    public function getExpiringSoon($days = 7)
    {
        $futureDate = date('Y-m-d', strtotime("+$days days"));
        return $this->where('expiry_date <=', $futureDate)
                    ->where('expiry_date >=', date('Y-m-d'))
                    ->findAll();
    }

    public function generateBarcode()
    {
        $barcodeGenerator = new \App\Libraries\BarcodeGenerator();
        return $barcodeGenerator->generateBarcode('INV', 12);
    }

    public function getByBarcode($barcode)
    {
        return $this->where('barcode', $barcode)->first();
    }

    public function getByBranch($branchId)
    {
        return $this->where('branch_id', $branchId)->findAll();
    }

    public function getBySupplier($supplierId)
    {
        return $this->where('supplier_id', $supplierId)->findAll();
    }

    public function getTotalStockValue()
    {
        // Assuming we add a unit_price field later, for now return count
        return $this->countAll();
    }

    public function updateQuantity($id, $newQuantity)
    {
        return $this->update($id, ['quantity' => $newQuantity]);
    }

    public function getAlerts()
    {
        $lowStock = $this->getLowStockItems();
        $expiring = $this->getExpiringSoon();
        $expired = $this->getExpiredItems();

        return [
            'low_stock' => $lowStock,
            'expiring_soon' => $expiring,
            'expired' => $expired
        ];
    }
}
