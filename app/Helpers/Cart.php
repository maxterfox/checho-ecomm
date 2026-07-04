<?php

namespace App\Helpers;

use App\Core\Auth;
use App\Core\Database;
use App\Core\Session;

class Cart
{
    private const SESSION_KEY = 'cart';

    public static function getItems(): array
    {
        if (Auth::isLoggedIn()) {
            self::syncSessionFromDb();
        }

        $cart = Session::get(self::SESSION_KEY, []);
        return $cart['items'] ?? [];
    }

    public static function add(int $productId, int $quantity, array $product): void
    {
        $quantity = max(1, $quantity);

        $cart = Session::get(self::SESSION_KEY, ['items' => []]);

        if (isset($cart['items'][$productId])) {
            $cart['items'][$productId]['quantity'] += $quantity;
        } else {
            $cart['items'][$productId] = [
                'product_id' => $productId,
                'name' => $product['name'],
                'slug' => $product['slug'],
                'price' => (float) $product['price'],
                'quantity' => $quantity,
                'image' => $product['image'] ?? null,
            ];
        }

        Session::set(self::SESSION_KEY, $cart);

        if (Auth::isLoggedIn()) {
            self::persistToDb();
        }
    }

    public static function update(int $productId, int $quantity): void
    {
        $quantity = max(0, $quantity);

        $cart = Session::get(self::SESSION_KEY, ['items' => []]);

        if ($quantity === 0) {
            unset($cart['items'][$productId]);
        } elseif (isset($cart['items'][$productId])) {
            $cart['items'][$productId]['quantity'] = $quantity;
        }

        Session::set(self::SESSION_KEY, $cart);

        if (Auth::isLoggedIn()) {
            self::persistToDb();
        }
    }

    public static function remove(int $productId): void
    {
        $cart = Session::get(self::SESSION_KEY, ['items' => []]);
        unset($cart['items'][$productId]);
        Session::set(self::SESSION_KEY, $cart);

        if (Auth::isLoggedIn()) {
            self::persistToDb();
        }
    }

    public static function clear(): void
    {
        Session::remove(self::SESSION_KEY);

        if (Auth::isLoggedIn()) {
            $db = Database::getInstance();
            $cart = self::getDbCart();
            if ($cart) {
                $db->delete('cart_items', 'cart_id = :id', ['id' => $cart['id']]);
                $db->delete('carts', 'id = :id', ['id' => $cart['id']]);
            }
        }
    }

    public static function count(): int
    {
        $total = 0;
        foreach (self::getItems() as $item) {
            $total += $item['quantity'];
        }
        return $total;
    }

    public static function subtotal(): float
    {
        $total = 0.0;
        foreach (self::getItems() as $item) {
            $total += (float) $item['price'] * (int) $item['quantity'];
        }
        return $total;
    }

    public static function hasItem(int $productId): bool
    {
        $items = self::getItems();
        return isset($items[$productId]);
    }

    public static function mergeGuestCartOnLogin(): void
    {
        $sessionCart = Session::get(self::SESSION_KEY, ['items' => []])['items'] ?? [];
        if (empty($sessionCart)) {
            return;
        }

        $db = Database::getInstance();
        $cart = self::getDbCart();

        if (!$cart) {
            $sessionId = session_id();
            $cartId = $db->insert('carts', [
                'user_id' => Auth::id(),
                'session_id' => $sessionId,
                'created_at' => date('Y-m-d H:i:s'),
            ]);
            $cart = ['id' => $cartId];
        }

        foreach ($sessionCart as $item) {
            $existing = $db->fetch(
                "SELECT id, quantity FROM cart_items WHERE cart_id = :cart_id AND product_id = :product_id",
                ['cart_id' => $cart['id'], 'product_id' => $item['product_id']]
            );

            if ($existing) {
                $db->update('cart_items',
                    ['quantity' => $existing['quantity'] + $item['quantity'], 'price' => $item['price']],
                    'id = :id', ['id' => $existing['id']]
                );
            } else {
                $db->insert('cart_items', [
                    'cart_id' => $cart['id'],
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
            }
        }

        self::syncSessionFromDb();
    }

    private static function getDbCart(): ?array
    {
        $db = Database::getInstance();
        return $db->fetch(
            "SELECT id FROM carts WHERE user_id = :user_id ORDER BY id DESC LIMIT 1",
            ['user_id' => Auth::id()]
        );
    }

    private static function syncSessionFromDb(): void
    {
        $db = Database::getInstance();
        $cart = self::getDbCart();

        if (!$cart) {
            return;
        }

        $items = $db->fetchAll(
            "SELECT ci.product_id, ci.quantity, ci.price, p.name, p.slug
             FROM cart_items ci
             JOIN products p ON p.id = ci.product_id
             WHERE ci.cart_id = :cart_id",
            ['cart_id' => $cart['id']]
        );

        $sessionItems = [];
        foreach ($items as $item) {
            $image = $db->fetch(
                "SELECT image_path FROM product_images WHERE product_id = :id AND is_primary = 1 LIMIT 1",
                ['id' => $item['product_id']]
            );

            $sessionItems[$item['product_id']] = [
                'product_id' => (int) $item['product_id'],
                'name' => $item['name'],
                'slug' => $item['slug'],
                'price' => (float) $item['price'],
                'quantity' => (int) $item['quantity'],
                'image' => $image['image_path'] ?? null,
            ];
        }

        Session::set(self::SESSION_KEY, ['items' => $sessionItems]);
    }

    private static function persistToDb(): void
    {
        $db = Database::getInstance();
        $sessionItems = self::getItems();

        $cart = self::getDbCart();
        if (!$cart) {
            $sessionId = session_id();
            $cartId = $db->insert('carts', [
                'user_id' => Auth::id(),
                'session_id' => $sessionId,
                'created_at' => date('Y-m-d H:i:s'),
            ]);
            $cart = ['id' => $cartId];
        }

        $existingItems = $db->fetchAll(
            "SELECT id, product_id FROM cart_items WHERE cart_id = :cart_id",
            ['cart_id' => $cart['id']]
        );
        $existingProductIds = [];
        foreach ($existingItems as $ei) {
            $existingProductIds[$ei['product_id']] = $ei['id'];
        }

        foreach ($sessionItems as $item) {
            $pid = $item['product_id'];
            if (isset($existingProductIds[$pid])) {
                $db->update('cart_items',
                    ['quantity' => $item['quantity'], 'price' => $item['price']],
                    'id = :id', ['id' => $existingProductIds[$pid]]
                );
                unset($existingProductIds[$pid]);
            } else {
                $db->insert('cart_items', [
                    'cart_id' => $cart['id'],
                    'product_id' => $pid,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
            }
        }

        foreach ($existingProductIds as $pid => $ciId) {
            $db->delete('cart_items', 'id = :id', ['id' => $ciId]);
        }
    }
}
