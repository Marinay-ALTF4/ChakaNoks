<?= $this->extend('layout') ?>

<?= $this->section('title') ?>Suppliers<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <h2 class="mb-4">Suppliers</h2>

    <div class="table-container bg-white p-4 rounded shadow-sm">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Supplier Name</th>
                    <th>Contact</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>ABC Distributors</td>
                    <td>abc@email.com</td>
                    <td><span class="badge bg-success">Active</span></td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>XYZ Traders</td>
                    <td>xyz@email.com</td>
                    <td><span class="badge bg-secondary">Inactive</span></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
