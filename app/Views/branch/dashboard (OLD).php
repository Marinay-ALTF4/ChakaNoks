<?= $this->extend('Layout') ?>

<?= $this->section('content') ?>
    <h1>Welcome, <?= esc(session()->get('username')) ?>!</h1>
    <div class="cards">
        <div class="card">
            <h3>📦 Monitor Inventory</h3>
            <p>Check stock levels and track shortages.</p>
            <a href="<?= site_url('branch/monitor-inventory') ?>">View Inventory</a>
        </div>
        <div class="card">
            <h3>🛒 Purchase Requests</h3>
            <p>Create and submit purchase requests to Admin.</p>
            <a href="<?= site_url('branch/purchase-request') ?>">Create Request</a>
        </div>
        <div class="card">
            <h3>🔄 Intra-Branch Transfers</h3>
            <p>Approve transfer requests between branches.</p>
            <a href="<?= site_url('branch/approve-transfers') ?>">View Transfers</a>
        </div>
    </div>
<?= $this->endSection() ?>
