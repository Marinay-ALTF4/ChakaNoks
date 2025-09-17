<?= $this->extend('layout') ?>
<?= $this->section('title') ?>Edit Supplier<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Edit Supplier</h2>
       
    </div>

    <form action="<?= base_url('Central_AD/updateSupplier/'.$supplier['id']) ?>" method="post">
        <div class="mb-3">
            <label class="form-label">Supplier Name</label>
            <input type="text" name="supplier_name" class="form-control" value="<?= esc($supplier['supplier_name']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Contact</label>
            <input type="text" name="contact" class="form-control" value="<?= esc($supplier['contact']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="<?= esc($supplier['email']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Address</label>
            <input type="text" name="address" class="form-control" value="<?= esc($supplier['address']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Branch Serve</label>
            <input type="text" name="branch_serve" class="form-control" value="<?= esc($supplier['branch_serve']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select" required>
                <option value="Active" <?= $supplier['status'] === 'Active' ? 'selected' : '' ?>>Active</option>
                <option value="Inactive" <?= $supplier['status'] === 'Inactive' ? 'selected' : '' ?>>Inactive</option>
            </select>
        </div>

        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Update Supplier</button>
            <a href="<?= base_url('Central_AD/suppliers') ?>" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<style>
.form-label {
    font-weight: 500;
}
</style>

<?= $this->endSection() ?>
