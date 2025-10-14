<?= $this->extend('Layout') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <h2 class="mb-4">Dashboard</h2>

    <?php if ($role === 'admin'): ?>
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h6 class="text-muted">Total Items</h6>
                        <h3 class="mb-0"><?= esc($metrics['totalItems'] ?? 0) ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h6 class="text-muted">Low Stock</h6>
                        <h3 class="mb-0"><?= esc($metrics['lowStock'] ?? 0) ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h6 class="text-muted">Suppliers</h6>
                        <h3 class="mb-0"><?= esc($metrics['suppliers'] ?? 0) ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">Recent Items</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr><th>Item</th><th>Qty</th><th>Status</th><th>Updated</th></tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($recentItems)): foreach ($recentItems as $item): ?>
                                <tr>
                                    <td><?= esc($item['item_name'] ?? '') ?></td>
                                    <td><?= esc($item['quantity'] ?? '') ?></td>
                                    <td><?= esc($item['status'] ?? '') ?></td>
                                    <td><?= esc($item['updated_at'] ?? '') ?></td>
                                </tr>
                            <?php endforeach; else: ?>
                                <tr><td colspan="4" class="text-center">No data</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    <?php elseif ($role === 'branch_manager'): ?>
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card"><div class="card-body"><h6 class="text-muted">Branch Items</h6><h3 class="mb-0"><?= esc($metrics['branchInventoryCount'] ?? 0) ?></h3></div></div>
            </div>
            <div class="col-md-4">
                <div class="card"><div class="card-body"><h6 class="text-muted">Pending Transfers</h6><h3 class="mb-0"><?= esc($metrics['pendingTransfers'] ?? 0) ?></h3></div></div>
            </div>
            <div class="col-md-4">
                <div class="card"><div class="card-body"><h6 class="text-muted">Purchase Requests</h6><h3 class="mb-0"><?= esc($metrics['purchaseRequests'] ?? 0) ?></h3></div></div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">Recent Branch Inventory</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead><tr><th>Item</th><th>Qty</th><th>Updated</th></tr></thead>
                        <tbody>
                            <?php if (!empty($inventory)): foreach ($inventory as $row): ?>
                                <tr>
                                    <td><?= esc($row['item_name'] ?? '') ?></td>
                                    <td><?= esc($row['quantity'] ?? '') ?></td>
                                    <td><?= esc($row['updated_at'] ?? '') ?></td>
                                </tr>
                            <?php endforeach; else: ?>
                                <tr><td colspan="3" class="text-center">No data</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    <?php elseif ($role === 'inventory'): ?>
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="card"><div class="card-body"><h6 class="text-muted">Stock Count</h6><h3 class="mb-0"><?= esc($metrics['stockCount'] ?? 0) ?></h3></div></div>
            </div>
            <div class="col-md-6">
                <div class="card"><div class="card-body"><h6 class="text-muted">Low Stock</h6><h3 class="mb-0"><?= esc($metrics['lowStock'] ?? 0) ?></h3></div></div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">Recent Items</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr><th>Item</th><th>Qty</th><th>Status</th><th>Updated</th></tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($recentItems)): foreach ($recentItems as $item): ?>
                                <tr>
                                    <td><?= esc($item['item_name'] ?? '') ?></td>
                                    <td><?= esc($item['quantity'] ?? '') ?></td>
                                    <td><?= esc($item['status'] ?? '') ?></td>
                                    <td><?= esc($item['updated_at'] ?? '') ?></td>
                                </tr>
                            <?php endforeach; else: ?>
                                <tr><td colspan="4" class="text-center">No data</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>


