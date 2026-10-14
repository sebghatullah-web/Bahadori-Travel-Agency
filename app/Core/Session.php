<?php

namespace App\Core;

/**
 * Session wrapper + authentication helpers for the admin area.
 */
class Session
{
    public const AUTH_KEY = 'admin_auth';

    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start([
                'cookie_httponly' => true,
                'cookie_samesite' => 'Lax',
                'use_strict_mode' => true,
            ]);
        }
    }

    public static function set(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    public static function get(string $key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    public static function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    public static function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }

    public static function destroy(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
        $_SESSION = [];
    }

    /** Regenerate id on login (session fixation protection). */
    public static function regenerate(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_regenerate_id(true);
        }
    }

    // ---- Authentication -------------------------------------------------

    public static function login(array $user): void
    {
        self::regenerate();
        $_SESSION[self::AUTH_KEY] = [
            'id'    => $user['id'],
            'name'  => $user['name'] ?? '',
            'email' => $user['email'] ?? '',
            'role'  => $user['role'] ?? 'admin',
        ];
    }

    public static function user(): ?array
    {
        return $_SESSION[self::AUTH_KEY] ?? null;
    }

    public static function check(): bool
    {
        return !empty($_SESSION[self::AUTH_KEY]);
    }

    public static function logout(): void
    {
        unset($_SESSION[self::AUTH_KEY]);
        self::regenerate();
    }

    // ---- Flash messages -------------------------------------------------

    public static function flash(string $key, $value = null)
    {
        if ($value !== null) {
            $_SESSION['_flash'][$key] = $value;
            return null;
        }
        $v = $_SESSION['_flash'][$key] ?? null;
        unset($_SESSION['_flash'][$key]);
        return $v;
    }

    // ---- CSRF -----------------------------------------------------------

    public static function csrfToken(): string
    {
        if (empty($_SESSION['_csrf'])) {
            $_SESSION['_csrf'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['_csrf'];
    }

    public static function verifyCsrf(?string $token): bool
    {
        return !empty($_SESSION['_csrf']) && is_string($token) && hash_equals($_SESSION['_csrf'], $token);
    }
}
