<?php

use App\Core\Session;
use App\Core\Auth;
use App\Core\Request;

function view(string $view, array $data = [], string $layout = 'main'): void
{
    \App\Core\View::render($view, $data, $layout);
}

function escape(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function old(string $key, string $default = ''): string
{
    $oldInput = Session::get('old_input', []);
    return $oldInput[$key] ?? $_POST[$key] ?? $default;
}

function error(string $key): ?string
{
    $errors = Session::get('errors', []);
    return $errors[$key] ?? null;
}

function hasErrors(): bool
{
    return !empty(Session::get('errors', []));
}

function flash(string $key): ?string
{
    return Session::getFlash($key);
}

function hasFlash(string $key): bool
{
    return Session::hasFlash($key);
}

function isLoggedIn(): bool
{
    return Auth::isLoggedIn();
}

function currentUser(): ?array
{
    return Auth::user();
}

function csrfField(): string
{
    $token = Request::csrfToken();
    return '<input type="hidden" name="csrf_token" value="' . $token . '">';
}

function asset(string $path): string
{
    return APP_URL . '/' . ltrim($path, '/');
}

function url(string $path = ''): string
{
    return APP_URL . '/' . ltrim($path, '/');
}

function formatPrice(float $price): string
{
    return '$' . number_format($price, 2);
}

function truncate(string $text, int $length = 100): string
{
    if (mb_strlen($text) <= $length) {
        return $text;
    }
    return mb_substr($text, 0, $length) . '...';
}

function slugify(string $text): string
{
    $text = mb_strtolower($text, 'UTF-8');
    $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
    $text = preg_replace('/[\s-]+/', '-', $text);
    return trim($text, '-');
}

function cartCount(): int
{
    return \App\Helpers\Cart::count();
}
