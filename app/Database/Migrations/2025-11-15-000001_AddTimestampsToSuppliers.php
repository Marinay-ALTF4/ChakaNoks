<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTimestampsToSuppliers extends Migration
{
    public function up()
    {
        $this->forge->addColumn('suppliers', [
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('suppliers', 'created_at');
        $this->forge->dropColumn('suppliers', 'updated_at');
    }
}
