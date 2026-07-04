<?php

namespace App\Core;

class Auth
{
    public static function login(int $userId, array $userData = []): void
    {
        Session::set('user_id', $userId);
        Session::set('user', $userData);
        Session::set('logged_in', true);
    }

    public static function logout(): void
    {
        Session::destroy();
    }

    public static function isLoggedIn(): bool
    {
        return Session::get('logged_in', false);
    }

    public static function user(): ?array
    {
        return Session::get('user');
    }

    public static function id(): ?int
    {
        return Session::get('user_id');
    }

    public static function isAdmin(): bool
    {
        $user = self::user();
        return $user && ($user['role_slug'] === 'admin' || $user['role_slug'] === 'superadmin');
    }

    public static function hasAccess(): bool
    {
        $user = self::user();
        return $user && !empty($user['access_granted']);
    }

    public static function canAccessModule(string $module): bool
    {
        $user = self::user();

        if (!$user || empty($user['modules'])) {
            return false;
        }

        if ($user['role_slug'] === 'superadmin') {
            return true;
        }

        return in_array($module, $user['modules']);
    }

    public static function canModifyModule(string $module): bool
    {
        $user = self::user();

        if (!$user || empty($user['permissions'])) {
            return false;
        }

        if ($user['role_slug'] === 'superadmin') {
            return true;
        }

        return isset($user['permissions'][$module])
            && $user['permissions'][$module] === PERMISSION_MODIFY;
    }

    public static function canViewModule(string $module): bool
    {
        $user = self::user();

        if (!$user || empty($user['permissions'])) {
            return false;
        }

        if ($user['role_slug'] === 'superadmin') {
            return true;
        }

        return isset($user['permissions'][$module]);
    }
}
