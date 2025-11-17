<?= $this->extend('Layout') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <h2 class="mb-4">User Management</h2>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="<?= base_url('admin/users/create') ?>" class="btn btn-primary">Add New User</a>

        <form method="get" class="d-flex">
            <select name="branch" class="form-select me-2" style="width: auto;">
                <option value="">All Branches</option>
                <?php foreach ($branches as $branch): ?>
                    <option value="<?= $branch['id'] ?>" <?= $selectedBranch == $branch['id'] ? 'selected' : '' ?>>
                        <?= esc($branch['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-outline-secondary">Filter</button>
        </form>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Branch</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($users)): ?>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?= esc($user['username']) ?></td>
                                    <td><?= esc($user['email']) ?></td>
                                    <td>
                                        <span class="badge bg-<?= $user['role'] === 'admin' ? 'danger' : ($user['role'] === 'branch_manager' ? 'primary' : 'info') ?>">
                                            <?= ucfirst(str_replace('_', ' ', $user['role'])) ?>
                                        </span>
                                    </td>
                                    <td><?= esc($user['branch_name'] ?? 'N/A') ?></td>
                                    <td><?= date('M d, Y', strtotime($user['created_at'])) ?></td>
                                    <td>
                                        <a href="<?= base_url('admin/users/edit/' . $user['id']) ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                                        <a href="<?= base_url('admin/users/delete/' . $user['id']) ?>" class="btn btn-sm btn-outline-danger"
                                           onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted">No users found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
