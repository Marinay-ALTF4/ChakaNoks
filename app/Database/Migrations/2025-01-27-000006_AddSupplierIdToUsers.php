<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSupplierIdToUsers extends Migration
{
    public function up()
    {
        $this->forge->addColumn('users', [
            'supplier_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'after' => 'branch_id'
            ]
        ]);

        // Add foreign key
        $this->forge->addForeignKey('supplier_id', 'suppliers', 'id', 'CASCADE', 'SET NULL');
    }

    public function down()
    {
        // Drop foreign key first
        try {
            $this->db->query('ALTER TABLE users DROP FOREIGN KEY users_supplier_id_foreign');
        } catch (\Exception $e) {
            // Continue
        }
        
        $this->forge->dropColumn('users', 'supplier_id');
    }
}

