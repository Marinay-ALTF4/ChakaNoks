<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddApprovedByToPurchaseRequests extends Migration
{
    public function up()
    {
        $this->forge->addColumn('purchase_requests', [
            'approved_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'after' => 'status'
            ]
        ]);

        // Add foreign key constraint for approved_by
        $this->forge->addForeignKey('approved_by', 'users', 'id', 'CASCADE', 'SET NULL');
    }

    public function down()
    {
        // Drop foreign key first
        try {
            $this->db->query('ALTER TABLE purchase_requests DROP FOREIGN KEY purchase_requests_approved_by_foreign');
        } catch (\Exception $e) {
            // Foreign key might not exist or have different name, continue
        }
        
        // Drop column
        $this->forge->dropColumn('purchase_requests', 'approved_by');
    }
}

