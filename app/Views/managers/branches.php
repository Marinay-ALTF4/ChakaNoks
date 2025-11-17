<?= $this->extend('Layout') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <h2 class="mb-4">Branches Management</h2>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <p class="mb-0">Manage all branches in the system.</p>
        <a href="<?= base_url('Central_AD/branches/add') ?>" class="btn btn-primary">Add New Branch</a>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">All Branches</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Location</th>
                            <th>Manager</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($branches)): ?>
                            <?php foreach ($branches as $branch): ?>
                                <tr>
                                    <td><?= esc($branch['id']) ?></td>
                                    <td><?= esc($branch['name']) ?></td>
                                    <td><?= esc($branch['location']) ?></td>
                                    <td><?= esc($branch['manager_name'] ?? 'N/A') ?></td>
                                    <td>
                                        <span class="badge bg-<?= $branch['status'] === 'active' ? 'success' : 'secondary' ?>">
                                            <?= ucfirst(esc($branch['status'])) ?>
                                        </span>
                                    </td>
                                    <td><?= esc($branch['created_at']) ?></td>
                                    <td>
                                        <a href="<?= base_url('Central_AD/branches/edit/' . $branch['id']) ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                                        <a href="<?= base_url('Central_AD/branches/delete/' . $branch['id']) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this branch?')">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center">No branches found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
