<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'username'   => 'superadmin',
                'email'      => 'admin@example.com',
                'password'   => password_hash('admin123', PASSWORD_DEFAULT),
                'role'       => 'admin',
                'branch_id'  => null, // Admin doesn't belong to a branch
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'username'   => 'inv_staff',
                'email'      => 'staff@example.com',
                'password'   => password_hash('staff123', PASSWORD_DEFAULT),
                'role'       => 'inventory',
                'branch_id'  => null, // Inventory staff might not belong to a specific branch
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'username'   => 'branch_manager',
                'email'      => 'branch@company.com',
                'password'   => password_hash('branch123', PASSWORD_DEFAULT),
                'role'       => 'branch_manager',
                'branch_id'  => 1, // Assign to Branch 1 - Downtown
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ];

        // insert multiple rows
        $this->db->table('users')->insertBatch($data);
    }
}
