<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\InventoryModel;

class InventorySeeder extends Seeder
{
public function run()
{
$inventoryModel = new InventoryModel();
$barcodeGenerator = new \App\Libraries\BarcodeGenerator();

// Get all branches to distribute items
$branches = $this->db->table('branches')->get()->getResultArray();
$branchIds = array_column($branches, 'id');

if (empty($branchIds)) {
// If no branches exist, skip seeding (branches should be seeded first)
return;
}

$now = date('Y-m-d H:i:s');
$today = date('Y-m-d');

// Sample chicken inventory items
$items = [
// Whole Chicken Products
[
'item_name' => 'Whole Chicken - Premium Grade',
'type' => 'Whole Chicken',
'quantity' => 45,
'barcode' => $barcodeGenerator->generateBarcode('INV', 12),
'expiry_date' => date('Y-m-d', strtotime('+5 days')),
'branch_id' => $branchIds[0] ?? null,
'status' => 'available',
'created_at' => $now,
'updated_at' => $now,
],
[
'item_name' => 'Whole Chicken - Standard Grade',
'type' => 'Whole Chicken',
'quantity' => 30,
'barcode' => $barcodeGenerator->generateBarcode('INV', 12),
'expiry_date' => date('Y-m-d', strtotime('+3 days')),
'branch_id' => $branchIds[1] ?? null,
'status' => 'available',
'created_at' => $now,
'updated_at' => $now,
],
[
'item_name' => 'Whole Chicken - Fresh',
'type' => 'Whole Chicken',
'quantity' => 4,
'barcode' => $barcodeGenerator->generateBarcode('INV', 12),
'expiry_date' => date('Y-m-d', strtotime('+1 day')),
'branch_id' => $branchIds[2] ?? null,
'status' => 'low_stock',
'created_at' => $now,
'updated_at' => $now,
],

// Chicken Parts - Breasts
[
'item_name' => 'Chicken Breast - Boneless',
'type' => 'Part Chicken',
'quantity' => 60,
'barcode' => $barcodeGenerator->generateBarcode('INV', 12),
'expiry_date' => date('Y-m-d', strtotime('+7 days')),
'branch_id' => $branchIds[0] ?? null,
'status' => 'available',
'created_at' => $now,
'updated_at' => $now,
],
[
'item_name' => 'Chicken Breast - With Bone',
'type' => 'Part Chicken',
'quantity' => 3,
'barcode' => $barcodeGenerator->generateBarcode('INV', 12),
'expiry_date' => date('Y-m-d', strtotime('+2 days')),
'branch_id' => $branchIds[1] ?? null,
'status' => 'low_stock',
'created_at' => $now,
'updated_at' => $now,
],

// Chicken Parts - Thighs
[
'item_name' => 'Chicken Thighs - Skin On',
'type' => 'Part Chicken',
'quantity' => 50,
'barcode' => $barcodeGenerator->generateBarcode('INV', 12),
'expiry_date' => date('Y-m-d', strtotime('+6 days')),
'branch_id' => $branchIds[2] ?? null,
'status' => 'available',
'created_at' => $now,
'updated_at' => $now,
],
[
'item_name' => 'Chicken Thighs - Boneless',
'type' => 'Part Chicken',
'quantity' => 2,
'barcode' => $barcodeGenerator->generateBarcode('INV', 12),
'expiry_date' => date('Y-m-d', strtotime('+1 day')),
'branch_id' => $branchIds[3] ?? null,
'status' => 'low_stock',
'created_at' => $now,
'updated_at' => $now,
],

// Chicken Parts - Wings
[
'item_name' => 'Chicken Wings - Whole',
'type' => 'Part Chicken',
'quantity' => 40,
'barcode' => $barcodeGenerator->generateBarcode('INV', 12),
'expiry_date' => date('Y-m-d', strtotime('+4 days')),
'branch_id' => $branchIds[0] ?? null,
'status' => 'available',
'created_at' => $now,
'updated_at' => $now,
],
[
'item_name' => 'Chicken Wings - Party Pack',
'type' => 'Part Chicken',
'quantity' => 0,
'barcode' => $barcodeGenerator->generateBarcode('INV', 12),
'expiry_date' => date('Y-m-d', strtotime('-1 day')),
'branch_id' => $branchIds[4] ?? null,
'status' => 'out_of_stock',
'created_at' => $now,
'updated_at' => $now,
],

// Chicken Parts - Drumsticks
[
'item_name' => 'Chicken Drumsticks',
'type' => 'Part Chicken',
'quantity' => 35,
'barcode' => $barcodeGenerator->generateBarcode('INV', 12),
'expiry_date' => date('Y-m-d', strtotime('+5 days')),
'branch_id' => $branchIds[1] ?? null,
'status' => 'available',
'created_at' => $now,
'updated_at' => $now,
],
[
'item_name' => 'Chicken Drumsticks - Jumbo',
'type' => 'Part Chicken',
'quantity' => 1,
'barcode' => $barcodeGenerator->generateBarcode('INV', 12),
'expiry_date' => date('Y-m-d', strtotime('+1 day')),
'branch_id' => $branchIds[2] ?? null,
'status' => 'low_stock',
'created_at' => $now,
'updated_at' => $now,
],

// Other Chicken Parts
[
'item_name' => 'Chicken Gizzard',
'type' => 'Part Chicken',
'quantity' => 25,
'barcode' => $barcodeGenerator->generateBarcode('INV', 12),
'expiry_date' => date('Y-m-d', strtotime('+3 days')),
'branch_id' => $branchIds[3] ?? null,
'status' => 'available',
'created_at' => $now,
'updated_at' => $now,
],
[
'item_name' => 'Chicken Liver',
'type' => 'Part Chicken',
'quantity' => 20,
'barcode' => $barcodeGenerator->generateBarcode('INV', 12),
'expiry_date' => date('Y-m-d', strtotime('+2 days')),
'branch_id' => $branchIds[4] ?? null,
'status' => 'available',
'created_at' => $now,
'updated_at' => $now,
],
[
'item_name' => 'Chicken Feet',
'type' => 'Part Chicken',
'quantity' => 5,
'barcode' => $barcodeGenerator->generateBarcode('INV', 12),
'expiry_date' => date('Y-m-d', strtotime('+2 days')),
'branch_id' => $branchIds[0] ?? null,
'status' => 'low_stock',
'created_at' => $now,
'updated_at' => $now,
],

// Ingredients - Oils
[
'item_name' => 'Cooking Oil - Vegetable',
'type' => 'Ingredient',
'quantity' => 80,
'barcode' => $barcodeGenerator->generateBarcode('INV', 12),
'expiry_date' => date('Y-m-d', strtotime('+180 days')),
'branch_id' => $branchIds[1] ?? null,
'status' => 'available',
'created_at' => $now,
'updated_at' => $now,
],
[
'item_name' => 'Cooking Oil - Canola',
'type' => 'Ingredient',
'quantity' => 4,
'barcode' => $barcodeGenerator->generateBarcode('INV', 12),
'expiry_date' => date('Y-m-d', strtotime('+90 days')),
'branch_id' => $branchIds[2] ?? null,
'status' => 'low_stock',
'created_at' => $now,
'updated_at' => $now,
],
[
'item_name' => 'Cooking Oil - Olive Oil',
'type' => 'Ingredient',
'quantity' => 15,
'barcode' => $barcodeGenerator->generateBarcode('INV', 12),
'expiry_date' => date('Y-m-d', strtotime('+120 days')),
'branch_id' => $branchIds[3] ?? null,
'status' => 'available',
'created_at' => $now,
'updated_at' => $now,
],
[
'item_name' => 'Cooking Oil - Palm Oil',
'type' => 'Ingredient',
'quantity' => 0,
'barcode' => $barcodeGenerator->generateBarcode('INV', 12),
'expiry_date' => date('Y-m-d', strtotime('+60 days')),
'branch_id' => $branchIds[4] ?? null,
'status' => 'out_of_stock',
'created_at' => $now,
'updated_at' => $now,
],

// Ingredients - Spices & Seasonings
[
'item_name' => 'Garlic Powder',
'type' => 'Ingredient',
'quantity' => 50,
'barcode' => $barcodeGenerator->generateBarcode('INV', 12),
'expiry_date' => date('Y-m-d', strtotime('+365 days')),
'branch_id' => $branchIds[0] ?? null,
'status' => 'available',
'created_at' => $now,
'updated_at' => $now,
],
[
'item_name' => 'Black Pepper',
'type' => 'Ingredient',
'quantity' => 30,
'barcode' => $barcodeGenerator->generateBarcode('INV', 12),
'expiry_date' => date('Y-m-d', strtotime('+200 days')),
'branch_id' => $branchIds[1] ?? null,
'status' => 'available',
'created_at' => $now,
'updated_at' => $now,
],
[
'item_name' => 'Chicken Seasoning Mix',
'type' => 'Ingredient',
'quantity' => 2,
'barcode' => $barcodeGenerator->generateBarcode('INV', 12),
'expiry_date' => date('Y-m-d', strtotime('+30 days')),
'branch_id' => $branchIds[2] ?? null,
'status' => 'low_stock',
'created_at' => $now,
'updated_at' => $now,
],

// More Whole Chicken (for variety)
[
'item_name' => 'Whole Chicken - Organic',
'type' => 'Whole Chicken',
'quantity' => 25,
'barcode' => $barcodeGenerator->generateBarcode('INV', 12),
'expiry_date' => date('Y-m-d', strtotime('+4 days')),
'branch_id' => $branchIds[3] ?? null,
'status' => 'available',
'created_at' => $now,
'updated_at' => $now,
],
[
'item_name' => 'Whole Chicken - Free Range',
'type' => 'Whole Chicken',
'quantity' => 18,
'barcode' => $barcodeGenerator->generateBarcode('INV', 12),
'expiry_date' => date('Y-m-d', strtotime('+6 days')),
'branch_id' => $branchIds[4] ?? null,
'status' => 'available',
'created_at' => $now,
'updated_at' => $now,
],

// More Parts
[
'item_name' => 'Chicken Neck',
'type' => 'Part Chicken',
'quantity' => 15,
'barcode' => $barcodeGenerator->generateBarcode('INV', 12),
'expiry_date' => date('Y-m-d', strtotime('+3 days')),
'branch_id' => $branchIds[0] ?? null,
'status' => 'available',
'created_at' => $now,
'updated_at' => $now,
],

// Expired items for alerts
[
'item_name' => 'Chicken Breast - Expired Sample',
'type' => 'Part Chicken',
'quantity' => 10,
'barcode' => $barcodeGenerator->generateBarcode('INV', 12),
'expiry_date' => date('Y-m-d', strtotime('-2 days')),
'branch_id' => $branchIds[1] ?? null,
'status' => 'available',
'created_at' => $now,
'updated_at' => $now,
],
[
'item_name' => 'Whole Chicken - Expired',
'type' => 'Whole Chicken',
'quantity' => 8,
'barcode' => $barcodeGenerator->generateBarcode('INV', 12),
'expiry_date' => date('Y-m-d', strtotime('-5 days')),
'branch_id' => $branchIds[2] ?? null,
'status' => 'available',
'created_at' => $now,
'updated_at' => $now,
],
];

// Insert all items
$this->db->table('inventory')->insertBatch($items);
}
}