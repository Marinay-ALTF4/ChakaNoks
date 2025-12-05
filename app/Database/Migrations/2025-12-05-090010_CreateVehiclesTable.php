<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateVehiclesTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'plate_no' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'capacity' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'null'       => true,
            ],
            'driver_id' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['available', 'assigned', 'maintenance', 'inactive'],
                'default'    => 'available',
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
        $this->forge->addKey('plate_no');
        $this->forge->addForeignKey('driver_id', 'drivers', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('vehicles', true);
    }

    public function down(): void
    {
        $this->forge->dropTable('vehicles', true);
    }
}
