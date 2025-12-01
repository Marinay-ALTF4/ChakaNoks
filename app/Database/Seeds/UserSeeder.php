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
            [
                'username'   => 'logistics',
                'email'      => 'logistics@chakanoks.com',
                'password'   => password_hash('logistics123', PASSWORD_DEFAULT),
                'role'       => 'logistics_coordinator',
                'branch_id'  => null,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'username'   => 'sysadmin',
                'email'      => 'it@chakanoks.com',
                'password'   => password_hash('sysadmin123', PASSWORD_DEFAULT),
                'role'       => 'system_administrator',
                'branch_id'  => null,
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ];

        // Insert users one by one, checking if they already exist
        foreach ($data as $user) {
            $existing = $this->db->table('users')
                ->where('username', $user['username'])
                ->orWhere('email', $user['email'])
                ->get()
                ->getRow();
            
            if (!$existing) {
                $this->db->table('users')->insert($user);
                echo "Created user: {$user['username']}\n";
            } else {
                echo "User {$user['username']} already exists. Skipping.\n";
            }
        }
    }
}
