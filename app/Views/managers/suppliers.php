<?= $this->extend('layout') ?>
<?= $this->section('title') ?>Suppliers<?= $this->endSection() ?>
<?= $this->section('content') ?>

<h2 class="mb-3">Suppliers</h2>

<div class="mb-2 d-flex justify-content-end">
    <a href="<?= base_url('suppliers/create') ?>" class="btn btn-success btn-sm">+ Add Supplier</a>
</div>

<div class="table-container bg-white p-3 rounded shadow-sm table-responsive">
    <table class="table table-bordered table-striped w-100 text-center table-sm">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Supplier</th>
                <th>Contact</th>
                <th>Email</th>
                <th>Address</th>
                <th>Branch Serve</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($suppliers) && is_array($suppliers)): ?>
                <?php foreach ($suppliers as $supplier): ?>
                <tr>
                    <td><?= esc($supplier['id']) ?></td>
                    <td><?= esc($supplier['supplier_name']) ?></td>
                    <td><?= esc($supplier['contact']) ?></td>
                    <td><?= esc($supplier['email']) ?></td>
                    <td class="address-column"><?= esc($supplier['address']) ?></td>
                    <td><?= esc($supplier['branch_serve']) ?></td>
                    <td>
                        <span class="badge <?= ($supplier['status'] === 'Active') ? 'bg-success' : 'bg-secondary' ?>">
                            <?= esc($supplier['status']) ?>
                        </span>
                    </td>
                    <td>
                        <a href="<?= base_url('suppliers/edit/'.$supplier['id']) ?>" class="btn btn-sm btn-primary">Edit</a>
                        <a href="<?= base_url('suppliers/delete/'.$supplier['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8" class="text-center">No suppliers found</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<style>
.address-column { min-width: 180px; }
.table-container table th, .table-container table td { vertical-align: middle; white-space: nowrap; }
</style>

<?= $this->endSection() ?>
                