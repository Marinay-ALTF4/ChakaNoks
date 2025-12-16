<?php use App\Models\DeliveryModel; ?>

<?= $this->extend('Layout') ?>

<?= $this->section('content') ?>

<div class="container-fluid py-3">
    <h2 class="mb-4">Supplier Deliveries</h2>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="border-0">Delivery Code</th>
                            <th class="border-0">Supplier</th>
                            <th class="border-0">Item</th>
                            <th class="border-0">Destination Branch</th>
                            <th class="border-0">Status</th>
                            <th class="border-0">Timeline</th>
                            <th class="border-0">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (! empty($deliveries)): ?>
                            <?php foreach ($deliveries as $delivery): ?>
                                <?php
                                $status = $delivery['status'] ?? DeliveryModel::STATUS_PENDING;
                                $statusClass = 'badge bg-secondary';
                                switch ($status) {
                                    case DeliveryModel::STATUS_PENDING:
                                        $statusClass = 'badge bg-warning text-dark';
                                        break;
                                    case DeliveryModel::STATUS_DISPATCHED:
                                        $statusClass = 'badge bg-info';
                                        break;
                                    case DeliveryModel::STATUS_IN_TRANSIT:
                                        $statusClass = 'badge bg-primary';
                                        break;
                                    case DeliveryModel::STATUS_DELIVERED:
                                        $statusClass = 'badge bg-success';
                                        break;
                                    case DeliveryModel::STATUS_ACKNOWLEDGED:
                                        $statusClass = 'badge bg-secondary';
                                        break;
                                    case DeliveryModel::STATUS_CANCELLED:
                                        $statusClass = 'badge bg-danger';
                                        break;
                                }
                                ?>
                                <tr class="align-top">
                                    <td class="border-0">
                                        <strong><?= esc($delivery['delivery_code'] ?? 'N/A') ?></strong>
                                        <div class="small text-muted">Updated <?= ! empty($delivery['updated_at']) ? esc(date('M d, Y H:i', strtotime($delivery['updated_at']))) : 'N/A' ?></div>
                                    </td>
                                    <td class="border-0">
                                        <?= esc($delivery['supplier_name'] ?? '—') ?>
                                    </td>
                                    <td class="border-0">
                                        <?= esc($delivery['item_name'] ?? '—') ?><br>
                                        <small class="text-muted">Quantity: <?= esc($delivery['quantity'] ?? '—') ?> <?= esc($delivery['unit'] ?? '') ?></small>
                                        <?php if (! empty($delivery['purchase_order_status'])): ?>
                                            <div class="small text-muted">PO Status: <?= esc(ucwords(str_replace('_', ' ', $delivery['purchase_order_status']))) ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="border-0"><?= esc($delivery['destination_branch'] ?? '—') ?></td>
                                    <td class="border-0"><span class="<?= $statusClass ?>"><?= esc(ucwords(str_replace('_', ' ', $status))) ?></span></td>
                                    <td class="border-0">
                                        <?php if (! empty($delivery['timeline'])): ?>
                                            <ul class="list-unstyled mb-0 small">
                                                <?php foreach ($delivery['timeline'] as $step => $timestamp): ?>
                                                    <li>
                                                        <strong><?= esc(ucwords(str_replace('_', ' ', $step))) ?>:</strong>
                                                        <?php if ($timestamp): ?>
                                                            <?= esc(date('M d, Y H:i', strtotime($timestamp))) ?>
                                                        <?php else: ?>
                                                            <span class="text-muted">—</span>
                                                        <?php endif; ?>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php else: ?>
                                            <span class="text-muted small">No timeline data</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="border-0" style="min-width: 240px;">
                                        <form action="<?= base_url('logistics/update-delivery-status/' . $delivery['id']) ?>" method="post" class="d-flex flex-wrap gap-2">
                                            <?= csrf_field() ?>
                                            <select name="status" class="form-select form-select-sm" required>
                                                <?php foreach ($statusOptions as $option): ?>
                                                    <option value="<?= esc($option) ?>" <?= $option === $status ? 'selected' : '' ?>>
                                                        <?= esc(ucwords(str_replace('_', ' ', $option))) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <button type="submit" class="btn btn-sm btn-primary">Update</button>
                                        </form>
                                        <?php if (! empty($delivery['delivery_code'])): ?>
                                            <a href="<?= base_url('logistics/track-delivery/' . $delivery['delivery_code']) ?>" class="btn btn-link btn-sm px-0">Track timeline</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted">No supplier deliveries recorded.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
     font-size: .85rem;
    }

    .delivery-card .timeline li .time {
        display: block;
    }

    @media (max-width: 991.98px) {
        .delivery-card .action-panel {
            width: 100%;
        }
    }
    </style>
