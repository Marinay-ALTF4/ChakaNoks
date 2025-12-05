<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTransferRequestsTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'from_branch_id' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'to_branch_id' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'requested_by' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'approved_by' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'approved', 'rejected', 'scheduled', 'in_transit', 'completed', 'cancelled'],
                'default'    => 'pending',
            ],
            'scheduled_at' => [
                'type' => 'DATETIME',
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
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey(['from_branch_id', 'to_branch_id']);
        $this->forge->addKey('status');
        $this->forge->addForeignKey('from_branch_id', 'branches', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('to_branch_id', 'branches', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('requested_by', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('approved_by', 'users', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('transfer_requests', true);
    }

    public function down(): void
    {
        $this->forge->dropTable('transfer_requests', true);
    }
}
