<?= $this->extend('Layout') ?>

<?= $this->section('content') ?>

<h2>📦 Monitor Inventory</h2>

<div class="inventory-container">
  <table class="inventory-table">
    <thead>
      <tr>
        <th>Item</th>
        <th>Qty</th>
        <th>Barcode</th>
        <th>Expiry</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($inventory as $item): ?>
      <tr>
        <td><?= esc($item['item_name']) ?></td>
        <td><?= esc($item['quantity']) ?></td>
        <td><?= esc($item['barcode']) ?></td>
        <td><?= esc($item['expiry_date']) ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
<a href="<?= site_url('branch/dashboard') ?>" class="back-btn">⬅ Back</a>

<style>
/* Page-specific styles only. Do not override global layout. */

h2 {
  margin-bottom: 20px;
  font-size: 22px;
  font-weight: bold;
  color:#222;
  margin-left: 15%;
}

.inventory-container {
  background: #fff;
  padding: 20px;
  border-radius: 12px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.1);
  border: 1px solid black;
  margin-left: 15%;
}

.inventory-table {
  width: 100%;
  border-collapse: collapse; 
  font-size: 14px;
}

.inventory-table th, 
.inventory-table td {
  padding: 12px 15px;
  text-align: left;
  border: 1px solid #ddd; 
}

.inventory-table th {
  background: #0456adff;
  color: white;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  font-size: 13px;
  border: 1px solid black;
}

.inventory-table tr:hover {
  background: #f9f9f9;
}

/* Removed duplicate sidebar styles to use the shared layout */
.back-btn {
  display: inline-block;
  margin-top: 20px;
  padding: 10px 14px;
  background: #6c757d;
  color: #fff;
  border-radius: 6px;
  text-decoration: none;
  transition: background 0.2s ease;
  font-weight: bold;
  border: 1px solid black;
}

.back-btn:hover {
  background: #5a6268;
}
</style>

<?= $this->endSection() ?>
