<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Stock</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>
<div class="container mt-4">
  <div class="page-header">
    <h1>Edit Stock Item</h1>
    <span>Welcome, <?= esc(session()->get('username')) ?>!</span>
  </div>

  <div class="card">
    <form method="post" action="">
      <div class="mb-3">
        <label class="form-label">Item Name</label>
        <input type="text" name="item_name" value="<?= isset($item['item_name']) ? esc($item['item_name']) : '' ?>" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Quantity</label>
        <input type="number" name="quantity" value="<?= isset($item['quantity']) ? esc($item['quantity']) : '' ?>" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Expiry Date</label>
        <input type="date" name="expiry_date" value="<?= isset($item['expiry_date']) ? esc($item['expiry_date']) : '' ?>" class="form-control" required>
      </div>

      <div class="d-flex justify-content-between">
        <a href="<?= site_url('inventory/stock-list') ?>" class="btn btn-secondary">← Back</a>
        <button type="submit" class="btn btn-primary"> Update Stock</button>
      </div>
    </form>
  </div>
</div>

</body>
</html>
<style>
    body {
      background-color: #f8f9fa;
      font-family: "Segoe UI", Arial, sans-serif;
    }
    .page-header {
      background-color: #343a40;
      color: #fff;
      padding: 15px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-radius: 6px;
      margin-bottom: 25px;
    }
    .page-header h1 {
      font-size: 20px;
      margin: 0;
    }
    .card {
      background-color: #fff;
      border: none;
      box-shadow: 0 3px 8px rgba(0,0,0,0.1);
      border-radius: 10px;
      padding: 25px;
      max-width: 600px;
      margin: 0 auto;
    }
    .form-label {
      font-weight: 600;
      color: #333;
    }
    input[type="text"], input[type="number"], input[type="date"] {
      border-radius: 8px;
      padding: 10px;
      border: 1px solid #ccc;
    }
    .btn-primary {
      background-color: #198754;
      border: none;
      border-radius: 8px;
      padding: 10px 20px;
      font-weight: 500;
    }
    .btn-primary:hover {
      background-color: #157347;
    }
    .btn-secondary {
      background-color: #6c757d;
      border: none;
      border-radius: 8px;
      padding: 10px 20px;
      font-weight: 500;
    }
    .btn-secondary:hover {
      background-color: #5c636a;
    }
  </style>