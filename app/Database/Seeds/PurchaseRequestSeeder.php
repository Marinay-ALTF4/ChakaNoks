<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PurchaseRequestSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'branch_id' => 1,
                'item_name' => 'Tomato',
                'quantity' => 20,
                'status' => 'pending',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'branch_id' => 2,
                'item_name' => 'Potato',
                'quantity' => 30,
                'status' => 'approved',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'branch_id' => 1,
                'item_name' => 'Onion',
                'quantity' => 15,
                'status' => 'pending',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        // insert multiple rows
        $this->db->table('purchase_requests')->insertBatch($data);
    }
}
