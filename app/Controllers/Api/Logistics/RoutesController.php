<?php

namespace App\Controllers\Api\Logistics;

use App\Controllers\BaseController;
use App\Models\RouteModel;
use App\Services\RouteOptimizerService;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;

class RoutesController extends BaseController
{
    public function __construct(
        private readonly RouteModel $routeModel = new RouteModel(),
        private readonly RouteOptimizerService $routeOptimizerService = new RouteOptimizerService(),
    ) {
    }

    public function index(): ResponseInterface
    {
        $builder = $this->routeModel;

        if ($deliveryId = $this->request->getGet('delivery_id')) {
            $builder = $builder->where('delivery_id', $deliveryId);
        }

        $routes = $builder->orderBy('created_at', 'DESC')->findAll(20);

        return $this->response->setJSON([
            'data' => $routes,
        ]);
    }

    public function optimize(): ResponseInterface
    {
        $payload = $this->request->getJSON(true) ?? [];
        $validation = Services::validation();
        $validation->setRules([
            'stops'           => 'required|is_array',
            'stops.*.lat'     => 'required',
            'stops.*.lng'     => 'required',
        ]);

        if (! $validation->run($payload)) {
            return $this->response->setStatusCode(422)->setJSON([
                'message' => 'Validation failed',
                'errors'  => $validation->getErrors(),
            ]);
        }

        $ordered = $this->routeOptimizerService->optimize($payload['stops'], [
            'provider' => $payload['provider'] ?? 'internal_stub',
            'endpoint' => $payload['endpoint'] ?? null,
        ]);

        return $this->response->setJSON([
            'message' => 'Route optimized',
            'data'    => $ordered,
        ]);
    }
}
