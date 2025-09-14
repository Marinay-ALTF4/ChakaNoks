<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supplier Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8fbff;
        }
        .page-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            margin-bottom: 20px;
        }
        .status-badge {
            border-radius: 8px;
            padding: 4px 8px;
            font-size: 0.9rem;
            font-weight: bold;
        }
        .active { background-color: #d4edda; color: #155724; }
        .inactive { background-color: #f8d7da; color: #721c24; }
    </style>
</head>
<body>

<div class="container mt-4">
    <h3>Supplier Management</h3>

    <!-- Supplier List -->
    <div class="page-card">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Supplier Directory</h5>
            <button class="btn btn-success">+ Add Supplier</button>
        </div>

        <!-- Search Bar -->
        <input type="text" id="searchInput" class="form-control mb-3" placeholder="Search supplier...">

        <table class="table table-bordered table-striped" id="supplierTable">
            <thead class="table-dark">
                <tr>
                    <th>Supplier Name</th>
                    <th>Contact</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th style="width: 150px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Fresh Farms Inc.</td>
                    <td>+63 912 345 6789</td>
                    <td>freshfarms@example.com</td>
                    <td><span class="status-badge active">Active</span></td>
                    <td>
                        <button class="btn btn-sm btn-primary">View</button>
                        <button class="btn btn-sm btn-warning">Edit</button>
                    </td>
                </tr>
                <tr>
                    <td>Meat World</td>
                    <td>+63 987 654 3210</td>
                    <td>meatworld@example.com</td>
                    <td><span class="status-badge inactive">Inactive</span></td>
                    <td>
                        <button class="btn btn-sm btn-primary">View</button>
                        <button class="btn btn-sm btn-warning">Edit</button>
                    </td>
                </tr>
                <tr>
                    <td>Agri Supply Co.</td>
                    <td>+63 923 456 7890</td>
                    <td>agrisupply@example.com</td>
                    <td><span class="status-badge active">Active</span></td>
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
        <p>Keep supplier contact details updated regularly to avoid supply chain delays.</p>
    </div>
</div>

<script>
    // Simple live search filter
    document.getElementById('searchInput').addEventListener('keyup', function () {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll('#supplierTable tbody tr');

        rows.forEach(row => {
            let text = row.textContent.toLowerCase();
            row.style.display = text.includes(filter) ? '' : 'none';
        });
    });
</script>

</body>
</html>
