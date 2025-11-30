<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSupplierRole extends Migration
{
    public function up()
    {
        // Add 'supplier' to the role enum
        $this->db->query("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'inventory', 'branch_manager', 'supplier') DEFAULT 'inventory'");
    }

    public function down()
    {
        // Revert back to original enum (remove supplier)
        $this->db->query("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'inventory', 'branch_manager') DEFAULT 'inventory'");
    }
}

