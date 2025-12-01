<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSystemAdministratorRole extends Migration
{
    public function up()
    {
        // Add 'system_administrator' to the role enum
        $this->db->query("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'inventory', 'branch_manager', 'supplier', 'logistics_coordinator', 'franchise_manager', 'system_administrator') DEFAULT 'inventory'");
    }

    public function down()
    {
        // Revert back (remove system_administrator)
        $this->db->query("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'inventory', 'branch_manager', 'supplier', 'logistics_coordinator', 'franchise_manager') DEFAULT 'inventory'");
    }
}
