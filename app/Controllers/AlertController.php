<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\InventoryModel;
use App\Models\LogModel;

class AlertController extends Controller
{
    protected $inventoryModel;
    protected $logModel;

    public function __construct()
    {
        $this->inventoryModel = new InventoryModel();
        $this->logModel = new LogModel();
    }

    // Send Low Stock Alerts
    public function sendLowStockAlerts()
    {
        $lowStockItems = $this->inventoryModel->getLowStockItems();

        foreach ($lowStockItems as $item) {
            // Send email alert
            $this->sendEmailAlert('Low Stock Alert', "Item {$item['item_name']} is low on stock (Quantity: {$item['quantity']})");

            // Log the alert
            $this->logModel->logAction(0, 'low_stock_alert', "Alert sent for item {$item['item_name']}");
        }

        return json_encode(['status' => 'success', 'alerts_sent' => count($lowStockItems)]);
    }

    // Send Expiry Alerts
    public function sendExpiryAlerts()
    {
        $expiringItems = $this->inventoryModel->getExpiringSoon();

        foreach ($expiringItems as $item) {
            $this->sendAlert('Expiry Alert', "Item {$item['item_name']} is expiring soon (Expiry: {$item['expiry_date']})");
            $this->logModel->logAction(0, 'expiry_alert', "Alert sent for expiring item {$item['item_name']}");
        }

        return json_encode(['status' => 'success', 'alerts_sent' => count($expiringItems)]);
    }

    // Get All Alerts
    public function getAlerts()
    {
        $alerts = $this->inventoryModel->getAlerts();
        return json_encode($alerts);
    }

    // Private method to send email alerts
    private function sendEmailAlert($subject, $message)
    {
        $email = \Config\Services::email();
        $email->setTo('manager@chakanoks.com'); // Configure recipient
        $email->setSubject($subject);
        $email->setMessage($message);

        if ($email->send()) {
            log_message('info', "Email alert sent: $subject");
        } else {
            log_message('error', "Failed to send email alert: " . $email->printDebugger());
        }
    }

    // Private method to send alerts (placeholder for actual implementation)
    private function sendAlert($subject, $message)
    {
        // Placeholder for SMS sending logic
        // You can integrate with SMS services like Twilio, etc.

        // For now, just log to console or file
        log_message('info', "Alert: $subject - $message");
    }

    // API endpoint for real-time alerts
    public function apiAlerts()
    {
        $alerts = $this->inventoryModel->getAlerts();
        return $this->response->setJSON($alerts);
    }
}
