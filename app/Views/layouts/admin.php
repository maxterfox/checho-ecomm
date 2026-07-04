<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?> Admin <?= isset($title) ? '| ' . escape($title) : '' ?></title>
    <link rel="stylesheet" href="<?= asset('assets/css/app.css') ?>">
    <link rel="stylesheet" href="<?= asset('assets/css/admin.css') ?>">
</head>
<body>
    <div class="admin-wrapper">
        <?php require __DIR__ . '/../partials/sidebar.php' ?>
        <div class="admin-content">
            <?php require __DIR__ . '/../partials/alerts.php' ?>
            <?= $content ?? '' ?>
        </div>
    </div>

    <script src="<?= asset('assets/js/app.js') ?>"></script>
    <script src="<?= asset('assets/js/admin.js') ?>"></script>
</body>
</html>
