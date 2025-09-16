<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->renderSection('title') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        html {
            overflow-y: scroll; /* Prevent layout shift when content is long */
        }
        body {
            background-color: #f8fbff;
            margin: 0;
            display: flex;
        }
        .sidebar {
            width: 220px;
            min-height: 100vh;
            background-color: #dcdcdc;
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 15px;
            position: fixed; /* ✅ Make sidebar fixed */
            top: 0;
            left: 0;
        }
        .content {
            margin-left: 220px; /* ✅ Push content to the right of sidebar */
            flex: 1;
            padding: 20px;
            min-height: 100vh;
        }
        .sidebar a {
            text-decoration: none;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h5>Welcome, superadmin</h5>
        <a href="<?= base_url('Central_AD/dashboard') ?>" class="btn btn-dark w-100">Dashboard</a>
        <a href="<?= base_url('Central_AD/inventory') ?>" class="btn btn-dark w-100">Inventory</a>
        <a href="<?= base_url('Central_AD/suppliers') ?>" class="btn btn-dark w-100">Suppliers</a>
        <a href="<?= base_url('Central_AD/orders') ?>" class="btn btn-dark w-100">Orders</a>
        <a href="<?= base_url('Central_AD/franchising') ?>" class="btn btn-dark w-100">Franchising</a>
        <a href="<?= base_url('Central_AD/reports') ?>" class="btn btn-dark w-100">Reports</a>
        <a href="<?= base_url('Central_AD/settings') ?>" class="btn btn-dark w-100">Settings</a>
        <a href="<?= base_url('logout') ?>" class="btn btn-danger w-100">Logout</a>
    </div>

    <!-- Page Content -->
    <div class="content">
        <?= $this->renderSection('content') ?>
    </div>

</body>
</html>
