<?= $this->extend('layout') ?>
<?= $this->section('title') ?>Edit Product<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h5 class="mb-0">Edit Product</h5>
    </div>
    <div class="card-body">
        <?php if (isset($validation)): ?>
            <div class="alert alert-danger">
                <?= $validation->listErrors() ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('products/update/' . $product['id']) ?>" method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="_method" value="PUT">
            
            <div class="mb-3">
                <label for="product_name" class="form-label">Product Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="product_name" name="product_name" 
                       value="<?= old('product_name', $product['product_name']) ?>" required>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="category" name="category" 
                           list="categoryList" value="<?= old('category', $product['category']) ?>" required>
                    <datalist id="categoryList">
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= esc($category) ?>">
                        <?php endforeach; ?>
                    </datalist>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="branch_id" class="form-label">Branch (Leave empty for all branches)</label>
                    <select class="form-select" id="branch_id" name="branch_id">
                        <option value="">All Branches</option>
                        <?php foreach ($branches as $branch): ?>
                            <option value="<?= $branch['id'] ?>" <?= (old('branch_id', $product['branch_id']) == $branch['id']) ? 'selected' : '' ?>>
                                <?= esc($branch['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="price" class="form-label">Price (₱) <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">₱</span>
                        <input type="number" class="form-control" id="price" name="price" 
                               step="0.01" min="0" value="<?= old('price', $product['price']) ?>" required>
                    </div>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="stock" class="form-label">Current Stock <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" id="stock" name="stock" 
                           min="0" value="<?= old('stock', $product['stock']) ?>" required>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" 
                          rows="3"><?= old('description', $product['description']) ?></textarea>
            </div>
            
            <div class="d-flex justify-content-between">
                <a href="<?= base_url('products') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Cancel
                </a>
                <div>
                    <a href="<?= base_url('products/delete/' . $product['id']) ?>" 
                       class="btn btn-danger me-2" 
                       onclick="return confirm('Are you sure you want to delete this product?')">
                        <i class="fas fa-trash"></i> Delete
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Product
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
