<?= $this->extend('layout') ?>
<?= $this->section('title') ?>Edit Item<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Edit Item</h2>
    </div>

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

    <form action="<?= base_url('Central_AD/updateItem/'.$item['id']) ?>" method="post">
        <div class="mb-3">
            <label class="form-label">Item Name</label>
            <input type="text" name="item_name" class="form-control" value="<?= old('item_name', $item['item_name']) ?>" required>
        </div>
        
        <div class="mb-3">
            <label class="form-label">Type</label>
            <input type="text" name="type" class="form-control" value="<?= old('type', $item['type'] ?? '') ?>" required>
        </div>
        
        <div class="mb-3">
            <label class="form-label">Quantity <span class="text-danger">*</span></label>
            <input type="number" name="quantity" id="quantityInput" class="form-control" value="<?= old('quantity', $item['quantity']) ?>" min="0" required>
            <small class="form-text text-muted">Status will auto-update: 0 = Out of Stock, 1-5 = Low Stock, >5 = Available</small>
        </div>
        
        <div class="mb-3">
            <label class="form-label">Barcode</label>
            <input type="text" name="barcode" id="barcodeInput" class="form-control" value="<?= old('barcode', $item['barcode'] ?? '') ?>">
        </div>

        <div class="mb-3" id="barcodePreview" style="display: none;">
            <label class="form-label">Barcode Preview:</label>
            <div class="barcode-preview-container">
                <svg id="barcodePreviewSvg" class="barcode-preview-svg"></svg>
                <small id="barcodePreviewText" class="barcode-preview-text"></small>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Expiry Date</label>
            <input type="date" name="expiry_date" class="form-control" value="<?= old('expiry_date', $item['expiry_date'] ?? '') ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Branch</label>
            <select name="branch_id" class="form-select">
                <option value="">Select Branch (Optional)</option>
                <?php
                $branchModel = new \App\Models\BranchModel();
                $branches = $branchModel->getActiveBranches();
                foreach ($branches as $branch):
                ?>
                    <option value="<?= esc($branch['id']) ?>" <?= (isset($item['branch_id']) && $item['branch_id'] == $branch['id']) || old('branch_id') == $branch['id'] ? 'selected' : '' ?>>
                        <?= esc($branch['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" id="statusSelect" class="form-select">
                <option value="available" <?= old('status', $item['status'] ?? 'available') === 'available' ? 'selected' : '' ?>>Available</option>
                <option value="low_stock" <?= old('status', $item['status'] ?? 'available') === 'low_stock' ? 'selected' : '' ?>>Low Stock</option>
                <option value="out_of_stock" <?= old('status', $item['status'] ?? 'available') === 'out_of_stock' ? 'selected' : '' ?>>Out of Stock</option>
                <option value="damaged" <?= old('status', $item['status'] ?? 'available') === 'damaged' ? 'selected' : '' ?>>Damaged</option>
                <option value="unavailable" <?= old('status', $item['status'] ?? 'available') === 'unavailable' ? 'selected' : '' ?>>Unavailable</option>
            </select>
            <small class="form-text text-muted">Note: Status will auto-update based on quantity. You can manually override by selecting a different status.</small>
        </div>

        <button type="submit" class="btn btn-primary">Update Item</button>
        <a href="<?= base_url('Central_AD/inventory') ?>" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<style>
.form-label {
    font-weight: 500;
}

.barcode-preview-container {
    text-align: center;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    background-color: #f9f9f9;
}

.barcode-preview-svg {
    width: 150px;
    height: 75px;
    border: 1px solid #ccc;
    border-radius: 3px;
    margin-bottom: 5px;
    background: white;
}

.barcode-preview-text {
    display: block;
    font-family: monospace;
    font-size: 12px;
    color: #666;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const barcodeInput = document.getElementById('barcodeInput');
    const barcodePreview = document.getElementById('barcodePreview');
    const barcodePreviewSvg = document.getElementById('barcodePreviewSvg');
    const barcodePreviewText = document.getElementById('barcodePreviewText');
    
    barcodeInput.addEventListener('input', function() {
        const value = this.value.trim();
        
        if (value.length > 0) {
            // Show preview
            barcodePreview.style.display = 'block';
            
            // Generate barcode using JsBarcode
            try {
                JsBarcode(barcodePreviewSvg, value, {
                    format: "CODE128",
                    width: 2,
                    height: 60,
                    displayValue: false,
                    margin: 0,
                    background: "#ffffff",
                    lineColor: "#000000"
                });
                barcodePreviewText.textContent = value;
            } catch (error) {
                console.error('Barcode generation error:', error);
                barcodePreviewText.textContent = 'Invalid barcode format';
            }
        } else {
            // Hide preview
            barcodePreview.style.display = 'none';
        }
    });
    
    // Trigger preview on page load if there's a value
    if (barcodeInput.value.trim().length > 0) {
        barcodeInput.dispatchEvent(new Event('input'));
    }
    
    // Auto-update status based on quantity
    const quantityInput = document.getElementById('quantityInput');
    const statusSelect = document.getElementById('statusSelect');
    let userChangedStatus = false;
    
    // Track if user manually changes status
    statusSelect.addEventListener('change', function() {
        userChangedStatus = true;
    });
    
    // Auto-update status when quantity changes (only if user hasn't manually set it)
    quantityInput.addEventListener('input', function() {
        if (!userChangedStatus) {
            const quantity = parseInt(this.value) || 0;
            if (quantity <= 0) {
                statusSelect.value = 'out_of_stock';
            } else if (quantity <= 5) {
                statusSelect.value = 'low_stock';
            } else {
                statusSelect.value = 'available';
            }
        }
    });
    
    // Reset flag when form is submitted (so it works on next edit)
    document.querySelector('form').addEventListener('submit', function() {
        userChangedStatus = false;
    });
});
</script>

<?= $this->endSection() ?>
