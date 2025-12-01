<?= $this->extend('layout') ?>

<?= $this->section('title') ?>Franchise Details<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Franchise Details</h3>
        <a href="<?= base_url('franchise/index') ?>" class="btn btn-outline-secondary">Back to List</a>
    </div>

    <?php if (!empty($franchise)): ?>
        <div class="row g-4">
            <div class="col-md-6">
                <div class="page-card">
                    <h5 class="mb-3">Franchise Information</h5>
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%">Franchise Name:</th>
                            <td><?= esc($franchise['franchise_name']) ?></td>
                        </tr>
                        <tr>
                            <th>Owner:</th>
                            <td><?= esc($franchise['owner']) ?></td>
                        </tr>
                        <tr>
                            <th>Location:</th>
                            <td><?= esc($franchise['location']) ?></td>
                        </tr>
                        <tr>
                            <th>Contact:</th>
                            <td><?= esc($franchise['contact']) ?></td>
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td>
                                <span class="badge <?= ($franchise['status'] === 'active') ? 'bg-success' : (($franchise['status'] === 'pending') ? 'bg-warning' : 'bg-secondary') ?>">
                                    <?= esc(ucfirst($franchise['status'])) ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Created:</th>
                            <td><?= $franchise['created_at'] ? date('M d, Y H:i', strtotime($franchise['created_at'])) : 'N/A' ?></td>
                        </tr>
                    </table>
                    <div class="mt-3">
                        <a href="<?= base_url('franchise/allocate-supply/' . $franchise['id']) ?>" class="btn btn-primary">Allocate Supply</a>
                        <a href="<?= base_url('franchise/calculate-royalty/' . $franchise['id']) ?>" class="btn btn-info">Calculate Royalty</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="page-card">
                    <h5 class="mb-3">Supply Allocations</h5>
                    <?php if (!empty($allocations) && is_array($allocations)): ?>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Quantity</th>
                                        <th>Period</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($allocations as $allocation): ?>
                                        <tr>
                                            <td><?= esc($allocation['item_name']) ?></td>
                                            <td><?= esc($allocation['allocated_quantity']) ?></td>
                                            <td><?= esc($allocation['period']) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">No supply allocations yet.</p>
                    <?php endif; ?>
                </div>
            </div>
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
