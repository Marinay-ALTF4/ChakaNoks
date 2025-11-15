<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TransferSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'from_branch' => 1,
                'to_branch' => 2,
                'item_name' => 'Apple',
                'quantity' => 10,
                'status' => 'pending',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'from_branch' => 2,
                'to_branch' => 1,
                'item_name' => 'Orange',
                'quantity' => 5,
                'status' => 'approved',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'from_branch' => 3,
                'to_branch' => 1,
                'item_name' => 'Chicken',
                'quantity' => 2,
                'status' => 'pending',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        // insert multiple rows
        $this->db->table('transfers')->insertBatch($data);
    }
}
