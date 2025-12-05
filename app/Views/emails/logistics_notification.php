<p>Hello <?= esc($user['username'] ?? $user['email'] ?? 'Team Member') ?>,</p>
<p>This is an automated update from the Logistics module.</p>
<ul>
    <li><strong>Delivery:</strong> <?= esc($data['delivery_code'] ?? 'N/A') ?></li>
    <li><strong>Status:</strong> <?= esc($data['status'] ?? 'N/A') ?></li>
    <?php if (! empty($data['scheduled_at'])): ?>
        <li><strong>Scheduled:</strong> <?= date('M d, Y H:i', strtotime($data['scheduled_at'])) ?></li>
    <?php endif; ?>
    <?php if (! empty($data['dispatched_at'])): ?>
        <li><strong>Dispatched:</strong> <?= date('M d, Y H:i', strtotime($data['dispatched_at'])) ?></li>
    <?php endif; ?>
    <?php if (! empty($data['delivered_at'])): ?>
        <li><strong>Delivered:</strong> <?= date('M d, Y H:i', strtotime($data['delivered_at'])) ?></li>
    <?php endif; ?>
</ul>
<p>You can view the full details inside the system.</p>
<p>Regards,<br>ChakaNoks Logistics</p>
