<?= $this->extend('Layout') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <h2 class="mb-4">New Purchase Request</h2>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Create Purchase Request</h5>
        </div>
        <div class="card-body">
            <?php if (session()->getFlashdata('errors')): ?>
                <div class="alert alert-danger">
                    <ul>
                        <?php foreach (session()->getFlashdata('errors') as $error): ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger">
                    <strong>Error:</strong> <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success">
                    <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>

            <form id="purchaseRequestForm" action="<?= base_url('branch/purchase-request') ?>" method="post">
                <?= csrf_field() ?>

                <div id="itemsContainer">
                    <!-- Item Row Template -->
                    <div class="item-row border rounded p-3 mb-3">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Supplier</label>
                                <select class="form-select supplier-select" name="items[0][supplier_id]" required>
                                    <option value="">Select Supplier</option>
                                    <?php if (isset($suppliers) && is_array($suppliers)): ?>
                                        <?php foreach ($suppliers as $supplier): ?>
                                            <option value="<?= esc($supplier['id']) ?>"><?= esc($supplier['supplier_name']) ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Item Name</label>
                                <select class="form-select item-select" name="items[0][item_name]" required disabled>
                                    <option value="">Select supplier first</option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Quantity</label>
                                <input type="number" class="form-control quantity-input" name="items[0][quantity]" min="1" required>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Unit</label>
                                <select class="form-select" name="items[0][unit]" required>
                                    <option value="">Select Unit</option>
                                    <option value="pcs">pcs</option>
                                    <option value="box">box</option>
                                    <option value="kg">kg</option>
                                    <option value="liters">liters</option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Unit Price (₱)</label>
                                <input type="number" class="form-control unit-price-input" name="items[0][unit_price]" step="0.01" min="0.01" required>
                            </div>
                        </div>

                        <div class="row g-3 mt-2">
                            <div class="col-12">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" name="items[0][description]" rows="2" placeholder="Enter description..."></textarea>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-2">
                            <button type="button" class="btn btn-danger btn-sm remove-item-btn" style="display: none;">
                                <i class="fas fa-trash"></i> Remove Item
                            </button>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <button type="button" class="btn btn-success" id="addItemBtn">
                        <i class="fas fa-plus"></i> Add Item
                    </button>

                    <div>
                        <a href="<?= base_url('branch/dashboard') ?>" class="btn btn-secondary me-2">
                            <i class="fas fa-arrow-left"></i> Back to Dashboard
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i> Submit Request
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let itemIndex = 1;

document.getElementById('addItemBtn').addEventListener('click', function() {
    const container = document.getElementById('itemsContainer');
    const newRow = createItemRow(itemIndex);
    container.appendChild(newRow);
    itemIndex++;

    // Show remove buttons if more than one item
    updateRemoveButtons();
});

function createItemRow(index) {
    const row = document.createElement('div');
    row.className = 'item-row border rounded p-3 mb-3';
    row.innerHTML = `
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Supplier</label>
                <select class="form-select supplier-select" name="items[${index}][supplier_id]" required>
                    <option value="">Select Supplier</option>
                    <?php if (isset($suppliers) && is_array($suppliers)): ?>
                        <?php foreach ($suppliers as $supplier): ?>
                            <option value="<?= esc($supplier['id']) ?>"><?= esc($supplier['supplier_name']) ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">Item Name</label>
                <select class="form-select item-select" name="items[${index}][item_name]" required disabled>
                    <option value="">Select supplier first</option>
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label">Quantity</label>
                <input type="number" class="form-control quantity-input" name="items[${index}][quantity]" min="1" required>
            </div>

            <div class="col-md-2">
                <label class="form-label">Unit</label>
                <select class="form-select" name="items[${index}][unit]" required>
                    <option value="">Select Unit</option>
                    <option value="pcs">pcs</option>
                    <option value="box">box</option>
                    <option value="kg">kg</option>
                    <option value="liters">liters</option>
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label">Unit Price (₱)</label>
                <input type="number" class="form-control unit-price-input" name="items[${index}][unit_price]" step="0.01" min="0.01" required>
            </div>
        </div>

        <div class="row g-3 mt-2">
            <div class="col-12">
                <label class="form-label">Description</label>
                <textarea class="form-control" name="items[${index}][description]" rows="2" placeholder="Enter description..."></textarea>
            </div>
        </div>

        <div class="d-flex justify-content-end mt-2">
            <button type="button" class="btn btn-danger btn-sm remove-item-btn">
                <i class="fas fa-trash"></i> Remove Item
            </button>
        </div>
    `;

    // Add event listeners
    const supplierSelect = row.querySelector('.supplier-select');
    const itemSelect = row.querySelector('.item-select');
    const removeBtn = row.querySelector('.remove-item-btn');

    supplierSelect.addEventListener('change', function() {
        loadItemsForSupplier(this.value, itemSelect);
    });

    removeBtn.addEventListener('click', function() {
        row.remove();
        updateRemoveButtons();
    });

    return row;
}

function updateRemoveButtons() {
    const rows = document.querySelectorAll('.item-row');
    const removeBtns = document.querySelectorAll('.remove-item-btn');

    if (rows.length > 1) {
        removeBtns.forEach(btn => btn.style.display = 'block');
    } else {
        removeBtns.forEach(btn => btn.style.display = 'none');
    }
}

// Load items for selected supplier
function loadItemsForSupplier(supplierId, itemSelect) {
    if (!supplierId) {
        itemSelect.innerHTML = '<option value="">Select supplier first</option>';
        itemSelect.disabled = true;
        return;
    }

    fetch(`<?= base_url('branch/get-supplier-items') ?>/${supplierId}`)
        .then(response => response.json())
        .then(data => {
            itemSelect.innerHTML = '<option value="">Select Item</option>';
            if (data.items && data.items.length > 0) {
                data.items.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.item_name;
                    option.textContent = item.item_name;
                    itemSelect.appendChild(option);
                });
            } else {
                itemSelect.innerHTML = '<option value="">No items available</option>';
            }
            itemSelect.disabled = false;
        })
        .catch(error => {
            console.error('Error loading items:', error);
            itemSelect.innerHTML = '<option value="">Error loading items</option>';
        });
}

// Attach event listeners to existing supplier selects
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.supplier-select').forEach(select => {
        select.addEventListener('change', function() {
            const itemSelect = this.closest('.item-row').querySelector('.item-select');
            loadItemsForSupplier(this.value, itemSelect);
        });
    });

    updateRemoveButtons();
});
</script>

<?= $this->endSection() ?>
