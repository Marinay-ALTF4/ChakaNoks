<?= $this->extend('layout') ?>
<?= $this->section('title') ?>Edit Franchise<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Edit Franchise</h2>
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

    <form action="<?= base_url('Central_AD/updateFranchise/'.$franchise['id']) ?>" method="post">
        <?= csrf_field() ?>
        <div class="mb-3">
            <label class="form-label">Franchise Name</label>
            <input type="text" name="franchise_name" class="form-control" value="<?= old('franchise_name', $franchise['franchise_name']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Owner</label>
            <input type="text" name="owner" class="form-control" value="<?= old('owner', $franchise['owner']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Location</label>
            <input type="text" name="location" class="form-control" value="<?= old('location', $franchise['location']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Contact</label>
            <input type="text" name="contact" class="form-control" value="<?= old('contact', $franchise['contact']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select" required>
                <option value="active" <?= old('status', $franchise['status']) === 'active' ? 'selected' : '' ?>>Active</option>
                <option value="inactive" <?= old('status', $franchise['status']) === 'inactive' ? 'selected' : '' ?>>Inactive</option>
            </select>
        </div>

        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Update Franchise</button>
            <a href="<?= base_url('Central_AD/franchising') ?>" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<style>
.form-label {
    font-weight: 500;
}
</style>

<?= $this->endSection() ?>

