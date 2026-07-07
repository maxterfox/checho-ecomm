<div class="page-header">
    <h1>Registro de actividades</h1>
    <div class="filter-group">
        <form action="<?= url('admin/activity-logs') ?>" method="get">
            <select name="module" onchange="this.form.submit()">
                <option value="">Todos los módulos</option>
                <?php foreach ($modules as $key => $label): ?>
                    <option value="<?= $key ?>" <?= $selectedModule === $key ? 'selected' : '' ?>><?= escape($label) ?></option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>
</div>

<table class="table">
    <thead>
        <tr>
            <th>Usuario</th>
            <th>Acción</th>
            <th>Módulo</th>
            <th>Descripción</th>
            <th>IP</th>
            <th>Fecha</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($logs['data'])): ?>
            <tr><td colspan="6" class="empty">Aún no hay actividad registrada.</td></tr>
        <?php else: ?>
            <?php foreach ($logs['data'] as $log): ?>
                <tr>
                    <td><?= escape($log['user_name'] ?? 'Sistema') ?></td>
                    <td><span class="badge-action badge-<?= $log['action'] ?>"><?= escape($log['action']) ?></span></td>
                    <td><?= escape($log['module']) ?></td>
                    <td><?= escape(truncate($log['description'] ?? '', 100)) ?></td>
                    <td><?= escape($log['ip_address'] ?? '-') ?></td>
                    <td><?= date('M j, g:ia', strtotime($log['created_at'])) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<?php if ($logs['lastPage'] > 1): ?>
    <div class="pagination">
        <?php for ($i = 1; $i <= $logs['lastPage']; $i++): ?>
            <a href="<?= url('admin/activity-logs?page=' . $i . ($selectedModule ? '&module=' . $selectedModule : '')) ?>"
               class="btn btn-sm <?= $i === $logs['page'] ? 'btn-primary' : '' ?>"><?= $i ?></a>
        <?php endfor; ?>
    </div>
<?php endif; ?>
