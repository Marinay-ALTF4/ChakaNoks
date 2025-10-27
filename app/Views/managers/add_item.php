<?= $this->extend('layout') ?>
<?= $this->section('title') ?>Add Item<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Add Item</h2>
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

    <form action="<?= base_url('Central_AD/storeItem') ?>" method="post">
        <div class="mb-3">
            <label class="form-label">Item Name</label>
            <input type="text" name="item_name" class="form-control" value="<?= old('item_name') ?>" required>
        </div>
        
        <div class="mb-3">
            <label class="form-label">Type</label>
            <input type="text" name="type" class="form-control" value="<?= old('type') ?>" required>
        </div>
        
        <div class="mb-3">
            <label class="form-label">Quantity (kg)</label>
            <input type="number" name="quantity" class="form-control" value="<?= old('quantity') ?>" min="1" required>
        </div>
        
        <div class="mb-3">
            <label class="form-label">Barcode (Optional)</label>
            <input type="text" name="barcode" id="barcodeInput" class="form-control" value="<?= old('barcode') ?>" placeholder="Leave empty to auto-generate">
            <small class="form-text text-muted">If left empty, a barcode will be auto-generated</small>
        </div>

        <div class="mb-3" id="barcodePreview" style="display: none;">
            <label class="form-label">Barcode Preview:</label>
            <div class="barcode-preview-container">
                <svg id="barcodePreviewSvg" class="barcode-preview-svg"></svg>
                <small id="barcodePreviewText" class="barcode-preview-text"></small>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Add Item</button>
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
});
</script>

<?= $this->endSection() ?>
