<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateInventoryTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'         => ['type'=>'INT','constraint'=>11,'unsigned'=>true,'auto_increment'=>true],
            'item_name'  => ['type'=>'VARCHAR','constraint'=>255],
            'quantity'   => ['type'=>'INT','constraint'=>11,'default'=>0],
            'status'     => ['type'=>'VARCHAR','constraint'=>50,'default'=>'available'],
            'created_at' => ['type'=>'DATETIME','null'=>true],
            'updated_at' => ['type'=>'DATETIME','null'=>true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('inventory', true);
    }

    public function down()
    {
        $this->forge->dropTable('inventory', true);
    }
}
