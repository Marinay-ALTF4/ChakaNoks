<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBranchInventory extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => ['type' => 'INT','constraint' => 11,'unsigned' => true,'auto_increment' => true],
            'branch_id'   => ['type' => 'INT','constraint' => 11,'unsigned' => true],
            'item_name'   => ['type' => 'VARCHAR','constraint' => 150],
            'quantity'    => ['type' => 'INT','constraint' => 11],
            'barcode'     => ['type' => 'VARCHAR','constraint' => 100,'null' => true],
            'expiry_date' => ['type' => 'DATE','null' => true],
            'created_at'  => ['type' => 'DATETIME','null' => true],
            'updated_at'  => ['type' => 'DATETIME','null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('branch_inventory');
    }

    public function down()
    {
        $this->forge->dropTable('branch_inventory');
    }
}
