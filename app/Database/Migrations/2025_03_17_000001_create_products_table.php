<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProductsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'product_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'category' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'price' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0.00,
            ],
            'stock' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'branch_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        
        // Create the table first
        $this->forge->createTable('products');
        
        // Add foreign key constraint only if the branches table exists
        $db = \Config\Database::connect();
        $query = $db->query("SHOW TABLES LIKE 'branches'");
        if ($query->getNumRows() > 0) {
            $this->db->query('SET FOREIGN_KEY_CHECKS=0');
            $this->db->query('ALTER TABLE `products` ADD CONSTRAINT `products_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches`(`id`) ON DELETE SET NULL ON UPDATE CASCADE');
            $this->db->query('SET FOREIGN_KEY_CHECKS=1');
        }
        
        // Add sample products
        $this->seedSampleProducts();
    }

    public function down()
    {
        $this->forge->dropTable('products', true);
    }
    
    private function seedSampleProducts()
    {
        $db = \Config\Database::connect();
        
        // Get branch IDs
        if (!$db->tableExists('branches')) {
            // If branches table doesn't exist, we can't add sample products
            return;
        }
        
        $branches = $db->table('branches')->get()->getResultArray();
        
        if (empty($branches)) {
            // If no branches exist, create some with the correct structure
            $branchData = [
                [
                    'name' => 'Main Branch',
                    'location' => '123 Main St',
                    'status' => 'active',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'name' => 'North Branch',
                    'location' => '456 North Ave',
                    'status' => 'active',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'name' => 'South Branch',
                    'location' => '789 South St',
                    'status' => 'active',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'name' => 'East Branch',
                    'location' => '101 East Rd',
                    'status' => 'active',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'name' => 'West Branch',
                    'location' => '202 West Blvd',
                    'status' => 'active',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
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
                'branch_id' => $branches[0]['id'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'product_name' => 'Wireless Mouse',
                'category' => 'Accessories',
                'price' => 29.99,
                'stock' => 200,
                'description' => 'Ergonomic wireless mouse with long battery life',
                'branch_id' => $branches[1]['id'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'product_name' => 'Mechanical Keyboard',
                'category' => 'Accessories',
                'price' => 89.99,
                'stock' => 100,
                'description' => 'Mechanical keyboard with RGB lighting',
                'branch_id' => $branches[2]['id'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'product_name' => '27\" 4K Monitor',
                'category' => 'Monitors',
                'price' => 349.99,
                'stock' => 75,
                'description' => '27-inch 4K UHD monitor with HDR',
                'branch_id' => $branches[3]['id'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'product_name' => 'Noise-Cancelling Headphones',
                'category' => 'Audio',
                'price' => 199.99,
                'stock' => 120,
                'description' => 'Wireless noise-cancelling headphones with 30h battery',
                'branch_id' => $branches[4]['id'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'product_name' => 'USB-C Hub',
                'category' => 'Accessories',
                'price' => 39.99,
                'stock' => 300,
                'description' => '7-in-1 USB-C Hub with 4K HDMI, USB 3.0, SD/TF Card Reader',
                'branch_id' => null, // Available in all branches
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];
        
        $db->table('products')->insertBatch($products);
    }
}
