<?php use App\Models\TransferRequestModel; ?>

<?= $this->extend('Layout') ?>
<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">Approve Transfer Requests</h2>
            <p class="text-muted mb-0">Review incoming transfer requests for your branch and approve those that are ready.</p>
        </div>
        <a class="btn btn-outline-secondary" href="<?= base_url('dashboard') ?>">Back to Dashboard</a>
    </div>

    <?php if ($success = session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= esc($success) ?></div>
    <?php endif; ?>
    <?php if ($error = session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= esc($error) ?></div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body">
            <?php if (!empty($requests)): ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>From Branch</th>
                                <th>Requested</th>
                                <th>Status</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($requests as $request): ?>
                                <?php
                                    $status = $request['status'] ?? TransferRequestModel::STATUS_PENDING;
                                    $statusLabel = ucfirst(str_replace('_', ' ', $status));
                                    $badgeClass = 'bg-secondary';
                                    switch ($status) {
                                        case TransferRequestModel::STATUS_PENDING:
                                            $badgeClass = 'bg-warning text-dark';
                                            break;
                                        case TransferRequestModel::STATUS_APPROVED:
                                            $badgeClass = 'bg-success';
                                            break;
                                        case TransferRequestModel::STATUS_REJECTED:
                                            $badgeClass = 'bg-danger';
                                            break;
                                    }
                                ?>
                                <tr>
                                    <td>#<?= esc($request['id']) ?></td>
                                    <td><?= esc($branchMap[$request['from_branch_id']] ?? ('Branch ' . ($request['from_branch_id'] ?? 'N/A'))) ?></td>
                                    <td><?= isset($request['created_at']) ? esc(date('M d, Y H:i', strtotime($request['created_at']))) : '—' ?></td>
                                    <td><span class="badge <?= $badgeClass ?>"><?= esc($statusLabel) ?></span></td>
                                    <td class="text-end">
                                        <?php if ($status === TransferRequestModel::STATUS_PENDING): ?>
                                            <a class="btn btn-sm btn-primary" href="<?= base_url('branch/approve-transfer/' . $request['id']) ?>">Approve</a>
                                        <?php else: ?>
                                            <span class="text-muted small">No action</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-5 text-muted">
                    <h5 class="fw-semibold">No transfer requests yet</h5>
                    <p class="mb-0">Requests for transfers to your branch will appear here when submitted.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
