<?= $this->extend('layout') ?>

<?= $this->section('title') ?>Orders<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="container mt-4">
    <h3>Order Management</h3>

    <div class="page-card">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Orders</h5>
            <a href="<?= base_url('Central_AD/createOrder') ?>" class="btn btn-success">+ Create Order</a>
        </div>

        <!-- Search and Filter -->
        <div class="row g-2 mb-3">
            <div class="col-md-8">
                <input type="text" id="searchInput" class="form-control" placeholder="Search orders...">
            </div>
            <div class="col-md-4">
                <select id="statusFilter" class="form-select">
                    <option value="">All Statuses</option>
                    <option value="pending_supplier">Pending Supplier</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="preparing">Preparing</option>
                    <option value="ready_for_delivery">Ready for Delivery</option>
                    <option value="delivered">Delivered</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
        </div>

        <!-- Orders Table -->
        <table class="table table-bordered table-striped" id="orderTable">
            <thead class="table-dark">
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Item</th>
                    <th>Quantity</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th style="width: 150px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($orders)): ?>
                    <?php foreach ($orders as $order): ?>
                        <tr data-status="<?= $order['status'] ?>">
                            <td>#ORD-<?= str_pad($order['id'], 3, '0', STR_PAD_LEFT) ?></td>
                            <td><?= $order['branch_name'] ?></td>
                            <td><?= $order['item_name'] ?></td>
                            <td><?= $order['quantity'] ?> units</td>
                            <td>
                                <span class="badge 
                                    <?php 
                                    $status = $order['status'];
                                    if ($status === 'pending_supplier') echo 'bg-warning';
                                    elseif ($status === 'confirmed') echo 'bg-info';
                                    elseif ($status === 'preparing') echo 'bg-primary';
                                    elseif ($status === 'ready_for_delivery') echo 'bg-success';
                                    elseif ($status === 'delivered') echo 'bg-secondary';
                                    else echo 'bg-danger';
                                    ?>">
                                    <?= ucfirst(str_replace('_', ' ', $status)) ?>
                                </span>
                            </td>
                            <td><?= date('Y-m-d', strtotime($order['order_date'])) ?></td>
                            <td>
                                <a href="<?= base_url('Central_AD/supplier-orders') ?>" class="btn btn-sm btn-primary">View Details</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted">No orders found. <a href="<?= base_url('Central_AD/createOrder') ?>">Create your first order</a></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Notes Section -->
    <div class="page-card">
        <h5>Notes</h5>
        <p>Monitor pending orders closely to ensure timely fulfillment and avoid inventory shortages.</p>
    </div>
</div>

<script>
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const rows = document.querySelectorAll('#orderTable tbody tr');

    function filterOrders() {
        let searchValue = searchInput.value.toLowerCase();
        let statusValue = statusFilter.value;

        rows.forEach(row => {
            let matchesSearch = row.textContent.toLowerCase().includes(searchValue);
            let matchesStatus = statusValue === "" || row.getAttribute('data-status') === statusValue;
            row.style.display = (matchesSearch && matchesStatus) ? '' : 'none';
        });
    }

    searchInput.addEventListener('keyup', filterOrders);
    statusFilter.addEventListener('change', filterOrders);
</script>

<?= $this->endSection() ?>
