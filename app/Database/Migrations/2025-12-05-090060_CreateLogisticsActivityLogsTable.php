<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLogisticsActivityLogsTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
            ],
            'action' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'model' => [
                'type'       => 'VARCHAR',
                'constraint' => 120,
            ],
            'model_id' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
            ],
            'meta' => [
                'type' => 'JSON',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey(['model', 'model_id']);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('logistics_activity_logs', true);
    }

    public function down(): void
    {
        $this->forge->dropTable('logistics_activity_logs', true);
    }
}
