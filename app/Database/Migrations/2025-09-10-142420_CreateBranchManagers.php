<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBranchManagers extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'username'    => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'unique'     => true,
            ],
            'email'       => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'unique'     => true,
            ],
            'password'    => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'role'        => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'branch_manager',
            ],
            'branch_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
            ],
            'created_at'  => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at'  => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('branch_managers');
    }

    public function down()
    {
        $this->forge->dropTable('branch_managers');
    }
}
