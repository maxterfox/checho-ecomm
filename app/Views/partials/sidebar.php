<?php
$userId = (int) (\App\Core\Auth::user()['role_id'] ?? 0);
$canView = fn($m) => \App\Helpers\Permission::canView($userId, $m);
$canModify = fn($m) => \App\Helpers\Permission::canModify($userId, $m);
$currentUrl = $_SERVER['REQUEST_URI'] ?? '';
$isActive = fn($path) => strpos($currentUrl, url('admin/' . $path)) !== false ? 'active' : '';
?>
<aside class="sidebar" id="adminSidebar">
    <div class="sidebar-header">
        <a href="<?= url('admin') ?>" class="logo"><?= APP_NAME ?></a>
        <span class="sidebar-badge">Admin</span>
    </div>
    <nav class="sidebar-nav">
        <a href="<?= url('admin') ?>" class="<?= $currentUrl === url('admin') || $currentUrl === url('admin/') ? 'active' : '' ?>">
            <span class="nav-icon">&#9632;</span> Dashboard
        </a>

        <?php if ($canView('users')): ?>
            <a href="<?= url('admin/users') ?>" class="<?= $isActive('users') ?>">
                <span class="nav-icon">&#128101;</span> Users
                <?php if (!$canModify('users')): ?><span class="perm-badge view-only">View</span><?php endif; ?>
            </a>
        <?php endif; ?>

        <?php if ($canView('roles')): ?>
            <a href="<?= url('admin/roles') ?>" class="<?= $isActive('roles') ?>">
                <span class="nav-icon">&#9878;</span> Roles
                <?php if (!$canModify('roles')): ?><span class="perm-badge view-only">View</span><?php endif; ?>
            </a>
        <?php endif; ?>

        <?php if ($canView('settings')): ?>
            <a href="<?= url('admin/fields') ?>" class="<?= $isActive('fields') ?>">
                <span class="nav-icon">&#9881;</span> Field Permissions
                <?php if (!$canModify('settings')): ?><span class="perm-badge view-only">View</span><?php endif; ?>
            </a>
        <?php endif; ?>

        <hr>

        <?php if ($canView('products')): ?>
            <a href="<?= url('admin/products') ?>" class="<?= $isActive('products') ?>">
                <span class="nav-icon">&#128717;</span> Products
                <?php if (!$canModify('products')): ?><span class="perm-badge view-only">View</span><?php endif; ?>
            </a>
        <?php endif; ?>

        <?php if ($canView('categories')): ?>
            <a href="<?= url('admin/categories') ?>" class="<?= $isActive('categories') ?>">
                <span class="nav-icon">&#128193;</span> Categories
                <?php if (!$canModify('categories')): ?><span class="perm-badge view-only">View</span><?php endif; ?>
            </a>
        <?php endif; ?>

        <?php if ($canView('orders')): ?>
            <a href="<?= url('admin/orders') ?>" class="<?= $isActive('orders') ?>">
                <span class="nav-icon">&#128230;</span> Orders
                <?php if (!$canModify('orders')): ?><span class="perm-badge view-only">View</span><?php endif; ?>
            </a>
        <?php endif; ?>

        <hr>

        <?php if ($canView('activity_logs')): ?>
            <a href="<?= url('admin/activity-logs') ?>" class="<?= $isActive('activity-logs') ?>">
                <span class="nav-icon">&#128196;</span> Activity Logs
                <?php if (!$canModify('activity_logs')): ?><span class="perm-badge view-only">View</span><?php endif; ?>
            </a>
        <?php endif; ?>

        <?php if ($canView('settings')): ?>
            <a href="<?= url('admin/settings') ?>" class="<?= $isActive('settings') ?>">
                <span class="nav-icon">&#9881;</span> Settings
                <?php if (!$canModify('settings')): ?><span class="perm-badge view-only">View</span><?php endif; ?>
            </a>
        <?php endif; ?>

        <hr>
        <a href="<?= url() ?>" target="_blank"><span class="nav-icon">&#127760;</span> View Store</a>
        <a href="<?= url('logout') ?>"><span class="nav-icon">&#10140;</span> Logout</a>
    </nav>
</aside>
