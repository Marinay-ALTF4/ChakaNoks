<?= $this->extend('Layout') ?>

<?= $this->section('content') ?>

<div class="container py-4">
  <div class="page-header mb-4 d-flex justify-content-between align-items-center">
    <div>
      <h1 class="h3 fw-bold mb-1">📦 Stock Alerts</h1>
      <span class="text-muted">Welcome, <?= esc(session()->get('username')) ?>!</span>
    </div>
  </div>

  <div class="card shadow-sm border-0">
    <div class="card-body">
      <h2 class="h5 fw-semibold mb-2">Items Requiring Attention</h2>
      <p class="text-muted mb-4">
        Low stock and perishable goods nearing expiry are listed below.
      </p>

      <div class="table-responsive">
        <table class="table table-hover align-middle text-center">
          <thead class="table-dark">
            <tr>
              <th scope="col">Item ID</th>
              <th scope="col">Item Name</th>
              <th scope="col">Quantity</th>
              <th scope="col">Last Update</th>
              <th scope="col">Branch</th>
              <th scope="col">Status</th>
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
</div>

<style>
  .container {
    max-width: 900px;
  }

  .card {
    border-radius: 12px;
  }

  .status { 
    padding: 6px 12px; 
    border-radius: 20px; 
    font-size: 13px; 
    font-weight: bold; 
    display: inline-block; 
  }

  .status.low { 
    background: #d9534f; 
    color: #fff; 
  }

  .status.expiry { 
    background: #f0ad4e; 
    color: #fff; 
  }

  .table th, .table td {
    vertical-align: middle;
  }

  .table thead th {
    text-transform: uppercase;
    font-size: 13px;
    letter-spacing: 0.5px;
  }

  .page-header h1 {
    margin-bottom: 0.25rem;
  }
</style>

<?= $this->endSection() ?>
