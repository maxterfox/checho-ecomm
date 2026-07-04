<div class="page-header">
    <h1>Settings</h1>
</div>

<div class="settings-grid">
    <a href="<?= url('admin/fields') ?>" class="settings-card">
        <span class="settings-icon">&#9881;</span>
        <h3>Field Permissions</h3>
        <p>Configure which fields each role can edit per module.</p>
    </a>

    <a href="<?= url('admin/roles') ?>" class="settings-card">
        <span class="settings-icon">&#9878;</span>
        <h3>Roles & Permissions</h3>
        <p>Create and manage roles with module-level access.</p>
    </a>
</div>

<div class="notice-box">
    <h3>Application Info</h3>
    <table class="table table-compact">
        <tr><td>App Name</td><td><?= escape(APP_NAME) ?></td></tr>
        <tr><td>Environment</td><td><?= escape(APP_ENV) ?></td></tr>
        <tr><td>URL</td><td><?= escape(APP_URL) ?></td></tr>
        <tr><td>Time Zone</td><td><?= escape(TIMEZONE) ?></td></tr>
    </table>
</div>
