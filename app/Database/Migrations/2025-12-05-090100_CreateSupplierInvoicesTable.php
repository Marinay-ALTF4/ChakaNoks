<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSupplierInvoicesTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'supplier_id' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'purchase_order_id' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
            ],
            'amount' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'default'    => 0,
            ],
            'reference_no' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['submitted', 'reviewing', 'approved', 'paid', 'rejected'],
                'default'    => 'submitted',
            ],
            'submitted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'processed_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'remarks' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'submitted_by' => [
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
        $this->forge->addKey('supplier_id');
        $this->forge->addKey('purchase_order_id');
        $this->forge->addKey('status');

        $this->forge->addForeignKey('supplier_id', 'suppliers', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('purchase_order_id', 'purchase_orders', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('submitted_by', 'users', 'id', 'SET NULL', 'CASCADE');

        $this->forge->createTable('supplier_invoices', true);
    }

    public function down(): void
    {
        $this->forge->dropTable('supplier_invoices', true);
    }
}
