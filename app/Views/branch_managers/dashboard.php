<?= $this->extend('Layout') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <h2 class="mb-4">Branch Manager Dashboard</h2>

    <!-- Branch Metrics -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">Branch Inventory Count</h6>
                    <h3 class="mb-0"><?= count($branchInventory ?? []) ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">Low Stock Items</h6>
                    <h3 class="mb-0 text-warning"><?= count($lowStockItems ?? []) ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">Pending Transfers</h6>
                    <h3 class="mb-0 text-info"><?= count($pendingTransfers ?? []) ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">Purchase Requests</h6>
                    <h3 class="mb-0 text-success"><?= $pendingPurchaseRequests ?? 0 ?></h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Low Stock Alerts -->
    <?php if (!empty($lowStockItems)): ?>
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-warning text-dark">
            <h5 class="mb-0"><i class="fas fa-exclamation-triangle"></i> Low Stock Alerts</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Item Name</th>
                            <th>Current Quantity</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lowStockItems as $item): ?>
                        <tr>
                            <td><?= esc($item['item_name']) ?></td>
                            <td><span class="badge bg-danger"><?= esc($item['quantity']) ?></span></td>
                            <td><span class="badge bg-warning">Low Stock</span></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Recent Inventory Activity -->
    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0">Recent Inventory Activity</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Item</th>
                            <th>Quantity</th>
                            <th>Status</th>
                            <th>Last Updated</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($branchInventory)): ?>
                            <?php foreach (array_slice($branchInventory, 0, 5) as $item): ?>
                            <tr>
                                <td><strong><?= esc($item['item_name']) ?></strong></td>
                                <td>
                                    <span class="badge <?= $item['quantity'] <= 5 ? 'bg-danger' : 'bg-success' ?>">
                                        <?= esc($item['quantity']) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-<?= $item['status'] === 'available' ? 'success' : 'warning' ?>">
                                        <?= ucfirst($item['status']) ?>
                                    </span>
                                </td>
                                <td><?= date('M d, Y H:i', strtotime($item['updated_at'])) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted">No inventory items found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<style>
.card {
    border-radius: 10px;
    border: none;
}

.card-header {
    border-radius: 10px 10px 0 0 !important;
    border: none;
}

.table th {
    border-top: none;
    font-weight: 600;
}

.shadow-sm {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
}
</style>
