<?php

namespace Tests;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\HTTP\ResponseInterface;

class CentralADControllerTest extends CIUnitTestCase
{
    public function testDashboard()
    {
        $result = $this->withURI('http://localhost:8080/central/dashboard')
                      ->controller(\App\Controllers\Central_AD::class)
                      ->execute('dashboard');

        $this->assertInstanceOf(ResponseInterface::class, $result);
        $this->assertEquals(200, $result->getStatusCode());
    }

    public function testInventory()
    {
        $result = $this->withURI('http://localhost:8080/central/inventory')
                      ->controller(\App\Controllers\Central_AD::class)
                      ->execute('inventory');

        $this->assertInstanceOf(ResponseInterface::class, $result);
        $this->assertEquals(200, $result->getStatusCode());
    }

    public function testSuppliers()
    {
        $result = $this->withURI('http://localhost:8080/central/suppliers')
                      ->controller(\App\Controllers\Central_AD::class)
                      ->execute('suppliers');

        $this->assertInstanceOf(ResponseInterface::class, $result);
        $this->assertEquals(200, $result->getStatusCode());
    }

    public function testReports()
    {
        $result = $this->withURI('http://localhost:8080/central/reports')
                      ->controller(\App\Controllers\Central_AD::class)
                      ->execute('reports');

        $this->assertInstanceOf(ResponseInterface::class, $result);
        $this->assertEquals(200, $result->getStatusCode());
    }

    public function testFranchising()
    {
        $result = $this->withURI('http://localhost:8080/central/franchising')
                      ->controller(\App\Controllers\Central_AD::class)
                      ->execute('franchising');

        $this->assertInstanceOf(ResponseInterface::class, $result);
        $this->assertEquals(200, $result->getStatusCode());
    }

    public function testOrders()
    {
        $result = $this->withURI('http://localhost:8080/central/orders')
                      ->controller(\App\Controllers\Central_AD::class)
                      ->execute('orders');

        $this->assertInstanceOf(ResponseInterface::class, $result);
        $this->assertEquals(200, $result->getStatusCode());
    }
}
