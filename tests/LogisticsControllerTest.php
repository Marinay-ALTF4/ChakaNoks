<?php

namespace Tests;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\HTTP\ResponseInterface;

class LogisticsControllerTest extends CIUnitTestCase
{
    public function testDashboard()
    {
        $result = $this->withURI('http://localhost:8080/logistics/dashboard')
                      ->controller(\App\Controllers\LogisticsController::class)
                      ->execute('dashboard');

        $this->assertInstanceOf(ResponseInterface::class, $result);
        $this->assertEquals(200, $result->getStatusCode());
    }

    public function testDeliveries()
    {
        $result = $this->withURI('http://localhost:8080/logistics/deliveries')
                      ->controller(\App\Controllers\LogisticsController::class)
                      ->execute('deliveries');

        $this->assertInstanceOf(ResponseInterface::class, $result);
        $this->assertEquals(200, $result->getStatusCode());
    }

    public function testOptimizeRoutes()
    {
        $result = $this->withURI('http://localhost:8080/logistics/optimize-routes')
                      ->controller(\App\Controllers\LogisticsController::class)
                      ->execute('optimizeRoutes');

        $this->assertInstanceOf(ResponseInterface::class, $result);
        $this->assertEquals(200, $result->getStatusCode());
    }

    public function testScheduleDelivery()
    {
        $result = $this->withURI('http://localhost:8080/logistics/schedule-delivery')
                      ->controller(\App\Controllers\LogisticsController::class)
                      ->execute('scheduleDelivery');

        $this->assertInstanceOf(ResponseInterface::class, $result);
        $this->assertEquals(200, $result->getStatusCode());
    }

    public function testTrackDelivery()
    {
        $result = $this->withURI('http://localhost:8080/logistics/track-delivery/TRK123456')
                      ->controller(\App\Controllers\LogisticsController::class)
                      ->execute('trackDelivery', 'TRK123456');

        $this->assertInstanceOf(ResponseInterface::class, $result);
        $this->assertEquals(200, $result->getStatusCode());
    }
}
