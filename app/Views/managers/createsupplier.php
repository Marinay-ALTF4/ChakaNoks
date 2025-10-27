<?= $this->extend('layout') ?>
<?= $this->section('title') ?>Add Supplier<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Add Supplier</h2>
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

    <form action="<?= base_url('Central_AD/storeSupplier') ?>" method="post">
        <div class="mb-3">
            <label class="form-label">Supplier Name</label>
            <input type="text" name="supplier_name" class="form-control" value="<?= old('supplier_name') ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Contact</label>
            <input type="text" name="contact" class="form-control" value="<?= old('contact') ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="<?= old('email') ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Address</label>
            <input type="text" name="address" class="form-control" value="<?= old('address') ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Branch Serve</label>
            <input type="text" name="branch_serve" class="form-control" value="<?= old('branch_serve') ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select" required>
                <option value="Active" <?= old('status') === 'Active' ? 'selected' : '' ?>>Active</option>
                <option value="Inactive" <?= old('status') === 'Inactive' ? 'selected' : '' ?>>Inactive</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Add Supplier</button>
        <a href="<?= base_url('Central_AD/suppliers') ?>" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?= $this->endSection() ?>
