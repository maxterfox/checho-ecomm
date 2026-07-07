<div class="dashboard">
    <div class="dashboard-header">
        <h1>Panel principal</h1>
        <p class="dashboard-greeting">Bienvenido de nuevo, <?= escape(\App\Core\Auth::user()['name'] ?? 'Admin') ?>.</p>
    </div>

    <div class="stat-grid">
        <div class="stat-card">
            <div class="stat-value"><?= $stats['products'] ?></div>
            <div class="stat-label">Total productos</div>
            <div class="stat-sub"><?= $stats['active_products'] ?> activos</div>
        </div>
        <div class="stat-card">
            <div class="stat-value"><?= $stats['categories'] ?></div>
            <div class="stat-label">Categorías</div>
        </div>
        <div class="stat-card">
            <div class="stat-value"><?= $stats['users'] ?></div>
            <div class="stat-label">Usuarios</div>
        </div>
        <div class="stat-card">
            <div class="stat-value"><?= $stats['orders'] ?></div>
            <div class="stat-label">Pedidos</div>
            <?php if ($stats['pending_orders'] > 0): ?>
                <div class="stat-sub stat-warn"><?= $stats['pending_orders'] ?> pendientes</div>
            <?php endif; ?>
        </div>
    </div>

    <div class="dashboard-grid">
        <div class="dashboard-card">
            <div class="dashboard-card-header">
                <h2>Acciones rápidas</h2>
            </div>
            <div class="quick-actions">
                <?php foreach ($modules as $mod): ?>
                    <a href="<?= url('admin/' . $mod['name']) ?>" class="quick-action-btn">
                        <span class="qa-icon"><?= match($mod['name']) {
                            'products' => '&#128717;', 'categories' => '&#128193;',
                            'users' => '&#128101;', 'orders' => '&#128230;',
                            default => '&#9632;'
                        } ?></span>
                        <span class="qa-label">Gestionar <?= ucfirst($mod['name']) ?></span>
                        <?php if (!$mod['can_modify']): ?>
                            <span class="perm-badge view-only">Ver</span>
                        <?php endif; ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <?php if (!empty($recentOrders)): ?>
        <div class="dashboard-card">
            <div class="dashboard-card-header">
                <h2>Pedidos recientes</h2>
                <a href="<?= url('admin/orders') ?>" class="card-link">Ver todos</a>
            </div>
            <table class="table table-compact">
                <thead>
                    <tr><th>#</th><th>Cliente</th><th>Total</th><th>Estado</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($recentOrders as $o): ?>
                        <tr>
                            <td><?= escape($o['order_number']) ?></td>
                            <td><?= escape($o['user_name'] ?? 'Invitado') ?></td>
                            <td><?= formatPrice((float) $o['total']) ?></td>
                            <td><span class="status-badge status-<?= $o['status'] ?>"><?= escape($o['status']) ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>

    <div class="dashboard-card">
        <div class="dashboard-card-header">
            <h2>Actividad reciente</h2>
            <a href="<?= url('admin/activity-logs') ?>" class="card-link">Ver todos</a>
        </div>
        <?php if (!empty($recentLogs)): ?>
            <div class="activity-feed">
                <?php foreach ($recentLogs as $log): ?>
                    <div class="activity-item">
                        <span class="activity-action badge-<?= $log['action'] ?>"><?= escape($log['action']) ?></span>
                        <span class="activity-desc"><?= escape(truncate($log['description'] ?? '', 80)) ?></span>
                        <span class="activity-user"><?= escape($log['user_name'] ?? 'Sistema') ?></span>
                        <span class="activity-time"><?= date('M j, g:ia', strtotime($log['created_at'])) ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="empty">Aún no hay actividad registrada.</p>
        <?php endif; ?>
    </div>
</div>
