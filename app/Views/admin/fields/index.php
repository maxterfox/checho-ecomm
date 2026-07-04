<div class="container">
    <div class="page-header">
        <h1>Field Permissions</h1>
    </div>

    <p>Select a module to configure which fields each role can edit.</p>

    <div class="module-grid">
        <?php foreach ($modules as $module): ?>
            <a href="<?= url('admin/fields/' . $module['name']) ?>" class="module-card">
                <h3><?= escape($module['display_name']) ?></h3>
                <p><?= $module['field_count'] ?> field(s)</p>
            </a>
        <?php endforeach; ?>
    </div>
</div>
