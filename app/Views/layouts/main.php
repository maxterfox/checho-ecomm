<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?><?= isset($title) ? ' — ' . escape($title) : '' ?></title>
    <link rel="stylesheet" href="<?= asset('assets/css/app.css') ?>">
</head>
<body>
    <?php require __DIR__ . '/../partials/header.php' ?>

    <main class="main-content">
        <?php require __DIR__ . '/../partials/alerts.php' ?>
        <?= $content ?>
    </main>

    <?php require __DIR__ . '/../partials/footer.php' ?>

    <script src="<?= asset('assets/js/app.js') ?>"></script>
</body>
</html>
