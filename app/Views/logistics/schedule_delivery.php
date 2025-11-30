<?= $this->extend('Layout') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <h2 class="mb-4">Schedule Delivery</h2>

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

    <?php if (!empty($readyOrders)): ?>
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Orders Ready for Delivery</h5>
            </div>
            <div class="card-body">
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
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#scheduleModal<?= $order['id'] ?>">
                                            Schedule Delivery
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
                                                        <label class="form-label">Scheduled Date & Time</label>
                                                        <input type="datetime-local" name="scheduled_date" class="form-control" required>
                                                    </div>
                                                    
                                                    <div class="mb-3">
                                                        <label class="form-label">Route/Address</label>
                                                        <textarea name="route" class="form-control" rows="3" required placeholder="Enter delivery route or address"></textarea>
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
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-info">
            <h5>No orders ready for delivery</h5>
            <p>Orders will appear here once suppliers mark them as ready for delivery.</p>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>

