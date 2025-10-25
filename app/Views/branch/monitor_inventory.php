<?= $this->extend('Layout') ?>

<?= $this->section('content') ?>

<?php
// 🐔 Hardcoded chicken inventory sample (hawaa lang ang hardcoded nga sample kung mag butang nag running code)
$inventory = [
    ['item_name' => 'Whole Chicken', 'quantity' => 25, 'barcode' => '012345678905', 'expiry_date' => '2025-12-15'],
    ['item_name' => 'Chicken Wings', 'quantity' => 40, 'barcode' => '036000291452', 'expiry_date' => '2025-11-30'],
    ['item_name' => 'Chicken Breast Fillet', 'quantity' => 20, 'barcode' => '5000159484695', 'expiry_date' => '2026-01-10'],
    ['item_name' => 'Frozen Chicken Nuggets', 'quantity' => 50, 'barcode' => '071831008987', 'expiry_date' => '2025-08-05'],
];
?>

<div class="page-header d-flex justify-content-between align-items-center mb-4">
  <h2 class="fw-bold mb-0">
    <i class="bi bi-egg-fried"></i> Chicken Inventory Monitor
  </h2>
  
</div>

<div class="card shadow-sm border-0 mx-auto inventory-card">
  <div class="card-body">
    <?php if (!empty($inventory)): ?>
      <div class="table-responsive">
        <table class="table table-hover align-middle text-center">
          <thead class="table-primary">
            <tr>
              <th>Product</th>
              <th>Quantity</th>
              <th>Barcode</th>
              <th>Expiry Date</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($inventory as $item): ?>
              <?php 
                $isExpired = strtotime($item['expiry_date']) < time();
                $expiringSoon = strtotime($item['expiry_date']) < strtotime('+15 days') && !$isExpired;
              ?>
              <tr>
                <td class="fw-semibold"><?= esc($item['item_name']) ?></td>
                <td><span class="badge bg-info text-dark px-3 py-2"><?= esc($item['quantity']) ?></span></td>
                <td>
                  <!-- ✅ Generate real barcode using bwip-js API -->
                  <img 
                    src="https://bwipjs-api.metafloor.com/?bcid=ean13&text=<?= esc($item['barcode']) ?>&scale=2&height=10" 
                    alt="Barcode" 
                    class="barcode-img mb-1"
                  >
                  <div class="small text-muted"><?= esc($item['barcode']) ?></div>
                </td>
                <td><?= esc($item['expiry_date']) ?></td>
                <td>
                  <?php if ($isExpired): ?>
                    <span class="badge bg-danger">Expired</span>
                  <?php elseif ($expiringSoon): ?>
                    <span class="badge bg-warning text-dark">Expiring Soon</span>
                  <?php else: ?>
                    <span class="badge bg-success">Fresh</span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <p class="text-muted text-center mb-0">No inventory items found.</p>
    <?php endif; ?>
  </div>
</div>

<style>
.page-header {
  border-bottom: 2px solid #dee2e6;
  padding-bottom: 10px;
}

.inventory-card {
  max-width: 90%;
  background: #ffffff;
  border-radius: 12px;
  margin-left: 5%;
}

.table {
  border-radius: 8px;
  overflow: hidden;
}

.table th {
  text-transform: uppercase;
  font-size: 13px;
  letter-spacing: 0.5px;
}

.table td {
  font-size: 14px;
  vertical-align: middle;
}

.table tbody tr:hover {
  background-color: #f6f9ff;
  transition: background 0.3s ease;
}

.badge {
  font-size: 12px;
  border-radius: 6px;
}

.barcode-img {
  width: 130px;
  height: 40px;
  object-fit: contain;
}

.btn-secondary {
  background-color: #6c757d;
  border: none;
  border-radius: 6px;
  transition: 0.2s;
}

.btn-secondary:hover {
  background-color: #5a6268;
}
</style>

<?= $this->endSection() ?>
