<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\BranchModel;
use App\Models\PurchaseOrderModel;
use App\Models\SupplierModel;
use App\Models\LogModel;
use App\Models\DeliveryModel;
use App\Models\SupplierInvoiceModel;
use App\Models\UserModel;
use App\Services\DeliveryService;
use CodeIgniter\I18n\Time;

class SupplierController extends Controller
{
    protected $purchaseOrderModel;
    protected $supplierModel;
    protected $logModel;
    protected $deliveryModel;
    protected $invoiceModel;
    protected DeliveryService $deliveryService;
    protected BranchModel $branchModel;

    public function __construct()
    {
        $this->purchaseOrderModel = new PurchaseOrderModel();
        $this->supplierModel      = new SupplierModel();
        $this->logModel           = new LogModel();
        $this->deliveryModel      = new DeliveryModel();
        $this->invoiceModel       = new SupplierInvoiceModel();
        $this->deliveryService    = new DeliveryService();
        $this->branchModel        = new BranchModel();
    }

    /**
     * Ensure supplier_id is always available for authenticated supplier accounts.
     */
    private function resolveSupplierId(): ?int
    {
        $supplierId = (int) (session()->get('supplier_id') ?? 0);
        if ($supplierId > 0) {
            return $supplierId;
        }

        $userId = (int) (session()->get('user_id') ?? 0);
        if ($userId <= 0) {
            return null;
        }

            $userModel = new UserModel();
        $user      = $userModel->select('supplier_id')->find($userId);
        $supplierId = (int) ($user['supplier_id'] ?? 0);

        if ($supplierId > 0) {
            session()->set('supplier_id', $supplierId);
            return $supplierId;
        }

        return null;
    }

    /**
     * Ensure a logistics delivery record exists once an order is ready for delivery.
     */
    private function ensureDeliveryRecordForOrder(array $order): void
    {
        $orderId = (int) ($order['id'] ?? 0);
        $destinationBranchId = (int) ($order['branch_id'] ?? 0);

        if ($orderId <= 0 || $destinationBranchId <= 0) {
            throw new \RuntimeException('Order is missing branch context.');
        }

        $existing = $this->deliveryModel->where('order_id', $orderId)->first();
        if ($existing) {
            $updates = [];

            if (empty($existing['scheduled_at'])) {
                $updates['scheduled_at'] = Time::now('Asia/Manila')->toDateTimeString();
            }

            if (($existing['status'] ?? null) === DeliveryModel::STATUS_CANCELLED) {
                $updates['status'] = DeliveryModel::STATUS_PENDING;
            }

            if (! empty($updates)) {
                $this->deliveryModel->update((int) $existing['id'], $updates);
            }

            return;
        }

        $sourceBranchId = $this->resolveSourceBranchIdForOrder($order);
        if ($sourceBranchId <= 0) {
            // Fallback to destination branch to satisfy FK constraint.
            $sourceBranchId = $destinationBranchId;
        }

        $payload = [
            'order_id'              => $orderId,
            'source_branch_id'      => $sourceBranchId,
            'destination_branch_id' => $destinationBranchId,
            'scheduled_at'          => Time::now('Asia/Manila')->toDateTimeString(),
            'status'                => DeliveryModel::STATUS_PENDING,
            'notes'                 => 'Auto-generated from ready-for-delivery order #' . $orderId,
        ];

        $this->deliveryService->createDelivery($payload, [], [], session()->get('user_id'));
    }

    private function resolveSourceBranchIdForOrder(array $order): int
    {
        $supplierId = (int) ($order['supplier_id'] ?? 0);
        if ($supplierId > 0) {
            $supplier = $this->supplierModel->find($supplierId);
            $branchName = trim((string) ($supplier['branch_serve'] ?? ''));

            if ($branchName !== '' && strcasecmp($branchName, 'all') !== 0) {
                $branch = $this->branchModel->where('name', $branchName)->first();
                if ($branch) {
                    return (int) $branch['id'];
                }
            }
        }

        $destinationBranchId = (int) ($order['branch_id'] ?? 0);
        if ($destinationBranchId > 0) {
            return $destinationBranchId;
        }

        $fallback = $this->branchModel->orderBy('id', 'ASC')->first();
        return (int) ($fallback['id'] ?? 0);
    }

    // Supplier Dashboard
    public function dashboard()
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'supplier') {
            return redirect()->to('/');
        }

        $supplierId = $this->resolveSupplierId();

        if (empty($supplierId)) {
            return redirect()->to('/dashboard')->with('error', 'Supplier ID not found. Please contact administrator.');
        }

            $pendingOrders = $this->purchaseOrderModel
                ->select('purchase_orders.*, branches.name as branch_name')
                ->join('branches', 'branches.id = purchase_orders.branch_id', 'left')
                ->where('purchase_orders.supplier_id', $supplierId)
                ->whereIn('purchase_orders.status', PurchaseOrderModel::SUPPLIER_PENDING_STATUSES)
                ->orderBy('purchase_orders.order_date', 'ASC')
                ->findAll();

            $confirmedOrders = $this->purchaseOrderModel
                ->select('purchase_orders.*, branches.name as branch_name')
                ->join('branches', 'branches.id = purchase_orders.branch_id', 'left')
                ->where('purchase_orders.supplier_id', $supplierId)
                ->where('purchase_orders.status', 'confirmed')
                ->orderBy('purchase_orders.supplier_confirmed_at', 'DESC')
                ->findAll();

            $preparingOrders = $this->purchaseOrderModel
                ->select('purchase_orders.*, branches.name as branch_name')
                ->join('branches', 'branches.id = purchase_orders.branch_id', 'left')
                ->where('purchase_orders.supplier_id', $supplierId)
                ->where('purchase_orders.status', 'preparing')
                ->orderBy('purchase_orders.prepared_at', 'DESC')
                ->findAll();

            return view('suppliers/dashboard', [
                'pendingOrders'   => $pendingOrders,
                'confirmedOrders' => $confirmedOrders,
                'preparingOrders' => $preparingOrders,
            ]);
    }

    // View all orders
    public function orders()
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'supplier') {
            return redirect()->to('/');
        }

        $supplierId = $this->resolveSupplierId();

        if (empty($supplierId)) {
            return redirect()->to('/dashboard')->with('error', 'Supplier context missing. Contact administrator.');
        }
        
        $data['orders'] = $this->purchaseOrderModel
            ->select('purchase_orders.*, branches.name as branch_name')
            ->join('branches', 'branches.id = purchase_orders.branch_id')
            ->where('purchase_orders.supplier_id', $supplierId)
            ->orderBy('purchase_orders.order_date', 'DESC')
            ->findAll();

        return view('suppliers/orders', $data);
    }

    public function deliveries()
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'supplier') {
            return redirect()->to('/');
        }

        $supplierId = $this->resolveSupplierId();

        if (empty($supplierId)) {
            return redirect()->to('/dashboard')->with('error', 'Supplier context missing. Contact administrator.');
        }

        return redirect()->to('/supplier/orders')->with(
            'info',
            'Delivery tracking is now handled by the logistics team. Please coordinate updates with Logistics.'
        );
    }

    public function updateDeliveryStatus(int $deliveryId)
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'supplier') {
            return redirect()->to('/');
        }

        return redirect()->to('/supplier/orders')->with(
            'info',
            'Delivery status updates are now managed by the logistics team.'
        );
    }

    public function invoices()
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'supplier') {
            return redirect()->to('/');
        }

        $supplierId = $this->resolveSupplierId();

        if (empty($supplierId)) {
            return redirect()->to('/dashboard')->with('error', 'Supplier context missing. Contact administrator.');
        }

        $orders = $this->purchaseOrderModel
            ->select('id, item_name, total_price, status')
            ->where('supplier_id', $supplierId)
            ->orderBy('order_date', 'DESC')
            ->findAll();

        $invoices = $this->invoiceModel
            ->where('supplier_id', $supplierId)
            ->orderBy('submitted_at', 'DESC')
            ->findAll();

        return view('suppliers/invoices', [
            'orders'   => $orders,
            'invoices' => $invoices,
            'metrics'  => [
                'submitted' => count($invoices),
            ],
        ]);
    }

    public function submitInvoice()
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'supplier') {
            return redirect()->to('/');
        }

        $supplierId = $this->resolveSupplierId();

        if (empty($supplierId)) {
            return redirect()->back()->with('error', 'Supplier context missing. Contact administrator.');
        }

        $rules = [
            'purchase_order_id' => 'required|integer',
            'amount'            => 'required|decimal',
            'reference_no'      => 'permit_empty|max_length[100]',
            'remarks'           => 'permit_empty|max_length[500]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Please review the invoice details.')
                ->with('errors', $this->validator->getErrors());
        }

        $orderId = (int) $this->request->getPost('purchase_order_id');
        $amount  = (float) $this->request->getPost('amount');

        if ($amount <= 0) {
            return redirect()->back()->withInput()->with('error', 'Invoice amount must be greater than zero.');
        }

        $order = $this->purchaseOrderModel->find($orderId);
        if (! $order || (int) $order['supplier_id'] !== (int) $supplierId) {
            return redirect()->back()->with('error', 'Purchase order not found or not assigned to your account.');
        }

        $this->invoiceModel->insert([
            'supplier_id'       => $supplierId,
            'purchase_order_id' => $orderId,
            'amount'            => $amount,
            'reference_no'      => $this->request->getPost('reference_no') ?: null,
            'remarks'           => $this->request->getPost('remarks') ?: null,
            'status'            => 'submitted',
            'submitted_at'      => date('Y-m-d H:i:s'),
            'submitted_by'      => session()->get('user_id'),
        ]);

        $this->logModel->logAction(session()->get('user_id'), 'supplier_invoice_submitted', "Supplier submitted invoice for PO #{$orderId}");

        return redirect()->to('/supplier/invoices')->with('success', 'Invoice submitted successfully.');
    }

    // Confirm order
    public function confirmOrder($orderId)
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'supplier') {
            return redirect()->to('/');
        }

        $supplierId = $this->resolveSupplierId();

        if (empty($supplierId)) {
            return redirect()->to('/dashboard')->with('error', 'Supplier context missing. Contact administrator.');
        }
        
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

        $supplierId = $this->resolveSupplierId();

        if (empty($supplierId)) {
            return redirect()->to('/dashboard')->with('error', 'Supplier context missing. Contact administrator.');
        }
        
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

        $supplierId = $this->resolveSupplierId();

        if (empty($supplierId)) {
            return redirect()->to('/dashboard')->with('error', 'Supplier context missing. Contact administrator.');
        }
        
        // Verify order belongs to this supplier
        $order = $this->purchaseOrderModel->find($orderId);
        if (!$order || $order['supplier_id'] != $supplierId) {
            return redirect()->back()->with('error', 'Order not found or access denied');
        }

        if ($this->purchaseOrderModel->markAsReadyForDelivery($orderId)) {
            try {
                $this->ensureDeliveryRecordForOrder($order);
            } catch (\Throwable $syncException) {
                log_message('error', 'Delivery sync failed for order #{orderId}: {message}', [
                    'orderId' => $orderId,
                    'message' => $syncException->getMessage(),
                ]);

                return redirect()
                    ->to('/supplier/dashboard')
                    ->with('success', 'Order marked as ready for delivery')
                    ->with('info', 'Logistics sync warning: ' . $syncException->getMessage());
            }

            $this->logModel->logAction(session()->get('user_id'), 'order_ready_delivery', "Order #$orderId marked as ready for delivery");

            return redirect()->to('/supplier/dashboard')->with('success', 'Order marked as ready for delivery');
        }
        
        return redirect()->back()->with('error', 'Failed to update order status');
    }
}

