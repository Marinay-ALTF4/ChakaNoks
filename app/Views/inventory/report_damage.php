<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Report Damaged/Expired Goods</title>
</head>
<body>
  <h2>Report Damaged/Expired Goods</h2>
  <form action="<?= site_url('inventory/report-damage') ?>" method="post">
    <label for="id">Select Item:</label>
    <select name="id" required>
      <?php foreach ($items as $item): ?>
        <option value="<?= $item['id'] ?>"><?= esc($item['item_name']) ?> (<?= $item['quantity'] ?> left)</option>
      <?php endforeach; ?>
    </select>
    <br><br>
    <label for="status">Mark As:</label>
    <select name="status" required>
      <option value="damaged">Damaged</option>
      <option value="expired">Expired</option>
    </select>
    <br><br>
    <button type="submit">Report</button>
  </form>
  <a href="<?= site_url('inventory') ?>">⬅ Back to Dashboard</a>
</body>
</html>
