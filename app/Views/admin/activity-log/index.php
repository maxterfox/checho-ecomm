<div class="container">
    <div class="page-header">
        <h1>Activity Log</h1>
    </div>

    <?php if (hasFlash('success')): ?>
        <div class="alert alert-success"><?= flash('success') ?></div>
    <?php endif; ?>

    <form method="GET" action="<?= url('/admin/activity-log') ?>" class="filter-form">
        <div class="form-group">
            <label for="module">Filter by Module</label>
            <select name="module" id="module" class="form-control" onchange="this.form.submit()">
                <option value="">All Modules</option>
                <?php if (!empty($modules)): ?>
                    <?php foreach ($modules as $module): ?>
                        <option value="<?= escape($module['name'] ?? $module) ?>" <?= (isset($_GET['module']) && $_GET['module'] === ($module['name'] ?? $module)) ? 'selected' : '' ?>>
                            <?= escape(ucfirst($module['name'] ?? $module)) ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
    </form>

    <table class="table">
        <thead>
            <tr>
                <th>User</th>
                <th>Action</th>
                <th>Module</th>
                <th>Description</th>
                <th>Date</th>
                <th>IP Address</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($logs['data'])): ?>
                <?php foreach ($logs['data'] as $log): ?>
                    <tr>
                        <td><?= escape($log['user_id']) ?></td>
                        <td><?= escape($log['action']) ?></td>
                        <td><?= escape($log['module']) ?></td>
                        <td><?= escape($log['description']) ?></td>
                        <td><?= escape($log['created_at']) ?></td>
                        <td><?= escape($log['ip_address']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center">No activity logs found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
