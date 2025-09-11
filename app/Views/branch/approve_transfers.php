<h2>Approve Transfers</h2>
<?php if (session()->getFlashdata('success')): ?>
<p style="color:green"><?= session()->getFlashdata('success') ?></p>
<?php endif; ?>
<table border="1">
  <tr><th>Item</th><th>Qty</th><th>From</th><th>Status</th><th>Action</th></tr>
  <?php foreach ($transfers as $t): ?>
  <tr>
    <td><?= esc($t['item_name']) ?></td>
    <td><?= esc($t['quantity']) ?></td>
    <td><?= esc($t['from_branch']) ?></td>
    <td><?= esc($t['status']) ?></td>
    <td>
      <?php if ($t['status'] == 'pending'): ?>
      <a href="<?= site_url('branch/approve-transfer/'.$t['id']) ?>">Approve</a>
      <?php endif; ?>
    </td>
  </tr>
  <?php endforeach; ?>
</table>
<a href="<?= site_url('branch/dashboard') ?>">⬅ Back</a>
