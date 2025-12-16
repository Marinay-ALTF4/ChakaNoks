<?= $this->extend('Layout') ?>

<?= $this->section('content') ?>

<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">Branch Logistics</h2>
            <p class="text-muted mb-0">Request inter-branch transfers and monitor incoming deliveries.</p>
        </div>
        <a href="<?= base_url('dashboard') ?>" class="btn btn-outline-secondary btn-sm">Back to Dashboard</a>
    </div>

    <div class="row g-4">
        <div class="col-lg-5">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0">New Transfer Request</h5>
                </div>
                <div class="card-body">
                    <form id="transferRequestForm">
                        <div class="mb-3">
                            <label class="form-label">From Branch ID</label>
                            <input type="number" name="from_branch_id" class="form-control" value="<?= esc(session()->get('branch_id')) ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">To Branch ID</label>
                            <input type="number" name="to_branch_id" class="form-control" required>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Submit Request</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Incoming Deliveries</h5>
                </div>
                <div class="card-body p-0">
                    <?php if (! empty($incomingDeliveries)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Delivery Code</th>
                                        <th>From Branch</th>
                                        <th>ETA</th>
                                        <th>Status</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($incomingDeliveries as $delivery): ?>
                                    <tr>
                                        <td><?= esc($delivery['delivery_code']) ?></td>
                                        <td><?= esc($delivery['source_branch_id']) ?></td>
                                        <td><?= $delivery['scheduled_at'] ? date('M d, Y H:i', strtotime($delivery['scheduled_at'])) : '—' ?></td>
                                        <td><span class="badge bg-secondary text-uppercase"><?= esc(str_replace('_', ' ', $delivery['status'])) ?></span></td>
                                        <td class="text-end">
                                            <?php if (in_array($delivery['status'], ['delivered', 'acknowledged'], true) === false): ?>
                                                <form action="<?= base_url('logistics/update-delivery-status/' . $delivery['id']) ?>" method="post" class="d-inline">
                                                    <?= csrf_field() ?>
                                                    <input type="hidden" name="status" value="acknowledged">
                                                    <button class="btn btn-sm btn-success">Acknowledge</button>
                                                </form>
                                            <?php else: ?>
                                                <span class="text-muted small">Acknowledged</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="p-4 text-center text-muted">No deliveries on the way.</div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Recent Transfer Requests</h5>
                </div>
                <div class="card-body p-0">
                    <?php if (! empty($transferRequests)): ?>
                        <div class="table-responsive">
                            <table class="table table-sm table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>To Branch</th>
                                        <th>Status</th>
                                        <th>Requested</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($transferRequests as $request): ?>
                                    <tr>
                                        <td>#<?= esc($request['id']) ?></td>
                                        <td><?= esc($request['to_branch_id']) ?></td>
                                        <td><?= esc(ucfirst(str_replace('_', ' ', $request['status']))) ?></td>
                                        <td><?= date('M d, Y', strtotime($request['created_at'])) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="p-3 text-center text-muted">No transfer requests yet.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const transferForm = document.getElementById('transferRequestForm');
if (transferForm) {
    transferForm.addEventListener('submit', async (event) => {
        event.preventDefault();
        const formData = new FormData(transferForm);
        const payload = Object.fromEntries(formData.entries());

        try {
            const response = await fetch("<?= base_url('api/logistics/transfer-requests') ?>", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            });

            const data = await response.json();
            if (response.ok) {
                location.reload();
            } else {
                alert(data.message || 'Failed to create request.');
            }
        } catch (error) {
            console.error(error);
            alert('Unexpected error encountered.');
        }
    });
}
</script>

<?= $this->endSection() ?>
