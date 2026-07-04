<section class="section">
    <div class="container">
        <div class="page-header">
            <h1>Shopping Cart</h1>
            <span class="result-count"><?= $count ?> item<?= $count !== 1 ? 's' : '' ?></span>
        </div>

        <?php if (empty($items)): ?>
            <div class="empty-cart">
                <div class="empty-cart-icon">&#128722;</div>
                <h2>Your cart is empty</h2>
                <p>Looks like you haven't added anything yet.</p>
                <a href="<?= url('products') ?>" class="btn btn-primary btn-lg">Browse Products</a>
            </div>
        <?php else: ?>
            <div class="cart-layout">
                <div class="cart-items">
                    <?php $i = 0; foreach ($items as $item): ?>
                        <div class="cart-item">
                            <div class="cart-item-image">
                                <a href="<?= url('products/' . $item['slug']) ?>">
                                    <?php if ($item['image']): ?>
                                        <img src="<?= asset('storage/' . $item['image']) ?>" alt="<?= escape($item['name']) ?>">
                                    <?php else: ?>
                                        <div class="cart-item-placeholder" style="background:linear-gradient(135deg,var(--navy-light),var(--gray));width:100%;height:100%;display:flex;align-items:center;justify-content:center;color:var(--gray-light);font-size:1.5rem;">&#9913;</div>
                                    <?php endif; ?>
                                </a>
                            </div>
                            <div class="cart-item-details">
                                <a href="<?= url('products/' . $item['slug']) ?>" class="cart-item-name"><?= escape($item['name']) ?></a>
                                <span class="cart-item-price"><?= formatPrice($item['price']) ?></span>
                            </div>
                            <form action="<?= url('cart/update') ?>" method="post" class="cart-item-quantity">
                                <?= csrfField() ?>
                                <input type="hidden" name="product_id" value="<?= $item['product_id'] ?>">
                                <button type="button" class="qty-btn qty-minus">-</button>
                                <input type="number" name="quantity" value="<?= $item['quantity'] ?>" min="0" class="qty-input">
                                <button type="button" class="qty-btn qty-plus">+</button>
                                <button type="submit" class="btn btn-sm btn-outline qty-update-btn">Update</button>
                            </form>
                            <div class="cart-item-total">
                                <?= formatPrice($item['price'] * $item['quantity']) ?>
                            </div>
                            <form action="<?= url('cart/remove/' . $item['product_id']) ?>" method="post" class="cart-item-remove">
                                <?= csrfField() ?>
                                <button type="submit" class="btn btn-sm remove-btn" title="Remove">&times;</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="cart-summary">
                    <h3>Order Summary</h3>
                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span><?= formatPrice($subtotal) ?></span>
                    </div>
                    <div class="summary-row summary-total">
                        <span>Total</span>
                        <span><?= formatPrice($subtotal) ?></span>
                    </div>
                    <div class="summary-actions">
                        <a href="<?= url('products') ?>" class="btn btn-outline btn-block">Continue Shopping</a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>
