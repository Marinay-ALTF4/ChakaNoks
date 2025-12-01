<?= $this->extend('layout') ?>

<?= $this->section('title') ?>Franchise Applications<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Franchise Applications</h3>
        <a href="<?= base_url('franchise/index') ?>" class="btn btn-outline-secondary">View All Franchises</a>
    </div>

    <!-- Success/Error Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= esc(session()->getFlashdata('success')) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= esc(session()->getFlashdata('error')) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

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
                        <th>Date Applied</th>
                        <th style="width: 200px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($applications) && is_array($applications)): ?>
                        <?php foreach ($applications as $application): ?>
                            <tr>
                                <td><?= esc($application['id']) ?></td>
                                <td><?= esc($application['franchise_name']) ?></td>
                                <td><?= esc($application['owner']) ?></td>
                                <td><?= esc($application['location']) ?></td>
                                <td><?= esc($application['contact']) ?></td>
                                <td>
                                    <span class="badge bg-warning"><?= esc(ucfirst($application['status'])) ?></span>
                                </td>
                                <td><?= $application['created_at'] ? date('M d, Y', strtotime($application['created_at'])) : 'N/A' ?></td>
                                <td>
                                    <a href="<?= base_url('franchise/approve/' . $application['id']) ?>" 
                                       class="btn btn-sm btn-success" 
                                       onclick="return confirm('Are you sure you want to approve this franchise application?')">
                                        Approve
                                    </a>
                                    <a href="<?= base_url('franchise/reject/' . $application['id']) ?>" 
                                       class="btn btn-sm btn-danger" 
                                       onclick="return confirm('Are you sure you want to reject this franchise application?')">
                                        Reject
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted">No pending franchise applications</td>
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
