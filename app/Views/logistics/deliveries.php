<?= $this->extend('Layout') ?>

<?= $this->section('content') ?>

<div class="container-fluid py-3">
    <h2 class="mb-4">Deliveries Overview</h2>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white">
            <h5 class="mb-0">Recent Deliveries</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Code</th>
                            <th>Route</th>
                            <th>Schedule</th>
                            <th>Vehicle/Driver</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (! empty($deliveries)): ?>
                            <?php foreach ($deliveries as $delivery): ?>
                                <tr>
                                    <td class="fw-semibold"><?= esc($delivery['delivery_code']) ?></td>
                                    <td>
                                        <div class="small text-muted">Route</div>
                                        <div><?= esc($delivery['source_branch_name'] ?? $delivery['source_branch_id'] ?? '—') ?> → <?= esc($delivery['destination_branch_name'] ?? $delivery['destination_branch_id'] ?? '—') ?></div>
                                        <?php if (! empty($delivery['items'])): ?>
                                            <div class="small text-muted mt-1">
                                                <?= count($delivery['items']) ?> item(s)
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="small text-muted">Scheduled</div>
                                        <div><?= $delivery['scheduled_at'] ? date('M d, Y H:i', strtotime($delivery['scheduled_at'])) : '—' ?></div>
                                        <?php if (! empty($delivery['delivered_at'])): ?>
                                            <div class="small text-muted">Delivered <?= date('M d, Y H:i', strtotime($delivery['delivered_at'])) ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div><?= esc($delivery['assigned_vehicle_id'] ?? 'Unassigned') ?></div>
                                        <div class="small text-muted">Driver: <?= esc($delivery['assigned_driver_id'] ?? '–') ?></div>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary text-uppercase"><?= esc(str_replace('_', ' ', $delivery['status'])) ?></span>
                                    </td>
                                </tr>
                                <?php if (! empty($delivery['items'])): ?>
                                    <tr class="table-light">
                                        <td colspan="5">
                                            <strong>Items</strong>
                                            <ul class="small mb-0 mt-2">
                                                <?php foreach ($delivery['items'] as $item): ?>
                                                    <li>
                                                        Product <?= esc($item['product_id']) ?> ·
                                                        Qty <?= esc($item['quantity']) ?> <?= esc($item['unit'] ?? '') ?>
                                                        <?php if (! empty($item['expiry_date'])): ?>
                                                            · Exp <?= date('M d, Y', strtotime($item['expiry_date'])) ?>
                                                        <?php endif; ?>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">No deliveries recorded yet.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

