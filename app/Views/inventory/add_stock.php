<?= $this->extend('Layout') ?>

<?= $this->section('content') ?>
<div class="page-header mb-3 d-flex justify-content-between align-items-center">
  <div>
    <h1 class="h4">Add Stock</h1>
    <span>Welcome, <?= esc(session()->get('username')) ?>!</span>
  </div>

  <!-- ✅ Back Button -->
  <a href="<?= site_url('dashboard') ?>" class="btn btn-secondary">
    <i class="bi bi-arrow-left"></i> Back to Dashboard
  </a>
</div>

<div class="content">
  <div class="form-card mx-auto">
    <h2 class="mb-2">New Stock Entry</h2>
    <p class="text-muted mb-4">Fill in the details below to add stock to the inventory.</p>

    <?php if (session()->getFlashdata('error')): ?>
      <div class="alert alert-danger">
        <?= session()->getFlashdata('error') ?>
      </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('errors')): ?>
      <div class="alert alert-danger">
        <ul class="mb-0">
          <?php foreach (session()->getFlashdata('errors') as $error): ?>
            <li><?= esc($error) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <form method="post" action="<?= site_url('inventory/add-stock') ?>">
      <?= csrf_field() ?>
      <div class="form-group mb-3">
        <label>Item Name <span class="text-danger">*</span></label>
        <input type="text" name="item_name" class="form-control" value="<?= old('item_name') ?>" required>
      </div>

      <div class="form-group mb-3">
        <label>Type</label>
        <input type="text" name="type" class="form-control" value="<?= old('type') ?>" placeholder="e.g., Meat, Vegetable, Spice">
      </div>

      <div class="form-group mb-3">
        <label>Quantity <span class="text-danger">*</span></label>
        <input type="number" name="quantity" class="form-control" value="<?= old('quantity') ?>" min="0" required>
      </div>

      <div class="form-group mb-3">
        <label>Barcode</label>
        <input type="text" name="barcode" class="form-control" value="<?= old('barcode') ?>" placeholder="Leave empty to auto-generate">
        <small class="form-text text-muted">If left empty, a barcode will be auto-generated</small>
      </div>

      <div class="form-group mb-3">
        <label>Expiry Date</label>
        <input type="date" name="expiry_date" class="form-control" value="<?= old('expiry_date') ?>">
      </div>

      <div class="form-group mb-3">
        <label>Branch</label>
        <select name="branch_id" class="form-control">
          <option value="">Select Branch (Optional)</option>
          <?php if (isset($branches) && !empty($branches)): ?>
            <?php foreach ($branches as $branch): ?>
              <option value="<?= esc($branch['id']) ?>" <?= old('branch_id') == $branch['id'] ? 'selected' : '' ?>>
                <?= esc($branch['name']) ?>
              </option>
            <?php endforeach; ?>
          <?php endif; ?>
        </select>
      </div>

      <div class="d-flex justify-content-between">
        <a href="<?= site_url('inventory/stock-list') ?>" class="btn btn-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary">Add Stock</button>
      </div>
    </form>
  </div>
</div>

<!-- ✅ Styles -->
<style>
  .content {
    padding: 20px;
  }

  .form-card {
    background-color: #ffffff;
    padding: 24px;
    border-radius: 10px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    max-width: 640px;
  }

  .form-group label {
    font-weight: 500;
    margin-bottom: 6px;
    display: block;
  }

  .btn {
    border-radius: 6px;
  }

  .btn-primary {
    background: #0d6efd;
    border: none;
  }

  .btn-primary:hover {
    background: #0b5ed7;
  }

  .btn-secondary {
    background: #6c757d;
    border: none;
  }

  .btn-secondary:hover {
    background: #5c636a;
  }
</style>

<?= $this->endSection() ?>
