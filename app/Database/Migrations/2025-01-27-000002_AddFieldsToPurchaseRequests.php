<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFieldsToPurchaseRequests extends Migration
{
    public function up()
    {
        $this->forge->addColumn('purchase_requests', [
            'supplier_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'after' => 'branch_id'
            ],
            'unit' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'after' => 'quantity'
            ],
            'unit_price' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
                'after' => 'unit'
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'unit_price'
            ]
        ]);

        // Add foreign key constraint for supplier_id
        $this->forge->addForeignKey('supplier_id', 'suppliers', 'id', 'CASCADE', 'SET NULL');
    }

    public function down()
    {
        // Drop foreign key first
        try {
            $this->db->query('ALTER TABLE purchase_requests DROP FOREIGN KEY purchase_requests_supplier_id_foreign');
        } catch (\Exception $e) {
            // Foreign key might not exist or have different name, continue
        }
        
        // Drop columns
        $this->forge->dropColumn('purchase_requests', ['supplier_id', 'unit', 'unit_price', 'description']);
    }
}

