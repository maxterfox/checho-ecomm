<?php

namespace App\Core;

class Auth
{
    public static function login(int $userId, array $userData): void
    {
        Session::set('user_id', $userId);
        Session::set('user', $userData);
        Session::set('logged_in', true);
        session_regenerate_id(true);
    }

    public static function logout(): void
    {
        $_SESSION = [];
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        session_destroy();
    }

    public static function isLoggedIn(): bool
    {
        return Session::get('logged_in', false);
    }

    public static function id(): ?int
    {
        return Session::get('user_id');
    }

    public static function user(): ?array
    {
        return Session::get('user');
    }

    public static function hasAccess(): bool
    {
        $user = self::user();
        return $user && ($user['access_granted'] ?? false);
    }

    public static function isAdmin(): bool
    {
        $user = self::user();
        return $user && in_array($user['role_slug'] ?? '', ['admin', 'staff'], true);
    }

    public static function roleSlug(): ?string
    {
        $user = self::user();
        return $user['role_slug'] ?? null;
    }

    public static function roleName(): ?string
    {
        $user = self::user();
        return $user['role_name'] ?? null;
    }
}
