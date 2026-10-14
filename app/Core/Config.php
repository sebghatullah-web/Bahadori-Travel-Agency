<?php

namespace App\Core;

/**
 * Reads the single config array (config/config.php) and offers a dot
 * access helper.
 */
class Config
{
    private static array $data = [];
    private static bool  $loaded = false;

    public static function load(string $file): void
    {
        if (is_file($file)) {
            self::$data = require $file;
        }
        self::$loaded = true;
    }

    /** Dot access: Config::get('db.host'). */
    public static function get(string $key, $default = null)
    {
        $keys  = explode('.', $key);
        $value = self::$data;
        foreach ($keys as $k) {
            if (is_array($value) && array_key_exists($k, $value)) {
                $value = $value[$k];
            } else {
                return $default;
            }
        }
        return $value;
    }

    /** Set a top-level config section (used to inject the auto-detected base URL). */
    public static function setSection(string $key, array $value): void
    {
        self::$data[$key] = $value;
    }

    public static function all(): array
    {
        return self::$data;
    }
}
