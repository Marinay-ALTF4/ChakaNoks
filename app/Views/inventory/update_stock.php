<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Update Stock</title>
</head>
<body>
  <h2>Update Stock Levels</h2>
  <form action="<?= site_url('inventory/update-stock') ?>" method="post">
    <label for="id">Select Item:</label>
    <select name="id" required>
      <?php foreach ($items as $item): ?>
        <option value="<?= $item['id'] ?>"><?= esc($item['item_name']) ?> (Current: <?= $item['quantity'] ?>)</option>
      <?php endforeach; ?>
    </select>
    <br><br>
    <label for="quantity">New Quantity:</label>
    <input type="number" name="quantity" min="0" required>
    <br><br>
    <button type="submit">Update</button>
  </form>
  <a href="<?= site_url('inventory') ?>">⬅ Back to Dashboard</a>
</body>
</html>
