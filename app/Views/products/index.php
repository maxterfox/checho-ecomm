<section class="section page-top">
    <div class="container">
        <div class="page-top-header">
            <h1><?= $query ? 'Search: "' . escape($query) . '"' : ($currentCategory ? 'Category: ' . escape($currentCategory) : 'All Products') ?></h1>
            <span class="result-count"><?= $total ?> product<?= $total !== 1 ? 's' : '' ?> found</span>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="shop-layout">
            <aside class="shop-sidebar">
                <form action="<?= url('products') ?>" method="get" class="search-form">
                    <input type="search" name="q" class="search-input" placeholder="Search products..." value="<?= escape($query) ?>">
                    <button type="submit" class="search-btn">&#128269;</button>
                </form>

                <div class="filter-section">
                    <h3>Categories</h3>
                    <ul class="filter-list">
                        <li>
                            <a href="<?= url('products') ?>" class="<?= $currentCategory === '' ? 'active' : '' ?>">All</a>
                        </li>
                        <?php foreach ($categories as $cat): ?>
                            <li>
                                <?php
                                    $url = url('products?category=' . $cat['slug']);
                                    if ($query !== '') $url .= '&q=' . urlencode($query);
                                ?>
                                <a href="<?= $url ?>" class="<?= $currentCategory === $cat['slug'] ? 'active' : '' ?>">
                                    <?= escape($cat['name']) ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </aside>

            <div class="shop-main">
                <div class="shop-toolbar">
                    <div class="sort-form">
                        <label for="sort">Sort:</label>
                        <select id="sort" onchange="window.location.href=this.value">
                            <?php
                                $baseUrl = url('products');
                                $params = [];
                                if ($currentCategory) $params[] = 'category=' . urlencode($currentCategory);
                                if ($query) $params[] = 'q=' . urlencode($query);
                                $qs = implode('&', $params);
                            ?>
                            <option value="<?= $baseUrl . ($qs ? '?' . $qs . '&' : '?') ?>sort=latest" <?= $sort === 'latest' ? 'selected' : '' ?>>Latest</option>
                            <option value="<?= $baseUrl . ($qs ? '?' . $qs . '&' : '?') ?>sort=price_asc" <?= $sort === 'price_asc' ? 'selected' : '' ?>>Price: Low to High</option>
                            <option value="<?= $baseUrl . ($qs ? '?' . $qs . '&' : '?') ?>sort=price_desc" <?= $sort === 'price_desc' ? 'selected' : '' ?>>Price: High to Low</option>
                            <option value="<?= $baseUrl . ($qs ? '?' . $qs . '&' : '?') ?>sort=name_asc" <?= $sort === 'name_asc' ? 'selected' : '' ?>>Name: A-Z</option>
                            <option value="<?= $baseUrl . ($qs ? '?' . $qs . '&' : '?') ?>sort=name_desc" <?= $sort === 'name_desc' ? 'selected' : '' ?>>Name: Z-A</option>
                        </select>
                    </div>
                </div>

                <?php if (empty($products)): ?>
                    <p class="empty">No products found. Try adjusting your filters.</p>
                <?php else: ?>
                    <div class="product-grid">
                        <?php foreach ($products as $product): ?>
                            <?php require __DIR__ . '/partials/_card.php' ?>
                        <?php endforeach; ?>
                    </div>

                    <?php if ($lastPage > 1): ?>
                        <div class="pagination">
                            <?php
                                $pageUrl = url('products');
                                $pageParams = [];
                                if ($currentCategory) $pageParams[] = 'category=' . urlencode($currentCategory);
                                if ($query) $pageParams[] = 'q=' . urlencode($query);
                                if ($sort !== 'latest') $pageParams[] = 'sort=' . $sort;
                                $pageQs = implode('&', $pageParams);
                            ?>
                            <?php if ($page > 1): ?>
                                <a href="<?= $pageUrl . '?' . $pageQs . ($pageQs ? '&' : '') . 'page=' . ($page - 1) ?>" class="btn btn-sm">&laquo; Prev</a>
                            <?php endif; ?>

                            <?php for ($i = 1; $i <= $lastPage; $i++): ?>
                                <a href="<?= $pageUrl . '?' . $pageQs . ($pageQs ? '&' : '') . 'page=' . $i ?>" class="btn btn-sm <?= $i === $page ? 'btn-primary' : '' ?>"><?= $i ?></a>
                            <?php endfor; ?>

                            <?php if ($page < $lastPage): ?>
                                <a href="<?= $pageUrl . '?' . $pageQs . ($pageQs ? '&' : '') . 'page=' . ($page + 1) ?>" class="btn btn-sm">Next &raquo;</a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
