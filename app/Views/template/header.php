<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$role = session()->get('role');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->renderSection('title') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        html { overflow-y: scroll; }
        body {
            background-color: #f8fbff;
            margin: 0;
            display: flex;
            font-family: Arial, sans-serif;
        }
        .sidebar {
            width: 220px;
            min-height: 100vh;
            background-color: #dcdcdc;
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 15px;
            position: fixed;
            top: 0;
            left: 0;
            overflow-y: auto;
        }
        .sidebar h5 { margin-bottom: 20px; font-weight: bold; }
        .sidebar a {
            text-decoration: none;
            padding: 10px 15px;
            border: none;
            width: 100%;
            text-align: left;
            border-radius: 5px;
            transition: background-color 0.3s, color 0.3s;
            display: inline-block;
            font-weight: 500;
            color: white;
        }
        .sidebar a.btn-dark { background-color: #4e4e4eff; }
        .sidebar a.btn-dark:hover, .sidebar a.btn-dark.active { background-color: black; color: white; }
        .sidebar a.btn-danger { background-color: #dc3545; }
        .sidebar a.btn-danger:hover { background-color: #b02a37; color: white; }
        .content { margin-left: 220px; flex: 1; padding: 20px; min-height: 100vh; }
    </style>
</head>
<body>

    <div class="sidebar">
        <h5>Welcome, <?= esc(session()->get('username')) ?></h5>

        <?php if ($role === 'admin'): ?>
            <a href="<?= base_url('dashboard') ?>" class="btn btn-dark w-100 <?= (uri_string() == 'dashboard') ? 'active' : '' ?>">Dashboard</a>
            <a href="<?= base_url('Central_AD/inventory') ?>" class="btn btn-dark w-100">Inventory</a>
            <a href="<?= base_url('Central_AD/suppliers') ?>" class="btn btn-dark w-100">Suppliers</a>
            <a href="<?= base_url('Central_AD/orders') ?>" class="btn btn-dark w-100">Orders</a>
            <a href="<?= base_url('Central_AD/franchising') ?>" class="btn btn-dark w-100">Franchising</a>
            <a href="<?= base_url('Central_AD/reports') ?>" class="btn btn-dark w-100">Reports</a>
            <a href="<?= base_url('Central_AD/settings') ?>" class="btn btn-dark w-100">Settings</a>
        <?php elseif ($role === 'branch_manager'): ?>
            <a href="<?= site_url('dashboard') ?>" class="btn btn-dark w-100 <?= (uri_string() == 'dashboard') ? 'active' : '' ?>">Dashboard</a>
            <a href="<?= site_url('branch/monitor-inventory') ?>" class="btn btn-dark w-100">Monitor Inventory</a>
            <a href="<?= site_url('branch/purchase-request') ?>" class="btn btn-dark w-100">Create Purchase Request</a>
            <a href="<?= site_url('branch/approve-transfers') ?>" class="btn btn-dark w-100">Approve Transfers</a>
        <?php elseif ($role === 'inventory'): ?>
            <a href="<?= site_url('dashboard') ?>" class="btn btn-dark w-100 <?= (uri_string() == 'dashboard') ? 'active' : '' ?>">Dashboard</a>
            <!-- <a href="<?= site_url('inventory/add-stock') ?>" class="btn btn-dark w-100">Add Stock</a> -->
            <!-- <a href="<?= site_url('inventory/edit-stock') ?>" class="btn btn-dark w-100">Edit Stock</a> -->
            <a href="<?= site_url('inventory/stock-list') ?>" class="btn btn-dark w-100">Stock List</a>
            <a href="<?= site_url('inventory/alerts') ?>" class="btn btn-dark w-100">Alerts</a>
        <?php endif; ?>

        <a href="<?= base_url('logout') ?>" class="btn btn-danger w-100">Logout</a>
    </div>