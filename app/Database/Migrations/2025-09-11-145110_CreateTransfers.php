<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTransfers extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'           => ['type' => 'INT','constraint' => 11,'unsigned' => true,'auto_increment' => true],
            'from_branch'  => ['type' => 'INT','constraint' => 11,'unsigned' => true],
            'to_branch'    => ['type' => 'INT','constraint' => 11,'unsigned' => true],
            'item_name'    => ['type' => 'VARCHAR','constraint' => 150],
            'quantity'     => ['type' => 'INT','constraint' => 11],
            'status'       => ['type' => 'ENUM("pending","approved","rejected")','default' => 'pending'],
            'created_at'   => ['type' => 'DATETIME','null' => true],
            'updated_at'   => ['type' => 'DATETIME','null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('transfers');
    }

    public function down()
    {
        $this->forge->dropTable('transfers');
    }
}
