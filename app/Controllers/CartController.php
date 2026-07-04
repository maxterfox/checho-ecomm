<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Request;
use App\Core\Session;
use App\Core\Auth;
use App\Core\Log;
use App\Helpers\Cart;

class CartController extends Controller
{
    public function index(): void
    {
        $items = Cart::getItems();
        $subtotal = Cart::subtotal();
        $count = Cart::count();

        $this->view('cart/index', [
            'items' => $items,
            'subtotal' => $subtotal,
            'count' => $count,
            'title' => 'Shopping Cart',
        ]);
    }

    public function add(): void
    {
        if (!Request::validateCsrf(Request::post('csrf_token', ''))) {
            Session::setFlash('error', 'Invalid form token.');
            $this->redirect('/cart');
        }

        $productId = (int) Request::post('product_id', 0);
        $quantity = max(1, (int) Request::post('quantity', 1));

        if ($productId <= 0) {
            Session::setFlash('error', 'Invalid product.');
            $this->redirect(Request::post('redirect', '/products'));
        }

        $db = Database::getInstance();
        $product = $db->fetch(
            "SELECT id, name, slug, price, stock FROM products WHERE id = :id AND status = 'active' AND deleted_at IS NULL",
            ['id' => $productId]
        );

        if (!$product) {
            Session::setFlash('error', 'Product not found.');
            $this->redirect(Request::post('redirect', '/products'));
        }

        if ($product['stock'] < 1) {
            Session::setFlash('error', 'This product is out of stock.');
            $this->redirect(Request::post('redirect', '/products'));
        }

        $product['image'] = null;
        $image = $db->fetch(
            "SELECT image_path FROM product_images WHERE product_id = :id AND is_primary = 1 LIMIT 1",
            ['id' => $productId]
        );
        if ($image) {
            $product['image'] = $image['image_path'];
        }

        Cart::add($productId, $quantity, $product);

        Log::write(Auth::id(), 'add_to_cart', 'cart', "Added '{$product['name']}' (qty: {$quantity})", $productId);
        Session::setFlash('success', '"' . $product['name'] . '" added to cart.');
        $this->redirect(Request::post('redirect', '/cart'));
    }

    public function update(): void
    {
        if (!Request::validateCsrf(Request::post('csrf_token', ''))) {
            Session::setFlash('error', 'Invalid form token.');
            $this->redirect('/cart');
        }

        $productId = (int) Request::post('product_id', 0);
        $quantity = (int) Request::post('quantity', 0);

        if ($productId <= 0) {
            Session::setFlash('error', 'Invalid product.');
            $this->redirect('/cart');
        }

        if ($quantity < 1) {
            Cart::remove($productId);
            Log::write(Auth::id(), 'remove_from_cart', 'cart', "Removed product #{$productId} from cart", $productId);
            Session::setFlash('success', 'Item removed from cart.');
        } else {
            $db = Database::getInstance();
            $product = $db->fetch(
                "SELECT stock FROM products WHERE id = :id AND status = 'active' AND deleted_at IS NULL",
                ['id' => $productId]
            );

            if (!$product) {
                Cart::remove($productId);
                Session::setFlash('error', 'Product no longer available.');
                $this->redirect('/cart');
            }

            if ($quantity > $product['stock']) {
                Session::setFlash('error', 'Only ' . $product['stock'] . ' units available.');
                $quantity = $product['stock'];
            }

            Cart::update($productId, $quantity);
            Log::write(Auth::id(), 'update_cart', 'cart', "Updated product #{$productId} qty: {$quantity}", $productId);
            Session::setFlash('success', 'Cart updated.');
        }

        $this->redirect('/cart');
    }

    public function remove(int $id): void
    {
        if (!Request::validateCsrf(Request::post('csrf_token', ''))) {
            Session::setFlash('error', 'Invalid form token.');
            $this->redirect('/cart');
        }

        Cart::remove($id);
        Log::write(Auth::id(), 'remove_from_cart', 'cart', "Removed product #{$id} from cart", $id);
        Session::setFlash('success', 'Item removed from cart.');
        $this->redirect('/cart');
    }
}
