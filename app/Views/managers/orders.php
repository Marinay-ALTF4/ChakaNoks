<?= $this->extend('layout') ?>

<?= $this->section('title') ?>Orders<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="container mt-4">
    <h3>Order Management</h3>

    <div class="page-card">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Orders</h5>
            <button class="btn btn-success">+ Create Order</button>
        </div>

        <!-- Search and Filter -->
        <div class="row g-2 mb-3">
            <div class="col-md-8">
                <input type="text" id="searchInput" class="form-control" placeholder="Search orders...">
            </div>
            <div class="col-md-4">
                <select id="statusFilter" class="form-select">
                    <option value="">All Statuses</option>
                    <option value="pending">Pending</option>
                    <option value="completed">Completed</option>
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
                <tr data-status="pending">
                    <td>#ORD-001</td>
                    <td>Main Branch</td>
                    <td>Chicken Thigh</td>
                    <td>50 kg</td>
                    <td><span class="status-badge pending">Pending</span></td>
                    <td>2025-09-15</td>
                    <td>
                        <button class="btn btn-sm btn-primary">View</button>
                        <button class="btn btn-sm btn-warning">Edit</button>
                    </td>
                </tr>
                <tr data-status="completed">
                    <td>#ORD-002</td>
                    <td>Branch A</td>
                    <td>Chicken Wing</td>
                    <td>20 kg</td>
                    <td><span class="status-badge completed">Completed</span></td>
                    <td>2025-09-14</td>
                    <td>
                        <button class="btn btn-sm btn-primary">View</button>
                        <button class="btn btn-sm btn-warning">Edit</button>
                    </td>
                </tr>
                <tr data-status="cancelled">
                    <td>#ORD-003</td>
                    <td>Branch B</td>
                    <td>Chicken Breast</td>
                    <td>30 kg</td>
                    <td><span class="status-badge cancelled">Cancelled</span></td>
                    <td>2025-09-13</td>
                    <td>
                        <button class="btn btn-sm btn-primary">View</button>
                        <button class="btn btn-sm btn-warning">Edit</button>
                    </td>
                </tr>
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
