<?= $this->extend('Layout') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <h2 class="mb-4">Supplier Invoices</h2>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>

    <?php if ($errors = session()->getFlashdata('errors')): ?>
        <div class="alert alert-warning">
            <ul class="mb-0">
                <?php foreach ($errors as $message): ?>
                    <li><?= esc($message) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <div class="col-lg-5">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light border-0 fw-semibold">Submit Invoice</div>
                <div class="card-body">
                    <form action="<?= base_url('supplier/invoices') ?>" method="post">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label for="purchase_order_id" class="form-label">Purchase Order</label>
                            <select name="purchase_order_id" id="purchase_order_id" class="form-select" required>
                                <option value="" disabled selected>Select purchase order</option>
                                <?php foreach ($orders as $order): ?>
                                    <option value="<?= esc($order['id']) ?>" <?= old('purchase_order_id') == $order['id'] ? 'selected' : '' ?>>
                                        #<?= esc($order['id']) ?> &mdash; <?= esc($order['item_name']) ?> (<?= esc(ucwords(str_replace('_', ' ', $order['status'] ?? 'pending'))) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="amount" class="form-label">Invoice Amount</label>
                                <input type="number" step="0.01" min="0.01" class="form-control" id="amount" name="amount" value="<?= esc(old('amount')) ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="reference_no" class="form-label">Reference No.</label>
                                <input type="text" class="form-control" id="reference_no" name="reference_no" value="<?= esc(old('reference_no')) ?>" placeholder="e.g. INV-2025-001">
                            </div>
                        </div>
                        <div class="mb-3 mt-3">
                            <label for="remarks" class="form-label">Remarks</label>
                            <textarea name="remarks" id="remarks" rows="3" class="form-control" placeholder="Optional notes for finance."><?= esc(old('remarks')) ?></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit Invoice</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light border-0 fw-semibold">Recent Invoices</div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0">Reference</th>
                                    <th class="border-0">PO #</th>
                                    <th class="border-0">Amount</th>
                                    <th class="border-0">Status</th>
                                    <th class="border-0">Submitted</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($invoices)): ?>
                                    <?php foreach ($invoices as $invoice): ?>
                                        <?php
                                        $status = $invoice['status'] ?? 'submitted';
                                        $badgeClass = 'badge bg-secondary';
                                        switch ($status) {
                                            case 'submitted':
                                                $badgeClass = 'badge bg-info';
                                                break;
                                            case 'reviewing':
                                                $badgeClass = 'badge bg-warning text-dark';
                                                break;
                                            case 'approved':
                                                $badgeClass = 'badge bg-success';
                                                break;
                                            case 'paid':
                                                $badgeClass = 'badge bg-primary';
                                                break;
                                            case 'rejected':
                                                $badgeClass = 'badge bg-danger';
                                                break;
                                        }
                                        ?>
                                        <tr>
                                            <td class="border-0"><?= esc($invoice['reference_no'] ?? 'N/A') ?></td>
                                            <td class="border-0">#<?= esc($invoice['purchase_order_id'] ?? '—') ?></td>
                                            <td class="border-0">₱<?= number_format((float) ($invoice['amount'] ?? 0), 2) ?></td>
                                            <td class="border-0"><span class="<?= $badgeClass ?>"><?= esc(ucwords(str_replace('_', ' ', $status))) ?></span></td>
                                            <td class="border-0 small">
                                                <?= !empty($invoice['submitted_at']) ? esc(date('M d, Y H:i', strtotime($invoice['submitted_at']))) : '—' ?><br>
                                                <?php if (!empty($invoice['processed_at'])): ?>
                                                    <span class="text-muted">Processed <?= esc(date('M d, Y H:i', strtotime($invoice['processed_at']))) ?></span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">No invoices submitted yet.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
