<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdatePurchaseOrdersWorkflow extends Migration
{
    public function up()
    {
        // Add purchase_request_id to link orders to requests
        $this->forge->addColumn('purchase_orders', [
            'purchase_request_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'after' => 'id'
            ],
            'unit' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'after' => 'quantity'
            ],
            'supplier_confirmed_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'order_date'
            ],
            'prepared_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'supplier_confirmed_at'
            ]
        ]);

        // Add foreign key for purchase_request_id
        $this->forge->addForeignKey('purchase_request_id', 'purchase_requests', 'id', 'CASCADE', 'SET NULL');

        // Update status enum to include supplier workflow statuses
        // Note: MySQL doesn't support ALTER ENUM easily, so we'll use a workaround
        $this->db->query("ALTER TABLE purchase_orders MODIFY COLUMN status ENUM('pending_supplier', 'confirmed', 'preparing', 'ready_for_delivery', 'delivered', 'cancelled') DEFAULT 'pending_supplier'");
    }

    public function down()
    {
        // Drop foreign key
        try {
            $this->db->query('ALTER TABLE purchase_orders DROP FOREIGN KEY purchase_orders_purchase_request_id_foreign');
        } catch (\Exception $e) {
            // Continue
        }
        
        // Revert status enum
        $this->db->query("ALTER TABLE purchase_orders MODIFY COLUMN status ENUM('pending', 'approved', 'ordered', 'delivered', 'cancelled') DEFAULT 'pending'");
        
        // Drop columns
        $this->forge->dropColumn('purchase_orders', ['purchase_request_id', 'unit', 'supplier_confirmed_at', 'prepared_at']);
    }
}

