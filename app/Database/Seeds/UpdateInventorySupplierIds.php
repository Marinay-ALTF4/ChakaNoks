<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UpdateInventorySupplierIds extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();
        
        // Update existing inventory items with supplier IDs based on item type/name
        // This is a one-time update script for existing records
        
        $updates = [
            // Fruits -> Local Produce Co. (ID: 3)
            ['item_name' => 'Apple', 'supplier_id' => 3],
            ['item_name' => 'Banana', 'supplier_id' => 3],
            ['item_name' => 'Orange', 'supplier_id' => 3],
            ['item_name' => 'Grapes', 'supplier_id' => 3],
            ['item_name' => 'Strawberry', 'supplier_id' => 3],
            
            // Dairy -> Fresh Foods Inc. (ID: 1)
            ['item_name' => 'Milk', 'supplier_id' => 1],
            ['item_name' => 'Cheese', 'supplier_id' => 1],
            ['item_name' => 'Yogurt', 'supplier_id' => 1],
            ['item_name' => 'Butter', 'supplier_id' => 1],
            
            // Bakery -> Fresh Foods Inc. (ID: 1)
            ['item_name' => 'Bread', 'supplier_id' => 1],
            ['item_name' => 'Croissant', 'supplier_id' => 1],
            ['item_name' => 'Bagel', 'supplier_id' => 1],
            
            // Meat -> Fresh Foods Inc. (ID: 1)
            ['item_name' => 'Chicken', 'supplier_id' => 1],
            ['item_name' => 'Beef', 'supplier_id' => 1],
            ['item_name' => 'Pork', 'supplier_id' => 1],
            
            // Eggs -> Global Distributors (ID: 2)
            ['item_name' => 'Eggs', 'supplier_id' => 2],
        ];
        
        $builder = $db->table('inventory');
        
        foreach ($updates as $update) {
            // Update items that match the name and don't have a supplier_id yet
            $builder->where('item_name', $update['item_name'])
                   ->where('supplier_id IS NULL', null, false)
                   ->update(['supplier_id' => $update['supplier_id']]);
        }
        
        // For any remaining items without supplier_id, assign to Fresh Foods Inc. (default)
        $builder->where('supplier_id IS NULL', null, false)
               ->update(['supplier_id' => 1]);
        
        echo "Inventory supplier IDs updated successfully!\n";
    }
}

