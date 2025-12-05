<?php

namespace Tests;

use App\Models\DeliveryModel;
use CodeIgniter\I18n\Time;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;

class LogisticsControllerTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    private DeliveryModel $deliveryModel;
    private array $delivery;

    protected function setUp(): void
    {
        parent::setUp();
        $this->deliveryModel = new DeliveryModel();
        $code                = 'TEST-DEL-' . uniqid();

        $id = $this->deliveryModel->insert([
            'delivery_code'         => $code,
            'source_branch_id'      => 1,
            'destination_branch_id' => 2,
            'status'                => DeliveryModel::STATUS_PENDING,
            'scheduled_at'          => Time::now('Asia/Manila')->toDateTimeString(),
        ], true);

        $this->delivery = $this->deliveryModel->find($id);
    }

    public function testDashboard(): void
    {
        $response = $this->withSession([
                'logged_in' => true,
                'role'      => 'logistics_coordinator',
            ])
            ->get('/logistics/dashboard');

        $response->assertStatus(302);
        $response->assertRedirectTo(site_url('dashboard'));
    }

    public function testDeliveries(): void
    {
        $response = $this->withSession([
                'logged_in' => true,
                'role'      => 'logistics_coordinator',
            ])
            ->get('/logistics/deliveries');

        $response->assertStatus(200);
    }

    public function testOptimizeRoutes(): void
    {
        $response = $this->withSession([
                'logged_in' => true,
                'role'      => 'logistics_coordinator',
            ])
            ->withHeaders(['Content-Type' => 'application/json'])
            ->withBody(json_encode(['stops' => [['lat' => 14.6, 'lng' => 121.0]]]))
            ->post('/logistics/optimize-routes');

        $response->assertStatus(200);
        $this->assertNotEmpty($response->getJSON());
    }

    public function testScheduleDeliveryView(): void
    {
        $response = $this->withSession([
                'logged_in' => true,
                'role'      => 'logistics_coordinator',
            ])
            ->get('/logistics/schedule-delivery');

        $response->assertStatus(200);
    }

    public function testTrackDelivery(): void
    {
        $code = $this->delivery['delivery_code'];
        $response = $this->withSession([
                'logged_in' => true,
                'role'      => 'logistics_coordinator',
            ])
            ->get('/logistics/track-delivery/' . $code);

        $response->assertStatus(200);
    }
}
