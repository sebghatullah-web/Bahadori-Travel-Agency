<?php

/**
 * -------------------------------------------------------------------------
 *  Global helper functions
 * -------------------------------------------------------------------------
 */

use App\Core\Config;
use App\Core\Session;

if (!function_exists('config')) {
    function config(string $key, $default = null)
    {
        return Config::get($key, $default);
    }
}

if (!function_exists('e')) {
    /** HTML escape (short alias used throughout the views). */
    function e($value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}

if (!function_exists('base_url')) {
    function base_url(string $path = ''): string
    {
        $base = rtrim(Config::get('app.base_url', ''), '/');
        return $base . '/' . ltrim($path, '/');
    }
}

if (!function_exists('asset')) {
    function asset(string $path = ''): string
    {
        return base_url('assets/' . ltrim($path, '/'));
    }
}

if (!function_exists('upload_url')) {
    /** Public URL of an uploaded image (stored under assets/uploads). */
    function upload_url(?string $file): string
    {
        if (empty($file)) {
            return asset('images/placeholder.jpg');
        }
        // Already a full URL (seeded remote images).
        if (preg_match('#^https?://#i', $file)) {
            return $file;
        }
        return base_url('assets/uploads/' . ltrim($file, '/'));
    }
}

if (!function_exists('csrf_token')) {
    function csrf_token(): string
    {
        return Session::csrfToken();
    }
}

if (!function_exists('csrf_field')) {
    function csrf_field(): string
    {
        return '<input type="hidden" name="_csrf" value="' . csrf_token() . '">';
    }
}

if (!function_exists('fa_number')) {
    /** Convert western digits to Persian digits for display. */
    function fa_number($value): string
    {
        $fa = ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'];
        $en = ['0','1','2','3','4','5','6','7','8','9'];
        return str_replace($en, $fa, (string) $value);
    }
}

if (!function_exists('slugify')) {
    /** Create an ASCII slug. For non-latin titles returns a random token. */
    function slugify(string $text, string $prefix = ''): string
    {
        $text = trim($text);
        if ($text === '' || !preg_match('/[A-Za-z]/', $text)) {
            return ($prefix ? $prefix . '-' : '') . bin2hex(random_bytes(4));
        }
        $text = strtolower($text);
        $text = preg_replace('/[^a-z0-9]+/', '-', $text);
        $slug = trim($text, '-');
        return $slug !== '' ? $slug : (($prefix ? $prefix . '-' : '') . bin2hex(random_bytes(4)));
    }
}

if (!function_exists('format_price')) {
    /** Format a price with thousands separators. */
    function format_price($value): string
    {
        return number_format((float) $value);
    }
}

if (!function_exists('old')) {
    /** Repopulate a form field after a validation error. */
    function old(string $key, $default = '')
    {
        return Session::get('_old', [])[$key] ?? $default;
    }
}
