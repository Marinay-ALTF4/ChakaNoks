<?= $this->extend('Layout') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <h2 class="mb-4">All Orders</h2>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Item</th>
                            <th>Quantity</th>
                            <th>Branch</th>
                            <th>Total Price</th>
                            <th>Status</th>
                            <th>Order Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($orders)): ?>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td>#<?= esc($order['id']) ?></td>
                                    <td><?= esc($order['item_name']) ?></td>
                                    <td><?= esc($order['quantity']) ?> <?= esc($order['unit'] ?? 'units') ?></td>
                                    <td><?= esc($order['branch_name']) ?></td>
                                    <td>₱<?= number_format($order['total_price'] ?? 0, 2) ?></td>
                                    <td>
                                        <span class="badge 
                                            <?php 
                                            $status = $order['status'];
                                            if ($status === 'pending_supplier') echo 'bg-warning';
                                            elseif ($status === 'confirmed') echo 'bg-info';
                                            elseif ($status === 'preparing') echo 'bg-primary';
                                            elseif ($status === 'ready_for_delivery') echo 'bg-success';
                                            elseif ($status === 'delivered') echo 'bg-secondary';
                                            else echo 'bg-danger';
                                            ?>">
                                            <?= ucfirst(str_replace('_', ' ', $status)) ?>
                                        </span>
                                    </td>
                                    <td><?= date('M d, Y H:i', strtotime($order['order_date'])) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted">No orders found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

