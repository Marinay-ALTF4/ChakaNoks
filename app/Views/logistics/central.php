<?= $this->extend('Layout') ?>

<?= $this->section('content') ?>

<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">Central Logistics Control</h2>
            <p class="text-muted mb-0">Approve transfer requests and monitor logistics performance.</p>
        </div>
        <a href="<?= base_url('dashboard') ?>" class="btn btn-outline-secondary btn-sm">Back to Dashboard</a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <p class="text-muted mb-1 small">Completed Deliveries</p>
                    <h3 class="fw-bold text-success mb-0"><?= esc($metrics['completedDeliveries'] ?? 0) ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <p class="text-muted mb-1 small">Average Lead Time (hrs)</p>
                    <h3 class="fw-bold text-primary mb-0"><?= esc($metrics['averageLeadTime'] ?? '—') ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <p class="text-muted mb-1 small">In Transit</p>
                    <h3 class="fw-bold text-warning mb-0"><?= esc($metrics['inTransit'] ?? 0) ?></h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Pending Transfer Approvals</h5>
            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#batchApprovalModal">Bulk Approve</button>
        </div>
        <div class="card-body p-0">
            <?php if (! empty($pendingRequests)): ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>From</th>
                                <th>To</th>
                                <th>Requested By</th>
                                <th>Requested At</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($pendingRequests as $request): ?>
                            <tr>
                                <td>#<?= esc($request['id']) ?></td>
                                <td><?= esc($request['from_branch_id']) ?></td>
                                <td><?= esc($request['to_branch_id']) ?></td>
                                <td><?= esc($request['requested_by']) ?></td>
                                <td><?= date('M d, Y H:i', strtotime($request['created_at'])) ?></td>
                                <td class="text-end">
                                    <?php $encoded = htmlspecialchars(json_encode($request), ENT_QUOTES, 'UTF-8'); ?>
                                    <button class="btn btn-sm btn-success" data-request='<?= $encoded ?>' onclick="approveRequest(this)">Approve</button>
                                    <button class="btn btn-sm btn-outline-danger" data-request-id="<?= esc($request['id']) ?>" onclick="rejectRequest(this)">Reject</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="p-4 text-center text-muted">No transfer requests awaiting decision.</div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Bulk approval modal -->
<div class="modal fade" id="batchApprovalModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bulk Approval</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0">Approving all pending transfer requests will move them to scheduling. Continue?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="bulkApprove()">Approve All</button>
            </div>
        </div>
    </div>
</div>

<script>
async function approveRequest(button) {
    const request = JSON.parse(button.getAttribute('data-request'));
    const payload = {
        status: 'approved',
        delivery: {
            source_branch_id: request.from_branch_id,
            destination_branch_id: request.to_branch_id,
            scheduled_at: new Date().toISOString(),
        },
        items: []
    };

    await submitDecision(request.id, payload);
}

async function rejectRequest(button) {
    const id = button.getAttribute('data-request-id');
    const payload = { status: 'rejected' };
    await submitDecision(id, payload);
}

async function bulkApprove() {
    const buttons = document.querySelectorAll('button[data-request]');
    for (const button of buttons) {
        await approveRequest(button);
    }
    location.reload();
}

async function submitDecision(id, payload) {
    try {
        const response = await fetch(`<?= base_url('api/logistics/transfer-requests') ?>/` + id + '/approve', {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(payload)
        });

        const data = await response.json();
        if (!response.ok) {
            alert(data.message || 'Unable to process request');
            return;
        }
        location.reload();
    } catch (error) {
        console.error(error);
        alert('Unexpected error encountered.');
    }
}
</script>

<?= $this->endSection() ?>
