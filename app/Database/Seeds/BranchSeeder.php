<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class BranchSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name'        => 'Branch 1 - Downtown',
                'location'    => '123 Main Street, Downtown City',
                'manager_id'  => 3, // Use existing branch_manager user ID
                'status'      => 'active',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'name'        => 'Branch 2 - Uptown',
                'location'    => '456 Oak Avenue, Uptown City',
                'manager_id'  => 3, // Use existing branch_manager user ID
                'status'      => 'active',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'name'        => 'Branch 3 - Suburb',
                'location'    => '789 Pine Road, Suburb Town',
                'manager_id'  => 3, // Use existing branch_manager user ID
                'status'      => 'active',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'name'        => 'Branch 4 - Mall',
                'location'    => '321 Shopping Mall, Mall City',
                'manager_id'  => 3, // Use existing branch_manager user ID
                'status'      => 'active',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'name'        => 'Branch 5 - Airport',
                'location'    => '654 Airport Terminal, Airport City',
                'manager_id'  => 3, // Use existing branch_manager user ID
                'status'      => 'active',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
        ];

        // insert multiple rows
        $this->db->table('branches')->insertBatch($data);
    }
}
