<?= $this->extend('Layout') ?>

<?= $this->section('content') ?>
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h3 mb-0"><i class="fas fa-store text-primary"></i> Branch Manager Dashboard</h1>
                        <p class="text-muted mb-0">Welcome back, <?= session()->get('username') ?>!</p>
                    </div>
                    <div>
                        <a href="<?= base_url('logout') ?>" class="btn btn-outline-danger">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white card-hover">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Branch Inventory</h6>
                                <h4 class="mb-0"><?= $branchInventory ?? 0 ?> items</h4>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-boxes fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white card-hover">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Low Stock Alerts</h6>
                                <h4 class="mb-0"><?= count($lowStockItems ?? []) ?> items</h4>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-exclamation-triangle fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white card-hover">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Pending Transfers</h6>
                                <h4 class="mb-0"><?= count($pendingTransfers ?? []) ?> requests</h4>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-truck fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white card-hover">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Purchase Requests</h6>
                                <h4 class="mb-0"><?= $purchaseRequests ?? 0 ?> total</h4>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-shopping-cart fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Actions -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-tasks"></i> Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <a href="<?= base_url('branch/monitor-inventory') ?>" class="btn btn-outline-primary btn-lg w-100">
                                    <i class="fas fa-boxes fa-2x d-block mb-2"></i>
                                    Monitor Inventory
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="<?= base_url('branch/purchase-request') ?>" class="btn btn-outline-success btn-lg w-100">
                                    <i class="fas fa-plus-circle fa-2x d-block mb-2"></i>
                                    Create Purchase Request
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="<?= base_url('branch/approve-transfers') ?>" class="btn btn-outline-warning btn-lg w-100">
                                    <i class="fas fa-check-circle fa-2x d-block mb-2"></i>
                                    Approve Transfers
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="<?= base_url('branch/performance') ?>" class="btn btn-outline-info btn-lg w-100">
                                    <i class="fas fa-chart-line fa-2x d-block mb-2"></i>
                                    View Performance
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alerts and Notifications -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-bell"></i> Alerts & Notifications</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($lowStockItems)): ?>
                            <div class="alert alert-warning alert-custom alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Low Stock Alert:</strong> <?= count($lowStockItems) ?> items are running low on stock.
                                <a href="<?= base_url('branch/monitor-inventory') ?>" class="alert-link">View Details</a>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($pendingTransfers)): ?>
                            <div class="alert alert-info alert-custom alert-dismissible fade show" role="alert">
                                <i class="fas fa-truck me-2"></i>
                                <strong>Pending Transfers:</strong> You have <?= count($pendingTransfers) ?> transfer requests awaiting approval.
                                <a href="<?= base_url('branch/approve-transfers') ?>" class="alert-link">Review Now</a>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <div class="alert alert-success alert-custom alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            <strong>System Status:</strong> All branch systems are operating normally.
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Inventory and Recent Requests -->
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-clock"></i> Recent Inventory Updates</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($inventory)): ?>
                            <div class="list-group list-group-flush">
                                <?php foreach (array_slice($inventory, 0, 5) as $item): ?>
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong><?= esc($item['item_name']) ?></strong>
                                            <br>
                                            <small class="text-muted">Quantity: <?= $item['quantity'] ?> | Status: <?= ucfirst($item['status']) ?></small>
                                        </div>
                                        <small class="text-muted">
                                            <?= date('M d, H:i', strtotime($item['updated_at'])) ?>
                                        </small>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-muted text-center py-3">No recent inventory updates</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-shopping-cart"></i> Recent Purchase Requests</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>Coffee Beans</strong>
                                    <br>
                                    <small class="text-muted">Quantity: 50 units | Status: <span class="badge bg-warning">Pending</span></small>
                                </div>
                                <small class="text-muted">2 hours ago</small>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>Milk</strong>
                                    <br>
                                    <small class="text-muted">Quantity: 30 units | Status: <span class="badge bg-success">Approved</span></small>
                                </div>
                                <small class="text-muted">1 day ago</small>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>Sugar</strong>
                                    <br>
                                    <small class="text-muted">Quantity: 25 units | Status: <span class="badge bg-danger">Rejected</span></small>
                                </div>
                                <small class="text-muted">3 days ago</small>
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
    .card-hover:hover { transform: translateY(-2px); transition: transform 0.2s; }
    .alert-custom { border-left: 4px solid; }
</style>
<?= $this->endSection() ?>
