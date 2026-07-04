<section class="hero">
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <h1 class="hero-title">Gear Up.<br>Dominate the Game.</h1>
        <p class="hero-subtitle">Premium sportswear and footwear for athletes who never settle.</p>
        <div class="hero-actions">
            <a href="<?= url('products') ?>" class="btn btn-primary btn-lg">Shop Now</a>
            <a href="#featured" class="btn btn-outline btn-lg">Explore</a>
        </div>
    </div>
</section>

<section id="featured" class="section">
    <div class="container">
        <div class="section-header">
            <h2>Featured Products</h2>
            <a href="<?= url('products') ?>" class="section-link">View All &rarr;</a>
        </div>

        <?php if (empty($featured)): ?>
            <p class="empty">No products available yet.</p>
        <?php else: ?>
            <div class="product-grid">
                <?php foreach ($featured as $product): ?>
                    <?php require __DIR__ . '/products/partials/_card.php' ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php if (!empty($categories)): ?>
<section class="section section-categories">
    <div class="container">
        <div class="section-header">
            <h2>Shop by Category</h2>
        </div>
        <div class="category-grid">
            <?php foreach ($categories as $cat): ?>
                <a href="<?= url('products?category=' . $cat['slug']) ?>" class="category-card">
                    <div class="category-card-icon">&#9733;</div>
                    <h3><?= escape($cat['name']) ?></h3>
                    <?php if (!empty($cat['description'])): ?>
                        <p><?= escape(truncate($cat['description'], 80)) ?></p>
                    <?php endif; ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<section class="section section-cta">
    <div class="container">
        <div class="cta-box">
            <h2>Join the Movement</h2>
            <p>Sign up for exclusive access to new drops, limited editions, and member-only pricing.</p>
            <a href="<?= url('register') ?>" class="btn btn-primary btn-lg">Create Account</a>
        </div>
    </div>
</section>
