<?php

namespace App\Models;

use CodeIgniter\Model;

class SupplierInvoiceModel extends Model
{
    protected $table            = 'supplier_invoices';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    protected $allowedFields = [
        'supplier_id',
        'purchase_order_id',
        'amount',
        'reference_no',
        'status',
        'submitted_at',
        'processed_at',
        'remarks',
        'submitted_by',
    ];

    public function scopeForSupplier(int $supplierId)
    {
        return $this->where('supplier_id', $supplierId);
    }

    public function markReviewed(int $invoiceId, string $status = 'reviewing'): bool
    {
        return (bool) $this->update($invoiceId, [
            'status'       => $status,
            'processed_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
