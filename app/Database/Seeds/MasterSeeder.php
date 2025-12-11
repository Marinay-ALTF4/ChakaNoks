<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MasterSeeder extends Seeder
{
    public function run()
    {
        // Order matters to satisfy FK and data dependencies
        $this->call(SupplierSeeder::class);        // suppliers for inventory linkage
        $this->call(UserSeeder::class);            // core users/roles
        $this->call(BranchSeeder::class);          // branches used by many tables
        $this->call(UpdateUserBranchIds::class);   // align branch managers to branches
        $this->call(BranchManagersSeeder::class);  // branch manager table entries
        $this->call(BranchInventorySeeder::class); // branch-level stock counts
        $this->call(InventorySeeder::class);       // master inventory items
        $this->call(PurchaseRequestSeeder::class); // sample purchase requests
        $this->call(LogisticsSeeder::class);       // drivers/vehicles/deliveries
        $this->call(AdminSeeder::class);           // fallback admin account
    }
}
