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
        // Use DB builder directly to ensure clean query
        // Get items with low quantity (<= threshold) OR status 'low_stock'
        // Exclude out of stock items
        return $this->db->table($this->table)
                    ->groupStart()
                        ->groupStart()
                            ->where('quantity <=', $threshold)
                            ->where('quantity >', 0)
                        ->groupEnd()
                        ->orWhere('status', 'low_stock')
                    ->groupEnd()
                    ->where('status !=', 'out_of_stock')
                    ->get()
                    ->getResultArray();
    }

    public function getOutOfStockItems()
    {
        // Use DB builder directly to ensure clean query
        return $this->db->table($this->table)
                    ->groupStart()
                    ->where('quantity', 0)
                    ->orWhere('status', 'out_of_stock')
                    ->groupEnd()
                    ->get()
                    ->getResultArray();
    }

    public function getExpiredItems()
    {
        // Use DB builder directly to ensure clean query
        return $this->db->table($this->table)
                    ->where('expiry_date <', date('Y-m-d'))
                    ->where('expiry_date IS NOT NULL')
                    ->where('expiry_date !=', '')
                    ->get()
                    ->getResultArray();
    }

    public function getExpiringSoon($days = 7)
    {
        // Use DB builder directly to ensure clean query
        $today = date('Y-m-d');
        $futureDate = date('Y-m-d', strtotime("+$days days"));
        return $this->db->table($this->table)
                    ->where('expiry_date <=', $futureDate)
                    ->where('expiry_date >=', $today)
                    ->where('expiry_date IS NOT NULL')
                    ->where('expiry_date !=', '')
                    ->get()
                    ->getResultArray();
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

    public function getDamagedItems()
    {
        // Use DB builder directly to ensure clean query
        return $this->db->table($this->table)
                    ->where('status', 'damaged')
                    ->get()
                    ->getResultArray();
    }

    public function getAlerts()
    {
        // Use DB builder directly to avoid query builder conflicts
        $lowStock = $this->getLowStockItems();
        $outOfStock = $this->getOutOfStockItems();
        $expiring = $this->getExpiringSoon();
        $expired = $this->getExpiredItems();
        $damaged = $this->getDamagedItems();

        return [
            'low_stock' => $lowStock,
            'out_of_stock' => $outOfStock,
            'expiring_soon' => $expiring,
            'expired' => $expired,
            'damaged' => $damaged
        ];
    }

    // Auto-update status based on quantity
    public function autoUpdateStatus($id, $quantity)
    {
        $status = 'available';
        if ($quantity <= 0) {
            $status = 'out_of_stock';
        } elseif ($quantity <= 5) {
            $status = 'low_stock';
        }
        
        return $this->update($id, ['status' => $status]);
    }
}
