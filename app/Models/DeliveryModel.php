<?php

namespace App\Models;

use CodeIgniter\I18n\Time;
use CodeIgniter\Model;

class DeliveryModel extends Model
{
    public const STATUS_PENDING      = 'pending';
    public const STATUS_DISPATCHED   = 'dispatched';
    public const STATUS_IN_TRANSIT   = 'in_transit';
    public const STATUS_DELIVERED    = 'delivered';
    public const STATUS_ACKNOWLEDGED = 'acknowledged';
    public const STATUS_CANCELLED    = 'cancelled';

    protected $table            = 'deliveries';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    protected $allowedFields = [
        'delivery_code',
        'order_id',
        'source_branch_id',
        'destination_branch_id',
        'assigned_vehicle_id',
        'assigned_driver_id',
        'status',
        'scheduled_at',
        'dispatched_at',
        'in_transit_at',
        'delivered_at',
        'acknowledged_at',
        'total_cost',
        'notes',
        'created_by',
        'updated_by',
    ];

    /**
     * Returns the ordered list of statuses supported by the module.
     */
    public static function getStatusFlow(): array
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_DISPATCHED,
            self::STATUS_IN_TRANSIT,
            self::STATUS_DELIVERED,
            self::STATUS_ACKNOWLEDGED,
            self::STATUS_CANCELLED,
        ];
    }

    /**
     * Generates a unique delivery code with date prefix.
     */
    public function generateDeliveryCode(): string
    {
        $todayPrefix = date('Ymd');
        do {
            $code = sprintf('DLV-%s-%04d', $todayPrefix, random_int(0, 9999));
        } while ($this->where('delivery_code', $code)->countAllResults() > 0);

        return $code;
    }

    /**
     * Maintains backward compatibility with legacy code pathways.
     */
    public function generateTrackingNumber(): string
    {
        return $this->generateDeliveryCode();
    }

    /**
     * Updates delivery status and automatically stamps relevant datetime fields.
     */
    public function transitionStatus(int $deliveryId, string $status): bool
    {
        $status = strtolower($status);
        if (! in_array($status, self::getStatusFlow(), true)) {
            throw new \InvalidArgumentException('Unsupported delivery status: ' . $status);
        }

        $fields = ['status' => $status];
        $time   = Time::now('Asia/Manila')->toDateTimeString();
        $record = $this->find($deliveryId) ?? [];

        switch ($status) {
            case self::STATUS_PENDING:
                $fields['scheduled_at'] = $record['scheduled_at'] ?? $time;
                break;
            case self::STATUS_DISPATCHED:
                $fields['dispatched_at'] = $time;
                break;
            case self::STATUS_IN_TRANSIT:
                $fields['in_transit_at'] = $time;
                if (! ($record['dispatched_at'] ?? null)) {
                    $fields['dispatched_at'] = $time;
                }
                break;
            case self::STATUS_DELIVERED:
                $fields['delivered_at'] = $time;
                break;
            case self::STATUS_ACKNOWLEDGED:
                $fields['acknowledged_at'] = $time;
                if (! ($record['delivered_at'] ?? null)) {
                    $fields['delivered_at'] = $time;
                }
                break;
            case self::STATUS_CANCELLED:
                break;
        }

        return (bool) $this->update($deliveryId, $fields);
    }

    /**
     * Retrieves timeline friendly data for UI rendering.
     */
    public function getStatusTimeline(int $deliveryId): array
    {
        $record = $this->find($deliveryId);
        if (! $record) {
            return [];
        }

        return [
            self::STATUS_PENDING      => $record['scheduled_at'] ?? null,
            self::STATUS_DISPATCHED   => $record['dispatched_at'] ?? null,
            self::STATUS_IN_TRANSIT   => $record['in_transit_at'] ?? null,
            self::STATUS_DELIVERED    => $record['delivered_at'] ?? null,
            self::STATUS_ACKNOWLEDGED => $record['acknowledged_at'] ?? null,
            self::STATUS_CANCELLED    => null,
        ];
    }

    /**
     * Convenience helpers for related records.
     */
    public function getSourceBranch(int $deliveryId): ?array
    {
        $record = $this->find($deliveryId);
        if (! $record || empty($record['source_branch_id'])) {
            return null;
        }

        return (new BranchModel())->find($record['source_branch_id']);
    }

    public function getDestinationBranch(int $deliveryId): ?array
    {
        $record = $this->find($deliveryId);
        if (! $record || empty($record['destination_branch_id'])) {
            return null;
        }

        return (new BranchModel())->find($record['destination_branch_id']);
    }

    public function getAssignedVehicle(int $deliveryId): ?array
    {
        $record = $this->find($deliveryId);
        if (! $record || empty($record['assigned_vehicle_id'])) {
            return null;
        }

        return (new VehicleModel())->find($record['assigned_vehicle_id']);
    }

    public function getAssignedDriver(int $deliveryId): ?array
    {
        $record = $this->find($deliveryId);
        if (! $record || empty($record['assigned_driver_id'])) {
            return null;
        }

        return (new DriverModel())->find($record['assigned_driver_id']);
    }
}
