<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Stock</title>
</head>
<body>
  <div class="sidebar">
    <div>
      <h2>Inventory</h2>
      <div class="nav">  
        <a href="<?= site_url('inventory') ?>">🏠 Dashboard</a>    
        <a href="<?= site_url('inventory/add-stock') ?>" class="active">➕ Add Stock</a>
        <a href="<?= site_url('inventory/edit-stock') ?>">✏️ Edit Stock</a>
        <a href="<?= site_url('inventory/stock-list') ?>">📋 Stock List</a>
        <a href="<?= site_url('inventory/alerts') ?>">⚠️ Alerts</a>
      </div>
    </div>
    <a href="<?= base_url('logout') ?>" class="logout">Logout</a>
  </div>

  <div class="main">
    <div class="header">
      <h1>Add Stock</h1>
      <span>Welcome, <?= session()->get('username') ?>!</span>
    </div>

    <div class="content">
      <div class="form-card">
        <h2>  New Stock Entry</h2>
        <p>Fill in the details below to add stock to the inventory.</p>
        <form method="post" action="">
          <div class="form-group">
            <label>Item Name</label>
            <input type="text" name="item_name" required>
          </div>

          <div class="form-group">
            <label>Quantity</label>
            <input type="number" name="quantity" required>
          </div>

          <div class="form-group">
            <label>Expiry Date</label>
            <input type="date" name="expiry_date" required>
          </div>

          <button type="submit"> Add Stock</button>
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

    .nav a:hover, .nav a.active {
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
      padding: 40px;
      display: flex;
      justify-content: center;
      align-items: flex-start;
      overflow-y: auto;
    }

    .form-card {
      background-color: #2a2a2a;
      padding: 30px;
      border-radius: 12px;
      width: 100%;
      max-width: 600px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.5);
    }

    .form-card h2 {
      margin-top: 0;
      margin-bottom: 10px;
      color: #fff;
    }

    .form-card p {
      margin-bottom: 20px;
      color: #ccc;
      font-size: 14px;
    }

    .form-group {
      margin-bottom: 18px;
      display: flex;
      flex-direction: column;
    }

    label {
      font-weight: bold;
      margin-bottom: 6px;
      color: #fff;
    }

    input {
      padding: 12px;
      border: none;
      border-radius: 6px;
      background-color: #1e1e1e;
      color: #fff;
      font-size: 14px;
    }

    button {
      padding: 12px 32px;
      border: none;
      border-radius: 6px;
      background-color: #28a745;
      color: #fff;
      font-weight: bold;
      cursor: pointer;
      transition: 0.3s ease;
    }

    button:hover {
      background-color: #218838;
      transform: translateY(-2px);
    }
  </style>
</body>
</html>


