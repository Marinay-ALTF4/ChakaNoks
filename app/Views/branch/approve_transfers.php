<div class="tips-box">
  <h3>ℹ️ Tips</h3>
  <ul>
    <li>Review the transfer details carefully before approving.</li>
    <li>Only approve transfers with <strong>pending</strong> status.</li>
    <li>Check the source branch quantity to ensure availability.</li>
    <li>Once approved, the transfer cannot be undone.</li>
  </ul>
</div>

<h2>Approve Transfers</h2>

<?php if (session()->getFlashdata('success')): ?>
<p class="success-msg"><?= session()->getFlashdata('success') ?></p>
<?php endif; ?>

<table class="transfers-table">
  <thead>
    <tr>
      <th>Item</th>
      <th>Qty</th>
      <th>From</th>
      <th>Status</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($transfers as $t): ?>
    <tr>
      <td><?= esc($t['item_name']) ?></td>
      <td><?= esc($t['quantity']) ?></td>
      <td><?= esc($t['from_branch']) ?></td>
      <td><?= esc($t['status']) ?></td>
      <td>
        <?php if ($t['status'] == 'pending'): ?>
        <a class="approve-btn" href="<?= site_url('branch/approve-transfer/'.$t['id']) ?>">Approve</a>
        <?php endif; ?>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
      <div class="sidebar">
          <h2>BRANCH MANAGER</h2>
          <a href="<?= site_url('branch/dashboard') ?>">🏠 Dashboard</a>
          <a href="<?= site_url('branch/monitor-inventory') ?>">📦 Monitor Inventory</a>
          <a href="<?= site_url('branch/purchase-request') ?>">🛒 Create Purchase Request</a>
          <a href="<?= site_url('branch/approve-transfers') ?>">🔄 Approve Transfers</a>
          <a href="<?= site_url('logout') ?>" class="logout">🚪 Logout</a>
      </div>

<style>
body {
  font-family:Arial, sans-serif;
  background: #f4f6f8;
  color: #333;
  padding: 20px;
  display: flex;
  flex-direction: column;
  align-items: center;
}

h2 {
  text-align: center;
  margin-bottom: 20px;
  font-size: 22px;
  color: #222;
}

.success-msg {
  color: #28a745;
  background: #e9f9ee;
  border: 1px solid #c3e6cb;
  padding: 10px 12px;
  border-radius: 6px;
  margin-bottom: 15px;
  text-align: center;
}

.tips-box {
  width: 50%;
  max-width: 380px;
  background: #f9f9f9;
  padding: 15px 20px;
  border: 1px solid #ddd;
  border-radius: 8px;
  margin-bottom: 20px;
  font-size: 14px;
  line-height: 1.5;
}

.tips-box h3 {
  margin-top: 0;
  margin-bottom: 10px;
  color: #007bff;
}

.tips-box ul {
  margin: 0;
  padding-left: 18px;
}

.transfers-table {
  width: 100%;
  max-width: 900px;
  border-collapse: collapse;
  margin-bottom: 20px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}

.transfers-table th,
.transfers-table td {
  border: 1px solid #ccc;
  padding: 10px 12px;
  text-align: center;
}

.transfers-table th {
  background: #007bff;
  color: #fff;
  border: 1px solid black;
}

.approve-btn {
  padding: 5px 12px;
  background: #28a745;
  color: #fff;
  border-radius: 6px;
  text-decoration: none;
  font-weight: bold;
  transition: background 0.2s ease;
}

.approve-btn:hover {
  background: #218838;
}

.sidebar {
  position:fixed;
  left:0;
  top:0;
  width:220px;
  height:100%;
  background:#333;
  color:#fff;
  padding:20px;
  box-sizing:border-box;
  display:flex;
  flex-direction:column;
}
.sidebar h2 {
  text-align:center;
  margin:0 0 18px 0;
  font-size:14px;
  letter-spacing:0.6px;
  color: white;
}
.sidebar a {
  display:block;
  width:100%;
  box-sizing:border-box;
  padding:10px 14px;
  margin:6px 0;
  color:#fff;
  text-decoration:none;
  background:#444;
  border-radius:8px;
  transition: transform .12s ease, background .12s ease;
  text-align:left;
  border: 1px solid black;
}
.sidebar a:hover {
  background:#222;
  transform: translateY(-2px);
  text-decoration:none;
}
.sidebar a.logout {
  background:#e74c3c;
  font-weight:600;
  padding:12px 14px;
  margin:12px 0 0 0;
  margin-top:auto;
  text-align:center;
}
.sidebar a.logout:hover {
  background:#c0392b;
  transform: translateY(-3px);
}

@media (max-width: 768px) {
  .transfers-table {
    width: 100%;
    font-size: 14px;
  }

  .back-btn {
    padding: 8px 30px;
  }
}
</style>
