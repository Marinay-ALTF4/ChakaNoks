<?= $this->extend('Layout') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <h2 class="mb-4">Create New User</h2>

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <?php if (session()->getFlashdata('errors')): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url('admin/users/store') ?>" method="post">
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="username" class="form-label">Username *</label>
                            <input type="text" class="form-control" id="username" name="username"
                                   value="<?= old('username') ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" class="form-control" id="email" name="email"
                                   value="<?= old('email') ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password *</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                            <div class="form-text">Minimum 6 characters</div>
                        </div>

                        <div class="mb-3">
                            <label for="role" class="form-label">Role *</label>
                            <select class="form-select" id="role" name="role" required>
                                <option value="">Select Role</option>
                                <option value="admin" <?= old('role') === 'admin' ? 'selected' : '' ?>>Admin</option>
                                <option value="branch_manager" <?= old('role') === 'branch_manager' ? 'selected' : '' ?>>Branch Manager</option>
                                <option value="inventory" <?= old('role') === 'inventory' ? 'selected' : '' ?>>Inventory Staff</option>
                                <option value="supplier" <?= old('role') === 'supplier' ? 'selected' : '' ?>>Supplier</option>
                                <option value="logistics_coordinator" <?= old('role') === 'logistics_coordinator' ? 'selected' : '' ?>>Logistics Coordinator</option>
                            </select>
                        </div>

                        <div class="mb-3" id="branch_field">
                            <label for="branch_id" class="form-label">Branch</label>
                            <select class="form-select" id="branch_id" name="branch_id">
                                <option value="">Select Branch (Optional)</option>
                                <?php foreach ($branches as $branch): ?>
                                    <option value="<?= $branch['id'] ?>" <?= old('branch_id') == $branch['id'] ? 'selected' : '' ?>>
                                        <?= esc($branch['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="form-text">Required for Branch Manager role</div>
                        </div>

                        <div class="mb-3" id="supplier_field" style="display: none;">
                            <label for="supplier_id" class="form-label">Supplier *</label>
                            <select class="form-select" id="supplier_id" name="supplier_id">
                                <option value="">Select Supplier</option>
                                <?php if (isset($suppliers)): ?>
                                    <?php foreach ($suppliers as $supplier): ?>
                                        <option value="<?= $supplier['id'] ?>" <?= old('supplier_id') == $supplier['id'] ? 'selected' : '' ?>>
                                            <?= esc($supplier['supplier_name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <div class="form-text">Required for Supplier role</div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Create User</button>
                            <a href="<?= base_url('admin/users') ?>" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('role').addEventListener('change', function() {
    const branchField = document.getElementById('branch_field');
    const branchSelect = document.getElementById('branch_id');
    const branchLabel = branchSelect.previousElementSibling;
    const supplierField = document.getElementById('supplier_field');
    const supplierSelect = document.getElementById('supplier_id');

    if (this.value === 'branch_manager') {
        branchField.style.display = 'block';
        branchSelect.required = true;
        branchLabel.innerHTML = 'Branch *';
        supplierField.style.display = 'none';
        supplierSelect.required = false;
    } else if (this.value === 'supplier') {
        branchField.style.display = 'none';
        branchSelect.required = false;
        supplierField.style.display = 'block';
        supplierSelect.required = true;
    } else {
        branchField.style.display = 'block';
        branchSelect.required = false;
        branchLabel.innerHTML = 'Branch';
        supplierField.style.display = 'none';
        supplierSelect.required = false;
    }
});

// Trigger on page load if role is pre-selected
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('role').dispatchEvent(new Event('change'));
});
</script>

<?= $this->endSection() ?>
