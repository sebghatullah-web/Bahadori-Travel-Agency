<?php

namespace App\Core;

/**
 * Lightweight regex-based router with support for route parameters
 * (e.g. /tours/{id}) and middleware-less controller dispatch.
 */
class Router
{
    /** @var array<string, array<callable>> */
    private array $routes = [];

    private string $basePath = '';

    public function setBasePath(string $base): void
    {
        $this->basePath = rtrim($base, '/');
    }

    public function add(string $method, string $pattern, callable $handler): void
    {
        $this->routes[$method][] = [
            'pattern' => $pattern,
            'handler' => $handler,
        ];
    }

    public function get(string $pattern, callable $handler): void    { $this->add('GET', $pattern, $handler); }
    public function post(string $pattern, callable $handler): void   { $this->add('POST', $pattern, $handler); }

    /**
     * Convert a friendly pattern like "/tours/{id}" into a regex.
     */
    private function compile(string $pattern): string
    {
        // Normalise to a single leading slash (root "/" stays "/").
        $pattern = '/' . trim($pattern, '/');
        $pattern = preg_replace('#\{([a-zA-Z_][a-zA-Z0-9_]*)\}#', '(?P<$1>[^/]+)', $pattern);
        return '#^' . $pattern . '$#';
    }

    /**
     * @throws NotFoundException when nothing matches.
     */
    public function dispatch(Request $request): mixed
    {
        $method = $request->method;
        $uri    = $request->uri;

        // HEAD should behave like GET.
        if ($method === 'HEAD') {
            $method = 'GET';
        }

        foreach ($this->routes[$method] ?? [] as $route) {
            $regex = $this->compile($route['pattern']);
            if (preg_match($regex, $uri, $matches)) {
                // Keep only named parameters.
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                return call_user_func($route['handler'], $request, $params);
            }
        }

        // Try the other-method version to produce a 405 hint.
        foreach ($this->routes as $m => $set) {
            foreach ($set as $route) {
                if (preg_match($this->compile($route['pattern']), $uri)) {
                    throw new HttpException('Method Not Allowed', 405);
                }
            }
        }

        throw new NotFoundException("Route not found: {$uri}");
    }
}
