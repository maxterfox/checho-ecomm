<div class="container">
    <div class="page-header">
        <h1>Permisos de campos</h1>
    </div>

    <p>Selecciona un módulo para configurar qué campos puede editar cada rol.</p>

    <div class="module-grid">
        <?php foreach ($modules as $module): ?>
            <a href="<?= url('admin/fields/' . $module['name']) ?>" class="module-card">
                <h3><?= escape($module['display_name']) ?></h3>
                <p><?= $module['field_count'] ?> campo(s)</p>
            </a>
        <?php endforeach; ?>
    </div>
</div>
