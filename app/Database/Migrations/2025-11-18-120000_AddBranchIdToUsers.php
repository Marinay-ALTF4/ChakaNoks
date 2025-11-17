<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddBranchIdToUsers extends Migration
{
    public function up()
    {
        // Add nullable branch_id column to users
        $fields = [
            'branch_id' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'null'       => true,
            ],
        ];

        $this->forge->addColumn('users', $fields);

        // Migrate existing branch_name values into branch_id where possible
        $db = \Config\Database::connect();
        // Update users.branch_id by matching users.branch_name to branches.name
        $db->query("UPDATE users u JOIN branches b ON u.branch_name = b.name SET u.branch_id = b.id");
    }

    public function down()
    {
        $this->forge->dropColumn('users', 'branch_id');
    }
}
