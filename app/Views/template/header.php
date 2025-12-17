<?php
// CodeIgniter handles sessions automatically, no need for manual session_start()
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
<a href="<?= base_url('admin/users') ?>" class="btn btn-dark w-100 <?= (strpos(uri_string(), 'admin/users') !== false) ? 'active' : '' ?>">User Management</a>
<a href="<?= base_url('Central_AD/branches') ?>" class="btn btn-dark w-100 <?= (strpos(uri_string(), 'Central_AD/branches') !== false) ? 'active' : '' ?>">Branches</a>
<a href="<?= base_url('Central_AD/suppliers') ?>" class="btn btn-dark w-100 <?= (strpos(uri_string(), 'Central_AD/supplier') !== false) ? 'active' : '' ?>">Suppliers</a>
<a href="<?= base_url('Central_AD/orders') ?>" class="btn btn-dark w-100 <?= (strpos(uri_string(), 'Central_AD/orders') !== false && strpos(uri_string(), 'supplier-orders') === false) ? 'active' : '' ?>">All Orders</a>
<a href="<?= base_url('Central_AD/franchising') ?>" class="btn btn-dark w-100 <?= (strpos(uri_string(), 'Central_AD/franchising') !== false) ? 'active' : '' ?>">Franchising</a>
<a href="<?= base_url('Central_AD/reports') ?>" class="btn btn-dark w-100 <?= (strpos(uri_string(), 'Central_AD/reports') !== false) ? 'active' : '' ?>">Reports</a>
<a href="<?= base_url('Central_AD/settings') ?>" class="btn btn-dark w-100 <?= (strpos(uri_string(), 'Central_AD/settings') !== false) ? 'active' : '' ?>">Settings</a>
<?php elseif ($role === 'branch_manager'): ?>
<a href="<?= base_url('dashboard') ?>" class="btn btn-dark w-100 <?= (uri_string() == 'dashboard') ? 'active' : '' ?>">Dashboard</a>
<!-- <a href="<?= base_url('branch/inventory') ?>" class="btn btn-dark w-100 <?= (strpos(uri_string(), 'branch/inventory') !== false) ? 'active' : '' ?>">Inventory</a> KARING ISA RA ATONG GAMITON -->
<a href="<?= base_url('branch/purchase-request') ?>" class="btn btn-dark w-100 <?= (strpos(uri_string(), 'branch/purchase-request') !== false) ? 'active' : '' ?>">Create Purchase Request</a>
<a href="<?= base_url('Central_AD/orders') ?>" class="btn btn-dark w-100 <?= (strpos(uri_string(), 'Central_AD/orders') !== false) ? 'active' : '' ?>">Orders</a>
<a href="<?= base_url('branch/approve-transfers') ?>" class="btn btn-dark w-100 <?= (strpos(uri_string(), 'branch/approve-transfers') !== false) ? 'active' : '' ?>">Approve Transfers</a>
<a href="<?= base_url('Central_AD/inventory') ?>" class="btn btn-dark w-100 <?= (strpos(uri_string(), 'Central_AD/inventory') !== false) ? 'active' : '' ?>">Inventory</a>
<?php elseif ($role === 'inventory'): ?>
<a href="<?= base_url('dashboard') ?>" class="btn btn-dark w-100 <?= (uri_string() == 'dashboard') ? 'active' : '' ?>">Dashboard</a>
<!-- <a href="<?= site_url('inventory/add-stock') ?>" class="btn btn-dark w-100">Add Stock</a> -->
<!-- <a href="<?= site_url('inventory/edit-stock') ?>" class="btn btn-dark w-100">Edit Stock</a> -->
<a href="<?= base_url('inventory/stock-list') ?>" class="btn btn-dark w-100 <?= (strpos(uri_string(), 'inventory/stock-list') !== false) ? 'active' : '' ?>">Stock List</a>
<a href="<?= base_url('Central_AD/inventory') ?>" class="btn btn-dark w-100 <?= (strpos(uri_string(), 'Central_AD/inventory') !== false) ? 'active' : '' ?>">Inventory</a>
<?php elseif ($role === 'supplier'): ?>
<a href="<?= base_url('dashboard') ?>" class="btn btn-dark w-100 <?= (uri_string() == 'dashboard') ? 'active' : '' ?>">Overview</a>
<a href="<?= base_url('supplier/dashboard') ?>" class="btn btn-dark w-100 <?= (strpos(uri_string(), 'supplier/dashboard') !== false) ? 'active' : '' ?>">Order Management</a>
<a href="<?= base_url('supplier/orders') ?>" class="btn btn-dark w-100 <?= (strpos(uri_string(), 'supplier/orders') !== false && strpos(uri_string(), 'supplier/dashboard') === false) ? 'active' : '' ?>">All Orders</a>
<a href="<?= base_url('supplier/invoices') ?>" class="btn btn-dark w-100 <?= (strpos(uri_string(), 'supplier/invoices') !== false) ? 'active' : '' ?>">Invoices</a>
<?php elseif ($role === 'logistics_coordinator'): ?>
<a href="<?= base_url('dashboard') ?>" class="btn btn-dark w-100 <?= (uri_string() == 'dashboard') ? 'active' : '' ?>">Dashboard</a>
<a href="<?= base_url('logistics/schedule-delivery') ?>" class="btn btn-dark w-100 <?= (strpos(uri_string(), 'logistics/schedule-delivery') !== false) ? 'active' : '' ?>">Schedule Delivery</a>
<a href="<?= base_url('logistics/supplier-deliveries') ?>" class="btn btn-dark w-100 <?= (strpos(uri_string(), 'logistics/supplier-deliveries') !== false) ? 'active' : '' ?>">Supplier Deliveries</a>
<a href="<?= base_url('logistics/deliveries') ?>" class="btn btn-dark w-100 <?= (strpos(uri_string(), 'logistics/deliveries') !== false && strpos(uri_string(), 'logistics/supplier-deliveries') === false) ? 'active' : '' ?>">All Deliveries</a>
<?php endif; ?>

<a href="<?= base_url('logout') ?>" class="btn btn-danger w-100">Logout</a>
</div>