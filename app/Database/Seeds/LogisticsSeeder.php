<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class LogisticsSeeder extends Seeder
{
    public function run(): void
    {
        $db = $this->db;
        $now = Time::now('Asia/Manila')->toDateTimeString();

        $db->table('drivers')->insertBatch([
            ['name' => 'Juan Dela Cruz', 'contact' => '09171234567', 'license_no' => 'NCR-123456', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Maria Santos', 'contact' => '09179876543', 'license_no' => 'NCR-654321', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $db->table('vehicles')->insertBatch([
            ['plate_no' => 'ABC-1234', 'capacity' => 1500, 'driver_id' => 1, 'status' => 'available', 'created_at' => $now, 'updated_at' => $now],
            ['plate_no' => 'XYZ-5678', 'capacity' => 2000, 'driver_id' => 2, 'status' => 'available', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $deliveryCode = 'DLV-SEED-' . random_int(1000, 9999);
        $deliveryId = $db->table('deliveries')->insert([
            'delivery_code'         => $deliveryCode,
            'source_branch_id'      => 1,
            'destination_branch_id' => 3,
            'assigned_vehicle_id'   => 1,
            'assigned_driver_id'    => 1,
            'status'                => 'dispatched',
            'scheduled_at'          => Time::parse('2025-12-10 08:00:00', 'Asia/Manila')->toDateTimeString(),
            'dispatched_at'         => Time::now('Asia/Manila')->toDateTimeString(),
            'total_cost'            => 1200,
            'notes'                 => 'Seeded delivery for demo',
            'created_at'            => $now,
            'updated_at'            => $now,
        ], true);

        $db->table('delivery_items')->insertBatch([
            ['delivery_id' => $deliveryId, 'product_id' => 5, 'quantity' => 20, 'unit' => 'boxes', 'unit_cost' => 50, 'created_at' => $now, 'updated_at' => $now],
            ['delivery_id' => $deliveryId, 'product_id' => 7, 'quantity' => 10, 'unit' => 'crates', 'unit_cost' => 80, 'created_at' => $now, 'updated_at' => $now],
        ]);

        $db->table('transfer_requests')->insert([
            'from_branch_id' => 2,
            'to_branch_id'   => 4,
            'requested_by'   => 2,
            'status'         => 'pending',
            'created_at'     => $now,
            'updated_at'     => $now,
        ]);
    }
}
