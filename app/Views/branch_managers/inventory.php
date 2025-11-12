<?= $this->extend('Layout') ?>

<?= $this->section('content') ?>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-boxes text-primary"></i> Branch Inventory</h2>
                    <a href="<?= base_url('branch/dashboard') ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Dashboard
                    </a>
                </div>

                <!-- Inventory Stats -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <h5 class="card-title"><i class="fas fa-box"></i> Total Items</h5>
                                <h3><?= count($inventory ?? []) ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body">
                                <h5 class="card-title"><i class="fas fa-exclamation-triangle"></i> Low Stock</h5>
                                <h3><?= count(array_filter($inventory ?? [], fn($item) => $item['quantity'] <= 5)) ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-danger text-white">
                            <div class="card-body">
                                <h5 class="card-title"><i class="fas fa-calendar-times"></i> Expired</h5>
                                <h3><?= count(array_filter($inventory ?? [], fn($item) => !empty($item['expiry_date']) && strtotime($item['expiry_date']) < time())) ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <h5 class="card-title"><i class="fas fa-clock"></i> Expiring Soon</h5>
                                <h3><?= count(array_filter($inventory ?? [], function($item) {
                                    if (empty($item['expiry_date'])) return false;
                                    $expiry = strtotime($item['expiry_date']);
                                    $weekFromNow = strtotime('+7 days');
                                    return $expiry <= $weekFromNow && $expiry >= time();
                                })) ?></h3>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Inventory Table -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-list"></i> Inventory Items</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($inventory)): ?>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th><i class="fas fa-hashtag"></i> ID</th>
                                            <th><i class="fas fa-tag"></i> Item Name</th>
                                            <th><i class="fas fa-layer-group"></i> Type</th>
                                            <th><i class="fas fa-barcode"></i> Barcode</th>
                                            <th><i class="fas fa-cubes"></i> Quantity</th>
                                            <th><i class="fas fa-info-circle"></i> Status</th>
                                            <th><i class="fas fa-calendar-alt"></i> Expiry Date</th>
                                            <th><i class="fas fa-clock"></i> Last Updated</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($inventory as $item): ?>
                                            <?php
                                            $rowClass = '';
                                            if ($item['quantity'] <= 5) $rowClass = 'low-stock';
                                            elseif (!empty($item['expiry_date']) && strtotime($item['expiry_date']) < time()) $rowClass = 'expired';
                                            elseif (!empty($item['expiry_date']) && strtotime($item['expiry_date']) <= strtotime('+7 days') && strtotime($item['expiry_date']) >= time()) $rowClass = 'expiring-soon';
                                            ?>
                                            <tr class="<?= $rowClass ?>">
                                                <td><?= $item['id'] ?></td>
                                                <td><strong><?= esc($item['item_name']) ?></strong></td>
                                                <td><span class="badge bg-secondary"><?= esc($item['type'] ?? 'N/A') ?></span></td>
                                                <td><code><?= esc($item['barcode'] ?? 'N/A') ?></code></td>
                                                <td>
                                                    <span class="badge <?= $item['quantity'] <= 5 ? 'bg-danger' : 'bg-success' ?>">
                                                        <?= $item['quantity'] ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-<?= $item['status'] === 'available' ? 'success' : ($item['status'] === 'damaged' ? 'danger' : 'warning') ?>">
                                                        <?= ucfirst($item['status']) ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php if (!empty($item['expiry_date'])): ?>
                                                        <span class="<?= strtotime($item['expiry_date']) < time() ? 'text-danger' : (strtotime($item['expiry_date']) <= strtotime('+7 days') ? 'text-warning' : 'text-success') ?>">
                                                            <?= date('M d, Y', strtotime($item['expiry_date'])) ?>
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="text-muted">N/A</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?= date('M d, Y H:i', strtotime($item['updated_at'])) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-5">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No inventory items found</h5>
                                <p class="text-muted">Your branch doesn't have any inventory items yet.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Legend -->
                <div class="mt-3">
                    <h6>Legend:</h6>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-warning p-2 me-2 rounded"></div>
                                <small>Low Stock (≤5 units)</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-danger p-2 me-2 rounded"></div>
                                <small>Expired Items</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-info p-2 me-2 rounded"></div>
                                <small>Expiring Soon (within 7 days)</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    .low-stock { background-color: #fff3cd; }
    .expired { background-color: #f8d7da; }
    .expiring-soon { background-color: #ffeaa7; }
</style>
<?= $this->endSection() ?>
