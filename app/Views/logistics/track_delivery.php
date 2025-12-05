<?= $this->extend('Layout') ?>

<?= $this->section('content') ?>

<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">Delivery Timeline</h2>
            <p class="text-muted mb-0">Status history for <?= esc($delivery['delivery_code']) ?></p>
        </div>
        <a href="<?= base_url('logistics/deliveries') ?>" class="btn btn-outline-secondary btn-sm">Back to Deliveries</a>
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Status Timeline</h5>
                </div>
                <div class="card-body">
                    <?= view('logistics/partials/status_timeline', ['timeline' => $timeline]) ?>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Delivery Details</h5>
                </div>
                <div class="card-body">
                    <dl class="row small mb-0">
                        <dt class="col-sm-5 text-muted">Source Branch</dt>
                        <dd class="col-sm-7"><?= esc($delivery['source_branch_id']) ?></dd>
                        <dt class="col-sm-5 text-muted">Destination Branch</dt>
                        <dd class="col-sm-7"><?= esc($delivery['destination_branch_id']) ?></dd>
                        <dt class="col-sm-5 text-muted">Vehicle</dt>
                        <dd class="col-sm-7"><?= esc($delivery['assigned_vehicle_id'] ?? 'Unassigned') ?></dd>
                        <dt class="col-sm-5 text-muted">Driver</dt>
                        <dd class="col-sm-7"><?= esc($delivery['assigned_driver_id'] ?? 'Unassigned') ?></dd>
                        <dt class="col-sm-5 text-muted">Total Cost</dt>
                        <dd class="col-sm-7">₱ <?= number_format($delivery['total_cost'] ?? 0, 2) ?></dd>
                    </dl>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Items</h5>
                </div>
                <div class="card-body p-0">
                    <?php if (! empty($items)): ?>
                        <div class="table-responsive">
                            <table class="table table-sm mb-0">
                                <thead>
                                    <tr>
                                        <th>Product ID</th>
                                        <th>Qty</th>
                                        <th>Unit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($items as $item): ?>
                                    <tr>
                                        <td><?= esc($item['product_id']) ?></td>
                                        <td><?= esc($item['quantity']) ?></td>
                                        <td><?= esc($item['unit'] ?? '') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="p-3 text-center text-muted">No items recorded.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <?php if ($route): ?>
        <div class="card shadow-sm border-0 mt-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Route Snapshot</h5>
            </div>
            <div class="card-body">
                <pre class="small bg-light p-3 rounded"><?= esc(json_encode($route, JSON_PRETTY_PRINT)) ?></pre>
            </div>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
