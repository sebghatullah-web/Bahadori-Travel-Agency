<?php

namespace App\Core;

/**
 * Handles a single uploaded image file. Validates type & size, generates a
 * unique name and stores it under public/assets/uploads. Returns the stored
 * file name (to be saved in DB) on success.
 */
class Upload
{
    public static function image(array $file, array $config): string
    {
        if (!isset($file['error']) || is_array($file['error'])) {
            throw new \RuntimeException('پارامترهای آپلود نامعتبر است.');
        }

        switch ($file['error']) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                throw new \RuntimeException('هیچ فایلی انتخاب نشده است.');
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                throw new \RuntimeException('حجم فایل بیش از حد مجاز است.');
            default:
                throw new \RuntimeException('خطای نامشخص در آپلود فایل.');
        }

        $maxSize = $config['upload']['max_size'] ?? (4 * 1024 * 1024);
        if ($file['size'] > $maxSize) {
            throw new \RuntimeException('حجم فایل بیش از حد مجاز است.');
        }

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = $config['upload']['allowed'] ?? ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg'];
        if (!in_array($ext, $allowed, true)) {
            throw new \RuntimeException('پسوند فایل مجاز نیست. فرمت‌های مجاز: ' . implode(', ', $allowed));
        }

        $dir = $config['upload']['dir'];
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        // Verify it really is an image (except SVG which is XML).
        if ($ext !== 'svg') {
            $check = @getimagesize($file['tmp_name']);
            if ($check === false) {
                throw new \RuntimeException('فایل انتخاب‌شده تصویر معتبری نیست.');
            }
        }

        $name = bin2hex(random_bytes(8)) . '_' . time() . '.' . $ext;
        $dest = rtrim($dir, '/\\') . DIRECTORY_SEPARATOR . $name;

        if (!move_uploaded_file($file['tmp_name'], $dest)) {
            throw new \RuntimeException('انتقال فایل به پوشهٔ مقصد ناموفق بود.');
        }

        return $name;
    }

    /** Delete a previously uploaded file (ignores missing / remote files). */
    public static function remove(?string $fileName, array $config): void
    {
        if (empty($fileName)) {
            return;
        }
        if (preg_match('#^https?://#i', $fileName)) {
            return; // remote URL, nothing to delete
        }
        $path = rtrim($config['upload']['dir'], '/\\') . DIRECTORY_SEPARATOR . ltrim($fileName, '/\\');
        if (is_file($path)) {
            @unlink($path);
        }
    }
}
