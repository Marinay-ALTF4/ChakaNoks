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
        // Only logistics_coordinator role can access
        if (!session()->get('logged_in') || session()->get('role') !== 'logistics_coordinator') {
            return redirect()->to('/')->with('error', 'Unauthorized access. Logistics coordinator access only.');
        }

        // Get orders ready for delivery
        $data['readyOrders'] = $this->purchaseOrderModel
            ->select('purchase_orders.*, suppliers.supplier_name, branches.name as branch_name')
            ->join('suppliers', 'suppliers.id = purchase_orders.supplier_id')
            ->join('branches', 'branches.id = purchase_orders.branch_id')
            ->where('purchase_orders.status', 'ready_for_delivery')
            ->orderBy('purchase_orders.prepared_at', 'ASC')
            ->findAll();

        $data['readyOrdersCount'] = count($data['readyOrders']);

        // Get scheduled deliveries with order details
        $data['scheduledDeliveries'] = $this->deliveryModel
            ->select('deliveries.*, purchase_orders.item_name, branches.name as branch_name')
            ->join('purchase_orders', 'purchase_orders.id = deliveries.order_id')
            ->join('branches', 'branches.id = purchase_orders.branch_id')
            ->where('deliveries.status', 'scheduled')
            ->orderBy('deliveries.scheduled_date', 'ASC')
            ->findAll();

        $data['scheduledCount'] = count($data['scheduledDeliveries']);
        $data['totalDeliveries'] = $this->deliveryModel->countAll();
        $data['completedToday'] = $this->deliveryModel
            ->where('status', 'delivered')
            ->where('DATE(actual_date)', date('Y-m-d'))
            ->countAllResults();

        return view('logistics/dashboard', $data);
    }

    // Schedule Delivery
    public function scheduleDelivery()
    {
        // Only logistics_coordinator role can access
        if (!session()->get('logged_in') || session()->get('role') !== 'logistics_coordinator') {
            return redirect()->to('/')->with('error', 'Unauthorized access. Logistics coordinator access only.');
        }

        if ($this->request->getMethod() === 'post') {
            $orderId = $this->request->getPost('order_id');
            $scheduledDate = $this->request->getPost('scheduled_date');
            $route = $this->request->getPost('route');

            $trackingNumber = $this->deliveryModel->generateTrackingNumber();

            $db = \Config\Database::connect();
            $db->transStart();

            try {
                // Create delivery record
                $this->deliveryModel->insert([
                    'order_id' => $orderId,
                    'status' => 'scheduled',
                    'tracking_number' => $trackingNumber,
                    'route' => $route,
                    'scheduled_date' => $scheduledDate,
                    'logistics_coordinator_id' => session()->get('user_id')
                ]);

                // Update purchase order status to delivered (or keep as ready_for_delivery until actually delivered)
                // The order status will change to 'delivered' when delivery is completed

                $db->transComplete();

                if ($db->transStatus() === false) {
                    throw new \Exception('Transaction failed');
                }

                // Log the action
                $this->logModel->logAction(session()->get('user_id'), 'scheduled_delivery', "Delivery scheduled for order #$orderId");

                return redirect()->to('/logistics/deliveries')->with('success', 'Delivery scheduled successfully');
            } catch (\Exception $e) {
                $db->transRollback();
                return redirect()->back()->withInput()->with('error', 'Error: ' . $e->getMessage());
            }
        }

        // Get orders that are ready for delivery (prepared by supplier)
        $data['readyOrders'] = $this->purchaseOrderModel
            ->select('purchase_orders.*, suppliers.supplier_name, branches.name as branch_name')
            ->join('suppliers', 'suppliers.id = purchase_orders.supplier_id')
            ->join('branches', 'branches.id = purchase_orders.branch_id')
            ->where('purchase_orders.status', 'ready_for_delivery')
            ->findAll();
        return view('logistics/schedule_delivery', $data);
    }

    // Update Delivery Status
    public function updateDeliveryStatus($deliveryId)
    {
        // Only logistics_coordinator role can access
        if (!session()->get('logged_in') || session()->get('role') !== 'logistics_coordinator') {
            return redirect()->to('/')->with('error', 'Unauthorized access. Logistics coordinator access only.');
        }

        if ($this->request->getMethod() === 'post') {
            $status = $this->request->getPost('status');
            $actualDate = ($status === 'delivered') ? date('Y-m-d H:i:s') : null;

            $db = \Config\Database::connect();
            $db->transStart();

            try {
                // Update delivery status
                $this->deliveryModel->updateStatus($deliveryId, $status, $actualDate);

                // If delivered, update purchase order status
                if ($status === 'delivered') {
                    $delivery = $this->deliveryModel->find($deliveryId);
                    if ($delivery && $delivery['order_id']) {
                        $this->purchaseOrderModel->update($delivery['order_id'], [
                            'status' => 'delivered',
                            'delivery_date' => $actualDate
                        ]);
                    }
                }

                $db->transComplete();

                if ($db->transStatus() === false) {
                    throw new \Exception('Transaction failed');
                }

                // Log the action
                $this->logModel->logAction(session()->get('user_id'), 'updated_delivery_status', "Updated delivery #$deliveryId to $status");

                return redirect()->to('/logistics/deliveries')->with('success', 'Delivery status updated');
            } catch (\Exception $e) {
                $db->transRollback();
                return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
            }
        }

        $data['delivery'] = $this->deliveryModel->find($deliveryId);
        return view('logistics/update_delivery_status', $data);
    }

    // View All Deliveries
    public function deliveries()
    {
        // Only logistics_coordinator role can access
        if (!session()->get('logged_in') || session()->get('role') !== 'logistics_coordinator') {
            return redirect()->to('/')->with('error', 'Unauthorized access. Logistics coordinator access only.');
        }

        $data['deliveries'] = $this->deliveryModel
            ->select('deliveries.*, purchase_orders.item_name, branches.name as branch_name, suppliers.supplier_name')
            ->join('purchase_orders', 'purchase_orders.id = deliveries.order_id')
            ->join('branches', 'branches.id = purchase_orders.branch_id')
            ->join('suppliers', 'suppliers.id = purchase_orders.supplier_id')
            ->orderBy('deliveries.created_at', 'DESC')
            ->findAll();
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
