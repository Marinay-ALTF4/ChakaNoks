<?= $this->extend('Layout') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <h2 class="mb-4">Inventory Management</h2>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div></div>
        <a href="<?= base_url('Central_AD/addItem') ?>" class="btn btn-primary">+ Add Item</a>
    </div>

    <!-- Summary Statistics -->
    <?php if (isset($stats)): ?>
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Total Items</h6>
                    <h3 class="mb-0 text-primary"><?= esc($stats['totalItems'] ?? 0) ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Total Quantity</h6>
                    <h3 class="mb-0 text-info"><?= number_format($stats['totalQuantity'] ?? 0) ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Available Items</h6>
                    <h3 class="mb-0 text-success"><?= esc($stats['availableItems'] ?? 0) ?></h3>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Inventory Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Inventory Items</h5>
        </div>
        <div class="card-body">
            <?php if (!empty($inventory)): ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th class="text-center">Item ID</th>
                                <th class="text-start">Item Name</th>
                                <th class="text-start">Type</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Expiry Date</th>
                                <th class="text-start">Branch</th>
                                <th class="text-center">Barcode</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $branchModel = new \App\Models\BranchModel();
                            foreach ($inventory as $item): 
                                // Get branch name
                                $branchName = 'N/A';
                                if (!empty($item['branch_id'])) {
                                    $branch = $branchModel->find($item['branch_id']);
                                    $branchName = $branch ? esc($branch['name']) : 'N/A';
                                }
                                
                                // Check if expired first
                                $isExpired = false;
                                $isExpiringSoon = false;
                                if (!empty($item['expiry_date'])) {
                                    $expiryDate = new \DateTime($item['expiry_date']);
                                    $today = new \DateTime();
                                    if ($expiryDate < $today) {
                                        $isExpired = true;
                                    } elseif ($expiryDate->diff($today)->days <= 7) {
                                        $isExpiringSoon = true;
                                    }
                                }
                                
                                // Determine status badge color - prioritize expired status
                                $statusClass = 'bg-secondary';
                                $statusText = ucfirst(str_replace('_', ' ', $item['status'] ?? 'available'));
                                
                                if ($isExpired) {
                                    $statusClass = 'bg-dark';
                                    $statusText = 'Expired';
                                } else {
                                    switch($item['status'] ?? 'available') {
                                        case 'available':
                                            $statusClass = 'bg-success';
                                            break;
                                        case 'low_stock':
                                            $statusClass = 'bg-warning text-dark';
                                            break;
                                        case 'out_of_stock':
                                            $statusClass = 'bg-danger';
                                            break;
                                        case 'damaged':
                                            $statusClass = 'bg-dark';
                                            break;
                                        case 'unavailable':
                                            $statusClass = 'bg-secondary';
                                            break;
                                    }
                                }
                                
                                // Add expiring soon badge if applicable
                                $expiringSoonBadge = '';
                                if ($isExpiringSoon && !$isExpired) {
                                    $expiringSoonBadge = ' <span class="badge bg-warning text-dark">Expiring Soon</span>';
                                }
                            ?>
                                <tr class="<?= ($item['quantity'] ?? 0) <= 5 ? 'table-warning' : '' ?>">
                                    <td class="text-center"><?= esc($item['id']) ?></td>
                                    <td class="text-start"><strong><?= esc($item['item_name']) ?></strong></td>
                                    <td class="text-start"><?= esc($item['type'] ?? 'N/A') ?></td>
                                    <td class="text-center"><span class="badge bg-primary"><?= esc($item['quantity'] ?? 0) ?></span></td>
                                    <td class="text-center">
                                        <span class="badge <?= $statusClass ?>"><?= $statusText ?></span>
                                        <?= $expiringSoonBadge ?>
                                    </td>
                                    <td class="text-center">
                                        <?= $item['expiry_date'] ? esc($item['expiry_date']) : 'N/A' ?>
                                    </td>
                                    <td class="text-start"><?= $branchName ?></td>
                                    <td class="text-center">
                                        <?php if (!empty($item['barcode'])): ?>
                                            <div class="barcode-container">
                                                <svg id="barcode-<?= $item['id'] ?>" class="barcode-svg"></svg>
                                                <small class="text-muted"><?= esc($item['barcode']) ?></small>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-muted">No barcode</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <a href="<?= base_url('Central_AD/editItem/'.$item['id']) ?>" class="btn btn-sm btn-primary">Edit</a>
                                        <a href="<?= base_url('Central_AD/deleteItem/'.$item['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No inventory items found</h5>
                    <p class="text-muted">Start by adding your first item.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"></script>
<script>
// Generate barcodes for all items
document.addEventListener('DOMContentLoaded', function() {
    <?php if (!empty($inventory) && is_array($inventory)): ?>
        <?php foreach ($inventory as $item): ?>
            <?php if (!empty($item['barcode'])): ?>
                JsBarcode("#barcode-<?= $item['id'] ?>", "<?= esc($item['barcode']) ?>", {
                    format: "CODE128",
                    width: 2,
                    height: 50,
                    displayValue: false,
                    margin: 0,
                    background: "#ffffff",
                    lineColor: "#000000"
                });
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>
});
</script>

<style>
.barcode-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 4px;
}

.barcode-svg {
    width: 130px;
    height: 50px;
}

.table td, .table th {
    vertical-align: middle;
}
</style>

<?= $this->endSection() ?>
