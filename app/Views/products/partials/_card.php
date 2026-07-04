<a href="<?= url('products/' . $product['slug']) ?>" class="product-card">
    <div class="product-card-image" style="background: linear-gradient(135deg, var(--navy-light), var(--gray));">
        <?php if (isset($primaryImages[$product['id']])): ?>
            <img src="<?= asset('storage/' . $primaryImages[$product['id']]) ?>" alt="<?= escape($product['name']) ?>">
        <?php else: ?>
            <div class="product-card-placeholder">
                <span class="placeholder-icon">&#9913;</span>
            </div>
        <?php endif; ?>
        <?php if ((float) $product['compare_price'] > 0): ?>
            <span class="product-badge sale">Sale</span>
        <?php endif; ?>
    </div>
    <div class="product-card-body">
        <?php if (!empty($product['category_name'])): ?>
            <span class="product-card-category"><?= escape($product['category_name']) ?></span>
        <?php endif; ?>
        <h3 class="product-card-title"><?= escape($product['name']) ?></h3>
        <div class="product-card-pricing">
            <span class="product-card-price"><?= formatPrice((float) $product['price']) ?></span>
            <?php if ((float) $product['compare_price'] > 0): ?>
                <span class="product-card-old-price"><?= formatPrice((float) $product['compare_price']) ?></span>
            <?php endif; ?>
        </div>
        <span class="btn btn-primary btn-sm product-card-btn">View Details</span>
    </div>
</a>
