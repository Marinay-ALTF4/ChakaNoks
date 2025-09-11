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
  background:#f4f6f8;
}
.sidebar {
  position:fixed; left:0; top:0;
  width:220px; height:100%;
  background:#333; color:white;
  padding-top:20px;
}
.sidebar h2 { text-align:center; margin-bottom:30px; }
.sidebar a {
  display:block; padding:12px 20px;
  color:white; text-decoration:none;
  margin:10px; border-radius:8px;
  background:#444;
  transition: all 0.2s ease;
}
.sidebar a:hover { background:#111; }
.sidebar a.logout { background:#e74c3c; position:absolute; bottom:20px; width:80%; left:10%; }
.main {
  margin-left:240px; padding:30px;
}
.cards {
  display:flex; gap:20px;
}
.card {
  flex:1; background:white; padding:20px;
  border-radius:12px; box-shadow:0 2px 6px rgba(0,0,0,0.1);
}
.card a {
  display:inline-block;
  margin-top:10px;
  color:#007bff;
  text-decoration:none;
  font-weight:bold;
}
.card a:hover {
  text-decoration:underline;
}
</style>

</body>
</html>
