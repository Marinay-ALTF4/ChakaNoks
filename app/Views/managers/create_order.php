<?= $this->extend('layout') ?>

<?= $this->section('title') ?>Create Order<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="container mt-4">
    <div class="header-section text-center mb-4">
        <h2 class="fw-bold text-primary"><i class="bi bi-plus-circle"></i> Create New Order</h2>
        <p class="text-muted mb-0">Create a purchase order for inventory items</p>
    </div>

    <div class="row justify-content-center g-4">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm p-4 rounded-4">
                <h5 class="fw-bold text-dark mb-3"><i class="bi bi-pencil-square"></i> Order Details</h5>
                <form method="post" action="<?= base_url('Central_AD/storeOrder') ?>" class="needs-validation" novalidate>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Branch</label>
                        <select name="branch_id" class="form-select" required>
                            <option value="" disabled selected>Select Branch</option>
                            <?php foreach ($branches as $branch): ?>
                                <option value="<?= $branch['id'] ?>"><?= $branch['name'] ?> (<?= $branch['location'] ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Supplier</label>
                        <select name="supplier_id" class="form-select" required>
                            <option value="" disabled selected>Select Supplier</option>
                            <?php foreach ($suppliers as $supplier): ?>
                                <option value="<?= $supplier['id'] ?>"><?= $supplier['supplier_name'] ?> (<?= $supplier['branch_serve'] ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Item Name</label>
                        <input type="text" name="item_name" class="form-control" placeholder="e.g., Whole Chicken" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Quantity</label>
                            <input type="number" name="quantity" class="form-control" placeholder="Enter quantity" required min="1">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Unit Price</label>
                            <input type="number" name="unit_price" class="form-control" placeholder="Enter unit price" required min="0" step="0.01">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Order Date</label>
                        <input type="date" name="order_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Type of Goods</label>
                        <select name="goods_type" id="goodsSelect" class="form-select" required>
                            <option value="" disabled selected>Select goods type</option>
                            <option>Frozen Meat</option>
                            <option>Fresh Produce</option>
                            <option>Packaging Supplies</option>
                            <option>Dry Goods</option>
                            <option>Others</option>
                        </select>
                    </div>

                    <div class="mb-4 d-none" id="otherGoodsField">
                        <label class="form-label fw-semibold">Specify Type of Goods (optional)</label>
                        <input type="text" name="other_goods" class="form-control" placeholder="Enter specific type">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Notes (Optional)</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="Additional notes for this order"></textarea>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary px-4 py-2">
                            <i class="bi bi-send"></i> Create Order
                        </button>
                        <a href="<?= base_url('Central_AD/orders') ?>" class="btn btn-secondary px-4 py-2 ms-2">
                            <i class="bi bi-arrow-left"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-info shadow-sm p-4 rounded-4 bg-light">
                <h5 class="fw-bold text-info mb-3"><i class="bi bi-info-circle"></i> Instructions</h5>
                <ul class="small text-muted mb-3">
                    <li>Select the branch that needs the items</li>
                    <li>Choose the appropriate supplier</li>
                    <li>Fill in all required fields carefully</li>
                    <li>Double-check quantities and prices</li>
                    <li>The order will be pending approval</li>
                </ul>
                <div class="alert alert-warning py-2 small mb-0">
                    <i class="bi bi-lightbulb"></i> Tip: Ensure supplier serves the selected branch.
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.container {
    max-width: 1200px;
}
.card:hover {
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}
.form-label {
    color: #333;
}
.btn-primary {
    background: #007bff;
    border: none;
    border-radius: 8px;
}
.btn-primary:hover {
    background: #0056b3;
}
.btn-secondary {
    border-radius: 8px;
}
.alert {
    border-radius: 8px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const goodsSelect = document.getElementById('goodsSelect');
    const otherField = document.getElementById('otherGoodsField');

    goodsSelect.addEventListener('change', function() {
        if (this.value === 'Others') {
            otherField.classList.remove('d-none');
        } else {
            otherField.classList.add('d-none');
            otherField.querySelector('input').value = '';
        }
    });

    // Form validation
    const form = document.querySelector('.needs-validation');
    form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        form.classList.add('was-validated');
    });
});
</script>

<?= $this->endSection() ?>
