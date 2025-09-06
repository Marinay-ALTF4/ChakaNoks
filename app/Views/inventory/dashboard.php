<h1>Inventory Dashboard</h1>
<p>Welcome, <?= session()->get('username') ?>!</p>
<ul>
<a href="<?= site_url('inventory/add-stock') ?>">➕ Add Stock</a><br>
<a href="<?= site_url('inventory/edit-stock') ?>">✏️ Edit Stock</a><br>
<a href="<?= site_url('inventory/stock-list') ?>">📋 Stock List</a><br>
<a href="<?= site_url('inventory/alerts') ?>">⚠️ Alerts</a><br>

<a href="<?= base_url('logout') ?>" class="logout">Logout</a>

</ul>
