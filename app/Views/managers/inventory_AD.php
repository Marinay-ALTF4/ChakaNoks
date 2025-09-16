<?= $this->extend('layout') ?>

<?= $this->section('title') ?>Inventory<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Inventory Management</h3>
        <a href="<?= site_url('Central_AD/request_stock') ?>" class="btn btn-dark">+ Request Stock Item</a>
    </div>

    <!-- Branch Inventory Alert Table -->
    <div class="page-card mb-4">
        <h5>Branch Inventory Alert</h5>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Branch Name</th>
                    <th>Item Name</th>
                    <th>Type</th>
                    <th>Quantity</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Main Branch</td>
                    <td>Chicken</td>
                    <td>Thigh</td>
                    <td>120 kg</td>
                    <td><span class="badge bg-warning text-dark">Low Stock</span></td>
                    <td><a href="#" class="btn btn-sm btn-danger">Refill</a></td>
                </tr>
                <tr>
                    <td>Branch A</td>
                    <td>Chicken</td>
                    <td>Wing</td>
                    <td>20 kg</td>
                    <td><span class="badge bg-warning text-dark">Low Stock</span></td>
                    <td><a href="#" class="btn btn-sm btn-danger">Refill</a></td>
                </tr>
                <tr>
                    <td>Branch B</td>
                    <td>Chicken</td>
                    <td>Breast</td>
                    <td>0 kg</td>
                    <td><span class="badge bg-danger">Out of Stock</span></td>
                    <td><a href="#" class="btn btn-sm btn-danger">Refill</a></td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Central Branch Stock Table -->
    <div class="page-card">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5>Central Branch Available Stock</h5>
            <input type="text" id="searchBox" class="form-control w-25" placeholder="Search item...">
        </div>

        <table class="table table-bordered" id="stockTable">
            <thead class="table-dark">
                <tr>
                    <th>Item Name</th>
                    <th>Type</th>
                    <th>Total Quantity</th>
                    <th>Last Updated</th>
                    <th>Barcode</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>

        <div class="d-flex justify-content-between mt-3">
            <button class="btn btn-success" onclick="printReport()">Print Report</button>
            <button class="btn btn-dark" onclick="openForm()">+ Add/Edit Item</button>
        </div>
    </div>
</div>

<!-- Popup Form -->
<div id="formPopup" class="form-popup">
    <div class="form-container">
        <h4>Add / Edit Item</h4>
        <label>Item Name:</label>
        <input type="text" id="itemName" class="form-control">
        <label>Type:</label>
        <input type="text" id="itemType" class="form-control">
        <label>Total Quantity (kg):</label>
        <input type="number" id="itemQty" class="form-control">
        <button id="confirmBtn" class="btn btn-primary mt-2">Confirm</button>
        <button onclick="closeForm()" class="btn btn-danger mt-2">Cancel</button>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"></script>
<script>
/* ✅ Keep all your previous JavaScript code here for add/update/delete items,
   localStorage saving, barcode generation, search filtering, printing */
</script>

<style>
.page-card {
    background: white;
    border-radius: 15px;
    padding: 20px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    margin-bottom: 20px;
}
.form-popup {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    justify-content: center;
    align-items: center;
}
.form-container {
    background: #fff;
    padding: 20px;
    border-radius: 15px;
    width: 300px;
}
.barcode {
    display: block;
    width: 130px !important;
    height: 50px !important;
    margin: 5px auto;
}
</style>

<?= $this->endSection() ?>
