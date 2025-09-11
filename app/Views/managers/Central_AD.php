<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Central Admin</title>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<div class="sidebar">
    <h2>Welcome, <?= session()->get('username') ?></h2>
    <a href="<?= base_url('dashboard') ?>" class="active">DASHBOARD</a>
    <a href="<?= base_url('admin/inventory') ?>">INVENTORY</a>
    <a href="#">SUPPLIERS</a>
    <a href="#">ORDERS</a>
    <a href="#">FRANCHISING</a>
    <a href="#">REPORTS</a>
    <a href="#">SETTINGS</a>
    <a href="<?= base_url('logout') ?>" class="logout">Logout</a>
</div>

<div class="main">
   <div class="header">
      <span>Central Admin Dashboard</span>
      <a href="<?= site_url('Central_AD/other-branches') ?>">View Other Branches</a>
   </div>

   <?php if (isset($section) && $section === 'otherBranches'): ?>
       <?= view('managers/other_branches') ?>
   <?php else: ?>
       <div class="top-cards">
           <a href="<?= site_url('Central_AD/branch-overview') ?>" class="card">Branch Overview</a>
           <a href="<?= site_url('Central_AD/branch-performance') ?>" class="card">Branch Performance</a>
           <a href="<?= site_url('Central_AD/user-roles') ?>" class="card">User & Role Management</a>
       </div>

       <div class="report">
           <canvas id="myChart"></canvas>
       </div>
   <?php endif; ?>
</div>
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
            legend: { 
                display: true, 
                position: 'top',
                labels: { color: '#555' }
            }
        },
        scales: {
            x: { ticks: { color: '#555' } },
            y: { beginAtZero: true, ticks: { color: '#555' } }
        }
    }
});
</script>
</body>
</html>

<style>
body {
    margin: 0;
    font-family: Arial, sans-serif;
    background-color: #f4f6f8;
}
.sidebar {
    position: fixed;
    top: 0;
    left: 0;
    width: 220px;
    height: 100%;
    background-color: #c9c9c9;
    padding-top: 20px;
    box-sizing: border-box;
    border-radius: 0 20px 20px 0;
    color: white;
    border: 1px solid black;

}
.sidebar h2 {
    text-align: center;
    font-size: 18px;
    margin-bottom: 40px;
    color: black;
    
}
.sidebar a {
    display: block;
    padding: 14px 15px;
    text-decoration: none;
    color: white;
    margin: 15px 20px;
    background-color: #494949;
    text-align: center;
    border-radius: 20px;
    font-weight: bold;
    transition: 0.3s;
    border: 2px solid black;

}
.sidebar a:hover {
    background-color: #0c0c0c;
    color: white;
}
.sidebar a.active {
    background-color: #000000;
    color: white;
}
.sidebar a.logout {
    background-color: #e74c3c;
    color: white;
    position: absolute;
    bottom: 20px;
    left: 15px;
    right: 15px;
    border-radius: 20px;
}
.sidebar a.logout:hover { 
    background-color: red;
}
.main {
    margin-left: 240px;
    padding: 30px 40px;
    box-sizing: border-box;
    max-width: calc(100% - 240px);
}
.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #b6b6b6;
    padding: 15px 20px;
    font-weight: bold;
    border-radius: 20px;
    color: #000000;
    border: 1px solid black;

}
.header a {
    background-color: #494949;
    color: white;
    padding: 7px 15px;
    text-decoration: none;
    font-size: 14px;
    border-radius: 20px;
    transition: 0.3s;
    border: 1px solid black;

}
.header a:hover {
    background-color: #000000;
}
.top-cards {
    display: flex;
    gap: 30px;
    margin: 30px 0;
}
.card {
    flex: 1;
    background-color: rgba(214, 214, 214, 1);
    padding: 40px 15px;
    text-align: center;
    font-weight: bold;
    border-radius: 20px;
    color: #000000;
    box-shadow: 0 3px 8px rgba(0,0,0,0.1);
    text-decoration: none;
    transition: 0.3s ease-in-out;
    border: 1px solid black;

}
.card:hover {
    background-color: #4949498a;
    color: white;
    transform: translateY(-5px);
    box-shadow: 0 6px 15px rgba(0,0,0,0.2);
}
.report {
    background: linear-gradient(135deg, #f0f4f8, #e6ebef);
    height: 260px;
    display: flex;
    justify-content: center;
    align-items: center;
    border-radius: 20px;
    margin-top: 40px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.08);
    padding: 15px;
    border: 1px solid black;

}
.report canvas {
    width: 100% !important;
    height: auto !important;
    max-height: 180px;
}
</style>
