<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'supplier_name' => 'Fresh Foods Inc.',
                'contact'       => '+09123456789',
                'email'         => 'contact@freshfoods.com',
                'address'       => '123 Supplier Lane, Food City',
                'branch_serve'  => 'All',
                'status'        => 'Active',
            ],
            [
                'supplier_name' => 'Global Distributors',
                'contact'       => '+0987456123',
                'email'         => 'sales@globaldist.com',
                'address'       => '456 Distribution Ave, Supply Town',
                'branch_serve'  => 'All',
                'status'        => 'Active',
            ],
            [
                'supplier_name' => 'Local Produce Co.',
                'contact'       => '+09321456987',
                'email'         => 'info@localproduce.com',
                'address'       => '789 Farm Road, Rural Area',
                'branch_serve'  => 'Branch 1 - Downtown',
                'status'        => 'Active',
            ],
        ];

        // insert multiple rows
        $this->db->table('suppliers')->insertBatch($data);
    }
}
