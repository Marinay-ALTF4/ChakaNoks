<?= $this->extend('layout') ?>

<?= $this->section('title') ?>Franchising<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <h3>Franchising Management</h3>

    <!-- Franchise Directory Card -->
    <div class="page-card bg-white p-4 rounded shadow-sm mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Franchise Directory</h5>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addFranchiseModal">
                + Add Franchise
            </button>
        </div>

        <!-- Search + Filter -->
        <div class="row g-2 mb-3">
            <div class="col-md-8">
                <input type="text" id="searchInput" class="form-control" placeholder="Search franchises...">
            </div>
            <div class="col-md-4">
                <select id="statusFilter" class="form-select">
                    <option value="">All Statuses</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
        </div>

        <!-- Franchises Table -->
        <table class="table table-bordered table-striped" id="franchiseTable">
            <thead class="table-dark">
                <tr>
                    <th>Franchise Name</th>
                    <th>Owner</th>
                    <th>Location</th>
                    <th>Contact</th>
                    <th>Status</th>
                    <th style="width: 150px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr data-status="active">
                    <td>Branch A</td>
                    <td>Juan Dela Cruz</td>
                    <td>Quezon City</td>
                    <td>+63 912 345 6789</td>
                    <td><span class="status-badge active">Active</span></td>
                    <td>
                        <button class="btn btn-sm btn-primary">View</button>
                        <button class="btn btn-sm btn-warning">Edit</button>
                    </td>
                </tr>
                <tr data-status="inactive">
                    <td>Branch B</td>
                    <td>Maria Santos</td>
                    <td>Makati City</td>
                    <td>+63 987 654 3210</td>
                    <td><span class="status-badge inactive">Inactive</span></td>
                    <td>
                        <button class="btn btn-sm btn-primary">View</button>
                        <button class="btn btn-sm btn-warning">Edit</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Notes Section -->
    <div class="page-card bg-white p-4 rounded shadow-sm">
        <h5>Notes</h5>
        <p>Regularly check on inactive franchises and provide support to help them resume operations.</p>
    </div>
</div>

<!-- Modal for Adding Franchise -->
<div class="modal fade" id="addFranchiseModal" tabindex="-1" aria-labelledby="addFranchiseLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content rounded-3">
            <div class="modal-header">
                <h5 class="modal-title" id="addFranchiseLabel">Add New Franchise</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addFranchiseForm">
                    <div class="mb-3">
                        <label class="form-label">Franchise Name</label>
                        <input type="text" class="form-control" id="franchiseName" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Owner</label>
                        <input type="text" class="form-control" id="franchiseOwner" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Location</label>
                        <input type="text" class="form-control" id="franchiseLocation" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contact</label>
                        <input type="text" class="form-control" id="franchiseContact" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" id="franchiseStatus">
                            <option value="active" selected>Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Add Franchise</button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .page-card { border-radius: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
    .status-badge { border-radius: 8px; padding: 4px 8px; font-size: 0.9rem; font-weight: bold; }
    .status-badge.active { background-color: #d4edda; color: #155724; }
    .status-badge.inactive { background-color: #f8d7da; color: #721c24; }
</style>

<script>
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const tableBody = document.querySelector('#franchiseTable tbody');

    function filterFranchises() {
        let searchValue = searchInput.value.toLowerCase();
        let statusValue = statusFilter.value;
        const rows = tableBody.querySelectorAll('tr');

        rows.forEach(row => {
            let matchesSearch = row.textContent.toLowerCase().includes(searchValue);
            let matchesStatus = statusValue === "" || row.getAttribute('data-status') === statusValue;
            row.style.display = (matchesSearch && matchesStatus) ? '' : 'none';
        });
    }

    searchInput.addEventListener('keyup', filterFranchises);
    statusFilter.addEventListener('change', filterFranchises);

    document.getElementById('addFranchiseForm').addEventListener('submit', function(e) {
        e.preventDefault();

        let name = document.getElementById('franchiseName').value;
        let owner = document.getElementById('franchiseOwner').value;
        let location = document.getElementById('franchiseLocation').value;
        let contact = document.getElementById('franchiseContact').value;
        let status = document.getElementById('franchiseStatus').value;

        let newRow = document.createElement('tr');
        newRow.setAttribute('data-status', status);
        newRow.innerHTML = `
            <td>${name}</td>
            <td>${owner}</td>
            <td>${location}</td>
            <td>${contact}</td>
            <td><span class="status-badge ${status}">${status.charAt(0).toUpperCase() + status.slice(1)}</span></td>
            <td>
                <button class="btn btn-sm btn-primary">View</button>
                <button class="btn btn-sm btn-warning">Edit</button>
            </td>
        `;

        tableBody.appendChild(newRow);
        this.reset();
        bootstrap.Modal.getInstance(document.getElementById('addFranchiseModal')).hide();
    });
</script>
<?= $this->endSection() ?>
