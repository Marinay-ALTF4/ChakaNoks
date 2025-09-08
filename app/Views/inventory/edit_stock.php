<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Stock</title>
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
      <h1>Edit Stock</h1>
      <span>Welcome, <?= session()->get('username') ?>!</span>
    </div>
    <div class="content">
      <div class="card">
        <form method="post" action="">
          <label>Item Name</label>
          <input type="text" name="item_name" value=""><br><br>

          <label>Quantity</label>
          <input type="number" name="quantity" value=""><br><br>

          <label>Expiry Date</label>
          <input type="date" name="expiry_date" value=""><br><br>

          <button type="submit">Update</button>
        </form>
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

    form label {
      display: block;
      margin-bottom: 6px;
      font-weight: bold;
      color: #fff;
    }

    form input {
      width: 100%;
      padding: 10px;
      border: none;
      border-radius: 6px;
      background-color: #1e1e1e;
      color: #f5f5f5;
      margin-bottom: 15px;
      box-shadow: inset 0 2px 4px rgba(0,0,0,0.4);
    }

    form button {
      padding: 12px 20px;
      background-color: #444;
      border: none;
      border-radius: 8px;
      color: #fff;
      font-size: 15px;
      cursor: pointer;
      transition: 0.3s ease;
    }

    form button:hover {
      background-color: #666;
      transform: translateY(-2px);
    }
  </style>
</body>
</html>
