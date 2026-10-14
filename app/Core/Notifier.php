<?php

namespace App\Core;

/**
 * Bridges PHP events to the Node.js notification server (see /node-server).
 * Uses a non-blocking cURL POST so a slow / down node server never blocks
 * the request. All calls are wrapped in try/catch and silently ignored on
 * failure – the core site works even when Node is offline.
 */
class Notifier
{
    public static function emit(array $payload): void
    {
        $config = Config::get('node');
        if (empty($config['host'])) {
            return;
        }

        $url = "http://{$config['host']}:{$config['port']}/notify";
        $json = json_encode($payload, JSON_UNESCAPED_UNICODE);

        // Only attempt if cURL is available.
        if (!function_exists('curl_init')) {
            return;
        }

        try {
            $ch = curl_init($url);
            curl_setopt_array($ch, [
                CURLOPT_POST           => true,
                CURLOPT_POSTFIELDS     => $json,
                CURLOPT_HTTPHEADER     => [
                    'Content-Type: application/json',
                    'X-Auth-Token: ' . ($config['secret'] ?? ''),
                ],
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT_MS     => 800,
                CURLOPT_CONNECTTIMEOUT_MS => 400,
                CURLOPT_NOSIGNAL       => 1,
            ]);
            curl_exec($ch);
            curl_close($ch);
        } catch (\Throwable $e) {
            error_log('Notifier error: ' . $e->getMessage());
        }
    }
}
