<?php

namespace App\Services;

use App\Models\DeliveryItemModel;
use App\Models\DeliveryModel;
use App\Models\TransferRequestModel;
use App\Models\UserModel;
use App\Models\VehicleModel;
use CodeIgniter\Database\BaseConnection;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\I18n\Time;
use DateTimeInterface;
use RuntimeException;
use Throwable;

class DeliveryService
{
    private BaseConnection $db;

    public function __construct(
        private readonly DeliveryModel $deliveryModel = new DeliveryModel(),
        private readonly DeliveryItemModel $deliveryItemModel = new DeliveryItemModel(),
        private readonly TransferRequestModel $transferRequestModel = new TransferRequestModel(),
        private readonly VehicleModel $vehicleModel = new VehicleModel(),
        private readonly NotificationService $notificationService = new NotificationService(),
        private readonly ActivityLogService $activityLogService = new ActivityLogService(),
        private readonly RouteOptimizerService $routeOptimizerService = new RouteOptimizerService(),
        private readonly UserModel $userModel = new UserModel(),
    ) {
        $this->db = db_connect();
    }

    /**
     * Creates a delivery with items and optional route definition.
     *
     * @throws RuntimeException
     */
    public function createDelivery(array $payload, array $items, array $routeStops = [], ?int $createdBy = null): array
    {
        $this->db->transStart();

        try {
            $deliveryId = $this->deliveryModel->insert([
                'delivery_code'         => $this->deliveryModel->generateDeliveryCode(),
                'order_id'              => $payload['order_id'] ?? null,
                'source_branch_id'      => $payload['source_branch_id'],
                'destination_branch_id' => $payload['destination_branch_id'],
                'assigned_vehicle_id'   => $payload['assigned_vehicle_id'] ?? null,
                'assigned_driver_id'    => $payload['assigned_driver_id'] ?? null,
                'status'                => $payload['status'] ?? DeliveryModel::STATUS_PENDING,
                'scheduled_at'          => $this->normalizeDate($payload['scheduled_at'] ?? null),
                'dispatched_at'         => $this->normalizeDate($payload['dispatched_at'] ?? null),
                'in_transit_at'         => $this->normalizeDate($payload['in_transit_at'] ?? null),
                'delivered_at'          => $this->normalizeDate($payload['delivered_at'] ?? null),
                'acknowledged_at'       => $this->normalizeDate($payload['acknowledged_at'] ?? null),
                'total_cost'            => $payload['total_cost'] ?? 0,
                'notes'                 => $payload['notes'] ?? null,
                'created_by'            => $createdBy,
                'updated_by'            => $createdBy,
            ], true);

            foreach ($items as $item) {
                $this->deliveryItemModel->insert([
                    'delivery_id' => $deliveryId,
                    'product_id'  => $item['product_id'],
                    'quantity'    => $item['quantity'],
                    'unit'        => $item['unit'] ?? null,
                    'unit_cost'   => $item['unit_cost'] ?? 0,
                    'expiry_date' => $item['expiry_date'] ?? null,
                ]);
            }

            if ($payload['assigned_vehicle_id'] ?? null) {
                $this->vehicleModel->update($payload['assigned_vehicle_id'], ['status' => 'assigned']);
            }

            if (! empty($routeStops)) {
                $orderedStops = $this->routeOptimizerService->optimize($routeStops, $payload['route_options'] ?? []);
                $this->routeOptimizerService->saveRoute($deliveryId, [
                    'geojson'     => json_encode($orderedStops, JSON_THROW_ON_ERROR),
                    'polyline'    => json_encode($orderedStops, JSON_THROW_ON_ERROR),
                    'distance_m'  => $payload['route_options']['distance_m'] ?? null,
                    'eta_minutes' => $payload['route_options']['eta_minutes'] ?? null,
                ]);
            }

            $this->db->transComplete();
        } catch (Throwable $e) {
            $this->db->transRollback();
            throw new RuntimeException('Failed to create delivery: ' . $e->getMessage(), previous: $e);
        }

        if (! $this->db->transStatus()) {
            throw new DatabaseException('Failed to create delivery transaction.');
        }

        $delivery = $this->deliveryModel->find($deliveryId);
        $this->activityLogService->log('delivery_created', DeliveryModel::class, $deliveryId, $delivery, $createdBy);
        $this->notifyOnDeliveryEvent($delivery, 'delivery.created');

        return $delivery;
    }

    /**
     * Attaches a delivery to a transfer request and updates its workflow state.
     */
    public function createFromTransferRequest(int $transferRequestId, array $payload, array $items, array $routeStops = [], ?int $userId = null): array
    {
        $transfer = $this->transferRequestModel->find($transferRequestId);
        if (! $transfer) {
            throw new RuntimeException('Transfer request not found.');
        }

        $payload['source_branch_id']      = $payload['source_branch_id'] ?? $transfer['from_branch_id'];
        $payload['destination_branch_id'] = $payload['destination_branch_id'] ?? $transfer['to_branch_id'];

        $delivery = $this->createDelivery($payload, $items, $routeStops, $userId);

        $this->transferRequestModel->update($transferRequestId, [
            'status'       => TransferRequestModel::STATUS_SCHEDULED,
            'scheduled_at' => $this->normalizeDate($payload['scheduled_at'] ?? null) ?? Time::now('Asia/Manila')->toDateTimeString(),
            'approved_by'  => $userId ?? $transfer['approved_by'],
        ]);

        $this->activityLogService->log('transfer_scheduled', TransferRequestModel::class, $transferRequestId, $delivery, $userId);

        return $delivery;
    }

    /**
     * Handles status transitions with notifications and audit logging.
     */
    public function updateStatus(int $deliveryId, string $status, ?int $userId = null): array
    {
        $this->deliveryModel->transitionStatus($deliveryId, $status);

        $delivery = $this->deliveryModel->find($deliveryId);
        if (! $delivery) {
            throw new RuntimeException('Delivery not found.');
        }

        if (in_array($status, [DeliveryModel::STATUS_DELIVERED, DeliveryModel::STATUS_ACKNOWLEDGED, DeliveryModel::STATUS_CANCELLED], true)
            && ($delivery['assigned_vehicle_id'] ?? null)) {
            $this->vehicleModel->update($delivery['assigned_vehicle_id'], ['status' => 'available']);
        }

        $this->activityLogService->log('delivery_status_updated', DeliveryModel::class, $deliveryId, [
            'status' => $status,
        ], $userId);

        $this->notifyOnDeliveryEvent($delivery, 'delivery.status.' . $status);

        return $delivery;
    }

    /**
     * Convenience wrapper for acknowledging delivery receipt.
     */
    public function acknowledge(int $deliveryId, ?int $userId = null): array
    {
        return $this->updateStatus($deliveryId, DeliveryModel::STATUS_ACKNOWLEDGED, $userId);
    }

    protected function notifyOnDeliveryEvent(array $delivery, string $type): void
    {
        $recipientIds = $this->resolveRecipients($delivery);
        if (empty($recipientIds)) {
            return;
        }

        $this->notificationService->notify($recipientIds, $type, [
            'delivery_id'    => $delivery['id'],
            'delivery_code'  => $delivery['delivery_code'],
            'status'         => $delivery['status'],
            'scheduled_at'   => $delivery['scheduled_at'],
            'dispatched_at'  => $delivery['dispatched_at'],
            'delivered_at'   => $delivery['delivered_at'],
            'acknowledged_at'=> $delivery['acknowledged_at'],
        ]);
    }

    protected function resolveRecipients(array $delivery): array
    {
        $userIds = [];

        // Logistics coordinators
        $coordinators = $this->userModel->where('role', 'logistics_coordinator')->findAll();
        foreach ($coordinators as $user) {
            $userIds[] = (int) $user['id'];
        }

        // Destination branch manager(s)
        $branchManagers = $this->userModel
            ->where('branch_id', $delivery['destination_branch_id'])
            ->where('role', 'branch_manager')
            ->findAll();

        foreach ($branchManagers as $user) {
            $userIds[] = (int) $user['id'];
        }

        if ($delivery['created_by'] ?? null) {
            $userIds[] = (int) $delivery['created_by'];
        }

        return array_values(array_unique($userIds));
    }

    protected function normalizeDate($value): ?string
    {
        if (! $value) {
            return null;
        }

        if ($value instanceof Time) {
            return $value->setTimezone('Asia/Manila')->toDateTimeString();
        }

        if ($value instanceof \DateTimeInterface) {
            return Time::createFromInstance($value)->setTimezone('Asia/Manila')->toDateTimeString();
        }

        return Time::parse((string) $value, 'Asia/Manila')->toDateTimeString();
    }
}
