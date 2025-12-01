<?= $this->extend('layout') ?>

<?= $this->section('title') ?>Calculate Royalty<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Calculate Royalty</h3>
        <a href="<?= base_url('franchise/view/' . $franchise['id']) ?>" class="btn btn-outline-secondary">Back</a>
    </div>

    <?php if (!empty($franchise)): ?>
        <div class="page-card">
            <h5 class="mb-3">Franchise: <?= esc($franchise['franchise_name']) ?></h5>
            
            <form action="<?= base_url('franchise/calculate-royalty/' . $franchise['id']) ?>" method="POST">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label class="form-label">Sales Amount (₱)</label>
                    <input type="number" class="form-control" name="sales_amount" step="0.01" min="0" required>
                    <small class="form-text text-muted">Enter the total sales amount for this franchise</small>
                </div>
                <button type="submit" class="btn btn-primary">Calculate Royalty</button>
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
