<section class="section product-detail">
    <div class="container">
        <nav class="breadcrumb">
            <a href="<?= url() ?>">Home</a>
            <span>/</span>
            <a href="<?= url('products') ?>">Products</a>
            <?php if (!empty($product['category_name'])): ?>
                <span>/</span>
                <a href="<?= url('products?category=' . $product['category_slug']) ?>"><?= escape($product['category_name']) ?></a>
            <?php endif; ?>
            <span>/</span>
            <span class="current"><?= escape($product['name']) ?></span>
        </nav>

        <div class="product-detail-layout">
            <div class="product-detail-gallery">
                <?php if (!empty($images)): ?>
                    <div class="product-main-image" style="background: linear-gradient(135deg, var(--navy-light), var(--gray));">
                        <img src="<?= asset('storage/' . $images[0]['image_path']) ?>" alt="<?= escape($product['name']) ?>">
                    </div>
                    <?php if (count($images) > 1): ?>
                        <div class="product-thumbnails">
                            <?php foreach ($images as $img): ?>
                                <div class="product-thumb" style="background: linear-gradient(135deg, var(--navy-light), var(--gray));">
                                    <img src="<?= asset('storage/' . $img['image_path']) ?>" alt="">
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="product-main-image product-no-image">
                        <div class="product-card-placeholder">
                            <span class="placeholder-icon">&#9913;</span>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <div class="product-detail-info">
                <?php if (!empty($product['category_name'])): ?>
                    <span class="product-detail-category"><?= escape($product['category_name']) ?></span>
                <?php endif; ?>

                <h1 class="product-detail-title"><?= escape($product['name']) ?></h1>

                <div class="product-detail-pricing">
                    <span class="product-detail-price"><?= formatPrice((float) $product['price']) ?></span>
                    <?php if ((float) $product['compare_price'] > 0): ?>
                        <span class="product-detail-old-price"><?= formatPrice((float) $product['compare_price']) ?></span>
                        <span class="product-detail-save">Save <?= formatPrice((float) $product['compare_price'] - (float) $product['price']) ?></span>
                    <?php endif; ?>
                </div>

                <?php if (!empty($product['description'])): ?>
                    <div class="product-detail-description">
                        <p><?= nl2br(escape($product['description'])) ?></p>
                    </div>
                <?php endif; ?>

                <div class="product-detail-meta">
                    <div class="meta-item">
                        <span class="meta-label">SKU:</span>
                        <span class="meta-value"><?= escape($product['sku'] ?? 'N/A') ?></span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Availability:</span>
                        <span class="meta-value <?= $product['stock'] > 0 ? 'in-stock' : 'out-of-stock' ?>">
                            <?= $product['stock'] > 0 ? 'In Stock (' . $product['stock'] . ' available)' : 'Out of Stock' ?>
                        </span>
                    </div>
                </div>

                <div class="product-detail-actions">
                    <?php if ($product['stock'] > 0): ?>
                        <form action="<?= url('cart/add') ?>" method="post" class="add-to-cart-form">
                            <?= csrfField() ?>
                            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                            <input type="hidden" name="redirect" value="<?= url('products/' . $product['slug']) ?>">
                            <div class="quantity-selector">
                                <button type="button" class="qty-btn qty-minus">-</button>
                                <input type="number" name="quantity" value="1" min="1" max="<?= $product['stock'] ?>" class="qty-input">
                                <button type="button" class="qty-btn qty-plus">+</button>
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg btn-block">Add to Cart</button>
                        </form>
                    <?php else: ?>
                        <button class="btn btn-lg btn-block" disabled style="background:var(--gray);color:var(--text-muted);cursor:not-allowed;">Out of Stock</button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php if (!empty($related)): ?>
<section class="section section-related">
    <div class="container">
        <div class="section-header">
            <h2>Related Products</h2>
        </div>
        <div class="product-grid">
            <?php foreach ($related as $relProduct): ?>
                <?php $product = $relProduct; ?>
                <?php require __DIR__ . '/partials/_card.php' ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>
