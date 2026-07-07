<div class="page-header">
    <h1>Pedidos</h1>
</div>

<table class="table">
    <thead>
        <tr>
            <th>Pedido #</th>
            <th>Cliente</th>
            <th>Total</th>
            <th>Estado</th>
            <th>Fecha</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($orders)): ?>
            <tr><td colspan="5" class="empty">Aún no hay pedidos.</td></tr>
        <?php else: ?>
            <?php foreach ($orders as $o): ?>
                <tr>
                    <td><?= escape($o['order_number']) ?></td>
                    <td><?= escape($o['user_name'] ?? 'Invitado') ?></td>
                    <td><?= formatPrice((float) $o['total']) ?></td>
                    <td><span class="status-badge status-<?= $o['status'] ?>"><?= escape($o['status']) ?></span></td>
                    <td><?= date('M j, Y', strtotime($o['created_at'])) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>
