<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFranchiseSupplyAllocationTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'franchise_id' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'item_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'allocated_quantity' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'period' => [
                'type'       => 'VARCHAR',
                'constraint' => 50, // e.g., 'monthly', 'weekly'
            ],
            'royalty_percentage' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'default' => 0.00,
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
        $this->forge->addForeignKey('franchise_id', 'franchises', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('franchise_supply_allocations');
    }

    public function down()
    {
        $this->forge->dropTable('franchise_supply_allocations');
    }
}
