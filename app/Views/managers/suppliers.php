<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Suppliers</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
     margin: 0;
    font-family: Arial, sans-serif;
    background-color: #f4f6f8;
    }

    /* Sidebar */
    .sidebar {
    position: fixed;
    top: 0;
    left: 0;
    width: 220px;
    height: 100%;
    padding-top: 20px;
    box-sizing: border-box;
    background-color: #c9c9c9;
    border-radius: 0 20px 20px 0;
    border: 1px solid black;
    }
    .sidebar h2 {
       text-align: center;
    font-size: 18px;
    margin-bottom: 40px;
    color: black;
    font-weight: bold;
    }
    .sidebar a {
    display: block;
    margin: 15px 20px;
    padding: 14px 15px;
    text-align: center;
    text-decoration: none;
    font-weight: bold;
    color: white;
    background-color: #494949;
    border-radius: 20px;
    transition: 0.3s;
    border: 2px solid black;
    }
    .sidebar a:hover {
          background-color: #0c0c0c;
    color: white;
    }
    .sidebar .logout {
      background-color: red;
      margin-top: auto;
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

    /* Main Content */
    .content {
      margin-left: 240px;
    padding: 30px 40px;
    box-sizing: border-box;
    max-width: calc(100% - 240px);
    }
    .table-container {
      background: #fff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0px 2px 6px rgba(0,0,0,0.1);
    }
  </style>
</head>
<body>
  <!-- Sidebar -->
  <div class="sidebar">
    <h2>Welcome, <?= session()->get('username') ?></h2>
    <a href="<?= base_url('Central_AD') ?>">DASHBOARD</a>
    <a href="<?= base_url('admin/inventory') ?>">INVENTORY</a>
<a href="<?= site_url('Central_AD/suppliers') ?>">SUPPLIERS</a>
<a href="<?= site_url('Central_AD/orders') ?>">ORDERS</a>
<a href="<?= site_url('Central_AD/franchising') ?>">FRANCHISING</a>
<a href="<?= site_url('Central_AD/reports') ?>">REPORTS</a>
<a href="<?= site_url('Central_AD/settings') ?>">SETTINGS</a>
<a href="<?= base_url('logout') ?>" class="logout">Logout</a>
  </div>

  <!-- Main Content -->
  <div class="content">
    <h2>Suppliers</h2>
    <div class="table-container">
      <table class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>ID</th>
            <th>Supplier Name</th>
            <th>Contact</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>1</td>
            <td>ABC Distributors</td>
            <td>abc@email.com</td>
            <td>Active</td>
          </tr>
          <tr>
            <td>2</td>
            <td>XYZ Traders</td>
            <td>xyz@email.com</td>
            <td>Inactive</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</body>
</html>
