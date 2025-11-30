<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSupplierIdToInventory extends Migration
{
    public function up()
    {
        $this->forge->addColumn('inventory', [
            'supplier_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'after' => 'branch_id'
            ]
        ]);

        // Add foreign key constraint
        $this->forge->addForeignKey('supplier_id', 'suppliers', 'id', 'CASCADE', 'SET NULL');
    }

    public function down()
    {
        // Try to drop foreign key (CodeIgniter auto-generates the name)
        // The format is usually: {table}_{column}_foreign
        try {
            $this->db->query('ALTER TABLE inventory DROP FOREIGN KEY inventory_supplier_id_foreign');
        } catch (\Exception $e) {
            // Foreign key might not exist or have different name, continue
        }
        
        // Drop column
        $this->forge->dropColumn('inventory', 'supplier_id');
    }
}

