<?php use App\Models\PurchaseOrderModel; ?>

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
            <?php elseif ($role === 'logistics_coordinator'): ?>
                <a href="<?= base_url('logistics/schedule-delivery') ?>" class="btn btn-primary" style="border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">Schedule Delivery</a>
            <?php elseif ($role === 'system_administrator'): ?>
                <a href="<?= base_url('system/users') ?>" class="btn btn-primary" style="border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">Manage Users</a>
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
                            <?php if (($role ?? '') === 'supplier'): ?>
                                <a href="<?= base_url('supplier/dashboard') ?>" class="btn btn-sm btn-outline-primary">Manage Supplier Orders</a>
                            <?php else: ?>
                                <span class="text-muted small">Supplier order workflow is now managed directly by supplier accounts.</span>
                            <?php endif; ?>
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
                                <?php
                                // Format status for display
                                $status = $item['status'] ?? 'available';
                                $statusText = ucfirst(str_replace('_', ' ', $status));
                                $statusClass = 'bg-secondary';
                                switch($status) {
                                    case 'available':
                                        $statusClass = 'bg-success';
                                        break;
                                    case 'low_stock':
                                        $statusClass = 'bg-warning text-dark';
                                        break;
                                    case 'out_of_stock':
                                        $statusClass = 'bg-danger';
                                        break;
                                    case 'damaged':
                                        $statusClass = 'bg-dark';
                                        break;
                                    case 'unavailable':
                                        $statusClass = 'bg-secondary';
                                        break;
                                }
                                ?>
                                <tr class="border-bottom">
                                    <td class="border-0"><?= esc($item['item_name'] ?? '') ?></td>
                                    <td class="border-0"><?= esc($item['quantity'] ?? '') ?></td>
                                    <td class="border-0">
                                        <span class="badge <?= $statusClass ?>"><?= esc($statusText) ?></span>
                                    </td>
                                    <td class="border-0"><?= $item['updated_at'] ? date('M d, Y H:i', strtotime($item['updated_at'])) : 'N/A' ?></td>
                                </tr>
                            <?php endforeach; else: ?>
                                <tr><td colspan="4" class="text-center border-0">No data</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    <?php elseif ($role === 'logistics_coordinator'): ?>
        <p class="text-muted mb-4">Schedule deliveries, monitor routes, and keep branches informed.</p>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
        <?php endif; ?>

        <?php
            $statusLabels = [
                'pending'      => ['Pending', 'text-warning'],
                'dispatched'   => ['Dispatched', 'text-info'],
                'inTransit'    => ['In Transit', 'text-primary'],
                'delivered'    => ['Delivered', 'text-success'],
                'acknowledged' => ['Acknowledged', 'text-secondary'],
            ];
        ?>

        <div class="row g-3 mb-4">
            <?php foreach ($statusLabels as $key => [$label, $class]): ?>
                <div class="col-sm-6 col-xl-2">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body text-center">
                            <p class="text-muted mb-1 small"><?= esc($label) ?></p>
                            <h3 class="fw-bold <?= esc($class) ?>">
                                <?= esc($stats[$key] ?? 0) ?>
                            </h3>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Active Deliveries</h5>
                        <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#routeOptimizerModal">Optimize Routes</button>
                    </div>
                    <div class="card-body p-0">
                        <?php if (!empty($upcomingDeliveries)): ?>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Delivery Code</th>
                                            <th>Branches</th>
                                            <th>Schedule</th>
                                            <th>Status</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($upcomingDeliveries as $delivery): ?>
                                        <tr>
                                            <td class="fw-semibold"><?= esc($delivery['delivery_code'] ?? 'N/A') ?></td>
                                            <td>
                                                <div class="small text-muted">From</div>
                                                <div><?= esc($delivery['source_branch_id'] ?? '—') ?> → <?= esc($delivery['destination_branch_id'] ?? '—') ?></div>
                                            </td>
                                            <td>
                                                <div class="small text-muted">Scheduled</div>
                                                <div><?= !empty($delivery['scheduled_at']) ? esc(date('M d, Y H:i', strtotime($delivery['scheduled_at']))) : '—' ?></div>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary text-uppercase"><?= esc(str_replace('_', ' ', $delivery['status'] ?? 'pending')) ?></span>
                                            </td>
                                            <td class="text-end">
                                                <?php if (!empty($delivery['delivery_code'])): ?>
                                                    <a href="<?= base_url('logistics/track-delivery/' . $delivery['delivery_code']) ?>" class="btn btn-sm btn-outline-secondary">View</a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="p-4 text-center text-muted">No active deliveries queued.</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Quick Schedule</h5>
                    </div>
                    <div class="card-body">
                        <form action="<?= base_url('logistics/schedule-delivery') ?>" method="post" class="needs-validation" novalidate>
                            <?= csrf_field() ?>
                            <div class="mb-3">
                                <label class="form-label">Source Branch ID</label>
                                <input type="number" name="source_branch_id" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Destination Branch ID</label>
                                <input type="number" name="destination_branch_id" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Scheduled Date</label>
                                <input type="datetime-local" name="scheduled_at" class="form-control" required>
                            </div>
                            <div class="row g-2">
                                <div class="col">
                                    <label class="form-label">Vehicle</label>
                                    <select name="assigned_vehicle_id" class="form-select">
                                        <option value="">Select</option>
                                        <?php foreach ($vehicles ?? [] as $vehicle): ?>
                                            <option value="<?= esc($vehicle['id']) ?>"><?= esc($vehicle['plate_no']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col">
                                    <label class="form-label">Driver</label>
                                    <select name="assigned_driver_id" class="form-select">
                                        <option value="">Select</option>
                                        <?php foreach ($drivers ?? [] as $driver): ?>
                                            <option value="<?= esc($driver['id']) ?>"><?= esc($driver['name']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="mt-3">
                                <button class="btn btn-success w-100" type="submit">Create Delivery</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="text-muted text-uppercase small">Tips</h6>
                        <p class="mb-2 small">Use the optimization modal to generate the best stop order before dispatching. Make sure vehicles are set to “Available”.</p>
                        <p class="mb-0 small">Statuses move automatically when updated from the mobile or branch portals.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Route Optimization Modal -->
        <div class="modal fade" id="routeOptimizerModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Route Optimization</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="routeOptimizerForm" class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Stops (lat,lng per line)</label>
                                <textarea class="form-control" name="stops" rows="5" placeholder="14.5995,120.9842
14.6760,121.0437" required></textarea>
                            </div>
                            <div class="col-12 text-end">
                                <button type="submit" class="btn btn-primary">Optimize</button>
                            </div>
                        </form>
                        <div class="mt-3" id="optimizedRouteResult" style="display:none;">
                            <h6>Suggested Order</h6>
                            <ol class="small" id="optimizedRouteList"></ol>
                        </div>
                    </div>
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
                                <?php
                                // Format status for display
                                $status = $item['status'] ?? 'available';
                                $statusText = ucfirst(str_replace('_', ' ', $status));
                                $statusClass = 'bg-secondary';
                                switch($status) {
                                    case 'available':
                                        $statusClass = 'bg-success';
                                        break;
                                    case 'low_stock':
                                        $statusClass = 'bg-warning text-dark';
                                        break;
                                    case 'out_of_stock':
                                        $statusClass = 'bg-danger';
                                        break;
                                    case 'damaged':
                                        $statusClass = 'bg-dark';
                                        break;
                                    case 'unavailable':
                                        $statusClass = 'bg-secondary';
                                        break;
                                }
                                ?>
                                <tr>
                                    <td><?= esc($item['item_name'] ?? '') ?></td>
                                    <td><?= esc($item['quantity'] ?? '') ?></td>
                                    <td>
                                        <span class="badge <?= $statusClass ?>"><?= esc($statusText) ?></span>
                                    </td>
                                    <td><?= $item['updated_at'] ? date('M d, Y H:i', strtotime($item['updated_at'])) : 'N/A' ?></td>
                                </tr>
                            <?php endforeach; else: ?>
                                <tr><td colspan="4" class="text-center">No data</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    <?php elseif ($role === 'supplier'): ?>
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <h6 class="text-muted">Pending Orders</h6>
                        <h3 class="mb-0 text-warning"><?= esc($metrics['pendingOrders'] ?? 0) ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <h6 class="text-muted">Confirmed / Preparing</h6>
                        <h3 class="mb-0 text-info"><?= esc($metrics['confirmedOrders'] ?? 0) ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <h6 class="text-muted">Ready for Delivery</h6>
                        <h3 class="mb-0 text-success"><?= esc($metrics['readyForDelivery'] ?? 0) ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <h6 class="text-muted">Active Deliveries</h6>
                        <h3 class="mb-0 text-primary"><?= esc($metrics['activeDeliveries'] ?? 0) ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-lg-6">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-light border-0 fw-semibold">Recent Purchase Orders</div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0 fw-semibold">PO #</th>
                                        <th class="border-0 fw-semibold">Item</th>
                                        <th class="border-0 fw-semibold">Branch</th>
                                        <th class="border-0 fw-semibold">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($recentOrders)): foreach ($recentOrders as $order): ?>
                                        <?php
                                        $status = $order['status'] ?? 'pending_supplier';
                                        $statusClass = 'bg-secondary';

                                        if (in_array($status, PurchaseOrderModel::SUPPLIER_PENDING_STATUSES, true)) {
                                            $statusClass = 'bg-warning text-dark';
                                        } else {
                                            switch ($status) {
                                                case 'confirmed':
                                                    $statusClass = 'bg-info';
                                                    break;
                                                case 'preparing':
                                                    $statusClass = 'bg-primary';
                                                    break;
                                                case 'ready_for_delivery':
                                                    $statusClass = 'bg-success';
                                                    break;
                                                case 'delivered':
                                                    $statusClass = 'bg-secondary';
                                                    break;
                                                default:
                                                    $statusClass = 'bg-dark';
                                            }
                                        }
                                        ?>
                                        <tr class="border-bottom">
                                            <td class="border-0">#<?= esc($order['id']) ?></td>
                                            <td class="border-0"><?= esc($order['item_name']) ?></td>
                                            <td class="border-0"><?= esc($order['branch_name'] ?? 'N/A') ?></td>
                                            <td class="border-0">
                                                <span class="badge <?= $statusClass ?>"><?= esc(ucwords(str_replace('_', ' ', $status))) ?></span>
                                            </td>
                                        </tr>
                                    <?php endforeach; else: ?>
                                        <tr><td colspan="4" class="text-center border-0 text-muted">No recent purchase orders</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-light border-0 fw-semibold">Logistics Deliveries</div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0 fw-semibold">Delivery</th>
                                        <th class="border-0 fw-semibold">Item</th>
                                        <th class="border-0 fw-semibold">Branch</th>
                                        <th class="border-0 fw-semibold">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($activeDeliveries)): foreach ($activeDeliveries as $delivery): ?>
                                        <?php
                                        $deliveryStatus = $delivery['status'] ?? 'pending';
                                        $deliveryClass = 'bg-secondary';
                                        switch ($deliveryStatus) {
                                            case 'pending':
                                                $deliveryClass = 'bg-warning text-dark';
                                                break;
                                            case 'dispatched':
                                                $deliveryClass = 'bg-info';
                                                break;
                                            case 'in_transit':
                                                $deliveryClass = 'bg-primary';
                                                break;
                                            case 'delivered':
                                                $deliveryClass = 'bg-success';
                                                break;
                                            case 'acknowledged':
                                                $deliveryClass = 'bg-secondary';
                                                break;
                                            case 'cancelled':
                                                $deliveryClass = 'bg-danger';
                                                break;
                                        }
                                        ?>
                                        <tr class="border-bottom">
                                            <td class="border-0"><?= esc($delivery['delivery_code'] ?? 'N/A') ?></td>
                                            <td class="border-0"><?= esc($delivery['item_name'] ?? 'N/A') ?></td>
                                            <td class="border-0"><?= esc($delivery['destination_branch'] ?? 'N/A') ?></td>
                                            <td class="border-0">
                                                <span class="badge <?= $deliveryClass ?>"><?= esc(ucwords(str_replace('_', ' ', $deliveryStatus))) ?></span>
                                            </td>
                                        </tr>
                                    <?php endforeach; else: ?>
                                        <tr><td colspan="4" class="text-center border-0 text-muted">No deliveries yet</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-lg-6">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-light border-0 fw-semibold">Recent Invoices</div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0 fw-semibold">Reference #</th>
                                        <th class="border-0 fw-semibold">PO #</th>
                                        <th class="border-0 fw-semibold">Amount</th>
                                        <th class="border-0 fw-semibold">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($recentInvoices)): foreach ($recentInvoices as $invoice): ?>
                                        <?php
                                        $invoiceStatus = $invoice['status'] ?? 'submitted';
                                        $invoiceClass = 'bg-secondary';
                                        switch ($invoiceStatus) {
                                            case 'submitted':
                                                $invoiceClass = 'bg-info';
                                                break;
                                            case 'reviewing':
                                                $invoiceClass = 'bg-warning text-dark';
                                                break;
                                            case 'approved':
                                                $invoiceClass = 'bg-success';
                                                break;
                                            case 'paid':
                                                $invoiceClass = 'bg-primary';
                                                break;
                                            case 'rejected':
                                                $invoiceClass = 'bg-danger';
                                                break;
                                        }
                                        ?>
                                        <tr class="border-bottom">
                                            <td class="border-0"><?= esc($invoice['reference_no'] ?? 'N/A') ?></td>
                                            <td class="border-0">#<?= esc($invoice['purchase_order_id'] ?? '—') ?></td>
                                            <td class="border-0">₱<?= number_format((float) ($invoice['amount'] ?? 0), 2) ?></td>
                                            <td class="border-0"><span class="badge <?= $invoiceClass ?>"><?= esc(ucwords(str_replace('_', ' ', $invoiceStatus))) ?></span></td>
                                        </tr>
                                    <?php endforeach; else: ?>
                                        <tr><td colspan="4" class="text-center border-0 text-muted">No invoices submitted</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-light border-0 fw-semibold">Supplier Quick Actions</div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <a href="<?= base_url('supplier/orders') ?>" class="btn btn-outline-primary w-100">
                                    <i class="bi bi-receipt"></i><br>
                                    <small>View Orders</small>
                                </a>
                            </div>
                            <div class="col-md-4">
                                <a href="<?= base_url('supplier/orders') ?>" class="btn btn-outline-success w-100" title="Delivery status updates are coordinated through Logistics.">
                                    <i class="bi bi-truck"></i><br>
                                    <small>Delivery Status</small>
                                </a>
                            </div>
                            <div class="col-md-4">
                                <a href="<?= base_url('supplier/invoices') ?>" class="btn btn-outline-info w-100">
                                    <i class="bi bi-file-earmark-text"></i><br>
                                    <small>Submit Invoice</small>
                                </a>
                            </div>
                        </div>
                        <p class="text-muted small mt-3 mb-0">
                            Total Orders: <?= esc($metrics['totalOrders'] ?? 0) ?> &bull; Invoices Submitted: <?= esc($metrics['submittedInvoices'] ?? 0) ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>

    <?php elseif ($role === 'system_administrator'): ?>
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card shadow-sm rounded-3 bg-white border-0">
                    <div class="card-body text-center">
                        <i class="bi bi-people fs-1 text-primary mb-2"></i>
                        <h6 class="text-muted fw-semibold">Total Users</h6>
                        <h3 class="mb-0 fw-bold text-dark"><?= esc($metrics['totalUsers'] ?? 0) ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm rounded-3 bg-white border-0">
                    <div class="card-body text-center">
                        <i class="bi bi-person-check fs-1 text-success mb-2"></i>
                        <h6 class="text-muted fw-semibold">Active Users</h6>
                        <h3 class="mb-0 fw-bold text-dark"><?= esc($metrics['activeUsers'] ?? 0) ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm rounded-3 bg-white border-0">
                    <div class="card-body text-center">
                        <i class="bi bi-file-text fs-1 text-info mb-2"></i>
                        <h6 class="text-muted fw-semibold">System Logs</h6>
                        <h3 class="mb-0 fw-bold text-dark"><?= esc($metrics['totalLogs'] ?? 0) ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm rounded-3 bg-white border-0">
                    <div class="card-body text-center">
                        <i class="bi bi-shield-check fs-1 text-warning mb-2"></i>
                        <h6 class="text-muted fw-semibold">System Health</h6>
                        <h3 class="mb-0 fw-bold text-dark"><?= esc(ucfirst($metrics['systemHealth']['security'] ?? 'Unknown')) ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Status Cards -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <h6 class="text-muted mb-2">Database</h6>
                        <h3 class="mb-0 text-success"><?= esc(ucfirst($metrics['systemHealth']['database'] ?? 'Unknown')) ?></h3>
                        <small class="text-muted">Status</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <h6 class="text-muted mb-2">Storage</h6>
                        <h3 class="mb-0 text-info"><?= esc(ucfirst($metrics['systemHealth']['storage'] ?? 'Unknown')) ?></h3>
                        <small class="text-muted">Status</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <h6 class="text-muted mb-2">Security</h6>
                        <h3 class="mb-0 text-warning"><?= esc(ucfirst($metrics['systemHealth']['security'] ?? 'Unknown')) ?></h3>
                        <small class="text-muted">Status</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="card shadow-sm rounded-3 bg-white border-0">
                    <div class="card-header bg-light border-0 fw-semibold">Recent Users</div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0 fw-semibold">Username</th>
                                        <th class="border-0 fw-semibold">Email</th>
                                        <th class="border-0 fw-semibold">Role</th>
                                        <th class="border-0 fw-semibold">Updated</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($recentUsers)): foreach ($recentUsers as $user): ?>
                                        <tr class="border-bottom">
                                            <td class="border-0"><?= esc($user['username'] ?? '') ?></td>
                                            <td class="border-0"><?= esc($user['email'] ?? '') ?></td>
                                            <td class="border-0">
                                                <span class="badge bg-secondary"><?= esc(ucfirst(str_replace('_', ' ', $user['role'] ?? ''))) ?></span>
                                            </td>
                                            <td class="border-0"><?= $user['updated_at'] ? date('M d, Y H:i', strtotime($user['updated_at'])) : 'N/A' ?></td>
                                        </tr>
                                    <?php endforeach; else: ?>
                                        <tr><td colspan="4" class="text-center border-0">No data</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm rounded-3 bg-white border-0">
                    <div class="card-header bg-light border-0 fw-semibold">System Activity Log</div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0 fw-semibold">Action</th>
                                        <th class="border-0 fw-semibold">Details</th>
                                        <th class="border-0 fw-semibold">Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($systemLogs)): foreach ($systemLogs as $log): ?>
                                        <tr class="border-bottom">
                                            <td class="border-0"><?= esc($log['action'] ?? '') ?></td>
                                            <td class="border-0"><small><?= esc(substr($log['details'] ?? '', 0, 50)) ?><?= strlen($log['details'] ?? '') > 50 ? '...' : '' ?></small></td>
                                            <td class="border-0"><small><?= $log['timestamp'] ? date('M d, H:i', strtotime($log['timestamp'])) : 'N/A' ?></small></td>
                                        </tr>
                                    <?php endforeach; else: ?>
                                        <tr><td colspan="3" class="text-center border-0">No logs available</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm rounded-3 bg-white border-0">
            <div class="card-header bg-light border-0 fw-semibold">Quick Actions</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <a href="<?= base_url('system/users') ?>" class="btn btn-outline-primary w-100">
                            <i class="bi bi-people"></i><br>
                            <small>Manage Users</small>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="<?= base_url('system/logs') ?>" class="btn btn-outline-info w-100">
                            <i class="bi bi-file-text"></i><br>
                            <small>View Logs</small>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="<?= base_url('system/backup') ?>" class="btn btn-outline-success w-100">
                            <i class="bi bi-download"></i><br>
                            <small>Backup System</small>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="<?= base_url('system/security') ?>" class="btn btn-outline-warning w-100">
                            <i class="bi bi-shield-lock"></i><br>
                            <small>Security Settings</small>
                        </a>
                    </div>
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

<?php if ($role === 'logistics_coordinator'): ?>
const routeForm = document.getElementById('routeOptimizerForm');
if (routeForm) {
    routeForm.addEventListener('submit', function (event) {
        event.preventDefault();

        const rawStops = routeForm.elements['stops'].value.trim();
        if (!rawStops) {
            alert('Please provide at least two stops.');
            return;
        }

        const parsedStops = rawStops.split('\n')
            .map(line => line.split(',').map(value => parseFloat(value.trim())))
            .filter(coords => coords.length === 2 && coords.every(coord => !Number.isNaN(coord)));

        if (parsedStops.length < 2) {
            alert('Enter at least two valid latitude,longitude pairs.');
            return;
        }

        const orderedStops = [parsedStops[0]];
        const remainingStops = parsedStops.slice(1);

        // Simple nearest-neighbor heuristic to give coordinators a quick suggested order.
        while (remainingStops.length > 0) {
            const lastStop = orderedStops[orderedStops.length - 1];
            let nearestIndex = 0;
            let nearestDistance = Number.POSITIVE_INFINITY;

            remainingStops.forEach((candidate, index) => {
                const distance = Math.hypot(candidate[0] - lastStop[0], candidate[1] - lastStop[1]);
                if (distance < nearestDistance) {
                    nearestDistance = distance;
                    nearestIndex = index;
                }
            });

            orderedStops.push(remainingStops.splice(nearestIndex, 1)[0]);
        }

        const listElement = document.getElementById('optimizedRouteList');
        const resultContainer = document.getElementById('optimizedRouteResult');

        listElement.innerHTML = '';
        orderedStops.forEach(coords => {
            const item = document.createElement('li');
            item.textContent = `${coords[0].toFixed(4)}, ${coords[1].toFixed(4)}`;
            listElement.appendChild(item);
        });

        resultContainer.style.display = 'block';
    });
}
<?php endif; ?>
</script>


