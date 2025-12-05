<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\BaseConnection;
use CodeIgniter\Database\Migration;

class RebuildDeliveriesTable extends Migration
{
    public function up(): void
    {
        // Drop legacy table if present to ensure clean schema
        /** @var BaseConnection $db */
        $db = $this->db;

        if ($db->tableExists('deliveries')) {
            $this->forge->dropTable('deliveries', true, true);
        }

        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'delivery_code' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
            ],
            'order_id' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
            ],
            'source_branch_id' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'destination_branch_id' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'assigned_vehicle_id' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
            ],
            'assigned_driver_id' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'dispatched', 'in_transit', 'delivered', 'acknowledged', 'cancelled'],
                'default'    => 'pending',
            ],
            'scheduled_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'dispatched_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'in_transit_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'delivered_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'acknowledged_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'total_cost' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'default'    => 0,
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_by' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
            ],
            'updated_by' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('delivery_code');
        $this->forge->addKey(['source_branch_id', 'destination_branch_id']);
        $this->forge->addKey('status');

        $this->forge->addForeignKey('order_id', 'purchase_orders', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('source_branch_id', 'branches', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('destination_branch_id', 'branches', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('assigned_vehicle_id', 'vehicles', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('assigned_driver_id', 'drivers', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('created_by', 'users', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('updated_by', 'users', 'id', 'SET NULL', 'CASCADE');

        $this->forge->createTable('deliveries', true);
    }

    public function down(): void
    {
        $this->forge->dropTable('deliveries', true);
    }
}
