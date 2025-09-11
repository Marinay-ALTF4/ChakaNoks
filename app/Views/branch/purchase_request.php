<h2>Create Purchase Request</h2>
<?php if (session()->getFlashdata('success')): ?>
<p style="color:green"><?= session()->getFlashdata('success') ?></p>
<?php endif; ?>
<form method="post">
  <input type="text" name="item_name" placeholder="Item Name" required>
  <input type="number" name="quantity" placeholder="Quantity" required>
  <button type="submit">Submit Request</button>
</form>
<a href="<?= site_url('branch/dashboard') ?>">⬅ Back</a>
