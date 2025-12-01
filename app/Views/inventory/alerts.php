<?= $this->extend('Layout') ?>

<?= $this->section('content') ?>

<div class="container py-4">
  <div class="page-header mb-4 d-flex justify-content-between align-items-center">
    <div>
      <h1 class="h3 fw-bold mb-1">⚠️ Stock Alerts</h1>
      <span class="text-muted">Welcome, <?= esc(session()->get('username')) ?>!</span>
    </div>
    <div>
      <a href="<?= site_url('inventory/stock-list') ?>" class="btn btn-secondary me-2">
        <i class="bi bi-arrow-left me-1"></i> Back to Stock List
      </a>
      <span class="badge bg-danger fs-6">Total Alerts: <?= isset($total_alerts) ? $total_alerts : 0 ?></span>
    </div>
  </div>

  <?php if (isset($total_alerts) && $total_alerts == 0): ?>
    <div class="alert alert-success">
      <i class="bi bi-check-circle me-2"></i> No alerts at this time. All items are in good condition!
    </div>
  <?php else: ?>

    <!-- Out of Stock Alerts -->
    <?php if (!empty($out_of_stock)): ?>
      <div class="card shadow-sm border-0 mb-4 border-danger border-start border-4">
        <div class="card-header bg-danger text-white">
          <h5 class="mb-0"><i class="bi bi-x-circle me-2"></i>Out of Stock (<?= count($out_of_stock) ?>)</h5>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover align-middle">
              <thead class="table-light">
                <tr>
                  <th>Item ID</th>
                  <th>Item Name</th>
                  <th>Type</th>
                  <th>Quantity</th>
                  <th>Branch</th>
                  <th>Last Update</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($out_of_stock as $item): ?>
                  <tr>
                    <td><?= esc($item['id']) ?></td>
                    <td><strong><?= esc($item['item_name']) ?></strong></td>
                    <td><?= esc($item['type'] ?? 'N/A') ?></td>
                    <td><span class="badge bg-danger"><?= esc($item['quantity']) ?></span></td>
                    <td><?= isset($branches[$item['branch_id']]) ? esc($branches[$item['branch_id']]) : 'N/A' ?></td>
                    <td><?= $item['updated_at'] ? date('Y-m-d H:i', strtotime($item['updated_at'])) : 'N/A' ?></td>
                    <td>
                      <a href="<?= site_url('inventory/edit-stock/' . $item['id']) ?>" class="btn btn-sm btn-warning">
                        <i class="bi bi-pencil"></i> Restock
                      </a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    <?php endif; ?>

    <!-- Damaged Items -->
    <?php if (!empty($damaged)): ?>
      <div class="card shadow-sm border-0 mb-4 border-secondary border-start border-4">
        <div class="card-header bg-secondary text-white">
          <h5 class="mb-0"><i class="bi bi-exclamation-triangle-fill me-2"></i>Damaged Items (<?= count($damaged) ?>)</h5>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover align-middle">
              <thead class="table-light">
                <tr>
                  <th>Item ID</th>
                  <th>Item Name</th>
                  <th>Type</th>
                  <th>Quantity</th>
                  <th>Expiry Date</th>
                  <th>Branch</th>
                  <th>Last Update</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($damaged as $item): ?>
                  <tr>
                    <td><?= esc($item['id']) ?></td>
                    <td><strong><?= esc($item['item_name']) ?></strong></td>
                    <td><?= esc($item['type'] ?? 'N/A') ?></td>
                    <td><span class="badge bg-secondary"><?= esc($item['quantity']) ?></span></td>
                    <td><?= $item['expiry_date'] ? esc($item['expiry_date']) : 'N/A' ?></td>
                    <td><?= isset($branches[$item['branch_id']]) ? esc($branches[$item['branch_id']]) : 'N/A' ?></td>
                    <td><?= $item['updated_at'] ? date('Y-m-d H:i', strtotime($item['updated_at'])) : 'N/A' ?></td>
                    <td>
                      <a href="<?= site_url('inventory/edit-stock/' . $item['id']) ?>" class="btn btn-sm btn-secondary text-white">
                        <i class="bi bi-pencil"></i> Update
                      </a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    <?php endif; ?>

    <!-- Expired Items -->
    <?php if (!empty($expired)): ?>
      <div class="card shadow-sm border-0 mb-4 border-dark border-start border-4">
        <div class="card-header bg-dark text-white">
          <h5 class="mb-0"><i class="bi bi-exclamation-triangle me-2"></i>Expired Items (<?= count($expired) ?>)</h5>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover align-middle">
              <thead class="table-light">
                <tr>
                  <th>Item ID</th>
                  <th>Item Name</th>
                  <th>Type</th>
                  <th>Quantity</th>
                  <th>Expiry Date</th>
                  <th>Branch</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($expired as $item): ?>
                  <tr>
                    <td><?= esc($item['id']) ?></td>
                    <td><strong><?= esc($item['item_name']) ?></strong></td>
                    <td><?= esc($item['type'] ?? 'N/A') ?></td>
                    <td><span class="badge bg-secondary"><?= esc($item['quantity']) ?></span></td>
                    <td><span class="badge bg-dark"><?= esc($item['expiry_date']) ?></span></td>
                    <td><?= isset($branches[$item['branch_id']]) ? esc($branches[$item['branch_id']]) : 'N/A' ?></td>
                    <td>
                      <a href="<?= site_url('inventory/edit-stock/' . $item['id']) ?>" class="btn btn-sm btn-danger">
                        <i class="bi bi-pencil"></i> Update
                      </a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    <?php endif; ?>

    <!-- Expiring Soon -->
    <?php if (!empty($expiring_soon)): ?>
      <div class="card shadow-sm border-0 mb-4 border-warning border-start border-4">
        <div class="card-header bg-warning text-dark">
          <h5 class="mb-0"><i class="bi bi-clock me-2"></i>Expiring Soon (Within 7 Days) (<?= count($expiring_soon) ?>)</h5>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover align-middle">
              <thead class="table-light">
                <tr>
                  <th>Item ID</th>
                  <th>Item Name</th>
                  <th>Type</th>
                  <th>Quantity</th>
                  <th>Expiry Date</th>
                  <th>Days Left</th>
                  <th>Branch</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($expiring_soon as $item): ?>
                  <?php
                  $expiryDate = new \DateTime($item['expiry_date']);
                  $today = new \DateTime();
                  $daysLeft = $today->diff($expiryDate)->days;
                  ?>
                  <tr>
                    <td><?= esc($item['id']) ?></td>
                    <td><strong><?= esc($item['item_name']) ?></strong></td>
                    <td><?= esc($item['type'] ?? 'N/A') ?></td>
                    <td><span class="badge bg-primary"><?= esc($item['quantity']) ?></span></td>
                    <td><?= esc($item['expiry_date']) ?></td>
                    <td><span class="badge bg-warning text-dark"><?= $daysLeft ?> days</span></td>
                    <td><?= isset($branches[$item['branch_id']]) ? esc($branches[$item['branch_id']]) : 'N/A' ?></td>
                    <td>
                      <a href="<?= site_url('inventory/edit-stock/' . $item['id']) ?>" class="btn btn-sm btn-warning">
                        <i class="bi bi-pencil"></i> Edit
                      </a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    <?php endif; ?>

    <!-- Low Stock -->
    <?php if (!empty($low_stock)): ?>
      <div class="card shadow-sm border-0 mb-4 border-info border-start border-4">
        <div class="card-header bg-info text-white">
          <h5 class="mb-0"><i class="bi bi-exclamation-circle me-2"></i>Low Stock (<?= count($low_stock) ?>)</h5>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover align-middle">
              <thead class="table-light">
                <tr>
                  <th>Item ID</th>
                  <th>Item Name</th>
                  <th>Type</th>
                  <th>Quantity</th>
                  <th>Expiry Date</th>
                  <th>Branch</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($low_stock as $item): ?>
                  <tr>
                    <td><?= esc($item['id']) ?></td>
                    <td><strong><?= esc($item['item_name']) ?></strong></td>
                    <td><?= esc($item['type'] ?? 'N/A') ?></td>
                    <td><span class="badge bg-info"><?= esc($item['quantity']) ?></span></td>
                    <td><?= $item['expiry_date'] ? esc($item['expiry_date']) : 'N/A' ?></td>
                    <td><?= isset($branches[$item['branch_id']]) ? esc($branches[$item['branch_id']]) : 'N/A' ?></td>
                    <td>
                      <a href="<?= site_url('inventory/edit-stock/' . $item['id']) ?>" class="btn btn-sm btn-info text-white">
                        <i class="bi bi-pencil"></i> Restock
                      </a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    <?php endif; ?>

  <?php endif; ?>
</div>

<style>
  .container {
    max-width: 1200px;
  }

  .card {
    border-radius: 12px;
  }

  .card-header {
    font-weight: 600;
  }

  .table th, .table td {
    vertical-align: middle;
  }

  .table thead th {
    text-transform: uppercase;
    font-size: 13px;
    letter-spacing: 0.5px;
    font-weight: 600;
  }

  .page-header h1 {
    margin-bottom: 0.25rem;
  }

  .badge {
    font-size: 0.85em;
    padding: 0.5em 0.75em;
  }

  .btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
  }
</style>

<?= $this->endSection() ?>
