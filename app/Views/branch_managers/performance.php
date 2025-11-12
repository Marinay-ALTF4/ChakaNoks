<?= $this->extend('Layout') ?>

<?= $this->section('content') ?>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-chart-line text-primary"></i> Branch Performance</h2>
                    <a href="<?= base_url('branch/dashboard') ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Dashboard
                    </a>
                </div>

                <!-- Performance Metrics -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <h5 class="card-title"><i class="fas fa-box"></i> Total Items</h5>
                                <h3><?= $totalItems ?? 0 ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body">
                                <h5 class="card-title"><i class="fas fa-exclamation-triangle"></i> Low Stock Items</h5>
                                <h3><?= $lowStockCount ?? 0 ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <h5 class="card-title"><i class="fas fa-shopping-cart"></i> Pending Requests</h5>
                                <h3><?= $pendingRequests ?? 0 ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <h5 class="card-title"><i class="fas fa-percentage"></i> Stock Health</h5>
                                <h3>
                                    <?php
                                    $total = $totalItems ?? 1;
                                    $low = $lowStockCount ?? 0;
                                    $health = round((($total - $low) / $total) * 100);
                                    echo $health . '%';
                                    ?>
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Row -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-chart-pie"></i> Inventory Status Distribution</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="statusChart" width="400" height="300"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-chart-bar"></i> Monthly Performance Trend</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="trendChart" width="400" height="300"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activities -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-history"></i> Recent Activities</h5>
                            </div>
                            <div class="card-body">
                                <div class="list-group list-group-flush">
                                    <div class="list-group-item">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Purchase Request Submitted</h6>
                                            <small class="text-muted">2 hours ago</small>
                                        </div>
                                        <p class="mb-1">Requested 50 units of Coffee Beans</p>
                                        <small class="text-success">Status: Pending Approval</small>
                                    </div>
                                    <div class="list-group-item">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Inventory Updated</h6>
                                            <small class="text-muted">1 day ago</small>
                                        </div>
                                        <p class="mb-1">Added 20 units of Milk</p>
                                        <small class="text-info">Via delivery receipt</small>
                                    </div>
                                    <div class="list-group-item">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Low Stock Alert</h6>
                                            <small class="text-muted">3 days ago</small>
                                        </div>
                                        <p class="mb-1">Sugar running low (5 units remaining)</p>
                                        <small class="text-warning">Action required</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-trophy"></i> Performance Insights</h5>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-success" role="alert">
                                    <h6 class="alert-heading"><i class="fas fa-check-circle"></i> Excellent Inventory Management</h6>
                                    <p class="mb-0">Your branch maintains above-average stock levels with minimal waste.</p>
                                </div>
                                <div class="alert alert-info" role="alert">
                                    <h6 class="alert-heading"><i class="fas fa-lightbulb"></i> Recommendation</h6>
                                    <p class="mb-0">Consider increasing reorder points for fast-moving items to prevent stockouts.</p>
                                </div>
                                <div class="alert alert-warning" role="alert">
                                    <h6 class="alert-heading"><i class="fas fa-exclamation-triangle"></i> Attention Needed</h6>
                                    <p class="mb-0">3 items are expiring within the next week. Review and plan usage.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Sample data for charts (replace with real data)
    const statusData = {
        labels: ['Available', 'Low Stock', 'Expired', 'Damaged'],
        datasets: [{
            data: [65, 15, 5, 15],
            backgroundColor: [
                '#28a745',
                '#ffc107',
                '#dc3545',
                '#6c757d'
            ]
        }]
    };

    const trendData = {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
        datasets: [{
            label: 'Stock Levels',
            data: [85, 90, 88, 92, 87, 91],
            borderColor: '#007bff',
            backgroundColor: 'rgba(0, 123, 255, 0.1)',
            tension: 0.4
        }]
    };

    // Status Chart
    new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',
        data: statusData,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });

    // Trend Chart
    new Chart(document.getElementById('trendChart'), {
        type: 'line',
        data: trendData,
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100
                }
            }
        }
    });
</script>
<?= $this->endSection() ?>
