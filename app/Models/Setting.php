<?php

namespace App\Models;

use App\Core\Database;
use App\Core\Model;

/**
 * Settings are stored as key/value rows. This model loads them all once and
 * caches them in a static array for the request.
 */
class Setting extends Model
{
    protected string $table = 'settings';
    protected string $primaryKey = 'setting_key';

    private static array $cache = [];

    /** Return every setting as a key => value map (cached). */
    public static function allMap(): array
    {
        if (empty(self::$cache)) {
            $rows = Database::all('SELECT setting_key, setting_value FROM settings');
            foreach ($rows as $row) {
                self::$cache[$row['setting_key']] = $row['setting_value'];
            }
        }
        return self::$cache;
    }

    public static function get(string $key, $default = '')
    {
        $map = self::allMap();
        return $map[$key] ?? $default;
    }

    /** Insert or update a setting. */
    public static function set(string $key, $value, string $group = 'general'): void
    {
        Database::run(
            'INSERT INTO settings (setting_key, setting_value, setting_group)
             VALUES (?, ?, ?)
             ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)',
            [$key, $value, $group]
        );
        self::$cache[$key] = $value;
    }

    public static function group(string $group): array
    {
        return Database::all(
            'SELECT setting_key, setting_value FROM settings WHERE setting_group = ?',
            [$group]
        );
    }
}
