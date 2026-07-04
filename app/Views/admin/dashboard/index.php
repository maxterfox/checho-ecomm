<div class="container">
    <div class="page-header">
        <h1>Dashboard</h1>
    </div>

    <?php if (hasFlash('success')): ?>
        <div class="alert alert-success"><?= flash('success') ?></div>
    <?php endif; ?>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon stat-icon-products">
                <span class="icon-box"></span>
            </div>
            <div class="stat-body">
                <h3 class="stat-number"><?= escape($totalProducts ?? 0) ?></h3>
                <p class="stat-label">Total Products</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon stat-icon-users">
                <span class="icon-users"></span>
            </div>
            <div class="stat-body">
                <h3 class="stat-number"><?= escape($totalUsers ?? 0) ?></h3>
                <p class="stat-label">Total Users</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon stat-icon-orders">
                <span class="icon-cart"></span>
            </div>
            <div class="stat-body">
                <h3 class="stat-number"><?= escape($totalOrders ?? 0) ?></h3>
                <p class="stat-label">Total Orders</p>
            </div>
        </div>
    </div>
</div>
