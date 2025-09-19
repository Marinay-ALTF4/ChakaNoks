<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Branch Manager Dashboard</title>
  <link rel="stylesheet" href="<?= base_url('css/branch_dashboard.css') ?>">
</head>
<body>

<div class="sidebar">
    <h2>BRANCH MANAGER</h2>
    <a href="<?= site_url('branch/dashboard') ?>">🏠 Dashboard</a>
    <a href="<?= site_url('branch/monitor-inventory') ?>">📦 Monitor Inventory</a>
    <a href="<?= site_url('branch/purchase-request') ?>">🛒 Create Purchase Request</a>
    <a href="<?= site_url('branch/approve-transfers') ?>">🔄 Approve Transfers</a>
    <a href="<?= site_url('logout') ?>" class="logout">🚪 Logout</a>
</div>

<div class="main">
    <h1>Welcome, <?= esc(session()->get('username')) ?>!</h1>
    <div class="cards">
        <div class="card">
            <h3>📦 Monitor Inventory</h3>
            <p>Check stock levels and track shortages.</p>
            <a href="<?= site_url('branch/monitor-inventory') ?>">View Inventory</a>
        </div>
        <div class="card">
            <h3>🛒 Purchase Requests</h3>
            <p>Create and submit purchase requests to Admin.</p>
            <a href="<?= site_url('branch/purchase-request') ?>">Create Request</a>
        </div>
        <div class="card">
            <h3>🔄 Intra-Branch Transfers</h3>
            <p>Approve transfer requests between branches.</p>
            <a href="<?= site_url('branch/approve-transfers') ?>">View Transfers</a>
        </div>
    </div>
</div>

<style>
body {
  margin:0;
  font-family: Arial, sans-serif;
  background:#F2BE5C;
}
.sidebar {
  position:fixed;
  left:0;
  top:0;
  width:220px;
  height:100%;
  background:#333;
  color:#fff;
  padding:20px;
  box-sizing:border-box;
  display:flex;
  flex-direction:column;
}
.sidebar h2 {
  text-align:center;
  margin:0 0 18px 0;
  font-size:14px;
  letter-spacing:0.6px;
}
.sidebar a {
  display:block;
  width:100%;
  box-sizing:border-box;
  padding:10px 14px;
  margin:6px 0;
  color:#fff;
  text-decoration:none;
  background:#444;
  border-radius:8px;
  transition: transform .12s ease, background .12s ease;
  text-align:left;
  border: 1px solid black;
}
.sidebar a:hover {
  background:#222;
  transform: translateY(-2px);
  text-decoration:none;
}
.sidebar a.logout {
  background:#e74c3c;
  font-weight:600;
  padding:12px 14px;
  margin:12px 0 0 0;
  margin-top:auto;
  text-align:center;
}
.sidebar a.logout:hover {
  background:#c0392b;
  transform: translateY(-3px);
}
.main {
  margin-left:240px;
  padding:30px;
}
.cards {
  display:flex;
  gap:20px;
}
.card {
  flex:1;
  background:white;
  padding:20px;
  border-radius:12px; 
  box-shadow:0 2px 6px rgba(0,0,0,0.1);
  display:flex;
  flex-direction:column;
  justify-content:space-between;
  transition: transform 0.25s ease, box-shadow 0.25s ease;
  border: 1px solid black;
}
.card:hover {
  transform: translateY(-8px);
  box-shadow:0 6px 16px rgba(0,0,0,0.15);
}
.card h3 {
  margin-top:0;
}
.card p {
  flex:1;
}
.card a {
  display:block;
  padding:10px;
  text-align:center;
  background:#007bff;
  color:white;
  border-radius:6px;
  font-weight:bold;
  transition: background 0.2s ease;
  border: 1px solid black;
}
.card a:hover {
  background:#0056b3;
  text-decoration:none;
}
</style>

</body>
</html>
