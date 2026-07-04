<section class="hero">
    <div class="container">
        <h1>Urban Performance Gear</h1>
        <p>Premium sportswear designed for the city athlete.</p>
        <a href="<?= url('products') ?>" class="btn btn-primary btn-lg">Shop Now</a>
    </div>
</section>

<section class="categories-section">
    <div class="container">
        <h2>Categories</h2>
        <div class="categories-grid">
            <?php foreach ($categories ?? [] as $category): ?>
                <a href="<?= url('categories/' . escape($category['slug'])) ?>" class="category-card">
                    <h3><?= escape($category['name']) ?></h3>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="products-section">
    <div class="container">
        <h2>Featured Products</h2>
        <div class="products-grid">
            <?php foreach ($products ?? [] as $product): ?>
                <div class="product-card">
                    <a href="<?= url('products/' . escape($product['slug'])) ?>">
                        <h3><?= escape($product['name']) ?></h3>
                        <p class="price"><?= formatPrice((float) $product['price']) ?></p>
                    </a>
                    <form action="<?= url('cart/add') ?>" method="post">
                        <?= csrfField() ?>
                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="btn btn-primary btn-sm">Add to Cart</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
