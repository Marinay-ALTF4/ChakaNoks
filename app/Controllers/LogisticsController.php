<?php

namespace App\Controllers;

use App\Models\BranchModel;
use App\Models\DeliveryItemModel;
use App\Models\DeliveryModel;
use App\Models\DriverModel;
use App\Models\RouteModel;
use App\Models\TransferRequestModel;
use App\Models\VehicleModel;
use App\Services\DeliveryService;
use App\Services\RouteOptimizerService;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\ResponseInterface;

class LogisticsController extends BaseController
{
    public function __construct(
        private readonly DeliveryModel $deliveryModel = new DeliveryModel(),
        private readonly DeliveryItemModel $deliveryItemModel = new DeliveryItemModel(),
        private readonly TransferRequestModel $transferRequestModel = new TransferRequestModel(),
        private readonly VehicleModel $vehicleModel = new VehicleModel(),
        private readonly DriverModel $driverModel = new DriverModel(),
        private readonly RouteModel $routeModel = new RouteModel(),
        private readonly DeliveryService $deliveryService = new DeliveryService(),
        private readonly RouteOptimizerService $routeOptimizerService = new RouteOptimizerService(),
        private readonly BranchModel $branchModel = new BranchModel(),
    ) {
    }

    public function dashboard(): string|RedirectResponse
    {
        if ($redirect = $this->guardRoles(['logistics_coordinator', 'admin', 'central_admin'])) {
            return $redirect;
        }

        // Consolidated dashboards now live under the shared dashboard route.
        return redirect()->to('/dashboard');
    }

    public function scheduleDelivery(): string|ResponseInterface|RedirectResponse
    {
        if ($redirect = $this->guardRoles(['logistics_coordinator', 'admin', 'central_admin'])) {
            return $redirect;
        }

        if ($this->request->getMethod() === 'post') {
            $payload = $this->request->getPost();
            $items   = $this->request->getPost('items') ?? [];

            $parsedItems = [];
            foreach ($items as $item) {
                if (empty($item['product_id']) || empty($item['quantity'])) {
                    continue;
                }

                $parsedItems[] = $item;
            }

            try {
                $this->deliveryService->createDelivery($payload, $parsedItems, $payload['route']['stops'] ?? [], session()->get('user_id'));
                return redirect()->to('/logistics/deliveries')->with('success', 'Delivery scheduled successfully.');
            } catch (\Throwable $e) {
                return redirect()->back()->withInput()->with('error', $e->getMessage());
            }
        }

        $recentDeliveries = $this->deliveryModel->orderBy('created_at', 'DESC')->findAll(20);

        $branchIds = [];
        foreach ($recentDeliveries as $delivery) {
            if (! empty($delivery['source_branch_id'])) {
                $branchIds[] = (int) $delivery['source_branch_id'];
            }
            if (! empty($delivery['destination_branch_id'])) {
                $branchIds[] = (int) $delivery['destination_branch_id'];
            }
        }

        $branchLookup = [];
        if (! empty($branchIds)) {
            $branches = $this->branchModel->whereIn('id', array_unique($branchIds))->findAll();
            foreach ($branches as $branch) {
                $branchLookup[(int) $branch['id']] = $branch['name'] ?? ('Branch ' . $branch['id']);
            }
        }

        $statusFlow = DeliveryModel::getStatusFlow();

        foreach ($recentDeliveries as &$delivery) {
            $delivery['source_branch_name'] = $branchLookup[(int) ($delivery['source_branch_id'] ?? 0)] ?? ($delivery['source_branch_id'] ?? '—');
            $delivery['destination_branch_name'] = $branchLookup[(int) ($delivery['destination_branch_id'] ?? 0)] ?? ($delivery['destination_branch_id'] ?? '—');

            $currentStatus = (string) ($delivery['status'] ?? DeliveryModel::STATUS_PENDING);
            $nextStatus = null;
            $currentIndex = array_search($currentStatus, $statusFlow, true);

            if ($currentIndex !== false) {
                for ($i = $currentIndex + 1, $max = count($statusFlow); $i < $max; $i++) {
                    $candidate = $statusFlow[$i];
                    if ($candidate === DeliveryModel::STATUS_CANCELLED) {
                        continue;
                    }
                    $nextStatus = $candidate;
                    break;
                }
            }

            $delivery['next_status'] = $nextStatus;
        }
        unset($delivery);

        return view('logistics/schedule_delivery', [
            'vehicles'         => $this->vehicleModel->available(),
            'drivers'          => $this->driverModel->findAll(),
            'recentDeliveries' => $recentDeliveries,
            'statusOptions'    => array_values(array_filter($statusFlow, static fn ($status) => $status !== DeliveryModel::STATUS_CANCELLED)),
        ]);
    }

    public function updateDeliveryStatus(int $deliveryId): string|RedirectResponse
    {
        if ($redirect = $this->guardRoles(['logistics_coordinator', 'admin', 'central_admin', 'branch_manager'])) {
            return $redirect;
        }

        if ($this->request->getMethod() === 'post') {
            $status = trim((string) $this->request->getPost('status'));

            if ($status === '') {
                return redirect()->back()->with('error', 'Select a status to continue.');
            }

            try {
                $this->deliveryService->updateStatus($deliveryId, $status, session()->get('user_id'));
                return redirect()->back()->with('success', 'Status updated.');
            } catch (\Throwable $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
        }

        return redirect()->to('/logistics/supplier-deliveries');
    }

    public function deliveries(): string|RedirectResponse
    {
        if ($redirect = $this->guardRoles(['logistics_coordinator', 'admin', 'central_admin'])) {
            return $redirect;
        }

        $deliveries = $this->deliveryModel->orderBy('created_at', 'DESC')->findAll(50);

        $branchIds = [];
        foreach ($deliveries as $delivery) {
            if (! empty($delivery['source_branch_id'])) {
                $branchIds[] = (int) $delivery['source_branch_id'];
            }
            if (! empty($delivery['destination_branch_id'])) {
                $branchIds[] = (int) $delivery['destination_branch_id'];
            }
        }

        $branchLookup = [];
        if (! empty($branchIds)) {
            $branches = $this->branchModel->whereIn('id', array_unique($branchIds))->findAll();
            foreach ($branches as $branch) {
                $branchLookup[(int) $branch['id']] = $branch['name'] ?? ('Branch ' . $branch['id']);
            }
        }

        $statusFlow = DeliveryModel::getStatusFlow();

        foreach ($deliveries as &$delivery) {
            $delivery['items']   = $this->deliveryItemModel->forDelivery((int) $delivery['id']);
            $delivery['timeline'] = $this->deliveryModel->getStatusTimeline((int) $delivery['id']);
            $delivery['source_branch_name'] = $branchLookup[(int) ($delivery['source_branch_id'] ?? 0)] ?? ($delivery['source_branch_id'] ?? '—');
            $delivery['destination_branch_name'] = $branchLookup[(int) ($delivery['destination_branch_id'] ?? 0)] ?? ($delivery['destination_branch_id'] ?? '—');

            $currentStatus = (string) ($delivery['status'] ?? DeliveryModel::STATUS_PENDING);
            $nextStatus = null;
            $currentIndex = array_search($currentStatus, $statusFlow, true);

            if ($currentIndex !== false) {
                for ($i = $currentIndex + 1, $max = count($statusFlow); $i < $max; $i++) {
                    $candidate = $statusFlow[$i];
                    if ($candidate === DeliveryModel::STATUS_CANCELLED) {
                        continue;
                    }
                    $nextStatus = $candidate;
                    break;
                }
            }

            $delivery['next_status'] = $nextStatus;
        }
        unset($delivery);

        return view('logistics/deliveries', [
            'deliveries' => $deliveries,
        ]);
    }

    public function supplierDeliveries(): string|RedirectResponse
    {
        if ($redirect = $this->guardRoles(['logistics_coordinator', 'admin', 'central_admin'])) {
            return $redirect;
        }

        $deliveries = $this->deliveryModel
            ->select('deliveries.*, purchase_orders.status AS purchase_order_status, purchase_orders.item_name, purchase_orders.quantity, purchase_orders.unit, branches.name AS destination_branch, suppliers.supplier_name AS supplier_name')
            ->join('purchase_orders', 'purchase_orders.id = deliveries.order_id', 'left')
            ->join('suppliers', 'suppliers.id = purchase_orders.supplier_id', 'left')
            ->join('branches', 'branches.id = deliveries.destination_branch_id', 'left')
            ->where('deliveries.order_id IS NOT NULL', null, false)
            ->orderBy('deliveries.updated_at', 'DESC')
            ->findAll(100);

        foreach ($deliveries as &$delivery) {
            $delivery['timeline'] = $this->deliveryModel->getStatusTimeline((int) ($delivery['id'] ?? 0));
        }
        unset($delivery);

        $statusOptions = [
            DeliveryModel::STATUS_DISPATCHED,
            DeliveryModel::STATUS_IN_TRANSIT,
            DeliveryModel::STATUS_DELIVERED,
            DeliveryModel::STATUS_ACKNOWLEDGED,
        ];

        return view('logistics/supplier_deliveries', [
            'deliveries'    => $deliveries,
            'statusOptions' => $statusOptions,
        ]);
    }

    public function optimizeRoutes(): ResponseInterface
    {
        if ($redirect = $this->guardRoles(['logistics_coordinator', 'admin', 'central_admin'])) {
            return $redirect;
        }

        $payload = $this->request->getJSON(true) ?? [];
        $stops = $payload['stops'] ?? [];
        $ordered = $this->routeOptimizerService->optimize($stops, $payload['options'] ?? []);

        return $this->response->setJSON([
            'data' => $ordered,
        ]);
    }

    public function trackDelivery(string $deliveryCode): string|RedirectResponse
    {
        $delivery = $this->deliveryModel->where('delivery_code', $deliveryCode)->first();
        if (! $delivery) {
            return redirect()->back()->with('error', 'Delivery not found.');
        }

        return view('logistics/track_delivery', [
            'delivery' => $delivery,
            'items'    => $this->deliveryItemModel->forDelivery((int) $delivery['id']),
            'route'    => $this->routeModel->latestForDelivery((int) $delivery['id']),
            'timeline' => $this->deliveryModel->getStatusTimeline((int) $delivery['id']),
        ]);
    }

    public function branch(): string|RedirectResponse
    {
        if ($redirect = $this->guardRoles(['branch_manager'])) {
            return $redirect;
        }

        $branchId = session()->get('branch_id');

        return view('logistics/branch', [
            'incomingDeliveries' => $this->deliveryModel
                ->where('destination_branch_id', $branchId)
                ->whereIn('status', [DeliveryModel::STATUS_PENDING, DeliveryModel::STATUS_DISPATCHED, DeliveryModel::STATUS_IN_TRANSIT, DeliveryModel::STATUS_DELIVERED])
                ->orderBy('scheduled_at', 'ASC')
                ->findAll(),
            'transferRequests' => $this->transferRequestModel
                ->where('from_branch_id', $branchId)
                ->orderBy('created_at', 'DESC')
                ->findAll(20),
        ]);
    }

    public function central(): string|RedirectResponse
    {
        if ($redirect = $this->guardRoles(['admin', 'central_admin'])) {
            return $redirect;
        }

        $pendingRequests = $this->transferRequestModel
            ->where('status', TransferRequestModel::STATUS_PENDING)
            ->orderBy('created_at', 'ASC')
            ->findAll(20);

        $metrics = [
            'completedDeliveries' => $this->deliveryModel->where('status', DeliveryModel::STATUS_ACKNOWLEDGED)->countAllResults(),
            'averageLeadTime'     => $this->calculateAverageLeadTime(),
            'inTransit'           => $this->deliveryModel->where('status', DeliveryModel::STATUS_IN_TRANSIT)->countAllResults(),
        ];

        return view('logistics/central', [
            'pendingRequests' => $pendingRequests,
            'metrics'         => $metrics,
        ]);
    }

    protected function guardRoles(array $roles): ?RedirectResponse
    {
        if (! session()->get('logged_in') || ! in_array(session()->get('role'), $roles, true)) {
            return redirect()->to('/')->with('error', 'Unauthorized access.');
        }

        return null;
    }

    protected function buildCoordinatorStats(): array
    {
        return [
            'pending'      => $this->deliveryModel->where('status', DeliveryModel::STATUS_PENDING)->countAllResults(),
            'dispatched'   => $this->deliveryModel->where('status', DeliveryModel::STATUS_DISPATCHED)->countAllResults(),
            'inTransit'    => $this->deliveryModel->where('status', DeliveryModel::STATUS_IN_TRANSIT)->countAllResults(),
            'delivered'    => $this->deliveryModel->where('status', DeliveryModel::STATUS_DELIVERED)->countAllResults(),
            'acknowledged' => $this->deliveryModel->where('status', DeliveryModel::STATUS_ACKNOWLEDGED)->countAllResults(),
        ];
    }

    protected function calculateAverageLeadTime(): ?float
    {
        $deliveries = $this->deliveryModel
            ->where('scheduled_at IS NOT NULL', null, false)
            ->where('acknowledged_at IS NOT NULL', null, false)
            ->findAll(100);

        if (empty($deliveries)) {
            return null;
        }

        $total = 0;
        foreach ($deliveries as $delivery) {
            $total += strtotime($delivery['acknowledged_at']) - strtotime($delivery['scheduled_at']);
        }

        return round($total / count($deliveries) / 3600, 2); // hours
    }
}
