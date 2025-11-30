<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class InventorySeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'item_name' => 'Apple',
                'quantity' => 100,
                'status' => 'available',
                'type' => 'fruit',
                'barcode' => '123456789012',
                'expiry_date' => '2025-12-01',
                'branch_id' => 1,
                'supplier_id' => 3, // Local Produce Co.
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'item_name' => 'Banana',
                'quantity' => 50,
                'status' => 'available',
                'type' => 'fruit',
                'barcode' => '123456789013',
                'expiry_date' => '2025-11-20',
                'branch_id' => 1,
                'supplier_id' => 3, // Local Produce Co.
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'item_name' => 'Orange',
                'quantity' => 75,
                'status' => 'available',
                'type' => 'fruit',
                'barcode' => '123456789014',
                'expiry_date' => '2025-12-05',
                'branch_id' => 2,
                'supplier_id' => 3, // Local Produce Co.
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'item_name' => 'Milk',
                'quantity' => 30,
                'status' => 'available',
                'type' => 'dairy',
                'barcode' => '123456789015',
                'expiry_date' => '2025-11-25',
                'branch_id' => 1,
                'supplier_id' => 1, // Fresh Foods Inc.
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'item_name' => 'Bread',
                'quantity' => 20,
                'status' => 'available',
                'type' => 'bakery',
                'barcode' => '123456789016',
                'expiry_date' => '2025-11-18',
                'branch_id' => 2,
                'supplier_id' => 1, // Fresh Foods Inc.
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'item_name' => 'Eggs',
                'quantity' => 5,
                'status' => 'low_stock',
                'type' => 'dairy',
                'barcode' => '123456789017',
                'expiry_date' => '2025-11-30',
                'branch_id' => 1,
                'supplier_id' => 2, // Global Distributors
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'item_name' => 'Chicken',
                'quantity' => 10,
                'status' => 'available',
                'type' => 'meat',
                'barcode' => '123456789018',
                'expiry_date' => '2025-11-22',
                'branch_id' => 3,
                'supplier_id' => 1, // Fresh Foods Inc.
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        // insert multiple rows
        $this->db->table('inventory')->insertBatch($data);
    }
}
