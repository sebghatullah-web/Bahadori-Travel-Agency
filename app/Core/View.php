<?php

namespace App\Core;

/**
 * Minimal view engine. Views are plain PHP files. Layout support lets a
 * view be injected into a shared layout (header / footer / sidebar).
 *
 * Usage inside a view to render inside a layout:
 *   $this->layout('admin', ['title' => 'Dashboard', ...]);
 */
class View
{
    private array  $shared = [];
    private string $viewPath;
    private string $layout;
    private array  $layoutData = [];

    public function __construct(string $viewPath)
    {
        $this->viewPath = rtrim($viewPath, '/\\');
    }

    public function share(string $key, $value): void
    {
        $this->shared[$key] = $value;
    }

    /** Render a view file into a string. */
    public function render(string $view, array $data = []): string
    {
        $file = $this->viewPath . '/' . ltrim($view, '/') . '.php';
        if (!is_file($file)) {
            throw new \RuntimeException("View not found: {$view} ({$file})");
        }

        $data = array_merge($this->shared, $data);
        // Expose the renderer to views via $view variable name.
        extract($data, EXTR_SKIP);

        ob_start();
        require $file;
        $content = ob_get_clean();

        // If a layout was requested, wrap the content.
        if (!empty($this->layout)) {
            return $this->renderLayout($content, $data);
        }

        return $content;
    }

    protected function layout(string $name, array $data = []): void
    {
        $this->layout     = $name;
        $this->layoutData = $data;
    }

    private function renderLayout(string $content, array $data): string
    {
        // Make content available to the layout.
        $data['content'] = $content;
        $data            = array_merge($this->shared, $this->layoutData, $data);
        extract($data, EXTR_SKIP);

        $layoutFile = $this->viewPath . '/layouts/' . $this->layout . '.php';
        if (!is_file($layoutFile)) {
            throw new \RuntimeException("Layout not found: {$this->layout} ({$layoutFile})");
        }

        ob_start();
        require $layoutFile;
        return ob_get_clean();
    }

    /** Escape helper (views use the global e() function; this is a static alias). */
    public static function e($value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    /**
     * Render a partial view inline (keeps its own variable scope via extract).
     * The partial file lives in the same view root.
     */
    public function partial(string $name, array $data = []): string
    {
        $file = $this->viewPath . '/' . ltrim($name, '/') . '.php';
        if (!is_file($file)) {
            throw new \RuntimeException("Partial not found: {$name} ({$file})");
        }
        // Give partials access to shared data + renderer helpers.
        $data = array_merge($this->shared, $data);
        $view = $this;
        extract($data, EXTR_SKIP);
        ob_start();
        require $file;
        return ob_get_clean();
    }
}
