<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FixFranchiseSupplyAllocationTableName extends Migration
{
    public function up()
    {
        // Check if the old table exists and rename it
        $tables = $this->db->listTables();
        
        if (in_array('franchise_supply_allocation', $tables) && !in_array('franchise_supply_allocations', $tables)) {
            // Rename the table from singular to plural
            $this->db->query("RENAME TABLE `franchise_supply_allocation` TO `franchise_supply_allocations`");
        } elseif (!in_array('franchise_supply_allocations', $tables)) {
            // If neither table exists, create the correct one
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
                    'constraint' => 50,
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
    }

    public function down()
    {
        // Rename back to singular if needed
        $tables = $this->db->listTables();
        
        if (in_array('franchise_supply_allocations', $tables)) {
            $this->db->query("RENAME TABLE `franchise_supply_allocations` TO `franchise_supply_allocation`");
        }
    }
}
