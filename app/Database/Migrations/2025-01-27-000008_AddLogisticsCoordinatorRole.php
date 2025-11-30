<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddLogisticsCoordinatorRole extends Migration
{
    public function up()
    {
        // Add 'logistics_coordinator' to the role enum
        $this->db->query("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'inventory', 'branch_manager', 'supplier', 'logistics_coordinator') DEFAULT 'inventory'");
    }

    public function down()
    {
        // Revert back (remove logistics_coordinator)
        $this->db->query("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'inventory', 'branch_manager', 'supplier') DEFAULT 'inventory'");
    }
}

