<?= $this->extend('Layout') ?>
<?= $this->section('content') ?>

<div class="container py-4">

  <!-- Header -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h1 class="h4 fw-bold mb-1"> Stock List</h1>
      <span class="text-muted">Welcome, <?= esc(session()->get('username')) ?>!</span>
    </div>
    <div>
      <a href="<?= site_url('inventory/alerts') ?>" class="btn btn-warning px-3">
        <i class="bi bi-exclamation-triangle me-1"></i> View Alerts
      </a>
    </div>
  </div>

  <!-- Success/Error Messages -->
  <?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <?= session()->getFlashdata('success') ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>

  <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <?= session()->getFlashdata('error') ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>

  <!-- Card -->
  <div class="card shadow-sm border-0">
    <div class="card-body">
      <p class="text-muted mb-4">Here you will see the list of all inventory items (branch-based).</p>

      <div class="table-responsive">
        <table class="table table-hover align-middle text-center">
          <thead class="table-dark">
            <tr>
              <th scope="col">Item ID</th>
              <th scope="col">Item Name</th>
              <th scope="col">Type</th>
              <th scope="col">Quantity</th>
              <th scope="col">Status</th>
              <th scope="col">Expiry Date</th>
              <th scope="col">Branch</th>
              <th scope="col">Barcode</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($items)): ?>
              <tr>
                <td colspan="8" class="text-center text-muted py-4">
                  No items found.
                </td>
              </tr>
            <?php else: ?>
              <?php foreach ($items as $item): ?>
                <?php
                // Get branch name
                $branchName = 'N/A';
                if (!empty($item['branch_id'])) {
                  $branchModel = new \App\Models\BranchModel();
                  $branch = $branchModel->find($item['branch_id']);
                  $branchName = $branch ? esc($branch['name']) : 'N/A';
                }
                
                // Determine status badge color
                $statusClass = 'bg-secondary';
                $statusText = ucfirst(str_replace('_', ' ', $item['status'] ?? 'available'));
                switch($item['status'] ?? 'available') {
                  case 'available':
                    $statusClass = 'bg-success';
                    break;
                  case 'low_stock':
                    $statusClass = 'bg-warning text-dark';
                    break;
                  case 'out_of_stock':
                    $statusClass = 'bg-danger';
                    break;
                  case 'damaged':
                    $statusClass = 'bg-dark';
                    break;
                  case 'unavailable':
                    $statusClass = 'bg-secondary';
                    break;
                }
                
                // Check if expired or expiring soon
                $expiryWarning = '';
                if (!empty($item['expiry_date'])) {
                  $expiryDate = new \DateTime($item['expiry_date']);
                  $today = new \DateTime();
                  if ($expiryDate < $today) {
                    $expiryWarning = ' <span class="badge bg-dark">EXPIRED</span>';
                  } elseif ($expiryDate->diff($today)->days <= 7) {
                    $expiryWarning = ' <span class="badge bg-warning text-dark">Expiring Soon</span>';
                  }
                }
                ?>
                <tr>
                  <td><?= esc($item['id']) ?></td>
                  <td><strong><?= esc($item['item_name']) ?></strong></td>
                  <td><?= esc($item['type'] ?? 'N/A') ?></td>
                  <td><span class="badge bg-primary"><?= esc($item['quantity']) ?></span></td>
                  <td>
                    <span class="badge <?= $statusClass ?>"><?= $statusText ?></span>
                  </td>
                  <td>
                    <?= $item['expiry_date'] ? esc($item['expiry_date']) : 'N/A' ?>
                    <?= $expiryWarning ?>
                  </td>
                  <td><?= $branchName ?></td>
                  <td>
                    <?php if (!empty($item['barcode'])): ?>
                      <div class="barcode-container">
                        <svg class="barcode" jsbarcode-value="<?= esc($item['barcode']) ?>"></svg>
                        <small class="text-muted"><?= esc($item['barcode']) ?></small>
                      </div>
                    <?php else: ?>
                      <span class="text-muted">No barcode</span>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Barcode Script -->
<script>
  window.addEventListener("load", () => {
    document.querySelectorAll(".barcode").forEach(svg => {
      JsBarcode(svg, svg.getAttribute("jsbarcode-value"), {
        format: "CODE128",
        lineColor: "#000000ff",
        width: 1,
        height: 30,
        displayValue: false
      });
    });
  });

</script>
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"></script>

<!-- Custom Styles -->
<style>
  .container {
    max-width: 1100px;
  }

  .card {
    border-radius: 12px;
  }

  .barcode {
    width: 130px;
    height: 50px;
  }

  .barcode-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 4px;
  }

  .table thead th {
    text-transform: uppercase;
    font-size: 13px;
    letter-spacing: 0.5px;
  }

  .table td, .table th {
    vertical-align: middle;
  }

  .btn-primary {
    background-color: #0d6efd;
    border: none;
    border-radius: 6px;
  }

  .btn-primary:hover {
    background-color: #0b5ed7;
  }

  .btn-success {
    background-color: #198754;
    border: none;
    border-radius: 6px;
  }

  .btn-success:hover {
    background-color: #157347;
  }
</style>

<?= $this->endSection() ?>
