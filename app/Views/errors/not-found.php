<section class="error-page">
    <h1>404</h1>
    <p><?= escape($message ?? 'Page not found.') ?></p>
    <a href="<?= url() ?>" class="btn btn-primary">Go Home</a>
    <a href="<?= url('products') ?>" class="btn btn-outline">Browse Products</a>
</section>
