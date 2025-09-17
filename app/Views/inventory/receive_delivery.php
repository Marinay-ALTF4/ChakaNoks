<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Receive Deliveries</title>
</head>
<body>
  <h2>Receive New Delivery</h2>
  <form action="<?= site_url('inventory/receive-delivery') ?>" method="post">
    <label for="item_name">Item Name:</label>
    <input type="text" name="item_name" required>
    <br><br>
    <label for="quantity">Quantity Received:</label>
    <input type="number" name="quantity" min="1" required>
    <br><br>
    <button type="submit">Add Delivery</button>
  </form>
  <a href="<?= site_url('inventory') ?>">⬅ Back to Dashboard</a>
</body>
</html>
