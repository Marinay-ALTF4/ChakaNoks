<?= $this->extend('layout') ?>

<?= $this->section('title') ?>Supply Allocation Report<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Supply Allocation Report</h3>
        <a href="<?= base_url('franchise/index') ?>" class="btn btn-outline-secondary">Back to Franchises</a>
    </div>

    <div class="page-card">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Franchise Name</th>
                        <th>Item Name</th>
                        <th>Allocated Quantity</th>
                        <th>Period</th>
                        <th>Royalty %</th>
                        <th>Date Allocated</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($allocations) && is_array($allocations)): ?>
                        <?php foreach ($allocations as $allocation): ?>
                            <tr>
                                <td><?= esc($allocation['id']) ?></td>
                                <td><?= esc($allocation['franchise_name'] ?? 'N/A') ?></td>
                                <td><?= esc($allocation['item_name']) ?></td>
                                <td><?= esc($allocation['allocated_quantity']) ?></td>
                                <td><?= esc($allocation['period']) ?></td>
                                <td><?= esc($allocation['royalty_percentage'] ?? '0.00') ?>%</td>
                                <td><?= $allocation['created_at'] ? date('M d, Y', strtotime($allocation['created_at'])) : 'N/A' ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted">No supply allocations found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
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
