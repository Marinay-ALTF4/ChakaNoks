<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDeliveryItemsTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'delivery_id' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'product_id' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'quantity' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
            ],
            'unit' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
                'null'       => true,
            ],
            'unit_cost' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'default'    => 0,
            ],
            'expiry_date' => [
                'type' => 'DATE',
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
        $this->forge->addKey('delivery_id');
        $this->forge->addForeignKey('delivery_id', 'deliveries', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('product_id', 'inventory', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('delivery_items', true);
    }

    public function down(): void
    {
        $this->forge->dropTable('delivery_items', true);
    }
}
