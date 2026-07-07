<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?> — Admin<?= isset($title) ? ' — ' . escape($title) : '' ?></title>
    <link rel="stylesheet" href="<?= asset('assets/css/app.css') ?>">
</head>
<body>
    <div class="admin-layout">
        <?php require __DIR__ . '/../partials/sidebar.php' ?>
        <div class="admin-wrapper">
            <?php $currentUser = \App\Core\Auth::user(); ?>
            <header class="admin-topbar">
                <button class="sidebar-toggle" id="sidebarToggle" aria-label="Alternar barra lateral">&#9776;</button>
                <div class="topbar-title"><?= isset($title) ? escape($title) : 'Panel principal' ?></div>
                <div class="topbar-right">
                    <a href="<?= url() ?>" target="_blank" class="topbar-link" title="Ver tienda">&#127760;</a>
                    <div class="topbar-user">
                        <span class="topbar-user-name"><?= escape($currentUser['name'] ?? 'Usuario') ?></span>
                        <span class="topbar-user-role"><?= escape(ucfirst($currentUser['role_name'] ?? '')) ?></span>
                    </div>
                    <a href="<?= url('logout') ?>" class="topbar-logout" title="Cerrar sesión">&#10140;</a>
                </div>
            </header>
            <main class="admin-main">
                <?php require __DIR__ . '/../partials/alerts.php' ?>
                <?= $content ?>
            </main>
        </div>
    </div>
    <script src="<?= asset('assets/js/app.js') ?>"></script>
</body>
</html>
