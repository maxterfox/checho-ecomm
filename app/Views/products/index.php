<div class="container">
    <div class="catalog-layout">
        <aside class="catalog-sidebar">
            <h3>Categories</h3>
            <nav class="category-list">
                <a href="<?= url('products') ?>" class="category-link">All</a>
                <?php foreach ($categories ?? [] as $cat): ?>
                    <a href="<?= url('categories/' . escape($cat['slug'])) ?>"
                       class="category-link <?= ($selectedCategory ?? '') == $cat['id'] ? 'active' : '' ?>">
                        <?= escape($cat['name']) ?>
                    </a>
                <?php endforeach; ?>
            </nav>
        </aside>

        <div class="catalog-content">
            <h1><?= isset($category) ? escape($category['name']) : 'All Products' ?></h1>

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

            <?php if (empty($products)): ?>
                <p class="empty-state">No products found.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
