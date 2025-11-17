<?= $this->extend('Layout') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <h2 class="mb-4">Request Stock Item</h2>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Create New Stock Request</h5>
        </div>
        <div class="card-body">
            <form action="<?= base_url('Admin/storeStockRequest') ?>" method="post">
                <?= csrf_field() ?>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="branch_id" class="form-label">Branch Name <span class="text-danger">*</span></label>
                        <select class="form-select" id="branch_id" name="branch_id" required>
                            <option value="" disabled selected>Select Branch</option>
                            <?php if (!empty($branches)): ?>
                                <?php foreach ($branches as $branch): ?>
                                    <option value="<?= esc($branch['id']) ?>"><?= esc($branch['name']) ?> - <?= esc($branch['location']) ?></option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="" disabled>No branches available</option>
                            <?php endif; ?>
                        </select>
                        <?php if (isset($errors['branch_id'])): ?>
                            <div class="text-danger small"><?= $errors['branch_id'] ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="col-md-6">
                        <label for="item_name" class="form-label">Item Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="item_name" name="item_name" value="<?= old('item_name') ?>" placeholder="Enter Item Name" required>
                        <?php if (isset($errors['item_name'])): ?>
                            <div class="text-danger small"><?= $errors['item_name'] ?></div>
                        <?php endif; ?>
                    </div>



                    <div class="col-md-6">
                        <label for="quantity" class="form-label">Quantity <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="quantity" name="quantity" value="<?= old('quantity') ?>" placeholder="0" min="1" required>
                        <?php if (isset($errors['quantity'])): ?>
                            <div class="text-danger small"><?= $errors['quantity'] ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="col-12">
                        <label for="message" class="form-label">Message</label>
                        <textarea class="form-control" id="message" name="message" rows="3" placeholder="Write message..."><?= old('message') ?></textarea>
                        <?php if (isset($errors['message'])): ?>
                            <div class="text-danger small"><?= $errors['message'] ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Submit Request</button>
                        <a href="<?= base_url('dashboard') ?>" class="btn btn-secondary ms-2">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
