<div class="container">
    <div class="product-detail">
        <div class="product-info">
            <h1><?= escape($product['name']) ?></h1>
            <p class="price"><?= formatPrice((float) $product['price']) ?></p>
            <p class="stock <?= ($product['stock'] ?? 0) > 0 ? 'in-stock' : 'out-of-stock' ?>">
                <?= ($product['stock'] ?? 0) > 0 ? 'In Stock' : 'Out of Stock' ?>
            </p>
            <p class="description"><?= nl2br(escape($product['description'] ?? '')) ?></p>

            <form action="<?= url('cart/add') ?>" method="post" class="add-to-cart-form">
                <?= csrfField() ?>
                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                <div class="quantity-selector">
                    <label for="quantity">Quantity:</label>
                    <input type="number" name="quantity" id="quantity" value="1" min="1" max="<?= (int) ($product['stock'] ?? 1) ?>">
                </div>
                <button type="submit" class="btn btn-primary btn-lg">Add to Cart</button>
            </form>
        </div>
    </div>

    <?php if (!empty($relatedProducts)): ?>
        <section class="related-products">
            <h2>Related Products</h2>
            <div class="products-grid">
                <?php foreach ($relatedProducts as $related): ?>
                    <?php if ($related['id'] === $product['id']) continue; ?>
                    <div class="product-card">
                        <a href="<?= url('products/' . escape($related['slug'])) ?>">
                            <h3><?= escape($related['name']) ?></h3>
                            <p class="price"><?= formatPrice((float) $related['price']) ?></p>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>
</div>
