<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class BranchInventorySeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'branch_id' => 1,
                'item_name' => 'Apple',
                'quantity' => 50,
                'barcode' => '123456789012',
                'expiry_date' => '2025-12-01',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'branch_id' => 1,
                'item_name' => 'Banana',
                'quantity' => 25,
                'barcode' => '123456789013',
                'expiry_date' => '2025-11-20',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'branch_id' => 2,
                'item_name' => 'Orange',
                'quantity' => 40,
                'barcode' => '123456789014',
                'expiry_date' => '2025-12-05',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'branch_id' => 1,
                'item_name' => 'Milk',
                'quantity' => 15,
                'barcode' => '123456789015',
                'expiry_date' => '2025-11-25',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'branch_id' => 2,
                'item_name' => 'Bread',
                'quantity' => 10,
                'barcode' => '123456789016',
                'expiry_date' => '2025-11-18',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'branch_id' => 1,
                'item_name' => 'Eggs',
                'quantity' => 3,
                'barcode' => '123456789017',
                'expiry_date' => '2025-11-30',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'branch_id' => 3,
                'item_name' => 'Chicken',
                'quantity' => 8,
                'barcode' => '123456789018',
                'expiry_date' => '2025-11-22',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        // insert multiple rows
        $this->db->table('branch_inventory')->insertBatch($data);
    }
}
