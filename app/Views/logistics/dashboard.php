<?= $this->extend('Layout') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <h2 class="mb-4">Logistics Dashboard</h2>

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
                    <h6 class="text-muted mb-2">Ready for Delivery</h6>
                    <h3 class="mb-0 text-success"><?= esc($readyOrdersCount ?? 0) ?></h3>
                    <small class="text-muted">Orders to Schedule</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Scheduled</h6>
                    <h3 class="mb-0 text-primary"><?= esc($scheduledCount ?? 0) ?></h3>
                    <small class="text-muted">In Transit</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Delivered Today</h6>
                    <h3 class="mb-0 text-info"><?= esc($completedToday ?? 0) ?></h3>
                    <small class="text-muted">Completed</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Total Deliveries</h6>
                    <h3 class="mb-0 text-dark"><?= esc($totalDeliveries ?? 0) ?></h3>
                    <small class="text-muted">All Time</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Ready Orders for Delivery -->
    <div class="card mb-4" id="ready-for-delivery-container">
        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Orders Ready for Delivery</h5>
            <a href="<?= base_url('logistics/schedule-delivery') ?>" class="btn btn-light btn-sm">Schedule Delivery</a>
        </div>
        <div class="card-body">
            <?php if (!empty($readyOrders)): ?>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Item</th>
                                <th>Quantity</th>
                                <th>Supplier</th>
                                <th>Branch</th>
                                <th>Total Price</th>
                                <th>Prepared At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($readyOrders as $order): ?>
                                <tr>
                                    <td>#<?= esc($order['id']) ?></td>
                                    <td><?= esc($order['item_name']) ?></td>
                                    <td><?= esc($order['quantity']) ?> <?= esc($order['unit'] ?? 'units') ?></td>
                                    <td><?= esc($order['supplier_name']) ?></td>
                                    <td><?= esc($order['branch_name']) ?></td>
                                    <td>₱<?= number_format($order['total_price'] ?? 0, 2) ?></td>
                                    <td><?= $order['prepared_at'] ? date('M d, Y H:i', strtotime($order['prepared_at'])) : 'N/A' ?></td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#scheduleModal<?= $order['id'] ?>">
                                            Schedule
                                        </button>
                                    </td>
                                </tr>

                                <!-- Schedule Modal -->
                                <div class="modal fade" id="scheduleModal<?= $order['id'] ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="<?= base_url('logistics/schedule-delivery') ?>" method="POST">
                                                <?= csrf_field() ?>
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Schedule Delivery for Order #<?= esc($order['id']) ?></h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="hidden" name="order_id" value="<?= esc($order['id']) ?>">
                                                    
                                                    <div class="mb-3">
                                                        <label class="form-label">Scheduled Date & Time *</label>
                                                        <input type="datetime-local" name="scheduled_date" class="form-control" required>
                                                    </div>
                                                    
                                                    <div class="mb-3">
                                                        <label class="form-label">Route/Address *</label>
                                                        <textarea name="route" class="form-control" rows="3" required placeholder="Enter delivery route or address"></textarea>
                                                        <div class="form-text">Example: From <?= esc($order['supplier_name']) ?> to <?= esc($order['branch_name']) ?></div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary">Schedule Delivery</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-info">
                    <h5>No orders ready for delivery</h5>
                    <p>Orders will appear here once suppliers mark them as ready for delivery.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Scheduled Deliveries -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Scheduled Deliveries</h5>
        </div>
        <div class="card-body">
            <?php if (!empty($scheduledDeliveries)): ?>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Tracking #</th>
                                <th>Order ID</th>
                                <th>Item</th>
                                <th>Branch</th>
                                <th>Scheduled Date</th>
                                <th>Route</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($scheduledDeliveries as $delivery): ?>
                                <tr>
                                    <td><strong><?= esc($delivery['tracking_number']) ?></strong></td>
                                    <td>#<?= esc($delivery['order_id']) ?></td>
                                    <td><?= esc($delivery['item_name'] ?? 'N/A') ?></td>
                                    <td><?= esc($delivery['branch_name'] ?? 'N/A') ?></td>
                                    <td><?= $delivery['scheduled_date'] ? date('M d, Y H:i', strtotime($delivery['scheduled_date'])) : 'N/A' ?></td>
                                    <td><?= esc($delivery['route'] ?? 'N/A') ?></td>
                                    <td>
                                        <span class="badge bg-primary"><?= ucfirst($delivery['status']) ?></span>
                                    </td>
                                    <td>
                                        <a href="<?= base_url('logistics/update-delivery-status/'.$delivery['id']) ?>" class="btn btn-sm btn-warning">Update Status</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-muted">No scheduled deliveries.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
// Real-time updates for logistics dashboard
document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('ready-for-delivery-container')) {
        realTime.startReadyForDelivery('ready-for-delivery-container');
    }
});
</script>

<?= $this->endSection() ?>

