<?php

namespace App\Services;

use App\Models\RouteModel;
use Config\Services;

class RouteOptimizerService
{
    public function __construct(private readonly RouteModel $routeModel = new RouteModel())
    {
    }

    /**
     * Returns ordered stops. Falls back to a simple nearest-neighbor heuristic.
     */
    public function optimize(array $stops, array $options = []): array
    {
        if (empty($stops)) {
            return [];
        }

        $provider = $options['provider'] ?? 'internal_stub';
        if ($provider !== 'internal_stub') {
            $result = $this->callExternalProvider($provider, $stops, $options);
            if ($result !== null) {
                return $result;
            }
        }

        return $this->nearestNeighbor($stops);
    }

    /**
     * Saves a generated route for a delivery.
     */
    public function saveRoute(int $deliveryId, array $routeData, string $provider = 'internal_stub'): int
    {
        return $this->routeModel->insert([
            'delivery_id'           => $deliveryId,
            'integration_provider'  => $provider,
            'geojson'               => $routeData['geojson'] ?? null,
            'polyline'              => $routeData['polyline'] ?? null,
            'distance_m'            => $routeData['distance_m'] ?? null,
            'eta_minutes'           => $routeData['eta_minutes'] ?? null,
        ]);
    }

    /**
     * Hook for future integration with OSRM or Google Directions.
     */
    protected function callExternalProvider(string $provider, array $stops, array $options): ?array
    {
        $http = Services::curlrequest();
        $endpoint = $options['endpoint'] ?? null;

        if (! $endpoint) {
            return null;
        }

        try {
            $response = $http->post($endpoint, [
                'json' => [
                    'provider' => $provider,
                    'stops'    => $stops,
                    'options'  => $options,
                ],
            ]);

            $payload = json_decode($response->getBody(), true, flags: JSON_THROW_ON_ERROR);
            return $payload['orderedStops'] ?? null;
        } catch (\Throwable $e) {
            return null;
        }
    }

    /**
     * Simple heuristic: greedy nearest neighbor based on haversine distance.
     */
    protected function nearestNeighbor(array $stops): array
    {
        $ordered = [];
        $unvisited = $stops;
        $current = array_shift($unvisited);
        $ordered[] = $current;

        while (! empty($unvisited)) {
            $nextKey = $this->findNearestKey($current, $unvisited);
            $current = $unvisited[$nextKey];
            $ordered[] = $current;
            unset($unvisited[$nextKey]);
        }

        return array_values($ordered);
    }

    protected function findNearestKey(array $from, array $candidates): int
    {
        $nearestKey = array_key_first($candidates);
        $nearestDistance = INF;

        foreach ($candidates as $key => $candidate) {
            $distance = $this->haversine(
                (float) ($from['lat'] ?? 0),
                (float) ($from['lng'] ?? 0),
                (float) ($candidate['lat'] ?? 0),
                (float) ($candidate['lng'] ?? 0)
            );

            if ($distance < $nearestDistance) {
                $nearestDistance = $distance;
                $nearestKey = $key;
            }
        }

        return $nearestKey;
    }

    protected function haversine(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371000; // meters
        $latFrom = deg2rad($lat1);
        $lonFrom = deg2rad($lon1);
        $latTo = deg2rad($lat2);
        $lonTo = deg2rad($lon2);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) + cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
        return $angle * $earthRadius;
    }
}
