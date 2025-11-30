<?= $this->extend('Layout') ?>

<?= $this->section('content') ?>

<div class="container-fluid position-relative">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0" style="font-weight: 600; color: #333;">Dashboard</h2>
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-success" id="realtime-indicator" style="display: none;">
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                Live Updates Active
            </span>
            <?php if ($role === 'admin'): ?>
                <a href="<?= base_url('Central_AD/branches/add') ?>" class="btn btn-primary" style="border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">Add New Branch</a>
            <?php endif; ?>
        </div>
    </div>

    <?php if ($role === 'admin'): ?>
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card shadow-sm rounded-3 bg-white border-0">
                    <div class="card-body text-center">
                        <i class="bi bi-box-seam fs-1 text-primary mb-2"></i>
                        <h6 class="text-muted fw-semibold">Total Items</h6>
                        <h3 class="mb-0 fw-bold text-dark"><?= esc($metrics['totalItems'] ?? 0) ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm rounded-3 bg-white border-0">
                    <div class="card-body text-center">
                        <i class="bi bi-exclamation-triangle fs-1 text-warning mb-2"></i>
                        <h6 class="text-muted fw-semibold">Low Stock</h6>
                        <h3 class="mb-0 fw-bold text-dark"><?= esc($metrics['lowStock'] ?? 0) ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm rounded-3 bg-white border-0">
                    <div class="card-body text-center">
                        <i class="bi bi-truck fs-1 text-info mb-2"></i>
                        <h6 class="text-muted fw-semibold">Suppliers</h6>
                        <h3 class="mb-0 fw-bold text-dark"><?= esc($metrics['suppliers'] ?? 0) ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm rounded-3 bg-white border-0">
                    <div class="card-body text-center">
                        <i class="bi bi-building fs-1 text-success mb-2"></i>
                        <h6 class="text-muted fw-semibold">Total Branches</h6>
                        <h3 class="mb-0 fw-bold text-dark"><?= esc($metrics['totalBranches'] ?? 0) ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Workflow Status Cards -->
        <div class="row g-3 mb-4" id="workflow-stats-container">
            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <h6 class="text-muted mb-2">Pending PRs</h6>
                        <h3 class="mb-0 text-warning" data-stat="pendingPurchaseRequests"><?= esc($metrics['pendingPurchaseRequests'] ?? 0) ?></h3>
                        <small class="text-muted">Awaiting Approval</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <h6 class="text-muted mb-2">Supplier Pending</h6>
                        <h3 class="mb-0 text-info" data-stat="pendingSupplierOrders"><?= esc($metrics['pendingSupplierOrders'] ?? 0) ?></h3>
                        <small class="text-muted">Awaiting Confirmation</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <h6 class="text-muted mb-2">Ready for Delivery</h6>
                        <h3 class="mb-0 text-success" data-stat="readyForDelivery"><?= esc($metrics['readyForDelivery'] ?? 0) ?></h3>
                        <small class="text-muted">Ready to Schedule</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <h6 class="text-muted mb-2">Scheduled</h6>
                        <h3 class="mb-0 text-primary" data-stat="scheduledDeliveries"><?= esc($metrics['scheduledDeliveries'] ?? 0) ?></h3>
                        <small class="text-muted">In Transit</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="card shadow-sm rounded-3 bg-white border-0">
                    <div class="card-body text-center">
                        <i class="bi bi-clipboard-check fs-1 text-secondary mb-2"></i>
                        <h6 class="text-muted fw-semibold">Pending Purchase Requests</h6>
                        <h3 class="mb-0 fw-bold text-dark"><?= esc($metrics['pendingPurchaseRequests'] ?? 0) ?></h3>
                        <div id="purchase-requests-container">
                            <?php if (!empty($pendingPurchaseRequests)): ?>
                                <div class="mt-3">
                                    <h6 class="text-muted">Recent Requests:</h6>
                                    <ul class="list-unstyled purchase-requests-list">
                                        <?php foreach (array_slice($pendingPurchaseRequests, 0, 3) as $request): ?>
                                            <li class="small">
                                                <strong><?= esc($request['item_name']) ?></strong> (<?= esc($request['quantity']) ?>) - Branch <?= esc($request['branch_name'] ?? 'N/A') ?>
                                                <div class="mt-1">
                                                    <a href="<?= base_url('Central_AD/approvePurchaseRequest/' . $request['id']) ?>" class="btn btn-sm btn-success me-1">Approve</a>
                                                    <a href="<?= base_url('Central_AD/rejectPurchaseRequest/' . $request['id']) ?>" class="btn btn-sm btn-danger">Reject</a>
                                                </div>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php else: ?>
                                <div class="mt-3">
                                    <h6 class="text-muted">Recent Requests:</h6>
                                    <ul class="list-unstyled purchase-requests-list">
                                        <li class="small text-muted">No pending requests</li>
                                    </ul>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm rounded-3 bg-white border-0">
                    <div class="card-body">
                        <h6 class="text-muted fw-semibold mb-3">Order Workflow Status</h6>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Confirmed Orders:</span>
                            <strong class="text-info"><?= esc($metrics['confirmedOrders'] ?? 0) ?></strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Preparing Orders:</span>
                            <strong class="text-primary"><?= esc($metrics['preparingOrders'] ?? 0) ?></strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Ready for Delivery:</span>
                            <strong class="text-success"><?= esc($metrics['readyForDelivery'] ?? 0) ?></strong>
                        </div>
                        <div class="mt-3">
                            <a href="<?= base_url('Central_AD/supplier-orders') ?>" class="btn btn-sm btn-outline-primary">Manage Supplier Orders</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm rounded-3 bg-white border-0">
            <div class="card-header bg-light border-0 fw-semibold">Recent Items</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr><th class="border-0 fw-semibold">Item</th><th class="border-0 fw-semibold">Qty</th><th class="border-0 fw-semibold">Status</th><th class="border-0 fw-semibold">Updated</th></tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($recentItems)): foreach ($recentItems as $item): ?>
                                <tr class="border-bottom">
                                    <td class="border-0"><?= esc($item['item_name'] ?? '') ?></td>
                                    <td class="border-0"><?= esc($item['quantity'] ?? '') ?></td>
                                    <td class="border-0"><?= esc($item['status'] ?? '') ?></td>
                                    <td class="border-0"><?= esc($item['updated_at'] ?? '') ?></td>
                                </tr>
                            <?php endforeach; else: ?>
                                <tr><td colspan="4" class="text-center border-0">No data</td></tr>
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

        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card bg-light shadow-sm rounded">
                    <div class="card-body">
                        <h6 class="text-muted">Sales Trend</h6>
                        <canvas id="salesChart" width="200" height="100"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-light shadow-sm rounded">
                    <div class="card-body">
                        <h6 class="text-muted">Low Stock Alerts</h6>
                        <ul class="list-unstyled">
                            <?php if (!empty($lowStockAlerts)): foreach ($lowStockAlerts as $alert): ?>
                                <li class="text-muted small"><?= esc($alert['item_name']) ?> (<?= esc($alert['quantity']) ?>)</li>
                            <?php endforeach; else: ?>
                                <li class="text-muted small">No low stock items</li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-light shadow-sm rounded">
                    <div class="card-body">
                        <h6 class="text-muted">Activity Log</h6>
                        <ul class="list-unstyled">
                            <?php if (!empty($activityLog)): foreach ($activityLog as $log): ?>
                                <li class="text-muted small"><?= esc($log['type']) ?>: <?= esc($log['description']) ?></li>
                            <?php endforeach; else: ?>
                                <li class="text-muted small">No recent activity</li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
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

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    <?php if ($role === 'branch_manager' && !empty($salesTrend)): ?>
        const ctx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?= json_encode(array_column($salesTrend, 'month')) ?>,
                datasets: [{
                    label: 'Sales',
                    data: <?= json_encode(array_column($salesTrend, 'sales')) ?>,
                    borderColor: '#6c757d',
                    backgroundColor: 'rgba(108, 117, 125, 0.1)',
                    borderWidth: 2,
                    fill: false,
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    <?php endif; ?>

// Real-time updates initialization
document.addEventListener('DOMContentLoaded', function() {
    // Start real-time updates for workflow stats
    if (document.getElementById('workflow-stats-container')) {
        realTime.startWorkflowStats('workflow-stats-container');
    }

    // Start real-time updates for purchase requests
    if (document.getElementById('purchase-requests-container')) {
        realTime.startPurchaseRequests('purchase-requests-container');
    }
});
</script>


