<?= $this->extend('Layout') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <h2 class="mb-4">User Management</h2>

    <div class="d-flex justify-content-end mb-3">
        <form method="get" class="d-flex gap-2">
            <select name="branch" class="form-select" style="width: auto;">
                <option value="">All Branches</option>
                <?php foreach ($branches as $branch): ?>
                    <option value="<?= $branch['id'] ?>" <?= ($selectedBranch ?? '') == $branch['id'] ? 'selected' : '' ?>>
                        <?= esc($branch['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <select name="status" class="form-select" style="width: auto;" onchange="this.form.submit()">
                <option value="active" <?= ($selectedStatus ?? 'active') === 'active' ? 'selected' : '' ?>>Active Users</option>
                <option value="inactive" <?= ($selectedStatus ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive Users</option>
                <option value="all" <?= ($selectedStatus ?? '') === 'all' ? 'selected' : '' ?>>All Users</option>
            </select>
            <button type="submit" class="btn btn-outline-secondary">Apply Filters</button>
        </form>
    </div>

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
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($users)): ?>
                            <?php foreach ($users as $user): ?>
                                <tr class="<?= $user['status'] === 'inactive' ? 'table-secondary' : '' ?>" id="user-row-<?= $user['id'] ?>">
                                    <td class="d-flex align-items-center gap-2">
                                        <?= esc($user['username']) ?>
                                        <?php if (session('user_id') == $user['id']): ?>
                                            <span class="badge bg-success">You</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= esc($user['email']) ?></td>
                                    <td>
                                        <span class="badge bg-<?= $user['role'] === 'admin' ? 'danger' : ($user['role'] === 'branch_manager' ? 'primary' : 'info') ?>">
                                            <?= ucfirst(str_replace('_', ' ', $user['role'])) ?>
                                        </span>
                                    </td>
                                    <td><?= esc($user['branch_name'] ?? 'N/A') ?></td>
                                    <td>
                                        <span class="badge bg-<?= $user['status'] === 'active' ? 'success' : 'secondary' ?>">
                                            <?= ucfirst($user['status']) ?>
                                        </span>
                                    </td>
                                    <td><?= date('M d, Y', strtotime($user['created_at'])) ?></td>
                                    <td class="d-flex gap-1">
                                        <a href="<?= base_url('admin/users/edit/' . $user['id']) ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                                        <?php if (session('user_id') != $user['id']): ?>
                                            <?php if ($user['status'] === 'active'): ?>
                                                <a href="<?= base_url('admin/users/delete/' . $user['id']) ?>" 
                                                   class="btn btn-sm btn-outline-warning deactivate-user"
                                                   data-user-id="<?= $user['id'] ?>"
                                                   onclick="return confirm('Are you sure you want to deactivate this user?')">
                                                    Deactivate
                                                </a>
                                            <?php else: ?>
                                                <a href="<?= base_url('admin/users/restore/' . $user['id']) ?>" 
                                                   class="btn btn-sm btn-outline-success restore-user"
                                                   data-user-id="<?= $user['id'] ?>"
                                                   onclick="return confirm('Are you sure you want to restore this user?')">
                                                    Restore
                                                </a>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <button class="btn btn-sm btn-outline-secondary" disabled title="Cannot modify your own account">
                                                Deactivate
                                            </button>
                                        <?php endif; ?>
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

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Function to update the UI after deactivation/restoration
    function updateUserRow(userId, action) {
        const row = document.getElementById('user-row-' + userId);
        if (!row) return;
        
        const statusBadge = row.querySelector('.badge.bg-success, .badge.bg-secondary');
        const actionButton = row.querySelector('.deactivate-user, .restore-user');
        
        if (action === 'deactivate') {
            // Update status to inactive
            row.classList.add('table-secondary');
            if (statusBadge) {
                statusBadge.className = 'badge bg-secondary';
                statusBadge.textContent = 'Inactive';
            }
            // Change button to Restore
            if (actionButton) {
                actionButton.className = 'btn btn-sm btn-outline-success restore-user';
                actionButton.textContent = 'Restore';
                actionButton.href = actionButton.href.replace('delete', 'restore');
                actionButton.onclick = function() {
                    return confirm('Are you sure you want to restore this user?');
                };
            }
        } else if (action === 'restore') {
            // Update status to active
            row.classList.remove('table-secondary');
            if (statusBadge) {
                statusBadge.className = 'badge bg-success';
                statusBadge.textContent = 'Active';
            }
            // Change button to Deactivate
            if (actionButton) {
                actionButton.className = 'btn btn-sm btn-outline-warning deactivate-user';
                actionButton.textContent = 'Deactivate';
                actionButton.href = actionButton.href.replace('restore', 'delete');
                actionButton.onclick = function() {
                    return confirm('Are you sure you want to deactivate this user?');
                };
            }
        }
    }

    // Handle deactivate/restore clicks with fetch API for better UX
    document.querySelectorAll('.deactivate-user, .restore-user').forEach(button => {
        button.addEventListener('click', async function(e) {
            e.preventDefault();
            const url = this.href;
            const isDeactivating = this.classList.contains('deactivate-user');
            
            if (confirm(isDeactivating ? 'Are you sure you want to deactivate this user?' : 'Are you sure you want to restore this user?')) {
                try {
                    const response = await fetch(url, {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    
                    const result = await response.json();
                    
                    if (result.success) {
                        // Update the UI without page reload
                        const userId = this.dataset.userId;
                        updateUserRow(userId, isDeactivating ? 'deactivate' : 'restore');
                        
                        // Show success message
                        alert(result.message || (isDeactivating ? 'User deactivated successfully.' : 'User restored successfully.'));
                        
                        // If we're on the inactive users page and restored a user, optionally refresh the page
                        if (!isDeactivating && window.location.search.includes('status=inactive')) {
                            window.location.reload();
                        }
                    } else {
                        throw new Error(result.message || 'An error occurred');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('An error occurred while processing your request.');
                }
            }
        });
    });
});
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?>
