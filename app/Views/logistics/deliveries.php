<?= $this->extend('Layout') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <h2 class="mb-4">All Deliveries</h2>

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

    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Delivery History</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Tracking #</th>
                            <th>Order ID</th>
                            <th>Item</th>
                            <th>Supplier</th>
                            <th>Branch</th>
                            <th>Scheduled Date</th>
                            <th>Actual Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($deliveries)): ?>
                            <?php foreach ($deliveries as $delivery): ?>
                                <tr>
                                    <td><strong><?= esc($delivery['tracking_number'] ?? 'N/A') ?></strong></td>
                                    <td>#<?= esc($delivery['order_id']) ?></td>
                                    <td><?= esc($delivery['item_name'] ?? 'N/A') ?></td>
                                    <td><?= esc($delivery['supplier_name'] ?? 'N/A') ?></td>
                                    <td><?= esc($delivery['branch_name'] ?? 'N/A') ?></td>
                                    <td><?= $delivery['scheduled_date'] ? date('M d, Y H:i', strtotime($delivery['scheduled_date'])) : 'N/A' ?></td>
                                    <td><?= $delivery['actual_date'] ? date('M d, Y H:i', strtotime($delivery['actual_date'])) : 'N/A' ?></td>
                                    <td>
                                        <?php
                                        $status = $delivery['status'];
                                        $badgeClass = 'bg-secondary';
                                        if ($status === 'scheduled') $badgeClass = 'bg-primary';
                                        elseif ($status === 'in_transit') $badgeClass = 'bg-info';
                                        elseif ($status === 'delivered') $badgeClass = 'bg-success';
                                        elseif ($status === 'delayed') $badgeClass = 'bg-warning';
                                        elseif ($status === 'cancelled') $badgeClass = 'bg-danger';
                                        ?>
                                        <span class="badge <?= $badgeClass ?>"><?= ucfirst(str_replace('_', ' ', $status)) ?></span>
                                    </td>
                                    <td>
                                        <a href="<?= base_url('logistics/update-delivery-status/'.$delivery['id']) ?>" class="btn btn-sm btn-warning">Update</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" class="text-center text-muted">No deliveries found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

