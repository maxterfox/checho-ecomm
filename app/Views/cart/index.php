<div class="container">
    <h1>Shopping Cart</h1>

    <?php if (empty($cart)): ?>
        <p class="empty-state">Your cart is empty.</p>
        <a href="<?= url('products') ?>" class="btn btn-primary">Browse Products</a>
    <?php else: ?>
        <div class="cart-table">
            <?php foreach ($cart as $item): ?>
                <div class="cart-item">
                    <div class="cart-item-info">
                        <h3><?= escape($item['product']['name'] ?? '') ?></h3>
                        <p class="price"><?= formatPrice((float) ($item['product']['price'] ?? 0)) ?></p>
                    </div>
                    <div class="cart-item-quantity">
                        <form action="<?= url('cart/update') ?>" method="post">
                            <?= csrfField() ?>
                            <input type="hidden" name="product_id" value="<?= $item['product_id'] ?>">
                            <input type="number" name="quantity" value="<?= $item['quantity'] ?>" min="0" onchange="this.form.submit()">
                        </form>
                    </div>
                    <div class="cart-item-total">
                        <?= formatPrice((float) ($item['product']['price'] ?? 0) * $item['quantity']) ?>
                    </div>
                    <div class="cart-item-remove">
                        <form action="<?= url('cart/remove') ?>" method="post">
                            <?= csrfField() ?>
                            <input type="hidden" name="product_id" value="<?= $item['product_id'] ?>">
                            <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="cart-total">
            <h3>Total: <?= formatPrice($total ?? 0) ?></h3>
        </div>

        <a href="<?= url('checkout') ?>" class="btn btn-primary btn-lg">Proceed to Checkout</a>
    <?php endif; ?>
</div>
