<?php

namespace Tests;

use App\Models\DeliveryModel;
use CodeIgniter\I18n\Time;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use Config\Database;

class LogisticsApiTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    private int $sourceBranchId;
    private int $destinationBranchId;
    private int $productId;
    private int $coordinatorId;
    private int $branchManagerId;

    protected function setUp(): void
    {
        parent::setUp();

        $db   = Database::connect();
        $now  = Time::now('Asia/Manila')->toDateTimeString();

        $this->sourceBranchId = $db->table('branches')->insert([
            'name'       => 'Source Branch ' . uniqid(),
            'location'   => 'Manila',
            'status'     => 'active',
            'created_at' => $now,
            'updated_at' => $now,
        ], true);

        $this->destinationBranchId = $db->table('branches')->insert([
            'name'       => 'Destination Branch ' . uniqid(),
            'location'   => 'Quezon City',
            'status'     => 'active',
            'created_at' => $now,
            'updated_at' => $now,
        ], true);

        $this->productId = $db->table('inventory')->insert([
            'item_name'  => 'Test Product ' . uniqid(),
            'quantity'   => 100,
            'status'     => 'available',
            'created_at' => $now,
            'updated_at' => $now,
        ], true);

        $this->coordinatorId = $db->table('users')->insert([
            'username'   => 'logi_' . uniqid(),
            'email'      => uniqid() . '@example.com',
            'password'   => password_hash('secret', PASSWORD_DEFAULT),
            'role'       => 'logistics_coordinator',
            'branch_id'  => $this->sourceBranchId,
            'created_at' => $now,
            'updated_at' => $now,
        ], true);

        $this->branchManagerId = $db->table('users')->insert([
            'username'   => 'branch_' . uniqid(),
            'email'      => uniqid() . '@example.com',
            'password'   => password_hash('secret', PASSWORD_DEFAULT),
            'role'       => 'branch_manager',
            'branch_id'  => $this->destinationBranchId,
            'created_at' => $now,
            'updated_at' => $now,
        ], true);
    }

    public function testCreateDeliveryFlow(): void
    {
        $payload = [
            'source_branch_id'      => $this->sourceBranchId,
            'destination_branch_id' => $this->destinationBranchId,
            'scheduled_at'          => Time::now('Asia/Manila')->addHours(4)->toDateTimeString(),
            'assigned_vehicle_id'   => null,
            'assigned_driver_id'    => null,
            'notes'                 => 'API test delivery',
            'items'                 => [
                [
                    'product_id' => $this->productId,
                    'quantity'   => 5,
                    'unit'       => 'boxes',
                    'unit_cost'  => 100,
                ],
            ],
        ];

        $response = $this->withSession([
                'logged_in' => true,
                'user_id'   => $this->coordinatorId,
                'role'      => 'logistics_coordinator',
            ])
            ->withHeaders(['Content-Type' => 'application/json'])
            ->withBody(json_encode($payload))
            ->post('api/logistics/deliveries');

        $response->assertStatus(201);
        $response->assertJSONFragment(['message' => 'Delivery created successfully']);

        $data = json_decode($response->getBody(), true);
        $this->assertArrayHasKey('data', $data);
        $this->assertArrayHasKey('delivery_code', $data['data']);
    }

    public function testUpdateDeliveryStatusFlow(): void
    {
        $deliveryModel = new DeliveryModel();
        $deliveryId = $deliveryModel->insert([
            'delivery_code'         => 'STATUS-' . uniqid(),
            'source_branch_id'      => $this->sourceBranchId,
            'destination_branch_id' => $this->destinationBranchId,
            'status'                => DeliveryModel::STATUS_PENDING,
            'scheduled_at'          => Time::now('Asia/Manila')->toDateTimeString(),
        ], true);

        $payload = ['status' => DeliveryModel::STATUS_DISPATCHED];

        $response = $this->withSession([
                'logged_in' => true,
                'user_id'   => $this->coordinatorId,
                'role'      => 'logistics_coordinator',
            ])
            ->withHeaders(['Content-Type' => 'application/json'])
            ->withBody(json_encode($payload))
            ->call('patch', 'api/logistics/deliveries/' . $deliveryId . '/status');

        $response->assertStatus(200);
        $response->assertJSONFragment(['message' => 'Status updated.']);
    }

    public function testTransferRequestLifecycle(): void
    {
        // Branch manager submits request
        $payload = [
            'from_branch_id' => $this->destinationBranchId,
            'to_branch_id'   => $this->sourceBranchId,
        ];

        $createResponse = $this->withSession([
                'logged_in' => true,
                'user_id'   => $this->branchManagerId,
                'role'      => 'branch_manager',
            ])
            ->withHeaders(['Content-Type' => 'application/json'])
            ->withBody(json_encode($payload))
            ->post('api/logistics/transfer-requests');

        $createResponse->assertStatus(201);
        $transfer = json_decode($createResponse->getBody(), true)['data'];

        // Coordinator approves the request
        $approvalPayload = [
            'status'   => 'approved',
            'delivery' => [
                'scheduled_at'          => Time::now('Asia/Manila')->addDays(1)->toDateTimeString(),
                'assigned_vehicle_id'   => null,
                'assigned_driver_id'    => null,
            ],
            'items' => [
                ['product_id' => $this->productId, 'quantity' => 2],
            ],
        ];

        $approveResponse = $this->withSession([
                'logged_in' => true,
                'user_id'   => $this->coordinatorId,
                'role'      => 'logistics_coordinator',
            ])
            ->withHeaders(['Content-Type' => 'application/json'])
            ->withBody(json_encode($approvalPayload))
            ->call('patch', 'api/logistics/transfer-requests/' . $transfer['id'] . '/approve');

        $approveResponse->assertStatus(200);
        $approveResponse->assertJSONFragment(['message' => 'Transfer request updated']);
    }
}
