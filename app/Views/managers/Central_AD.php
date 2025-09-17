<?= $this->extend('layout') ?>

<?= $this->section('title') ?>Central Admin Dashboard<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="container mt-4">
    <h3>Central Admin Dashboard</h3>

    <!-- Top Cards -->
    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <a href="<?= site_url('Central_AD/branch-overview') ?>" class="dashboard-card">
                Branch Overview
            </a>
        </div>
        <div class="col-md-4">
            <a href="<?= site_url('Central_AD/branch-performance') ?>" class="dashboard-card">
                Branch Performance
            </a>
        </div>
        <div class="col-md-4">
            <a href="<?= site_url('Central_AD/user-roles') ?>" class="dashboard-card">
                User & Role Management
            </a>
        </div>
    </div>

    <!-- Chart Section -->
    <div class="page-card">
        <h5>📊 Branch Sales</h5>
        <canvas id="myChart"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('myChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Branch A', 'Branch B', 'Branch C', 'Branch D'],
        datasets: [{
            label: 'Sales',
            data: [120, 90, 150, 80],
            backgroundColor: ['#4e79a7', '#f28e2b', '#e15759', '#76b7b2'],
            borderRadius: 6
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: true, position: 'top', labels: { color: '#555' } }
        },
        scales: {
            x: { ticks: { color: '#555' } },
            y: { beginAtZero: true, ticks: { color: '#555' } }
        }
    }
});
</script>

<style>
.dashboard-card {
    display: block;
    background-color: #d6d6d6;
    padding: 30px;
    text-align: center;
    font-weight: bold;
    border-radius: 15px;
    text-decoration: none;
    color: #000;
    border: 1px solid black;
    transition: 0.3s;
}
.dashboard-card:hover {
    background-color: #494949;
    color: white;
    transform: translateY(-3px);
    box-shadow: 0 6px 14px rgba(0,0,0,0.12);
}
.page-card {
    background: white;
    border-radius: 15px;
    padding: 20px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    margin-bottom: 20px;
}
</style>

<?= $this->endSection() ?>
