<div class="page-header">
    <h1>Configuración</h1>
</div>

<div class="settings-grid">
    <a href="<?= url('admin/fields') ?>" class="settings-card">
        <span class="settings-icon">&#9881;</span>
        <h3>Permisos de campos</h3>
        <p>Configura qué campos puede editar cada rol por módulo.</p>
    </a>

    <a href="<?= url('admin/roles') ?>" class="settings-card">
        <span class="settings-icon">&#9878;</span>
        <h3>Roles y permisos</h3>
        <p>Crea y gestiona roles con acceso a nivel de módulo.</p>
    </a>
</div>

<div class="notice-box">
    <h3>Información de la aplicación</h3>
    <table class="table table-compact">
        <tr><td>Nombre de la app</td><td><?= escape(APP_NAME) ?></td></tr>
        <tr><td>Entorno</td><td><?= escape(APP_ENV) ?></td></tr>
        <tr><td>URL</td><td><?= escape(APP_URL) ?></td></tr>
        <tr><td>Zona horaria</td><td><?= escape(TIMEZONE) ?></td></tr>
    </table>
</div>
