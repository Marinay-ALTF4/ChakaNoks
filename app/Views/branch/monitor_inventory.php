<h2>📦 Monitor Inventory</h2>

<div class="inventory-container">
  <table class="inventory-table">
    <thead>
      <tr>
        <th>Item</th>
        <th>Qty</th>
        <th>Barcode</th>
        <th>Expiry</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($inventory as $item): ?>
      <tr>
        <td><?= esc($item['item_name']) ?></td>
        <td><?= esc($item['quantity']) ?></td>
        <td><?= esc($item['barcode']) ?></td>
        <td><?= esc($item['expiry_date']) ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<div class="sidebar">
            <h2>BRANCH MANAGER</h2>
            <a href="<?= site_url('branch/dashboard') ?>">🏠 Dashboard</a>
            <a href="<?= site_url('branch/monitor-inventory') ?>">📦 Monitor Inventory</a>
            <a href="<?= site_url('branch/purchase-request') ?>">🛒 Create Purchase Request</a>
            <a href="<?= site_url('branch/approve-transfers') ?>">🔄 Approve Transfers</a>
            <a href="<?= site_url('logout') ?>" class="logout">🚪 Logout</a>
        </div>
<a href="<?= site_url('branch/dashboard') ?>" class="back-btn">⬅ Back</a>

<style>
body {
  font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Arial, sans-serif;
  background:#f4f6f8;
  color:#333;
}

h2 {
  margin-bottom: 20px;
  font-size: 22px;
  font-weight: bold;
  color:#222;
  margin-left: 13%;
}

.inventory-container {
  background: #fff;
  padding: 20px;
  border-radius: 12px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.1);
  border: 1px solid black;
  margin-left: 13%;
}

.inventory-table {
  width: 100%;
  border-collapse: collapse; 
  font-size: 14px;
}

.inventory-table th, 
.inventory-table td {
  padding: 12px 15px;
  text-align: left;
  border: 1px solid #ddd; 
}

.inventory-table th {
  background: #0456adff;
  color: white;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  font-size: 13px;
  border: 1px solid black;
}

.inventory-table tr:hover {
  background: #f9f9f9;
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
.back-btn {
  display: inline-block;
  margin-top: 20px;
  padding: 10px 14px;
  background: #6c757d;
  color: #fff;
  border-radius: 6px;
  text-decoration: none;
  transition: background 0.2s ease;
  font-weight: bold;
  border: 1px solid black;
}

.back-btn:hover {
  background: #5a6268;
}
</style>
