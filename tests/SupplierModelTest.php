<?php

namespace Tests;

use CodeIgniter\Test\CIUnitTestCase;
use App\Models\SupplierModel;

class SupplierModelTest extends CIUnitTestCase
{
    protected $supplierModel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->supplierModel = new SupplierModel();
    }

    public function testCanCreateSupplier()
    {
        $data = [
            'supplier_name' => 'Test Supplier',
            'contact' => '123-456-7890',
            'email' => 'test@supplier.com',
            'address' => '123 Test St',
            'branch_serve' => 'Main Branch',
            'status' => 'Active'
        ];

        $result = $this->supplierModel->insert($data);
        $this->assertTrue($result > 0);
    }

    public function testCanGetActiveSuppliers()
    {
        $activeSuppliers = $this->supplierModel->getActiveSuppliers();
        $this->assertIsArray($activeSuppliers);
    }

    public function testCanGetSuppliersByBranch()
    {
        $branch = 'Main Branch';
        $suppliers = $this->supplierModel->getSuppliersByBranch($branch);
        $this->assertIsArray($suppliers);
    }

    public function testCanUpdateStatus()
    {
        $supplierId = $this->supplierModel->insert([
            'supplier_name' => 'Status Test Supplier',
            'contact' => '098-765-4321',
            'email' => 'status@test.com',
            'address' => '456 Status Ave',
            'branch_serve' => 'Branch A',
            'status' => 'Active'
        ]);

        $result = $this->supplierModel->updateStatus($supplierId, 'Inactive');
        $this->assertTrue($result);

        $updatedSupplier = $this->supplierModel->find($supplierId);
        $this->assertEquals('Inactive', $updatedSupplier['status']);
    }

    public function testCanGetSupplierPerformance()
    {
        $supplierId = 1;
        $performance = $this->supplierModel->getSupplierPerformance($supplierId);
        $this->assertIsArray($performance);
    }
}
