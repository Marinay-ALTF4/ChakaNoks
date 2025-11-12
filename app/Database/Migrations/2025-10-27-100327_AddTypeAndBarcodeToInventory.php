<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTypeAndBarcodeToInventory extends Migration
{
    public function up()
    {
        $this->forge->addColumn('inventory', [
            'type' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'after' => 'item_name'
            ],
            'barcode' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'after' => 'type'
            ],
            'expiry_date' => [
                'type' => 'DATE',
                'null' => true,
                'after' => 'barcode'
            ],
            'branch_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'after' => 'expiry_date'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('inventory', ['type', 'barcode', 'expiry_date', 'branch_id']);
    }
}
