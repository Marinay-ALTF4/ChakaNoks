<?php

namespace App\Models;

use CodeIgniter\Model;

class TransferRequestModel extends Model
{
    public const STATUS_PENDING    = 'pending';
    public const STATUS_APPROVED   = 'approved';
    public const STATUS_REJECTED   = 'rejected';
    public const STATUS_SCHEDULED  = 'scheduled';
    public const STATUS_IN_TRANSIT = 'in_transit';
    public const STATUS_COMPLETED  = 'completed';
    public const STATUS_CANCELLED  = 'cancelled';

    protected $table          = 'transfer_requests';
    protected $primaryKey     = 'id';
    protected $returnType     = 'array';
    protected $useTimestamps  = true;
    protected $createdField   = 'created_at';
    protected $updatedField   = 'updated_at';
    protected $allowedFields  = [
        'from_branch_id',
        'to_branch_id',
        'requested_by',
        'approved_by',
        'status',
        'scheduled_at',
    ];

    public static function statusList(): array
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_APPROVED,
            self::STATUS_REJECTED,
            self::STATUS_SCHEDULED,
            self::STATUS_IN_TRANSIT,
            self::STATUS_COMPLETED,
            self::STATUS_CANCELLED,
        ];
    }
}
