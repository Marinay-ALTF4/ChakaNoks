<?= $this->extend('Layout') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <h2 class="mb-4">Add New Branch</h2>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Branch Details</h5>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('Central_AD/branches/store') ?>" method="post">
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="name" class="form-label">Branch Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" value="<?= old('name') ?>" required>
                            <?php if (isset($errors['name'])): ?>
                                <div class="text-danger small"><?= $errors['name'] ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="location" class="form-label">Location <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="location" name="location" value="<?= old('location') ?>" required>
                            <?php if (isset($errors['location'])): ?>
                                <div class="text-danger small"><?= $errors['location'] ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="manager_name" class="form-label">Manager Name</label>
                            <input type="text" class="form-control" id="manager_name" name="manager_name" value="<?= old('manager_name') ?>">
                            <?php if (isset($errors['manager_name'])): ?>
                                <div class="text-danger small"><?= $errors['manager_name'] ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="">Select Status</option>
                                <option value="active" <?= old('status') === 'active' ? 'selected' : '' ?>>Active</option>
                                <option value="inactive" <?= old('status') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                            </select>
                            <?php if (isset($errors['status'])): ?>
                                <div class="text-danger small"><?= $errors['status'] ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="<?= base_url('Central_AD/branches') ?>" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Add Branch</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
