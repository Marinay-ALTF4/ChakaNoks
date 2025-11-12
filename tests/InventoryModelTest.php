<?php

namespace Tests;

use CodeIgniter\Test\CIUnitTestCase;
use App\Models\InventoryModel;

class InventoryModelTest extends CIUnitTestCase
{
    protected $inventoryModel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->inventoryModel = new InventoryModel();
    }

    public function testCanCreateInventoryItem()
    {
        $data = [
            'item_name' => 'Test Item',
            'quantity' => 10,
            'type' => 'perishable',
            'barcode' => '123456789',
            'expiry_date' => '2025-12-31',
            'branch_id' => 1
        ];

        $result = $this->inventoryModel->insert($data);
        $this->assertTrue($result > 0);
    }

    public function testCanGetLowStockItems()
    {
        // Insert test data
        $this->inventoryModel->insert([
            'item_name' => 'Low Stock Item',
            'quantity' => 2,
            'type' => 'non-perishable',
            'barcode' => '987654321',
            'branch_id' => 1
        ]);

        $lowStockItems = $this->inventoryModel->getLowStockItems();
        $this->assertIsArray($lowStockItems);
        $this->assertGreaterThan(0, count($lowStockItems));
    }

    public function testCanGetExpiredItems()
    {
        // Insert expired item
        $this->inventoryModel->insert([
            'item_name' => 'Expired Item',
            'quantity' => 5,
            'type' => 'perishable',
            'barcode' => '111111111',
            'expiry_date' => '2020-01-01',
            'branch_id' => 1
        ]);

        $expiredItems = $this->inventoryModel->getExpiredItems();
        $this->assertIsArray($expiredItems);
        $this->assertGreaterThan(0, count($expiredItems));
    }

    public function testCanGetExpiringSoon()
    {
        // Insert item expiring soon
        $this->inventoryModel->insert([
            'item_name' => 'Expiring Soon Item',
            'quantity' => 5,
            'type' => 'perishable',
            'barcode' => '222222222',
            'expiry_date' => date('Y-m-d', strtotime('+3 days')),
            'branch_id' => 1
        ]);

        $expiringSoon = $this->inventoryModel->getExpiringSoon();
        $this->assertIsArray($expiringSoon);
        $this->assertGreaterThan(0, count($expiringSoon));
    }

    public function testCanGetAlerts()
    {
        $alerts = $this->inventoryModel->getAlerts();
        $this->assertIsArray($alerts);
    }

    public function testCanUpdateQuantity()
    {
        $itemId = $this->inventoryModel->insert([
            'item_name' => 'Update Test Item',
            'quantity' => 10,
            'type' => 'non-perishable',
            'barcode' => '333333333',
            'branch_id' => 1
        ]);

        $result = $this->inventoryModel->updateQuantity($itemId, 15);
        $this->assertTrue($result);

        $updatedItem = $this->inventoryModel->find($itemId);
        $this->assertEquals(15, $updatedItem['quantity']);
    }

    public function testCanGetByBarcode()
    {
        $barcode = '444444444';
        $this->inventoryModel->insert([
            'item_name' => 'Barcode Test Item',
            'quantity' => 5,
            'type' => 'non-perishable',
            'barcode' => $barcode,
            'branch_id' => 1
        ]);

        $item = $this->inventoryModel->getByBarcode($barcode);
        $this->assertIsArray($item);
        $this->assertEquals($barcode, $item['barcode']);
    }

    public function testCanGetByBranch()
    {
        $branchId = 1;
        $items = $this->inventoryModel->getByBranch($branchId);
        $this->assertIsArray($items);
    }
}
