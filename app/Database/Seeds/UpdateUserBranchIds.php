<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UpdateUserBranchIds extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();
        
        // Update existing users with branch_id
        // Admin users don't need branch_id (null is fine)
        // Branch managers should have a branch_id
        
        // Assign branch_manager to Branch 1 if not already assigned
        $db->table('users')
           ->where('role', 'branch_manager')
           ->where('branch_id IS NULL', null, false)
           ->update(['branch_id' => 1]);
        
        echo "User branch IDs updated successfully!\n";
        echo "Branch manager assigned to Branch 1.\n";
    }
}

