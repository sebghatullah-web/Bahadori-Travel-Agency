<?php

namespace App\Core;

/**
 * Immutable-ish request wrapper. Normalises method, URI, GET / POST / JSON
 * input so controllers never touch super-globals directly.
 */
class Request
{
    public string $method;
    public string $uri;      // path only, no query string
    public array  $query;    // $_GET
    public array  $body;     // merged $_POST or JSON body

    public function __construct()
    {
        $this->method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');

        $uri  = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
        $base = rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? ''), '/\\');
        if ($base !== '' && str_starts_with($uri, $base)) {
            $uri = substr($uri, strlen($base));
        }
        // Strip a leading index.php so PATH_INFO style URLs (/index.php/tours)
        // work on hosts where mod_rewrite is unavailable.
        $uri = preg_replace('#^/index\.php#', '', $uri);
        $this->uri   = '/' . trim($uri, '/');
        $this->query = $_GET;

        // Accept both classic form posts and JSON payloads (AJAX).
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        if (str_contains($contentType, 'application/json')) {
            $raw = file_get_contents('php://input');
            $this->body = json_decode($raw, true) ?: [];
        } else {
            $this->body = $_POST;
        }
    }

    public function isPost(): bool   { return $this->method === 'POST'; }
    public function isGet(): bool    { return $this->method === 'GET'; }

    /** AJAX detection (fetch with X-Requested-With). */
    public function isAjax(): bool
    {
        return (strtolower($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'xmlhttprequest');
    }

    public function wantsJson(): bool
    {
        $accept = $_SERVER['HTTP_ACCEPT'] ?? '';
        return $this->isAjax() || str_contains($accept, 'application/json');
    }

    public function input(string $key, $default = null)
    {
        return $this->body[$key] ?? $this->query[$key] ?? $default;
    }

    public function all(): array
    {
        return array_merge($this->query, $this->body);
    }

    public function str(string $key, string $default = ''): string
    {
        $v = $this->input($key, $default);
        return is_scalar($v) ? trim((string) $v) : $default;
    }

    public function int(string $key, int $default = 0): int
    {
        return (int) $this->input($key, $default);
    }

    public function bool(string $key): bool
    {
        $v = $this->input($key);
        return in_array($v, [1, '1', 'true', 'on', true], true);
    }
}
