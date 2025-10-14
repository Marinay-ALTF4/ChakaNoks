<?= $this->extend('Layout') ?>

<?= $this->section('content') ?>

<div class="page-header mb-3">
  <h1 class="h4">Stock List</h1>
  <span>Welcome, <?= esc(session()->get('username')) ?>!</span>
</div>

<div class="content">
  <div class="card">
    <p>Here you will see the list of all inventory items (branch-based).</p>

    <div class="table-responsive">
      <table class="table table-striped">
        <thead>
          <tr>
            <th>Item ID</th>
            <th>Item Name</th>
            <th>Quantity</th>
            <th>Last Update</th>
            <th>Expiry Date</th>
            <th>Branch</th>
            <th>Barcode</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>1</td>
            <td>Whole Chicken</td>
            <td>50</td>
            <td>2025-09-07 14:32</td>
            <td>2025-10-15</td>
            <td>Branch A</td>
            <td>
              <div class="barcode-container">
                <svg class="barcode" jsbarcode-value="1-Whole Chicken"></svg>
                <span>Whole Chicken</span>
              </div>
            </td>
            <td><a href="<?= site_url('inventory/edit-stock/1') ?>" class="btn">Edit</a></td>
          </tr>
          <tr>
            <td>2</td>
            <td>Cooking Oink</td>
            <td>30</td>
            <td>2025-09-08 09:10</td>
            <td>2025-11-02</td>
            <td>Branch B</td>
            <td>
              <div class="barcode-container">
                <svg class="barcode" jsbarcode-value="2-Cooking Oink"></svg>
                <span>Cooking Oink</span>
              </div>
            </td>
            <td><a href="<?= site_url('inventory/edit-stock/2') ?>" class="btn">Edit</a></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

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

<style>
  /* Page-specific styles only */
  .content { padding: 16px; }
  .barcode { width: 130px; height: 50px; }
  .barcode-container { display: flex; flex-direction: column; align-items: center; gap: 4px; }
  .btn { display: inline-block; padding: 6px 12px; background: #0d6efd; color: #fff; border-radius: 6px; text-decoration: none; }
  .btn:hover { background: #0b5ed7; }
</style>

<?= $this->endSection() ?>
