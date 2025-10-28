<?= $this->extend('layout') ?>

<?= $this->section('title') ?>Franchising<?= $this->endSection() ?>

<?= $this->section('content') ?>

<h2 class="mb-3">Franchising</h2>

<div class="mb-2 d-flex justify-content-end">
    <a href="<?= base_url('Central_AD/addFranchise') ?>" class="btn btn-success btn-sm">+ Add Franchise</a>
</div>

<div class="table-container bg-white p-3 rounded shadow-sm table-responsive">
    <table class="table table-bordered table-striped w-100 text-center table-sm">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Franchise Name</th>
                <th>Owner</th>
                <th>Location</th>
                <th>Contact</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($franchises) && is_array($franchises)): ?>
                <?php foreach ($franchises as $franchise): ?>
                <tr>
                    <td><?= esc($franchise['id']) ?></td>
                    <td><?= esc($franchise['franchise_name']) ?></td>
                    <td><?= esc($franchise['owner']) ?></td>
                    <td><?= esc($franchise['location']) ?></td>
                    <td><?= esc($franchise['contact']) ?></td>
                    <td>
                        <span class="badge <?= ($franchise['status'] === 'active') ? 'bg-success' : 'bg-secondary' ?>">
                            <?= esc(ucfirst($franchise['status'])) ?>
                        </span>
                    </td>
                    <td>
                        <a href="<?= base_url('Central_AD/editFranchise/'.$franchise['id']) ?>" class="btn btn-sm btn-primary">Edit</a>
                        <a href="<?= base_url('Central_AD/deleteFranchise/'.$franchise['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="text-center">No franchises found</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>
