
<?= $this->extend('template/header') ?>

<?= $this->section('content') ?>

<div class="content">
      <!-- Action Buttons -->
  <div class="action-row">
    <div class="card action-card">
      <h3>Update Stock Levels</h3>
      <p>Adjust stock quantities when items are sold or used.</p>
      <a href="<?= site_url('inventory/update_stock') ?>" class="btn">Update</a>
    </div>

    <div class="card action-card">
      <h3>Receive Deliveries</h3>
      <p>Add new items when deliveries are received.</p>
      <a href="<?= site_url('inventory/receive-delivery') ?>" class="btn">See Deliveries</a>
    </div>

    <div class="card action-card">
      <h3>Report Damaged/Expired Goods</h3>
      <p>Mark damaged or expired items and remove them from stock.</p>
      <a href="<?= site_url('inventory/report-damage') ?>" class="btn">Report</a>
    </div>
  </div>


        <!-- Stock Overview -->
        <div class="card small-card">
          <h3>Stock Overview</h3>
          <p>Total Items in Stock: <strong><?= $stockCount ?? 0 ?></strong></p>
          <p>Low Stock Items: <strong><?= $lowStock ?? 0 ?></strong></p>
        </div>

        <!-- Alerts -->
        <div class="card small-card">
          <h3>Alerts</h3>
          <?php if (!empty($lowStock) && $lowStock > 0): ?>
            <p>⚠️ There are <strong><?= $lowStock ?></strong> low-stock or damaged items.</p>
            <a href="<?= site_url('inventory/alerts') ?>" class="btn">View Alerts</a>
          <?php else: ?>
            <p>No alerts. All stock levels are good.</p>
          <?php endif; ?>
        </div>

        <!-- Recent Activity -->
        <div class="card small-card">
          <h3>Recent Activity</h3>
          <?php if (!empty($recentItems)): ?>
            <ul>
              <?php foreach ($recentItems as $item): ?>
                <li><?= esc($item['item_name']) ?> (<?= $item['quantity'] ?> units) - <?= $item['updated_at'] ?></li>
              <?php endforeach; ?>
            </ul>
          <?php else: ?>
            <p>No recent updates yet.</p>
          <?php endif; ?>
        </div>
      </div>
          </div>


 
<?= $this->endSection() ?>
<?= $this->extend('template/footer') ?>  
</body>
</html>