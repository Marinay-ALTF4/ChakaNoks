<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Central Admin</title>
</head>
<link rel="stylesheet" href="<?= base_url('css/Central_AD.css') ?>">

<body>

<div class="sidebar">
    <h2>ADMIN</h2>
<a href="<?= base_url('dashboard') ?>" class="active">DASHBOARD</a>
<a href="<?= base_url('admin/inventory') ?>">INVENTORY</a>

    <a href="#">SUPPLIERS</a>
    <a href="#">ORDERS</a>
    <a href="#">FRANCHISING</a>
    <a href="#">REPORTS</a>
    <a href="#">SETTINGS</a>
<a href="<?= base_url('logout') ?>" class="logout">Logout</a>
</div>

<div class="main">
   <div class="header">
    <span>Central Admin Dashboard</span>
    <a href="other_braches.php">View Other Branches</a>
</div>

    <div class="top-cards">
        <div class="card">Branch Overview</div>
        <div class="card">Branch Performance</div>
        <div class="card">User & Role Management</div>
    </div>

    <div class="report">Reports / Charts Placeholder</div>
</div>


</body>
</html>
