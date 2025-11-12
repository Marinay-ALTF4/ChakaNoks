<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\DeliveryModel;
use App\Models\PurchaseOrderModel;
use App\Models\LogModel;

class LogisticsController extends Controller
{
    protected $deliveryModel;
    protected $purchaseOrderModel;
    protected $logModel;

    public function __construct()
    {
        $this->deliveryModel = new DeliveryModel();
        $this->purchaseOrderModel = new PurchaseOrderModel();
        $this->logModel = new LogModel();
    }

    // Dashboard
    public function dashboard()
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'logistics_coordinator') {
            return redirect()->to('/');
        }

        $data['pendingDeliveries'] = $this->deliveryModel->getPendingDeliveries();
        $data['totalDeliveries'] = $this->deliveryModel->countAll();
        $data['completedToday'] = $this->deliveryModel->where('status', 'delivered')
                                                      ->where('actual_date', date('Y-m-d'))
                                                      ->countAllResults();

        return view('logistics/dashboard', $data);
    }

    // Schedule Delivery
    public function scheduleDelivery()
    {
        if ($this->request->getMethod() === 'post') {
            $orderId = $this->request->getPost('order_id');
            $scheduledDate = $this->request->getPost('scheduled_date');
            $route = $this->request->getPost('route');

            $trackingNumber = $this->deliveryModel->generateTrackingNumber();

            $this->deliveryModel->insert([
                'order_id' => $orderId,
                'status' => 'scheduled',
                'tracking_number' => $trackingNumber,
                'route' => $route,
                'scheduled_date' => $scheduledDate,
                'logistics_coordinator_id' => session()->get('user_id')
            ]);

            // Log the action
            $this->logModel->logAction(session()->get('user_id'), 'scheduled_delivery', "Delivery scheduled for order #$orderId");

            return redirect()->to('/logistics/deliveries')->with('success', 'Delivery scheduled successfully');
        }

        $data['pendingOrders'] = $this->purchaseOrderModel->where('status', 'approved')->findAll();
        return view('logistics/schedule_delivery', $data);
    }

    // Update Delivery Status
    public function updateDeliveryStatus($deliveryId)
    {
        if ($this->request->getMethod() === 'post') {
            $status = $this->request->getPost('status');
            $actualDate = ($status === 'delivered') ? date('Y-m-d H:i:s') : null;

            $this->deliveryModel->updateStatus($deliveryId, $status, $actualDate);

            // Log the action
            $this->logModel->logAction(session()->get('user_id'), 'updated_delivery_status', "Updated delivery #$deliveryId to $status");

            return redirect()->to('/logistics/deliveries')->with('success', 'Delivery status updated');
        }

        $data['delivery'] = $this->deliveryModel->find($deliveryId);
        return view('logistics/update_delivery_status', $data);
    }

    // View All Deliveries
    public function deliveries()
    {
        $data['deliveries'] = $this->deliveryModel->findAll();
        return view('logistics/deliveries', $data);
    }

    // Optimize Routes
    public function optimizeRoutes()
    {
        $pendingDeliveries = $this->deliveryModel->getPendingDeliveries();
        $optimizedDeliveries = $this->deliveryModel->optimizeRoute($pendingDeliveries);

        $data['optimizedDeliveries'] = $optimizedDeliveries;
        return view('logistics/optimized_routes', $data);
    }

    // Track Delivery
    public function trackDelivery($trackingNumber)
    {
        $delivery = $this->deliveryModel->where('tracking_number', $trackingNumber)->first();

        if (!$delivery) {
            return redirect()->back()->with('error', 'Tracking number not found');
        }

        $data['delivery'] = $delivery;
        $data['order'] = $this->purchaseOrderModel->find($delivery['order_id']);
        return view('logistics/track_delivery', $data);
    }
}
