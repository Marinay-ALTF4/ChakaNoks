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

    <form method="post" action="">
      <div class="form-group mb-3">
        <label>Item Name</label>
        <input type="text" name="item_name" class="form-control" required>
      </div>

      <div class="form-group mb-3">
        <label>Quantity</label>
        <input type="number" name="quantity" class="form-control" required>
      </div>

      <div class="form-group mb-3">
        <label>Expiry Date</label>
        <input type="date" name="expiry_date" class="form-control" required>
      </div>

      <button type="submit" class="btn btn-primary">Add Stock</button>
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
