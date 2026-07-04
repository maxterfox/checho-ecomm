<div class="page-header">
    <h1>Orders</h1>
</div>

<table class="table">
    <thead>
        <tr>
            <th>Order #</th>
            <th>Customer</th>
            <th>Total</th>
            <th>Status</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($orders)): ?>
            <tr><td colspan="5" class="empty">No orders yet.</td></tr>
        <?php else: ?>
            <?php foreach ($orders as $o): ?>
                <tr>
                    <td><?= escape($o['order_number']) ?></td>
                    <td><?= escape($o['user_name'] ?? 'Guest') ?></td>
                    <td><?= formatPrice((float) $o['total']) ?></td>
                    <td><span class="status-badge status-<?= $o['status'] ?>"><?= escape($o['status']) ?></span></td>
                    <td><?= date('M j, Y', strtotime($o['created_at'])) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>
