<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();
        
        // Get branch IDs (assuming you have branches in your database)
        $branches = $db->table('branches')->get()->getResultArray();
        
        if (empty($branches)) {
            // If no branches exist, create some
            $branchData = [
                ['name' => 'Main Branch', 'address' => '123 Main St', 'status' => 'active'],
                ['name' => 'North Branch', 'address' => '456 North Ave', 'status' => 'active'],
                ['name' => 'South Branch', 'address' => '789 South St', 'status' => 'active'],
                ['name' => 'East Branch', 'address' => '101 East Rd', 'status' => 'active'],
                ['name' => 'West Branch', 'address' => '202 West Blvd', 'status' => 'active'],
            ];
            $db->table('branches')->insertBatch($branchData);
            $branches = $db->table('branches')->get()->getResultArray();
        }
        
        // Sample products data
        $products = [
            [
                'product_name' => 'Premium Laptop',
                'category' => 'Electronics',
                'price' => 999.99,
                'stock' => 50,
                'description' => 'High-performance laptop for professionals',
                'branch_id' => $branches[0]['id'], // First branch
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'product_name' => 'Wireless Mouse',
                'category' => 'Accessories',
                'price' => 29.99,
                'stock' => 200,
                'description' => 'Ergonomic wireless mouse with long battery life',
                'branch_id' => $branches[1]['id'], // Second branch
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'product_name' => 'Mechanical Keyboard',
                'category' => 'Accessories',
                'price' => 89.99,
                'stock' => 100,
                'description' => 'Mechanical keyboard with RGB lighting',
                'branch_id' => $branches[2]['id'], // Third branch
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'product_name' => '27\" 4K Monitor',
                'category' => 'Monitors',
                'price' => 349.99,
                'stock' => 75,
                'description' => '27-inch 4K UHD monitor with HDR',
                'branch_id' => $branches[3]['id'], // Fourth branch
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'product_name' => 'Noise-Cancelling Headphones',
                'category' => 'Audio',
                'price' => 199.99,
                'stock' => 120,
                'description' => 'Wireless noise-cancelling headphones with 30h battery',
                'branch_id' => $branches[4]['id'], // Fifth branch
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'product_name' => 'USB-C Hub',
                'category' => 'Accessories',
                'price' => 39.99,
                'stock' => 300,
                'description' => '7-in-1 USB-C Hub with 4K HDMI, USB 3.0, SD/TF Card Reader',
                'branch_id' => NULL, // Available in all branches
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];
        
        // Insert products
        $db->table('products')->insertBatch($products);
    }
}
