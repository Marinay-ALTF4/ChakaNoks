<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRoutesTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'delivery_id' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'integration_provider' => [
                'type'       => 'VARCHAR',
                'constraint' => 60,
                'default'    => 'internal_stub',
            ],
            'geojson' => [
                'type' => 'MEDIUMTEXT',
                'null' => true,
            ],
            'polyline' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'distance_m' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'null'       => true,
            ],
            'eta_minutes' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'null'       => true,
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
        $this->forge->addKey('delivery_id');
        $this->forge->addForeignKey('delivery_id', 'deliveries', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('routes', true);
    }

    public function down(): void
    {
        $this->forge->dropTable('routes', true);
    }
}
