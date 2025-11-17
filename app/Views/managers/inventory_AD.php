<?= $this->extend('Layout') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <h2 class="mb-4">Inventory Management</h2>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div></div>
        <a href="<?= base_url('Central_AD/addItem') ?>" class="btn btn-primary">+ Add Item</a>
    </div>

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
