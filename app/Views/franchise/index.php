<?= $this->extend('layout') ?>

<?= $this->section('title') ?>All Franchises<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>All Franchises</h3>
        <div>
            <a href="<?= base_url('franchise/applications') ?>" class="btn btn-warning">View Applications</a>
            <a href="<?= base_url('Central_AD/addFranchise') ?>" class="btn btn-success">+ Add Franchise</a>
        </div>
    </div>

    <div class="page-card">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Franchise Name</th>
                        <th>Owner</th>
                        <th>Location</th>
                        <th>Contact</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th style="width: 200px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($franchises) && is_array($franchises)): ?>
                        <?php foreach ($franchises as $franchise): ?>
                            <tr>
                                <td><?= esc($franchise['id']) ?></td>
                                <td><?= esc($franchise['franchise_name']) ?></td>
                                <td><?= esc($franchise['owner']) ?></td>
                                <td><?= esc($franchise['location']) ?></td>
                                <td><?= esc($franchise['contact']) ?></td>
                                <td>
                                    <span class="badge <?= ($franchise['status'] === 'active') ? 'bg-success' : (($franchise['status'] === 'pending') ? 'bg-warning' : 'bg-secondary') ?>">
                                        <?= esc(ucfirst($franchise['status'])) ?>
                                    </span>
                                </td>
                                <td><?= $franchise['created_at'] ? date('M d, Y', strtotime($franchise['created_at'])) : 'N/A' ?></td>
                                <td>
                                    <a href="<?= base_url('franchise/view/' . $franchise['id']) ?>" class="btn btn-sm btn-primary">View</a>
                                    <a href="<?= base_url('franchise/allocate-supply/' . $franchise['id']) ?>" class="btn btn-sm btn-info">Allocate Supply</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted">No franchises found</td>
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
