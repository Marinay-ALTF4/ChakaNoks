<?= $this->extend('layout') ?>

<?= $this->section('title') ?>Royalty Calculation Result<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Royalty Calculation Result</h3>
        <a href="<?= base_url('franchise/view/' . $franchise['id']) ?>" class="btn btn-outline-secondary">Back</a>
    </div>

    <div class="page-card">
        <h5 class="mb-3">Franchise: <?= esc($franchise['franchise_name']) ?></h5>
        
        <div class="row">
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <th>Sales Amount:</th>
                        <td>₱ <?= number_format($salesAmount ?? 0, 2) ?></td>
                    </tr>
                    <tr>
                        <th>Royalty Rate:</th>
                        <td>5%</td>
                    </tr>
                    <tr class="border-top">
                        <th><h5>Royalty Amount:</h5></th>
                        <td><h5 class="text-primary">₱ <?= number_format($royalty ?? 0, 2) ?></h5></td>
                    </tr>
                </table>
            </div>
        </div>
        
        <div class="mt-3">
            <a href="<?= base_url('franchise/calculate-royalty/' . $franchise['id']) ?>" class="btn btn-primary">Calculate Again</a>
            <a href="<?= base_url('franchise/view/' . $franchise['id']) ?>" class="btn btn-secondary">Back to Franchise</a>
        </div>
    </div>
</div>

<style>
    .page-card {
        background: white;
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        margin-bottom: 20px;
    }
</style>

<?= $this->endSection() ?>
