<?php

namespace Tests;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\HTTP\ResponseInterface;

class AlertControllerTest extends CIUnitTestCase
{
    public function testSendLowStockAlerts()
    {
        $result = $this->withURI('http://localhost:8080/alerts/send-low-stock')
                      ->controller(\App\Controllers\AlertController::class)
                      ->execute('sendLowStockAlerts');

        $this->assertInstanceOf(ResponseInterface::class, $result);
        $responseData = json_decode($result->getBody(), true);
        $this->assertIsArray($responseData);
        $this->assertArrayHasKey('status', $responseData);
        $this->assertArrayHasKey('alerts_sent', $responseData);
    }

    public function testSendExpiryAlerts()
    {
        $result = $this->withURI('http://localhost:8080/alerts/send-expiry')
                      ->controller(\App\Controllers\AlertController::class)
                      ->execute('sendExpiryAlerts');

        $this->assertInstanceOf(ResponseInterface::class, $result);
        $responseData = json_decode($result->getBody(), true);
        $this->assertIsArray($responseData);
        $this->assertArrayHasKey('status', $responseData);
        $this->assertArrayHasKey('alerts_sent', $responseData);
    }

    public function testGetAlerts()
    {
        $result = $this->withURI('http://localhost:8080/alerts/get')
                      ->controller(\App\Controllers\AlertController::class)
                      ->execute('getAlerts');

        $this->assertInstanceOf(ResponseInterface::class, $result);
        $responseData = json_decode($result->getBody(), true);
        $this->assertIsArray($responseData);
    }

    public function testApiAlerts()
    {
        $result = $this->withURI('http://localhost:8080/api/alerts')
                      ->controller(\App\Controllers\AlertController::class)
                      ->execute('apiAlerts');

        $this->assertInstanceOf(ResponseInterface::class, $result);
        $this->assertEquals(200, $result->getStatusCode());
        $responseData = json_decode($result->getBody(), true);
        $this->assertIsArray($responseData);
    }
}
