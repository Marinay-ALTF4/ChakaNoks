<?php use App\Models\DeliveryModel; ?>

<?= $this->extend('Layout') ?>

<?= $this->section('content') ?>

<div class="container-fluid py-3">
    <h2 class="mb-4">Schedule Delivery</h2>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="<?= base_url('logistics/schedule-delivery') ?>" method="post" id="deliveryForm">
                <?= csrf_field() ?>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Source Branch ID</label>
                        <input type="number" name="source_branch_id" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Destination Branch ID</label>
                        <input type="number" name="destination_branch_id" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Scheduled Date</label>
                        <input type="datetime-local" name="scheduled_at" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Vehicle</label>
                        <select name="assigned_vehicle_id" class="form-select">
                            <option value="">Select Vehicle</option>
                            <?php foreach ($vehicles as $vehicle): ?>
                                <option value="<?= esc($vehicle['id']) ?>"><?= esc($vehicle['plate_no']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Driver</label>
                        <select name="assigned_driver_id" class="form-select">
                            <option value="">Select Driver</option>
                            <?php foreach ($drivers as $driver): ?>
                                <option value="<?= esc($driver['id']) ?>"><?= esc($driver['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Total Cost (optional)</label>
                        <input type="number" step="0.01" name="total_cost" class="form-control">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Special handling instructions"></textarea>
                    </div>
                </div>

                <hr class="my-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Delivery Items</h5>
                    <button type="button" class="btn btn-sm btn-outline-primary" id="addItemRow">Add Item</button>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered align-middle" id="itemsTable">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 20%">Product ID</th>
                                <th style="width: 20%">Quantity</th>
                                <th style="width: 20%">Unit</th>
                                <th style="width: 20%">Unit Cost</th>
                                <th style="width: 20%">Expiry Date</th>
                                <th style="width: 5%"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><input type="number" name="items[0][product_id]" class="form-control" required></td>
                                <td><input type="number" step="0.01" name="items[0][quantity]" class="form-control" required></td>
                                <td><input type="text" name="items[0][unit]" class="form-control" placeholder="pcs"></td>
                                <td><input type="number" step="0.01" name="items[0][unit_cost]" class="form-control"></td>
                                <td><input type="date" name="items[0][expiry_date]" class="form-control"></td>
                                <td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger remove-row">&times;</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-success">Create Delivery</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $recentDeliveries = $recentDeliveries ?? []; ?>
<?php $statusOptions = $statusOptions ?? []; ?>

<?php if (! empty($recentDeliveries)): ?>
    <div class="card shadow-sm border-0 mt-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Update Existing Deliveries</h5>
            <span class="small text-muted">Latest 20 deliveries</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Code</th>
                            <th>Route</th>
                            <th>Status</th>
                            <th class="text-end">Update</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentDeliveries as $delivery): ?>
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

                            $selectedStatus = $delivery['next_status'] ?? $status;
                            $options = $statusOptions;
                            if (! in_array($status, $options, true)) {
                                array_unshift($options, $status);
                                $options = array_values(array_unique($options));
                            }
                            ?>
                            <tr>
                                <td class="fw-semibold">
                                    <?= esc($delivery['delivery_code']) ?><br>
                                    <span class="small text-muted">Updated <?= ! empty($delivery['updated_at']) ? esc(date('M d, Y H:i', strtotime($delivery['updated_at']))) : 'N/A' ?></span>
                                </td>
                                <td>
                                    <div class="small text-muted">Route</div>
                                    <div><?= esc($delivery['source_branch_name'] ?? '—') ?> → <?= esc($delivery['destination_branch_name'] ?? '—') ?></div>
                                </td>
                                <td>
                                    <span class="<?= $statusClass ?> text-uppercase"><?= esc(str_replace('_', ' ', $status)) ?></span>
                                </td>
                                <td class="text-end">
                                    <form action="<?= base_url('logistics/update-delivery-status/' . $delivery['id']) ?>" method="post" class="d-inline-flex gap-2 align-items-center">
                                        <?= csrf_field() ?>
                                        <select name="status" class="form-select form-select-sm" required>
                                            <?php foreach ($options as $option): ?>
                                                <option value="<?= esc($option) ?>" <?= $option === $selectedStatus ? 'selected' : '' ?>>
                                                    <?= esc(ucwords(str_replace('_', ' ', $option))) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <button type="submit" class="btn btn-sm btn-primary">Update</button>
                                    </form>
                                    <a href="<?= base_url('logistics/track-delivery/' . $delivery['delivery_code']) ?>" class="btn btn-link btn-sm px-0">Timeline</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="alert alert-info mt-4">No deliveries available to update yet.</div>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const tableBody = document.querySelector('#itemsTable tbody');
    const addRowButton = document.getElementById('addItemRow');

    addRowButton.addEventListener('click', () => {
        const index = tableBody.querySelectorAll('tr').length;
        const row = document.createElement('tr');
        row.innerHTML = `
            <td><input type="number" name="items[${index}][product_id]" class="form-control" required></td>
            <td><input type="number" step="0.01" name="items[${index}][quantity]" class="form-control" required></td>
            <td><input type="text" name="items[${index}][unit]" class="form-control" placeholder="pcs"></td>
            <td><input type="number" step="0.01" name="items[${index}][unit_cost]" class="form-control"></td>
            <td><input type="date" name="items[${index}][expiry_date]" class="form-control"></td>
            <td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger remove-row">&times;</button></td>
        `;
        tableBody.appendChild(row);
    });

    tableBody.addEventListener('click', (event) => {
        if (event.target.classList.contains('remove-row')) {
            const rows = tableBody.querySelectorAll('tr');
            if (rows.length > 1) {
                event.target.closest('tr').remove();
            }
        }
    });
});
</script>

<?= $this->endSection() ?>

