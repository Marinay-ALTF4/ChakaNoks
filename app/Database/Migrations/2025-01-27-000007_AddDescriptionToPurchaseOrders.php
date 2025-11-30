<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDescriptionToPurchaseOrders extends Migration
{
    public function up()
    {
        $this->forge->addColumn('purchase_orders', [
            'description' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'total_price'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('purchase_orders', 'description');
    }
}

