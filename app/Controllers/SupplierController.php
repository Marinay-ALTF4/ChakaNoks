<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\PurchaseOrderModel;
use App\Models\SupplierModel;
use App\Models\LogModel;

class SupplierController extends Controller
{
    protected $purchaseOrderModel;
    protected $supplierModel;
    protected $logModel;

    public function __construct()
    {
        $this->purchaseOrderModel = new PurchaseOrderModel();
        $this->supplierModel = new SupplierModel();
        $this->logModel = new LogModel();
    }

    // Supplier Dashboard
    public function dashboard()
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'supplier') {
            return redirect()->to('/');
        }

        $supplierId = session()->get('supplier_id');
        
        if (empty($supplierId)) {
            return redirect()->to('/')->with('error', 'Supplier ID not found. Please contact administrator.');
        }

        // Get orders for this supplier
        $data['pendingOrders'] = $this->purchaseOrderModel
            ->select('purchase_orders.*, branches.name as branch_name')
            ->join('branches', 'branches.id = purchase_orders.branch_id')
            ->where('purchase_orders.supplier_id', $supplierId)
            ->where('purchase_orders.status', 'pending_supplier')
            ->orderBy('purchase_orders.order_date', 'ASC')
            ->findAll();
        
        $data['confirmedOrders'] = $this->purchaseOrderModel
            ->select('purchase_orders.*, branches.name as branch_name')
            ->join('branches', 'branches.id = purchase_orders.branch_id')
            ->where('purchase_orders.supplier_id', $supplierId)
            ->where('purchase_orders.status', 'confirmed')
            ->orderBy('purchase_orders.supplier_confirmed_at', 'DESC')
            ->findAll();
        
        $data['preparingOrders'] = $this->purchaseOrderModel
            ->select('purchase_orders.*, branches.name as branch_name')
            ->join('branches', 'branches.id = purchase_orders.branch_id')
            ->where('purchase_orders.supplier_id', $supplierId)
            ->where('purchase_orders.status', 'preparing')
            ->orderBy('purchase_orders.prepared_at', 'DESC')
            ->findAll();

        // Statistics
        $data['stats'] = [
            'pending' => count($data['pendingOrders']),
            'confirmed' => count($data['confirmedOrders']),
            'preparing' => count($data['preparingOrders']),
            'total' => $this->purchaseOrderModel->where('supplier_id', $supplierId)->countAllResults()
        ];

        return view('suppliers/dashboard', $data);
    }

    // View all orders
    public function orders()
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'supplier') {
            return redirect()->to('/');
        }

        $supplierId = session()->get('supplier_id');
        
        $data['orders'] = $this->purchaseOrderModel
            ->select('purchase_orders.*, branches.name as branch_name')
            ->join('branches', 'branches.id = purchase_orders.branch_id')
            ->where('purchase_orders.supplier_id', $supplierId)
            ->orderBy('purchase_orders.order_date', 'DESC')
            ->findAll();

        return view('suppliers/orders', $data);
    }

    // Confirm order
    public function confirmOrder($orderId)
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'supplier') {
            return redirect()->to('/');
        }

        $supplierId = session()->get('supplier_id');
        
        // Verify order belongs to this supplier
        $order = $this->purchaseOrderModel->find($orderId);
        if (!$order || $order['supplier_id'] != $supplierId) {
            return redirect()->back()->with('error', 'Order not found or access denied');
        }

        if ($this->purchaseOrderModel->confirmBySupplier($orderId)) {
            $this->logModel->logAction(session()->get('user_id'), 'supplier_confirmed_order', "Supplier confirmed order #$orderId");
            return redirect()->to('/supplier/dashboard')->with('success', 'Order confirmed successfully');
        }
        
        return redirect()->back()->with('error', 'Failed to confirm order');
    }

    // Mark as preparing
    public function markPreparing($orderId)
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'supplier') {
            return redirect()->to('/');
        }

        $supplierId = session()->get('supplier_id');
        
        // Verify order belongs to this supplier
        $order = $this->purchaseOrderModel->find($orderId);
        if (!$order || $order['supplier_id'] != $supplierId) {
            return redirect()->back()->with('error', 'Order not found or access denied');
        }

        if ($this->purchaseOrderModel->markAsPreparing($orderId)) {
            $this->logModel->logAction(session()->get('user_id'), 'order_preparing', "Order #$orderId marked as preparing");
            return redirect()->to('/supplier/dashboard')->with('success', 'Order marked as preparing');
        }
        
        return redirect()->back()->with('error', 'Failed to update order status');
    }

    // Mark as ready for delivery
    public function markReadyForDelivery($orderId)
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'supplier') {
            return redirect()->to('/');
        }

        $supplierId = session()->get('supplier_id');
        
        // Verify order belongs to this supplier
        $order = $this->purchaseOrderModel->find($orderId);
        if (!$order || $order['supplier_id'] != $supplierId) {
            return redirect()->back()->with('error', 'Order not found or access denied');
        }

        if ($this->purchaseOrderModel->markAsReadyForDelivery($orderId)) {
            $this->logModel->logAction(session()->get('user_id'), 'order_ready_delivery', "Order #$orderId marked as ready for delivery");
            return redirect()->to('/supplier/dashboard')->with('success', 'Order marked as ready for delivery');
        }
        
        return redirect()->back()->with('error', 'Failed to update order status');
    }
}

