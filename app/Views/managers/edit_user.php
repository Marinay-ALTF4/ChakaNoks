<?= $this->extend('Layout') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Edit User</h4>
                </div>
                <div class="card-body">
                    <?php if (session()->get('errors')): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach (session('errors') as $error): ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->get('error')): ?>
                        <div class="alert alert-danger">
                            <?= session('error') ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url('admin/users/update/' . $user['id']) ?>" method="post">
                        <?= csrf_field() ?>
                        
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" 
                                   value="<?= old('username', $user['username']) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="<?= old('email', $user['email']) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password (leave blank to keep current)</label>
                            <input type="password" class="form-control" id="password" name="password">
                            <div class="form-text">Leave blank to keep current password</div>
                        </div>

                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-select" id="role" name="role" required>
                                <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                                <option value="branch_manager" <?= $user['role'] === 'branch_manager' ? 'selected' : '' ?>>Branch Manager</option>
                                <option value="inventory" <?= $user['role'] === 'inventory' ? 'selected' : '' ?>>Inventory</option>
                                <option value="supplier" <?= $user['role'] === 'supplier' ? 'selected' : '' ?>>Supplier</option>
                                <option value="logistics_coordinator" <?= $user['role'] === 'logistics_coordinator' ? 'selected' : '' ?>>Logistics Coordinator</option>
                                <option value="franchise_manager" <?= $user['role'] === 'franchise_manager' ? 'selected' : '' ?>>Franchise Manager</option>
                                <option value="system_administrator" <?= $user['role'] === 'system_administrator' ? 'selected' : '' ?>>System Administrator</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="branch_id" class="form-label">Branch (if applicable)</label>
                            <select class="form-select" id="branch_id" name="branch_id">
                                <option value="">-- Select Branch --</option>
                                <?php foreach ($branches as $branch): ?>
                                    <option value="<?= $branch['id'] ?>" 
                                        <?= (isset($user['branch_id']) && $user['branch_id'] == $branch['id']) ? 'selected' : '' ?>>
                                        <?= esc($branch['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="<?= base_url('admin/users') ?>" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Back to Users
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Update User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
