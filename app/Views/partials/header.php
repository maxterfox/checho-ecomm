<header class="site-header">
    <div class="container">
        <a href="<?= url() ?>" class="logo">
            <span class="logo-icon">&#9889;</span>
            <span class="logo-text"><?= APP_NAME ?></span>
        </a>

        <nav class="main-nav">
            <a href="<?= url('products') ?>" class="nav-link">Products</a>
            <a href="<?= url('cart') ?>" class="nav-link">Cart</a>
            <?php if (isLoggedIn()): ?>
                <a href="<?= url('admin') ?>" class="nav-link">Admin</a>
                <a href="<?= url('logout') ?>" class="nav-link">Logout</a>
            <?php else: ?>
                <a href="<?= url('login') ?>" class="nav-link">Login</a>
                <a href="<?= url('register') ?>" class="nav-link btn btn-primary">Sign Up</a>
            <?php endif; ?>
        </nav>
    </div>
</header>
