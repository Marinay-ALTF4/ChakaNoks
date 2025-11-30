<?= $this->extend('Layout') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <h2 class="mb-4">Update Delivery Status</h2>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">Delivery #<?= esc($delivery['id']) ?></h5>
                </div>
                <div class="card-body">
                    <p><strong>Tracking Number:</strong> <?= esc($delivery['tracking_number'] ?? 'N/A') ?></p>
                    <p><strong>Order ID:</strong> #<?= esc($delivery['order_id']) ?></p>
                    <p><strong>Current Status:</strong> <span class="badge bg-primary"><?= ucfirst($delivery['status']) ?></span></p>
                    <p><strong>Scheduled Date:</strong> <?= $delivery['scheduled_date'] ? date('M d, Y H:i', strtotime($delivery['scheduled_date'])) : 'N/A' ?></p>

                    <form action="<?= base_url('logistics/update-delivery-status/'.$delivery['id']) ?>" method="POST">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label for="status" class="form-label">Update Status *</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="scheduled" <?= $delivery['status'] === 'scheduled' ? 'selected' : '' ?>>Scheduled</option>
                                <option value="in_transit" <?= $delivery['status'] === 'in_transit' ? 'selected' : '' ?>>In Transit</option>
                                <option value="delivered" <?= $delivery['status'] === 'delivered' ? 'selected' : '' ?>>Delivered</option>
                                <option value="delayed" <?= $delivery['status'] === 'delayed' ? 'selected' : '' ?>>Delayed</option>
                                <option value="cancelled" <?= $delivery['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                            </select>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Update Status</button>
                            <a href="<?= base_url('logistics/deliveries') ?>" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

