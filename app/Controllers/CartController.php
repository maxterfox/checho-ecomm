<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Models\Product;

class CartController extends Controller
{
    public function index(): void
    {
        $cart = Session::get('cart', []);
        $total = 0;

        foreach ($cart as &$item) {
            $product = Product::find($item['product_id']);
            $item['product'] = $product;
            $total += $product['price'] * $item['quantity'];
        }

        $this->view('cart/index', [
            'cart' => $cart,
            'total' => $total,
        ]);
    }

    public function add(): void
    {
        $productId = (int) Request::post('product_id');
        $quantity = max(1, (int) Request::post('quantity', 1));

        $product = Product::find($productId);
        if (!$product) {
            Session::setFlash('error', 'Product not found.');
            $this->back();
        }

        $cart = Session::get('cart', []);

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;
        } else {
            $cart[$productId] = [
                'product_id' => $productId,
                'quantity' => $quantity,
            ];
        }

        Session::set('cart', $cart);
        Session::setFlash('success', $product['name'] . ' added to cart.');
        $this->back();
    }

    public function update(): void
    {
        $productId = (int) Request::post('product_id');
        $quantity = max(0, (int) Request::post('quantity'));

        $cart = Session::get('cart', []);

        if ($quantity <= 0) {
            unset($cart[$productId]);
        } elseif (isset($cart[$productId])) {
            $cart[$productId]['quantity'] = $quantity;
        }

        Session::set('cart', $cart);
        $this->back();
    }

    public function remove(): void
    {
        $productId = (int) Request::post('product_id');
        $cart = Session::get('cart', []);
        unset($cart[$productId]);
        Session::set('cart', $cart);
        $this->back();
    }
}
