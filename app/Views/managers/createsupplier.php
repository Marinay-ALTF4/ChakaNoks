<?= $this->extend('layout') ?>
<?= $this->section('title') ?>Add Supplier<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="container mt-4">
    <h2 class="mb-4">Add Supplier</h2>

    <form action="<?= base_url('suppliers/store') ?>" method="post">
        <div class="mb-3">
            <label class="form-label">Supplier</label>
            <input type="text" name="supplier_name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Contact</label>
            <input type="text" name="contact" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Address</label>
            <input type="text" name="address" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Branch Serve</label>
            <input type="text" name="branch_serve" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select" required>
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Add Supplier</button>
        <a href="<?= base_url('suppliers') ?>" class="btn btn-secondary">Cancel</a>
    </form>
</div>
<?= $this->endSection() ?>
