<?php

namespace Tests;

use CodeIgniter\Test\CIUnitTestCase;
use App\Models\PurchaseOrderModel;

class PurchaseOrderModelTest extends CIUnitTestCase
{
    protected $purchaseOrderModel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->purchaseOrderModel = new PurchaseOrderModel();
    }

    public function testCanCreatePurchaseOrder()
    {
        $data = [
            'supplier_id' => 1,
            'branch_id' => 1,
            'item_name' => 'Test Item',
            'quantity' => 10,
            'unit_price' => 5.50,
            'total_price' => 55.00,
            'status' => 'pending',
            'order_date' => date('Y-m-d'),
            'delivery_date' => date('Y-m-d', strtotime('+7 days')),
            'approved_by' => 1
        ];

        $result = $this->purchaseOrderModel->insert($data);
        $this->assertTrue($result > 0);
    }

    public function testCanGetPendingOrders()
    {
        $pendingOrders = $this->purchaseOrderModel->getPendingOrders();
        $this->assertIsArray($pendingOrders);
    }

    public function testCanApproveOrder()
    {
        $orderId = $this->purchaseOrderModel->insert([
            'supplier_id' => 1,
            'branch_id' => 1,
            'item_name' => 'Approval Test Item',
            'quantity' => 5,
            'unit_price' => 10.00,
            'total_price' => 50.00,
            'status' => 'pending',
            'order_date' => date('Y-m-d'),
            'approved_by' => 1
        ]);

        $result = $this->purchaseOrderModel->approveOrder($orderId, 2);
        $this->assertTrue($result);

        $updatedOrder = $this->purchaseOrderModel->find($orderId);
        $this->assertEquals('approved', $updatedOrder['status']);
        $this->assertEquals(2, $updatedOrder['approved_by']);
    }

    public function testCanGetOrdersByBranch()
    {
        $branchId = 1;
        $orders = $this->purchaseOrderModel->getOrdersByBranch($branchId);
        $this->assertIsArray($orders);
    }

    public function testCanCalculateTotalPrice()
    {
        $quantity = 10;
        $unitPrice = 7.25;
        $total = $this->purchaseOrderModel->calculateTotalPrice($quantity, $unitPrice);
        $this->assertEquals(72.50, $total);
    }
}
