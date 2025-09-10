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
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'username'   => 'inv_staff',
                'email'      => 'staff@example.com',
                'password'   => password_hash('staff123', PASSWORD_DEFAULT),
                'role'       => 'inventory',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'username'   => 'Admin: Adrienne',
                'email'      => 'adrienne@marinay.com',
                'password'   => password_hash('adrienne123', PASSWORD_DEFAULT),
                'role'       => 'admin',
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ];

        // Insert but skip duplicates
        $this->db->table('users')->ignore(true)->insertBatch($data);
    }
}
