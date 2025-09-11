<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class BranchManagersSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'username'    => 'branch_manager',
                'email'       => 'branch@company.com',
                'password'    => password_hash('branch123', PASSWORD_DEFAULT),
                'role'        => 'branch_manager',
                'branch_name' => 'Main Branch',
                'created_at'  => date('Y-m-d H:i:s'),
            ]
            
        ];

        $this->db->table('branch_managers')->insertBatch($data);
    }
}
