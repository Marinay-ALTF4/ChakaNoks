<?= $this->extend('layout') ?>
<?= $this->section('title') ?><?= $title ?? 'Our Products' ?><?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Our Products</h2>
    <a href="<?= base_url('products/create') ?>" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add New Product
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Product Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Branch</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($products)): ?>
                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td><?= $product['id'] ?></td>
                                <td><?= esc($product['product_name']) ?></td>
                                <td><?= esc($product['category']) ?></td>
                                <td>₱<?= number_format($product['price'], 2) ?></td>
                                <td>
                                    <span class="badge bg-<?= $product['stock'] > 10 ? 'success' : ($product['stock'] > 0 ? 'warning' : 'danger') ?>">
                                        <?= $product['stock'] ?> in stock
                                    </span>
                                </td>
                                <td><?= $product['branch_name'] ?? 'All Branches' ?></td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?= base_url('products/edit/' . $product['id']) ?>" class="btn btn-outline-primary">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <button type="button" class="btn btn-outline-danger" onclick="confirmDelete(<?= $product['id'] ?>)">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center">No products found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this product? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <a href="#" id="confirmDelete" class="btn btn-danger">Delete</a>
            </div>
        </div>
    </div>
</div>

<?= $this->section('scripts') ?>
<script>
function confirmDelete(productId) {
    const deleteUrl = '<?= base_url('products/delete/') ?>' + productId;
    document.getElementById('confirmDelete').href = deleteUrl;
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?>
