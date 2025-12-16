<?= $this->extend('Layout') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <h2 class="mb-4">New Purchase Request</h2>

    <?php if (empty($selectedBranchId)): ?>
        <div class="alert alert-warning">
            Branch context is not set for your account yet. Please pick a branch before submitting requests.
        </div>
    <?php endif; ?>

    <?php if (!empty($selectedBranchId)): ?>
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <h6 class="text-muted text-uppercase small mb-2">Total Requests</h6>
                        <h3 class="mb-0"><?= esc($requestSummary['total'] ?? 0) ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <h6 class="text-muted text-uppercase small mb-2">Pending</h6>
                        <h3 class="mb-0 text-warning"><?= esc($requestSummary['pending'] ?? 0) ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <h6 class="text-muted text-uppercase small mb-2">Approved</h6>
                        <h3 class="mb-0 text-success"><?= esc($requestSummary['approved'] ?? 0) ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <h6 class="text-muted text-uppercase small mb-2">Rejected</h6>
                        <h3 class="mb-0 text-danger"><?= esc($requestSummary['rejected'] ?? 0) ?></h3>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

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

            <form id="purchaseRequestForm" action="<?= base_url('branch/purchase-request') ?>" method="post">
                <?= csrf_field() ?>
                <?php
                    $branches = $branches ?? [];
                    $branchMap = $branchMap ?? [];
                    $preferredBranchId = $selectedBranchId ?? null;
                    $activeBranchId = old('branch_id', $preferredBranchId);
                    $activeBranchId = ($activeBranchId !== null && $activeBranchId !== '') ? (int) $activeBranchId : null;
                ?>

                <div class="mb-3">
                    <label class="form-label">Branch</label>
                    <select class="form-select" name="branch_id" required>
                        <option value="">Select Branch</option>
                        <?php foreach ($branches as $branch): ?>
                            <?php $branchId = $branch['id'] ?? null; ?>
                            <option value="<?= esc($branchId) ?>" <?= (string) $branchId === (string) $activeBranchId ? 'selected' : '' ?>>
                                <?= esc($branch['name'] ?? ('Branch ' . $branchId)) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (! empty($preferredBranchId)): ?>
                        <small class="text-muted">Defaulting to your assigned branch; adjust if needed.</small>
                    <?php endif; ?>
                </div>

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
                                <input type="text" class="form-control item-input" name="items[0][item_name]" data-datalist-id="supplier-items-0" list="supplier-items-0" placeholder="Select or type item" required disabled>
                                <datalist id="supplier-items-0"></datalist>
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
                        <a href="<?= base_url('dashboard') ?>" class="btn btn-secondary me-2">
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

    <div class="card mt-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Recent Requests</h5>
            <span class="badge bg-light text-dark">Last <?= esc(count($recentRequests ?? [])) ?> record(s)</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Item</th>
                            <th>Supplier</th>
                            <th>Quantity</th>
                            <th>Status</th>
                            <th>Submitted</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($recentRequests)): ?>
                            <?php foreach ($recentRequests as $request): ?>
                                <?php
                                    $status = $request['status'] ?? 'pending';
                                    $badgeClass = match ($status) {
                                        'approved' => 'bg-success',
                                        'rejected' => 'bg-danger',
                                        default => 'bg-warning text-dark',
                                    };
                                ?>
                                <tr>
                                    <td>#PR-<?= esc(str_pad((string) $request['id'], 4, '0', STR_PAD_LEFT)) ?></td>
                                    <td><?= esc($request['item_name']) ?></td>
                                    <td><?= esc($request['supplier_name'] ?? 'N/A') ?></td>
                                    <td><?= esc($request['quantity']) ?> <?= esc($request['unit'] ?? 'unit/s') ?></td>
                                    <?php $statusLabel = ucwords(str_replace('_', ' ', $status)); ?>
                                    <td><span class="badge <?= $badgeClass ?>"><?= esc($statusLabel) ?></span></td>
                                    <td><?= $request['created_at'] ? date('M d, Y H:i', strtotime($request['created_at'])) : 'N/A' ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">No purchase requests found for this branch yet.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
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
    const datalistId = `supplier-items-${index}`;
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
                <input type="text" class="form-control item-input" name="items[${index}][item_name]" data-datalist-id="${datalistId}" list="${datalistId}" placeholder="Select or type item" required disabled>
                <datalist id="${datalistId}"></datalist>
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
    const itemInput = row.querySelector('.item-input');
    const removeBtn = row.querySelector('.remove-item-btn');

    supplierSelect.addEventListener('change', function() {
        loadItemsForSupplier(this.value, itemInput);
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
function loadItemsForSupplier(supplierId, itemInput) {
    if (!itemInput) {
        return;
    }

    const datalistId = itemInput.dataset.datalistId;
    const datalist = datalistId ? document.getElementById(datalistId) : null;

    if (datalist) {
        datalist.innerHTML = '';
    }

    itemInput.value = '';

    if (!supplierId) {
        itemInput.value = '';
        itemInput.disabled = true;
        itemInput.placeholder = 'Select supplier first';
        return;
    }

    fetch(`<?= base_url('branch/get-supplier-items') ?>/${supplierId}`)
        .then(response => response.json())
        .then(data => {
            if (datalist) {
                (data.items || []).forEach(item => {
                    if (!item.item_name) {
                        return;
                    }
                    const option = document.createElement('option');
                    option.value = item.item_name;
                    datalist.appendChild(option);
                });
            }

            itemInput.disabled = false;
            itemInput.placeholder = (data.items && data.items.length > 0)
                ? 'Select or type item'
                : 'Type item name';
        })
        .catch(error => {
            console.error('Error loading items:', error);
            if (datalist) {
                datalist.innerHTML = '';
            }
            itemInput.disabled = false;
            itemInput.placeholder = 'Type item name';
        });
}

// Attach event listeners to existing supplier selects
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.supplier-select').forEach(select => {
        select.addEventListener('change', function() {
            const itemInput = this.closest('.item-row').querySelector('.item-input');
            loadItemsForSupplier(this.value, itemInput);
        });
    });

    document.querySelectorAll('.item-input').forEach(input => {
        if (input.disabled) {
            input.placeholder = 'Select supplier first';
        }
    });

    updateRemoveButtons();
});
</script>

<?= $this->endSection() ?>
