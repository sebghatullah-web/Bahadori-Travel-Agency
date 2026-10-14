<?php
/**
 * -------------------------------------------------------------------------
 *  Front controller  —  every request enters through here.
 * -------------------------------------------------------------------------
 */

declare(strict_types=1);

use App\Core\Config;
use App\Core\Database;
use App\Core\HttpException;
use App\Core\NotFoundException;
use App\Core\Request;
use App\Core\Router;
use App\Core\Session;
use App\Core\View;

define('BASE_PATH', dirname(__DIR__));

// -------------------------------------------------------------------------
// 1. Error handling (behaviour driven by app.env in config).
// -------------------------------------------------------------------------
error_reporting(E_ALL);
ini_set('display_errors', '0');

// -------------------------------------------------------------------------
// 2. Composer-less PSR-4 autoloader for the App\ namespace.
// -------------------------------------------------------------------------
spl_autoload_register(function (string $class): void {
    $prefix = 'App\\';
    if (!str_starts_with($class, $prefix)) {
        return;
    }
    $relative = substr($class, strlen($prefix));
    $file = BASE_PATH . '/app/' . str_replace('\\', '/', $relative) . '.php';
    if (is_file($file)) {
        require $file;
    }
});

// -------------------------------------------------------------------------
// 3. Bootstrap: config, helpers, session, timezone.
// -------------------------------------------------------------------------
require BASE_PATH . '/app/helpers.php';

Config::load(BASE_PATH . '/config/config.php');
$config = Config::all();

date_default_timezone_set($config['app']['timezone'] ?? 'UTC');
mb_internal_encoding('UTF-8');

// Auto-detect base URL when left empty (works on shared hosting too).
if (empty($config['app']['base_url'])) {
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host   = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $script = $_SERVER['SCRIPT_NAME'] ?? '';            // /BahadoriTravelAgency/public/index.php
    // Drop the trailing /index.php to get the project's public dir.
    $dir    = rtrim(str_replace('\\', '/', dirname($script)), '/');
    if ($dir === '.') {
        $dir = '';
    }
    $config['app']['base_url'] = $scheme . '://' . $host . $dir;
    Config::setSection('app', $config['app']);
}

Session::start();

// -------------------------------------------------------------------------
// 4. Build router and register routes.
// -------------------------------------------------------------------------
$request = new Request();
$router  = new Router();
$router->setBasePath(parse_url($config['app']['base_url'], PHP_URL_PATH) ?? '');

$view = new View(BASE_PATH . '/app/Views');
$view->share('config', $config);
$view->share('baseUrl', $config['app']['base_url']);
$view->share('auth', Session::user());
$view->share('csrf', Session::csrfToken());

// Route definitions (kept in one place for clarity).
$routeFile = BASE_PATH . '/config/routes.php';
if (is_file($routeFile)) {
    require $routeFile;   // expects $router, $request to be in scope
}

// -------------------------------------------------------------------------
// 5. Dispatch with graceful error pages.
// -------------------------------------------------------------------------
try {
    $response = $router->dispatch($request);
    if (is_string($response)) {
        echo $response;
    }
} catch (NotFoundException $e) {
    http_response_code(404);
    $errorView = new View(BASE_PATH . '/app/Views');
    $errorView->share('config', $config);
    $errorView->share('baseUrl', $config['app']['base_url']);
    echo $errorView->render('frontend/404');
} catch (HttpException $e) {
    http_response_code($e->getStatus());
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
} catch (\PDOException $e) {
    http_response_code(500);
    error_log('DB Error: ' . $e->getMessage());
    if (($config['app']['debug'] ?? false)) {
        echo '<pre style="direction:ltr;padding:20px;background:#fff3f3;color:#b00020">'
            . 'Database error: ' . e($e->getMessage()) . '</pre>';
    } else {
        echo 'خطای داخلی سرور رخ داده است.';
    }
} catch (\Throwable $e) {
    http_response_code(500);
    error_log('App Error: ' . $e->getMessage());
    if (($config['app']['debug'] ?? false)) {
        echo '<pre style="direction:ltr;padding:20px;background:#fff3f3;color:#b00020">'
            . e($e->getMessage()) . "\n" . e($e->getFile()) . ':' . $e->getLine() . '</pre>';
    } else {
        echo 'خطای داخلی سرور رخ داده است.';
    }
}
