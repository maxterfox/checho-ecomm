<aside class="admin-sidebar">
    <div class="sidebar-header">
        <a href="<?= url('admin') ?>" class="sidebar-logo">
            <span class="logo-icon">&#9889;</span>
            <span><?= APP_NAME ?> Admin</span>
        </a>
    </div>

    <nav class="sidebar-nav">
        <a href="<?= url('admin/dashboard') ?>" class="sidebar-link">Dashboard</a>
        <a href="<?= url('admin/products') ?>" class="sidebar-link">Products</a>
        <a href="<?= url('admin/users') ?>" class="sidebar-link">Users</a>
        <a href="<?= url('admin/roles') ?>" class="sidebar-link">Roles & Permissions</a>
        <a href="<?= url('admin/activity-log') ?>" class="sidebar-link">Activity Log</a>
        <a href="<?= url('admin/modules') ?>" class="sidebar-link">Module Fields</a>
        <hr>
        <a href="<?= url('/') ?>" class="sidebar-link">Back to Store</a>
        <a href="<?= url('logout') ?>" class="sidebar-link">Logout</a>
    </nav>
</aside>
