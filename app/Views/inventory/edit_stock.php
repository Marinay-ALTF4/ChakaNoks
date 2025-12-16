<?= $this->extend('Layout') ?>

<?= $this->section('content') ?>

<div class="container mt-4">
  <div class="page-header">
    <h1>Edit Stock Item</h1>
    <span>Welcome, <?= esc(session()->get('username')) ?>!</span>
  </div>

  <?php if (session()->getFlashdata('errors')): ?>
    <div class="alert alert-danger">
      <ul class="mb-0">
        <?php foreach (session()->getFlashdata('errors') as $error): ?>
          <li><?= esc($error) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <div class="card">
    <form method="post" action="<?= site_url('inventory/edit-stock/' . (isset($item['id']) ? $item['id'] : '')) ?>">
      <?= csrf_field() ?>
      <input type="hidden" name="id" value="<?= isset($item['id']) ? esc($item['id']) : '' ?>">
      
      <div class="mb-3">
        <label class="form-label">Item Name <span class="text-danger">*</span></label>
        <input type="text" name="item_name" value="<?= isset($item['item_name']) ? esc($item['item_name']) : old('item_name') ?>" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Type</label>
        <input type="text" name="type" value="<?= isset($item['type']) ? esc($item['type']) : old('type') ?>" class="form-control" placeholder="e.g., Meat, Vegetable, Spice">
      </div>

      <div class="mb-3">
        <label class="form-label">Quantity <span class="text-danger">*</span></label>
        <input type="number" name="quantity" value="<?= isset($item['quantity']) ? esc($item['quantity']) : old('quantity') ?>" class="form-control" min="0" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Barcode</label>
        <input type="text" name="barcode" value="<?= isset($item['barcode']) ? esc($item['barcode']) : old('barcode') ?>" class="form-control">
      </div>

      <div class="mb-3">
        <label class="form-label">Expiry Date</label>
        <input type="date" name="expiry_date" value="<?= isset($item['expiry_date']) ? esc($item['expiry_date']) : old('expiry_date') ?>" class="form-control">
      </div>

      <div class="mb-3">
        <label class="form-label">Branch</label>
        <select name="branch_id" class="form-control">
          <option value="">Select Branch (Optional)</option>
          <?php if (isset($branches) && !empty($branches)): ?>
            <?php foreach ($branches as $branch): ?>
              <option value="<?= esc($branch['id']) ?>" <?= (isset($item['branch_id']) && $item['branch_id'] == $branch['id']) || old('branch_id') == $branch['id'] ? 'selected' : '' ?>>
                <?= esc($branch['name']) ?>
              </option>
            <?php endforeach; ?>
          <?php endif; ?>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-control" id="statusSelect">
          <option value="available" <?= (isset($item['status']) && $item['status'] == 'available') || old('status') == 'available' ? 'selected' : '' ?>>Available</option>
          <option value="low_stock" <?= (isset($item['status']) && $item['status'] == 'low_stock') || old('status') == 'low_stock' ? 'selected' : '' ?>>Low Stock</option>
          <option value="out_of_stock" <?= (isset($item['status']) && $item['status'] == 'out_of_stock') || old('status') == 'out_of_stock' ? 'selected' : '' ?>>Out of Stock</option>
          <option value="unavailable" <?= (isset($item['status']) && $item['status'] == 'unavailable') || old('status') == 'unavailable' ? 'selected' : '' ?>>Unavailable</option>
          <option value="damaged" <?= (isset($item['status']) && $item['status'] == 'damaged') || old('status') == 'damaged' ? 'selected' : '' ?>>Damaged</option>
        </select>
        <small class="form-text text-muted">Note: Status will auto-update based on quantity (0 = Out of Stock, ≤5 = Low Stock, >5 = Available)</small>
      </div>

      <div class="d-flex justify-content-between">
        <a href="<?= site_url('inventory/stock-list') ?>" class="btn btn-secondary">← Back</a>
        <button type="submit" class="btn btn-primary">Update Stock</button>
      </div>
    </form>
  </div>
</div>

</body>
</html>
<style>
    body {
      background-color: #f8f9fa;
      font-family: "Segoe UI", Arial, sans-serif;
    }
    .page-header {
      background-color: #343a40;
      color: #fff;
      padding: 15px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-radius: 6px;
      margin-bottom: 25px;
    }
    .page-header h1 {
      font-size: 20px;
      margin: 0;
    }
    .card {
      background-color: #fff;
      border: none;
      box-shadow: 0 3px 8px rgba(0,0,0,0.1);
      border-radius: 10px;
      padding: 25px;
      max-width: 600px;
      margin: 0 auto;
    }
    .form-label {
      font-weight: 600;
      color: #333;
    }
    input[type="text"], input[type="number"], input[type="date"] {
      border-radius: 8px;
      padding: 10px;
      border: 1px solid #ccc;
    }
    .btn-primary {
      background-color: #198754;
      border: none;
      border-radius: 8px;
      padding: 10px 20px;
      font-weight: 500;
    }
    .btn-primary:hover {
      background-color: #157347;
    }
    .btn-secondary {
      background-color: #6c757d;
      border: none;
      border-radius: 8px;
      padding: 10px 20px;
      font-weight: 500;
    }
    .btn-secondary:hover {
      background-color: #5c636a;
    }
  </style>

<?= $this->endSection() ?>