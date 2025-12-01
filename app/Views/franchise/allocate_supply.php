<?= $this->extend('layout') ?>

<?= $this->section('title') ?>Allocate Supply<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Allocate Supply to Franchise</h3>
        <a href="<?= base_url('franchise/view/' . $franchise['id']) ?>" class="btn btn-outline-secondary">Back</a>
    </div>

    <?php if (!empty($franchise)): ?>
        <div class="page-card">
            <h5 class="mb-3">Franchise: <?= esc($franchise['franchise_name']) ?></h5>
            
            <form action="<?= base_url('franchise/allocate-supply/' . $franchise['id']) ?>" method="POST">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label class="form-label">Item Name</label>
                    <input type="text" class="form-control" name="item_name" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Quantity</label>
                    <input type="number" class="form-control" name="quantity" min="1" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Period</label>
                    <select class="form-select" name="period" required>
                        <option value="">Select Period</option>
                        <option value="weekly">Weekly</option>
                        <option value="monthly">Monthly</option>
                        <option value="quarterly">Quarterly</option>
                        <option value="yearly">Yearly</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Allocate Supply</button>
                <a href="<?= base_url('franchise/view/' . $franchise['id']) ?>" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    <?php else: ?>
        <div class="alert alert-warning">Franchise not found.</div>
    <?php endif; ?>
</div>

<style>
    .page-card {
        background: white;
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        margin-bottom: 20px;
    }
</style>

<?= $this->endSection() ?>
