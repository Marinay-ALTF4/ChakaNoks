<?= $this->extend('Layout') ?>

<?= $this->section('content') ?>

<div class="page-header mb-3">
  <h1 class="h4">Stock Alerts</h1>
  <span>Welcome, <?= esc(session()->get('username')) ?>!</span>
</div>

<div class="content">
  <div class="card">
    <h2>Items Requiring Attention</h2>
    <p>Low stock and perishable goods nearing expiry are listed below.</p>
    
    <div class="table-responsive">
      <table class="table table-striped">
          <thead>
            <tr>
              <th>Item ID</th>
              <th>Item Name</th>
              <th>Quantity</th>
              <th>Last Update</th>
              <th>Branch</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>5</td>
              <td>Fresh Milk</td>
              <td>8</td>
              <td>2025-09-07 15:45</td>
              <td>Branch C</td>
              <td><span class="status low">Low Stock</span></td>
            </tr>
            <tr>
              <td>6</td>
              <td>Ground Pork</td>
              <td>12</td>
              <td>2025-09-08 09:00</td>
              <td>Branch B</td>
              <td><span class="status expiry">Nearing Expiry</span></td>
            </tr>
          </tbody>
      </table>
    </div>
  </div>
</div>

  <style>
<style>
  /* Page-specific styles only */
  .content { padding: 16px; }
  .status { padding: 6px 12px; border-radius: 20px; font-size: 13px; font-weight: bold; display: inline-block; }
  .status.low { background: #d9534f; color: #fff; }
  .status.expiry { background: #f0ad4e; color: #fff; }
</style>

<?= $this->endSection() ?>
