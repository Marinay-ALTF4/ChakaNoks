<?= $this->extend('Layout') ?>

<?= $this->section('content') ?>
  <div class="page-header mb-3">
    <h1 class="h4">Add Stock</h1>
    <span>Welcome, <?= esc(session()->get('username')) ?>!</span>
  </div>

  <div class="content">
    <div class="form-card">
      <h2>New Stock Entry</h2>
      <p>Fill in the details below to add stock to the inventory.</p>
      <form method="post" action="">
        <div class="form-group">
          <label>Item Name</label>
          <input type="text" name="item_name" required>
        </div>

        <div class="form-group">
          <label>Quantity</label>
          <input type="number" name="quantity" required>
        </div>

        <div class="form-group">
          <label>Expiry Date</label>
          <input type="date" name="expiry_date" required>
        </div>

        <button type="submit">Add Stock</button>
      </form>
    </div>
  </div>

  <style>
    /* Page-specific styles only; using shared layout */
    .content { padding: 20px; }
    .form-card {
      background-color: #ffffff;
      padding: 24px;
      border-radius: 10px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      max-width: 640px;
    }
    .form-card h2 { margin: 0 0 10px 0; }
    .form-group { margin-bottom: 14px; }
    .form-group input { padding: 10px; border: 1px solid #ccc; border-radius: 6px; }
    button { padding: 10px 18px; border-radius: 6px; border: none; background: #0d6efd; color: #fff; }
    button:hover { background: #0b5ed7; }
  </style>

<?= $this->endSection() ?>
