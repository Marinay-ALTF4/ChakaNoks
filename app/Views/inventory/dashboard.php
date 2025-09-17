<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Inventory Dashboard</title>
</head>
<body>
<div class="sidebar">
  <div>
    <h2>Inventory</h2>
    <div class="nav">  
      <a href="<?= site_url('inventory') ?>">🏠 Dashboard</a>    
      <a href="<?= site_url('inventory/add-stock') ?>">➕ Add Stock</a>
      <a href="<?= site_url('inventory/edit-stock') ?>">✏️ Edit Stock</a>
      <a href="<?= site_url('inventory/stock-list') ?>">📋 Stock List</a>
      <a href="<?= site_url('inventory/alerts') ?>">⚠️ Alerts</a>
    </div>
  </div>
  <a href="<?= base_url('logout') ?>" class="logout">Logout</a>
</div>


  <div class="main">
    <div class="header">
      <h1>Dashboard</h1>
      <span>Welcome, <?= session()->get('username') ?>!</span>
    </div>
    <div class="content">
      <!-- Action Buttons -->
<div class="action-row">
  <div class="card action-card">
    <h3>Update Stock Levels</h3>
    <p>Adjust stock quantities when items are sold or used.</p>
    <a href="<?= site_url('inventory/update_stock') ?>" class="btn">Update</a>
  </div>

  <div class="card action-card">
    <h3>Receive Deliveries</h3>
    <p>Add new items when deliveries are received.</p>
    <a href="<?= site_url('inventory/receive-delivery') ?>" class="btn">See Deliveries</a>
  </div>

  <div class="card action-card">
    <h3>Report Damaged/Expired Goods</h3>
    <p>Mark damaged or expired items and remove them from stock.</p>
    <a href="<?= site_url('inventory/report-damage') ?>" class="btn">Report</a>
  </div>
</div>


      <!-- Stock Overview -->
      <div class="card small-card">
        <h3>Stock Overview</h3>
        <p>Total Items in Stock: <strong><?= $stockCount ?? 0 ?></strong></p>
        <p>Low Stock Items: <strong><?= $lowStock ?? 0 ?></strong></p>
      </div>

      <!-- Alerts -->
      <div class="card small-card">
        <h3>Alerts</h3>
        <?php if (!empty($lowStock) && $lowStock > 0): ?>
          <p>⚠️ There are <strong><?= $lowStock ?></strong> low-stock or damaged items.</p>
          <a href="<?= site_url('inventory/alerts') ?>" class="btn">View Alerts</a>
        <?php else: ?>
          <p>No alerts. All stock levels are good.</p>
        <?php endif; ?>
      </div>

      <!-- Recent Activity -->
      <div class="card small-card">
        <h3>Recent Activity</h3>
        <?php if (!empty($recentItems)): ?>
          <ul>
            <?php foreach ($recentItems as $item): ?>
              <li><?= esc($item['item_name']) ?> (<?= $item['quantity'] ?> units) - <?= $item['updated_at'] ?></li>
            <?php endforeach; ?>
          </ul>
        <?php else: ?>
          <p>No recent updates yet.</p>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <style>
     body {
      margin: 0;
      font-family: Arial, sans-serif;
      background-color: #1e1e1e;
      color: #f5f5f5;
      display: flex;
      height: 100vh;
    }

    .sidebar {
      width: 240px;
      background-color: #111;
      padding: 20px 10px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }

    .sidebar h2 {
      text-align: center;
      margin-bottom: 20px;
      color: #fff;
      font-size: 22px;
    }

    .nav {
      display: flex;
      flex-direction: column;
      gap: 12px;
    }

    .nav a {
      display: block;
      background-color: #2a2a2a;
      padding: 15px;
      border-radius: 8px;
      color: #ccc;
      text-decoration: none;
      font-size: 15px;
      transition: all 0.3s ease;
      box-shadow: 0 2px 5px rgba(0,0,0,0.4);
    }

    .nav a:hover {
      background-color: #444;
      color: #fff;
      transform: translateY(-3px);
    }

    .logout {
      display: block;
      text-align: center;
      background-color: #2a2a2a;
      padding: 12px;
      margin-top: 20px;
      color: #f44336;
      font-weight: bold;
      border-radius: 8px;
      text-decoration: none;
      transition: all 0.3s ease;
      box-shadow: 0 2px 5px rgba(0,0,0,0.4);
    }

    .logout:hover {
      background-color: #444;
      transform: translateY(-3px);
    }

    .main {
      flex: 1;
      display: flex;
      flex-direction: column;
    }

    .header {
      background-color: #111;
      padding: 15px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: 1px solid #333;
    }

    .header h1 {
      margin: 0;
      font-size: 20px;
    }

    .content {
      flex: 1;
      padding: 20px;
      overflow-y: auto;
    }

    .card {
      background-color: #2a2a2a;
      padding: 15px;
      border-radius: 10px;
      margin-bottom: 20px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.4);
    }

    .card h3 {
      margin-top: 0;
      margin-bottom: 10px;
      color: #fff;
    }

    .card p {
      color: #ccc;
    }

    .action-card {
      border-left: 5px solid #4caf50;
      flex: 1;
      margin: 8px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }

    .action-card .btn {
      display: inline-block;
      margin-top: 10px;
      padding: 10px 15px;
      background-color: #4caf50;
      color: white;
      border-radius: 6px;
      text-decoration: none;
      transition: background 0.3s;
      text-align: center;
    }

    .action-card .btn:hover {
      background-color: #45a049;
    }

    .action-row {
      display: flex;
      gap: 10px;
      margin-bottom: 10px;
    }

    .small-card {
      font-size: 14px;
      opacity: 0.8;
      padding: 15px;
    }

    .small-card h3 {
      font-size: 16px;
    }
  </style>
</body>
</html>
