<?php

namespace App\Controllers\Api\Logistics;

use App\Controllers\BaseController;
use App\Models\TransferRequestModel;
use App\Models\UserModel;
use App\Services\ActivityLogService;
use App\Services\DeliveryService;
use App\Services\NotificationService;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;
use Throwable;

class TransferRequestsController extends BaseController
{
    public function __construct(
        private readonly TransferRequestModel $transferRequestModel = new TransferRequestModel(),
        private readonly DeliveryService $deliveryService = new DeliveryService(),
        private readonly NotificationService $notificationService = new NotificationService(),
        private readonly ActivityLogService $activityLogService = new ActivityLogService(),
        private readonly UserModel $userModel = new UserModel(),
    ) {
    }

    public function create(): ResponseInterface
    {
        $payload = $this->request->getJSON(true) ?? [];
        $validation = Services::validation();

        $validation->setRules([
            'from_branch_id' => 'required|is_natural_no_zero',
            'to_branch_id'   => 'required|is_natural_no_zero|differs[from_branch_id]',
        ]);

        if (! $validation->run($payload)) {
            return $this->response->setStatusCode(422)->setJSON([
                'message' => 'Validation failed',
                'errors'  => $validation->getErrors(),
            ]);
        }

        $data = [
            'from_branch_id' => $payload['from_branch_id'],
            'to_branch_id'   => $payload['to_branch_id'],
            'requested_by'   => session()->get('user_id'),
            'status'         => TransferRequestModel::STATUS_PENDING,
        ];

        $id = $this->transferRequestModel->insert($data, true);

        $this->activityLogService->log('transfer_request_created', TransferRequestModel::class, $id, $data, session()->get('user_id'));

        $this->notifyAdmins('transfer_request.created', [
            'transfer_request_id' => $id,
            'from_branch_id'      => $payload['from_branch_id'],
            'to_branch_id'        => $payload['to_branch_id'],
        ]);

        return $this->response->setStatusCode(201)->setJSON([
            'message' => 'Transfer request created',
            'data'    => $this->transferRequestModel->find($id),
        ]);
    }

    public function approve(int $id): ResponseInterface
    {
        $request = $this->transferRequestModel->find($id);
        if (! $request) {
            return $this->response->setStatusCode(404)->setJSON([
                'message' => 'Transfer request not found',
            ]);
        }

        $payload = $this->request->getJSON(true) ?? [];
        $status  = strtolower($payload['status'] ?? '');

        if (! in_array($status, [TransferRequestModel::STATUS_APPROVED, TransferRequestModel::STATUS_REJECTED], true)) {
            return $this->response->setStatusCode(422)->setJSON([
                'message' => 'Unsupported status transition.',
            ]);
        }

        try {
            $this->transferRequestModel->update($id, [
                'status'      => $status,
                'approved_by' => session()->get('user_id'),
            ]);

            $this->activityLogService->log('transfer_request_' . $status, TransferRequestModel::class, $id, $payload, session()->get('user_id'));

            $delivery = null;
            if ($status === TransferRequestModel::STATUS_APPROVED && ! empty($payload['delivery'])) {
                $delivery = $this->deliveryService->createFromTransferRequest(
                    $id,
                    $payload['delivery'],
                    $payload['items'] ?? [],
                    $payload['delivery']['route']['stops'] ?? [],
                    session()->get('user_id')
                );
            }

            $this->notificationService->notify([
                $request['requested_by'],
            ], 'transfer_request.' . $status, [
                'transfer_request_id' => $id,
                'status'              => $status,
            ]);

            return $this->response->setJSON([
                'message'  => 'Transfer request updated',
                'data'     => $this->transferRequestModel->find($id),
                'delivery' => $delivery,
            ]);
        } catch (Throwable $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'message' => 'Failed to process transfer request',
                'error'   => $e->getMessage(),
            ]);
        }
    }

    protected function notifyAdmins(string $type, array $data): void
    {
        $admins = $this->userModel->whereIn('role', ['admin', 'central_admin', 'logistics_coordinator'])->findAll();
        $adminIds = array_column($admins, 'id');

        if (! empty($adminIds)) {
            $this->notificationService->notify($adminIds, $type, $data);
        }
    }
}
