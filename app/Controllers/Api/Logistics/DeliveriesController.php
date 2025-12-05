<?php

namespace App\Controllers\Api\Logistics;

use App\Controllers\BaseController;
use App\Models\DeliveryItemModel;
use App\Models\DeliveryModel;
use App\Models\RouteModel;
use App\Services\DeliveryService;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;
use Throwable;

class DeliveriesController extends BaseController
{
    public function __construct(
        private readonly DeliveryService $deliveryService = new DeliveryService(),
        private readonly DeliveryModel $deliveryModel = new DeliveryModel(),
        private readonly DeliveryItemModel $deliveryItemModel = new DeliveryItemModel(),
        private readonly RouteModel $routeModel = new RouteModel(),
    ) {
    }

    public function index(): ResponseInterface
    {
        $query = $this->deliveryModel;

        if ($branchId = $this->request->getGet('branch_id')) {
            $query = $query->groupStart()
                ->where('source_branch_id', $branchId)
                ->orWhere('destination_branch_id', $branchId)
                ->groupEnd();
        }

        if ($status = $this->request->getGet('status')) {
            $query = $query->where('status', $status);
        }

        if ($dateFrom = $this->request->getGet('date_from')) {
            $query = $query->where('DATE(scheduled_at) >=', $dateFrom);
        }

        if ($dateTo = $this->request->getGet('date_to')) {
            $query = $query->where('DATE(scheduled_at) <=', $dateTo);
        }

        $perPage = (int) ($this->request->getGet('per_page') ?? 15);
        $page    = (int) ($this->request->getGet('page') ?? 1);

        $paginator = $query->orderBy('scheduled_at', 'DESC')->paginate($perPage, 'default', $page);

        return $this->response->setJSON([
            'data'       => $paginator,
            'pagination' => [
                'current_page' => $this->deliveryModel->pager->getCurrentPage('default'),
                'per_page'     => $this->deliveryModel->pager->getPerPage('default'),
                'total'        => $this->deliveryModel->pager->getTotal('default'),
                'last_page'    => $this->deliveryModel->pager->getPageCount('default'),
            ],
        ]);
    }

    public function show(int $id): ResponseInterface
    {
        $delivery = $this->deliveryModel->find($id);
        if (! $delivery) {
            return $this->response->setStatusCode(404)->setJSON([
                'message' => 'Delivery not found',
            ]);
        }

        $items = $this->deliveryItemModel->forDelivery($id);
        $route = $this->routeModel->latestForDelivery($id);

        return $this->response->setJSON([
            'data' => [
                'delivery' => $delivery,
                'items'    => $items,
                'route'    => $route,
            ],
        ]);
    }

    public function create(): ResponseInterface
    {
        $payload = $this->request->getJSON(true) ?? [];
        $validation = Services::validation();

        $validation->setRules([
            'source_branch_id'      => 'required|is_natural_no_zero',
            'destination_branch_id' => 'required|is_natural_no_zero',
            'scheduled_at'          => 'required',
            'items'                 => 'required|is_array',
            'items.*.product_id'    => 'required|is_natural_no_zero',
            'items.*.quantity'      => 'required|decimal',
        ]);

        if (! $validation->run($payload)) {
            return $this->response->setStatusCode(422)->setJSON([
                'message' => 'Validation failed',
                'errors'  => $validation->getErrors(),
            ]);
        }

        try {
            $delivery = $this->deliveryService->createDelivery(
                $payload,
                $payload['items'],
                $payload['route']['stops'] ?? [],
                session()->get('user_id')
            );

            return $this->response->setStatusCode(201)->setJSON([
                'message' => 'Delivery created successfully',
                'data'    => $delivery,
            ]);
        } catch (Throwable $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'message' => 'Failed to create delivery',
                'error'   => $e->getMessage(),
            ]);
        }
    }

    public function updateStatus(int $id): ResponseInterface
    {
        $payload = $this->request->getJSON(true) ?? [];
        $status  = $payload['status'] ?? null;

        if (! $status) {
            return $this->response->setStatusCode(422)->setJSON([
                'message' => 'Status is required.',
            ]);
        }

        try {
            $delivery = $this->deliveryService->updateStatus($id, $status, session()->get('user_id'));
            return $this->response->setJSON([
                'message' => 'Status updated.',
                'data'    => $delivery,
            ]);
        } catch (Throwable $e) {
            return $this->response->setStatusCode(400)->setJSON([
                'message' => 'Failed to update status',
                'error'   => $e->getMessage(),
            ]);
        }
    }
}
