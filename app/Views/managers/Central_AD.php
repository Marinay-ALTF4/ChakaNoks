<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Central Admin</title>
</head>


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
<style>
    
body {
    margin: 0;
    font-family: Arial, sans-serif;
    background-color: #f4f6f8;
}

.sidebar {
    position: fixed;
    top: 0;
    left: 0;
    width: 220px;
    height: 100%;
    background-color: #c9c9c9;
    padding-top: 20px;
    box-sizing: border-box;
    border-radius: 0 20px 20px 0;
    color: white;
}

.sidebar h2 {
    text-align: center;
    font-size: 18px;
    margin-bottom: 40px;
    color: black;
}

.sidebar a {
    display: block;
    padding: 14px 15px;
    text-decoration: none;
    color: white;
    margin: 15px 20px;
    background-color: #494949;
    text-align: center;
    border-radius: 20px;
    font-weight: bold;
    transition: 0.3s;
}

.sidebar a:hover {
    background-color: #0c0c0c;
    color: white;
}

.sidebar a.active {
    background-color: #000000;
    color: white;
}

.sidebar a.logout {
    background-color: #e74c3c;
    color: white;
    position: absolute;
    bottom: 20px;
    left: 15px;
    right: 15px;
    border-radius: 20px;
}

.sidebar a.logout:hover { 
    background-color: red;
}

.main {
    margin-left: 240px;
    padding: 30px 40px;
    box-sizing: border-box;
    max-width: calc(100% - 240px);
}

.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #b6b6b6;
    padding: 15px 20px;
    font-weight: bold;
    border-radius: 20px;
    color: #000000;
}

.header a {
    background-color: #494949;
    color: white;
    padding: 7px 15px;
    text-decoration: none;
    font-size: 14px;
    border-radius: 20px;
    transition: 0.3s;
}

.header a:hover {
    background-color: #000000;
}

.top-cards {
    display: flex;
    gap: 30px;
    margin: 30px 0;
}

.card {
    flex: 1;
    background-color: rgb(160, 160, 160);
    padding: 40px 15px;
    text-align: center;
    font-weight: bold;
    border-radius: 20px;
    color: #000000;
    box-shadow: 0 3px 8px rgba(0,0,0,0.1);
}

.report {
    background-color: rgb(160, 160, 160);
    height: 300px;
    display: flex;
    justify-content: center;
    align-items: center;
    font-weight: bold;
    font-size: 18px;
    border-radius: 20px;
    margin-top: 40px;
    color: #000000;
    box-shadow: 0 3px 8px rgba(0,0,0,0.1);
}
</style>
