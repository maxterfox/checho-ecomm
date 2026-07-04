<header class="header">
    <div class="container header-inner">
        <a href="<?= url() ?>" class="logo"><?= APP_NAME ?></a>
        <nav class="nav">
            <a href="<?= url('products') ?>">Products</a>
            <?php if (\App\Core\Auth::isLoggedIn()): ?>
                <a href="<?= url('admin') ?>">Admin</a>
                <a href="<?= url('logout') ?>">Logout</a>
            <?php else: ?>
                <a href="<?= url('login') ?>">Login</a>
                <a href="<?= url('register') ?>" class="btn btn-primary btn-sm">Sign Up</a>
            <?php endif; ?>
            <a href="<?= url('cart') ?>" class="cart-link" title="Cart">
                &#128722;
                <?php if (cartCount() > 0): ?>
                    <span class="cart-badge"><?= cartCount() ?></span>
                <?php endif; ?>
            </a>
        </nav>
    </div>
</header>
