<?= $this->extend('Layout') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <h2 class="mb-4">Supplier Dashboard</h2>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Pending Confirmation</h6>
                    <h3 class="mb-0 text-warning"><?= esc($stats['pending']) ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Confirmed</h6>
                    <h3 class="mb-0 text-info"><?= esc($stats['confirmed']) ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Preparing</h6>
                    <h3 class="mb-0 text-primary"><?= esc($stats['preparing']) ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Total Orders</h6>
                    <h3 class="mb-0 text-dark"><?= esc($stats['total']) ?></h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Supplier Confirmation -->
    <div class="card mb-4" id="supplier-orders-container">
        <div class="card-header bg-warning text-dark">
            <h5 class="mb-0">Pending Confirmation</h5>
        </div>
        <div class="card-body">
            <?php if (!empty($pendingOrders)): ?>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Item</th>
                                <th>Quantity</th>
                                <th>Unit Price</th>
                                <th>Total Price</th>
                                <th>Branch</th>
                                <th>Order Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pendingOrders as $order): ?>
                                <tr>
                                    <td>#<?= esc($order['id']) ?></td>
                                    <td><?= esc($order['item_name']) ?></td>
                                    <td><?= esc($order['quantity']) ?> <?= esc($order['unit'] ?? 'units') ?></td>
                                    <td>₱<?= number_format($order['unit_price'] ?? 0, 2) ?></td>
                                    <td><strong>₱<?= number_format($order['total_price'] ?? 0, 2) ?></strong></td>
                                    <td><?= esc($order['branch_name']) ?></td>
                                    <td><?= date('M d, Y H:i', strtotime($order['order_date'])) ?></td>
                                    <td>
                                        <a href="<?= base_url('supplier/confirm-order/'.$order['id']) ?>" class="btn btn-sm btn-success">Confirm</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-muted">No pending orders for confirmation.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Confirmed Orders -->
    <div class="card mb-4">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0">Confirmed Orders</h5>
        </div>
        <div class="card-body">
            <?php if (!empty($confirmedOrders)): ?>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Item</th>
                                <th>Quantity</th>
                                <th>Branch</th>
                                <th>Confirmed At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($confirmedOrders as $order): ?>
                                <tr>
                                    <td>#<?= esc($order['id']) ?></td>
                                    <td><?= esc($order['item_name']) ?></td>
                                    <td><?= esc($order['quantity']) ?> <?= esc($order['unit'] ?? 'units') ?></td>
                                    <td><?= esc($order['branch_name']) ?></td>
                                    <td><?= date('M d, Y H:i', strtotime($order['supplier_confirmed_at'])) ?></td>
                                    <td>
                                        <a href="<?= base_url('supplier/mark-preparing/'.$order['id']) ?>" class="btn btn-sm btn-primary">Mark as Preparing</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-muted">No confirmed orders.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Preparing Orders -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Preparing Orders</h5>
        </div>
        <div class="card-body">
            <?php if (!empty($preparingOrders)): ?>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Item</th>
                                <th>Quantity</th>
                                <th>Branch</th>
                                <th>Started Preparing</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($preparingOrders as $order): ?>
                                <tr>
                                    <td>#<?= esc($order['id']) ?></td>
                                    <td><?= esc($order['item_name']) ?></td>
                                    <td><?= esc($order['quantity']) ?> <?= esc($order['unit'] ?? 'units') ?></td>
                                    <td><?= esc($order['branch_name']) ?></td>
                                    <td><?= date('M d, Y H:i', strtotime($order['prepared_at'])) ?></td>
                                    <td>
                                        <a href="<?= base_url('supplier/mark-ready/'.$order['id']) ?>" class="btn btn-sm btn-success">Mark Ready for Delivery</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-muted">No orders currently being prepared.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
// Real-time updates for supplier dashboard
document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('supplier-orders-container')) {
        realTime.startSupplierOrders('supplier-orders-container');
    }
});
</script>

<?= $this->endSection() ?>

