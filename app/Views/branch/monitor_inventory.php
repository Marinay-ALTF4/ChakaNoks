<h2>Monitor Inventory</h2>
<table border="1">
  <tr><th>Item</th><th>Qty</th><th>Barcode</th><th>Expiry</th></tr>
  <?php foreach ($inventory as $item): ?>
  <tr>
    <td><?= esc($item['item_name']) ?></td>
    <td><?= esc($item['quantity']) ?></td>
    <td><?= esc($item['barcode']) ?></td>
    <td><?= esc($item['expiry_date']) ?></td>
  </tr>
  <?php endforeach; ?>
</table>
<a href="<?= site_url('branch/dashboard') ?>">⬅ Back</a>
