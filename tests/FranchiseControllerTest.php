<?php

namespace Tests;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\HTTP\ResponseInterface;

class FranchiseControllerTest extends CIUnitTestCase
{
    public function testDashboard()
    {
        $result = $this->withURI('http://localhost:8080/franchise/dashboard')
                      ->controller(\App\Controllers\FranchiseController::class)
                      ->execute('dashboard');

        $this->assertInstanceOf(ResponseInterface::class, $result);
        $this->assertEquals(200, $result->getStatusCode());
    }

    public function testApplications()
    {
        $result = $this->withURI('http://localhost:8080/franchise/applications')
                      ->controller(\App\Controllers\FranchiseController::class)
                      ->execute('applications');

        $this->assertInstanceOf(ResponseInterface::class, $result);
        $this->assertEquals(200, $result->getStatusCode());
    }

    public function testAllocationReport()
    {
        $result = $this->withURI('http://localhost:8080/franchise/allocation-report')
                      ->controller(\App\Controllers\FranchiseController::class)
                      ->execute('allocationReport');

        $this->assertInstanceOf(ResponseInterface::class, $result);
        $this->assertEquals(200, $result->getStatusCode());
    }

    public function testView()
    {
        $result = $this->withURI('http://localhost:8080/franchise/view/1')
                      ->controller(\App\Controllers\FranchiseController::class)
                      ->execute('view', 1);

        $this->assertInstanceOf(ResponseInterface::class, $result);
        $this->assertEquals(200, $result->getStatusCode());
    }

    public function testApproveApplication()
    {
        $result = $this->withURI('http://localhost:8080/franchise/approve/1')
                      ->controller(\App\Controllers\FranchiseController::class)
                      ->execute('approveApplication', 1);

        $this->assertInstanceOf(ResponseInterface::class, $result);
        $this->assertEquals(200, $result->getStatusCode());
    }

    public function testRejectApplication()
    {
        $result = $this->withURI('http://localhost:8080/franchise/reject/1')
                      ->controller(\App\Controllers\FranchiseController::class)
                      ->execute('rejectApplication', 1);

        $this->assertInstanceOf(ResponseInterface::class, $result);
        $this->assertEquals(200, $result->getStatusCode());
    }
}
