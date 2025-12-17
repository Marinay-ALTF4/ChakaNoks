<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddStatusToUsers extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        
        // Add status column if it doesn't exist
        if (!$db->fieldExists('status', 'users')) {
            $fields = [
                'status' => [
                    'type' => 'ENUM',
                    'constraint' => ['active', 'inactive'],
                    'default' => 'active',
                    'after' => 'branch_id'
                ]
            ];
            $this->forge->addColumn('users', $fields);
        }
    }

    public function down()
    {
        $db = \Config\Database::connect();
        
        // Drop status column if it exists
        if ($db->fieldExists('status', 'users')) {
            $this->forge->dropColumn('users', 'status');
        }
    }
}
