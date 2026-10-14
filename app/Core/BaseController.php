<?php

namespace App\Core;

/**
 * Base controller. Gives every controller access to the request, view
 * renderer and helpers for redirects / JSON responses.
 */
abstract class BaseController
{
    protected Request $request;
    protected View    $view;
    protected array   $config;
    protected ?array  $auth = null;   // currently authenticated admin/user

    public function setContext(Request $request, View $view, array $config, ?array $auth = null): void
    {
        $this->request = $request;
        $this->view    = $view;
        $this->config  = $config;
        $this->auth    = $auth;
    }

    /** Render a frontend view. */
    protected function view(string $name, array $data = []): string
    {
        return $this->view->render($name, $data);
    }

    /** Send a JSON response (used by AJAX endpoints). */
    protected function json($data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    /** JSON success shortcut. */
    protected function ok($data = null, string $message = 'ok'): void
    {
        $this->json(['status' => 'success', 'message' => $message, 'data' => $data]);
    }

    /** JSON error shortcut. */
    protected function fail(string $message = 'error', int $status = 400, $errors = null): void
    {
        $this->json(['status' => 'error', 'message' => $message, 'errors' => $errors], $status);
    }

    protected function redirect(string $url): void
    {
        header('Location: ' . $url);
        exit;
    }

    protected function baseUrl(string $path = ''): string
    {
        $base = rtrim($this->config['app']['base_url'] ?? '', '/');
        return $base . '/' . ltrim($path, '/');
    }

    /** Require an authenticated admin, else bounce to login. */
    protected function requireAdmin(): void
    {
        if (empty($this->auth)) {
            if ($this->request->wantsJson()) {
                $this->fail('دسترسی غیرمجاز', 401);
            }
            $this->redirect($this->baseUrl('admin/login'));
        }
    }
}
