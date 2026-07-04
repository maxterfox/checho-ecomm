<div class="dashboard">
    <div class="dashboard-header">
        <h1>Dashboard</h1>
        <p class="dashboard-greeting">Welcome back, <?= escape(\App\Core\Auth::user()['name'] ?? 'Admin') ?>.</p>
    </div>

    <div class="stat-grid">
        <div class="stat-card">
            <div class="stat-value"><?= $stats['products'] ?></div>
            <div class="stat-label">Total Products</div>
            <div class="stat-sub"><?= $stats['active_products'] ?> active</div>
        </div>
        <div class="stat-card">
            <div class="stat-value"><?= $stats['categories'] ?></div>
            <div class="stat-label">Categories</div>
        </div>
        <div class="stat-card">
            <div class="stat-value"><?= $stats['users'] ?></div>
            <div class="stat-label">Users</div>
        </div>
        <div class="stat-card">
            <div class="stat-value"><?= $stats['orders'] ?></div>
            <div class="stat-label">Orders</div>
            <?php if ($stats['pending_orders'] > 0): ?>
                <div class="stat-sub stat-warn"><?= $stats['pending_orders'] ?> pending</div>
            <?php endif; ?>
        </div>
    </div>

    <div class="dashboard-grid">
        <div class="dashboard-card">
            <div class="dashboard-card-header">
                <h2>Quick Actions</h2>
            </div>
            <div class="quick-actions">
                <?php foreach ($modules as $mod): ?>
                    <a href="<?= url('admin/' . $mod['name']) ?>" class="quick-action-btn">
                        <span class="qa-icon"><?= match($mod['name']) {
                            'products' => '&#128717;', 'categories' => '&#128193;',
                            'users' => '&#128101;', 'orders' => '&#128230;',
                            default => '&#9632;'
                        } ?></span>
                        <span class="qa-label">Manage <?= ucfirst($mod['name']) ?></span>
                        <?php if (!$mod['can_modify']): ?>
                            <span class="perm-badge view-only">View</span>
                        <?php endif; ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <?php if (!empty($recentOrders)): ?>
        <div class="dashboard-card">
            <div class="dashboard-card-header">
                <h2>Recent Orders</h2>
                <a href="<?= url('admin/orders') ?>" class="card-link">View All</a>
            </div>
            <table class="table table-compact">
                <thead>
                    <tr><th>#</th><th>Customer</th><th>Total</th><th>Status</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($recentOrders as $o): ?>
                        <tr>
                            <td><?= escape($o['order_number']) ?></td>
                            <td><?= escape($o['user_name'] ?? 'Guest') ?></td>
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
            <h2>Recent Activity</h2>
            <a href="<?= url('admin/activity-logs') ?>" class="card-link">View All</a>
        </div>
        <?php if (!empty($recentLogs)): ?>
            <div class="activity-feed">
                <?php foreach ($recentLogs as $log): ?>
                    <div class="activity-item">
                        <span class="activity-action badge-<?= $log['action'] ?>"><?= escape($log['action']) ?></span>
                        <span class="activity-desc"><?= escape(truncate($log['description'] ?? '', 80)) ?></span>
                        <span class="activity-user"><?= escape($log['user_name'] ?? 'System') ?></span>
                        <span class="activity-time"><?= date('M j, g:ia', strtotime($log['created_at'])) ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="empty">No activity logged yet.</p>
        <?php endif; ?>
    </div>
</div>
