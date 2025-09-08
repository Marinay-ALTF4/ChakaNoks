<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Stock Alerts</title>
</head>
<body>
  <div class="sidebar">
    <div>
      <h2>Inventory</h2>
      <div class="nav">      
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
      <h1>Stock Alerts</h1>
      <span>Welcome, <?= session()->get('username') ?>!</span>
    </div>
    <div class="content">
      <div class="card">
        <p>Low stock and perishable goods nearing expiry will show up here.</p>
        
        <table>
          <thead>
            <tr>
              <th>Item ID</th>
              <th>Item Name</th>
              <th>Quantity</th>
              <th>Last Update</th>
              <th>Branch</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>5</td>
              <td>Fresh Milk</td>
              <td>8</td>
              <td>2025-09-07 15:45</td>
              <td>Branch C</td>
              <td><span class="status low">Low Stock</span></td>
            </tr>
            <tr>
              <td>6</td>
              <td>Ground Pork</td>
              <td>12</td>
              <td>2025-09-08 09:00</td>
              <td>Branch B</td>
              <td><span class="status expiry">Nearing Expiry</span></td>
            </tr>
          </tbody>
        </table>
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
      padding: 20px;
      border-radius: 10px;
      margin-bottom: 20px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.4);
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 15px;
    }

    thead {
      background-color: #111;
    }

    th, td {
      padding: 12px;
      border-bottom: 1px solid #444;
      text-align: left;
    }

    th {
      color: #fff;
      font-weight: bold;
    }

    td {
      color: #ddd;
    }

    .status {
      padding: 4px 8px;
      border-radius: 4px;
      font-size: 13px;
      font-weight: bold;
    }

    .status.low {
      background-color: #d9534f;
      color: #fff;
    }

    .status.expiry {
      background-color: #f0ad4e;
      color: #fff;
    }
  </style>
</body>
</html>
