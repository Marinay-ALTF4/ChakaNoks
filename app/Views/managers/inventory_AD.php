<?= $this->extend('Layout') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <h2 class="mb-4">Inventory Management</h2>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div></div>
        <a href="<?= base_url('Central_AD/addItem') ?>" class="btn btn-primary">+ Add Item</a>
    </div>

    <!-- Summary Statistics -->
    <?php if (isset($stats)): ?>
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Total Items</h6>
                    <h3 class="mb-0 text-primary"><?= esc($stats['totalItems'] ?? 0) ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Total Quantity</h6>
                    <h3 class="mb-0 text-info"><?= number_format($stats['totalQuantity'] ?? 0) ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Low Stock Items</h6>
                    <h3 class="mb-0 text-warning"><?= esc($stats['lowStockItems'] ?? 0) ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Available Items</h6>
                    <h3 class="mb-0 text-success"><?= esc($stats['availableItems'] ?? 0) ?></h3>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Inventory Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Central Branch Available Stock</h5>
        </div>
        <div class="card-body">
            <?php if (!empty($inventory)): ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
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
                            <?php foreach ($inventory as $item): ?>
                                <tr class="<?= ($item['quantity'] ?? 0) <= 5 ? 'table-warning' : '' ?>">
                                    <td>
                                        <strong><?= esc($item['item_name']) ?></strong>
                                        <?php if (($item['quantity'] ?? 0) <= 5): ?>
                                            <span class="badge bg-warning text-dark ms-2">Low Stock</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= esc($item['type'] ?? 'N/A') ?></td>
                                    <td>
                                        <strong><?= esc($item['quantity'] ?? 0) ?></strong>
                                        <?php if (!empty($item['unit'])): ?>
                                            <span class="text-muted"><?= esc($item['unit']) ?></span>
                                        <?php else: ?>
                                            <span class="text-muted">units</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($item['updated_at'])): ?>
                                            <?= date('M d, Y H:i', strtotime($item['updated_at'])) ?>
                                        <?php else: ?>
                                            <span class="text-muted">N/A</span>
                                        <?php endif; ?>
                                    </td>
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
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No inventory items found</h5>
                    <p class="text-muted">Start by adding your first item.</p>
                </div>
            <?php endif; ?>
        </div>
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
</script>

<style>
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
