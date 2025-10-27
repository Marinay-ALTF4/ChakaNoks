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
            <tbody>
                <?php if (!empty($inventory) && is_array($inventory)): ?>
                    <?php foreach ($inventory as $item): ?>
                    <tr>
                        <td><?= esc($item['item_name']) ?></td>
                        <td><?= esc($item['type'] ?? 'N/A') ?></td>
                        <td><?= esc($item['quantity']) ?> kg</td>
                        <td><?= esc($item['updated_at']) ?></td>
                        <td>
                            <?php if (!empty($item['barcode'])): ?>
                                <div class="barcode-container">
                                    <svg id="barcode-<?= $item['id'] ?>" class="barcode-svg"></svg>
                                    <small class="barcode-text"><?= esc($item['barcode']) ?></small>
                                </div>
                            <?php else: ?>
                                <span class="text-muted">No barcode</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?= base_url('Central_AD/editItem/'.$item['id']) ?>" class="btn btn-sm btn-primary">Edit</a>
                            <a href="<?= base_url('Central_AD/deleteItem/'.$item['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">No items found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="d-flex justify-content-between mt-3">
            <button class="btn btn-success" onclick="printReport()">Print Report</button>
            <a href="<?= base_url('Central_AD/addItem') ?>" class="btn btn-dark">+ Add Item</a>
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
// Generate barcodes for all items
document.addEventListener('DOMContentLoaded', function() {
    <?php if (!empty($inventory) && is_array($inventory)): ?>
        <?php foreach ($inventory as $item): ?>
            <?php if (!empty($item['barcode'])): ?>
                JsBarcode("#barcode-<?= $item['id'] ?>", "<?= esc($item['barcode']) ?>", {
                    format: "CODE128",
                    width: 2,
                    height: 50,
                    displayValue: false,
                    margin: 0,
                    background: "#ffffff",
                    lineColor: "#000000"
                });
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>
});

// Search functionality
document.getElementById('searchBox').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    const tableRows = document.querySelectorAll('#stockTable tbody tr');
    
    tableRows.forEach(row => {
        const itemName = row.cells[0].textContent.toLowerCase();
        const itemType = row.cells[1].textContent.toLowerCase();
        
        if (itemName.includes(searchTerm) || itemType.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

// Print report function
function printReport() {
    window.print();
}

// Auto-generate barcode function
function generateBarcode(data, elementId) {
    JsBarcode(elementId, data, {
        format: "CODE128",
        width: 2,
        height: 50,
        displayValue: false,
        margin: 0,
        background: "#ffffff",
        lineColor: "#000000"
    });
}
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

.barcode-container {
    text-align: center;
    padding: 5px;
}

.barcode-svg {
    width: 120px;
    height: 50px;
    border: 1px solid #ddd;
    border-radius: 4px;
    margin-bottom: 2px;
    background: white;
}

.barcode-text {
    display: block;
    font-family: monospace;
    font-size: 10px;
    color: #666;
    margin-top: 2px;
}
</style>

<?= $this->endSection() ?>
