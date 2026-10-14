<?php
/**
 * -------------------------------------------------------------------------
 *  Route definitions. $router and $request are provided by index.php.
 * -------------------------------------------------------------------------
 */

use App\Core\Session;

/** Small factory: instantiate a controller and inject context. */
$make = function (string $class) use ($request, $view, $config) {
    /** @var \App\Core\BaseController $controller */
    $controller = new $class();
    $controller->setContext($request, $view, $config, Session::user());
    return $controller;
};

/* =======================================================================
 *  FRONTEND (public site)
 * ===================================================================== */

$router->get('/',              fn($req, $p) => $make(\App\Controllers\HomeController::class)->index());
$router->get('/home',          fn($req, $p) => $make(\App\Controllers\HomeController::class)->index());
$router->get('/about',         fn($req, $p) => $make(\App\Controllers\PageController::class)->about());
$router->get('/contact',       fn($req, $p) => $make(\App\Controllers\PageController::class)->contact());
$router->post('/contact',      fn($req, $p) => $make(\App\Controllers\PageController::class)->submitContact());

// Destinations
$router->get('/destinations',            fn($req, $p) => $make(\App\Controllers\DestinationController::class)->index());
$router->get('/destinations/{slug}',     fn($req, $p) => $make(\App\Controllers\DestinationController::class)->show($p['slug']));

// Tours / Packages
$router->get('/tours',          fn($req, $p) => $make(\App\Controllers\TourController::class)->index());
$router->get('/tours/{slug}',   fn($req, $p) => $make(\App\Controllers\TourController::class)->show($p['slug']));

// Articles / Blog
$router->get('/articles',       fn($req, $p) => $make(\App\Controllers\ArticleController::class)->index());
$router->get('/articles/{slug}',fn($req, $p) => $make(\App\Controllers\ArticleController::class)->show($p['slug']));

// Search (AJAX)
$router->get('/search',         fn($req, $p) => $make(\App\Controllers\SearchController::class)->index());

// Booking request (AJAX from destination / tour booking form)
$router->post('/book',          fn($req, $p) => $make(\App\Controllers\BookingController::class)->store());

// Newsletter & contact AJAX
$router->post('/newsletter',    fn($req, $p) => $make(\App\Controllers\PageController::class)->subscribe());


/* =======================================================================
 *  ADMIN — authentication
 * ===================================================================== */
$router->get('/admin/login',    fn($req, $p) => $make(\App\Controllers\Admin\AuthController::class)->showLogin());
$router->post('/admin/login',   fn($req, $p) => $make(\App\Controllers\Admin\AuthController::class)->login());
$router->get('/admin/logout',   fn($req, $p) => $make(\App\Controllers\Admin\AuthController::class)->logout());

/* =======================================================================
 *  ADMIN — dashboard & content management
 * ===================================================================== */
$router->get('/admin',                    fn($req, $p) => $make(\App\Controllers\Admin\DashboardController::class)->index());

// Bookings (hero search submissions / tour booking requests)
$router->get('/admin/bookings',           fn($req, $p) => $make(\App\Controllers\Admin\BookingController::class)->index());
$router->post('/admin/bookings/status',   fn($req, $p) => $make(\App\Controllers\Admin\BookingController::class)->updateStatus());
$router->post('/admin/bookings/delete',   fn($req, $p) => $make(\App\Controllers\Admin\BookingController::class)->destroy());

// Destinations
$router->get('/admin/destinations',            fn($req, $p) => $make(\App\Controllers\Admin\DestinationController::class)->index());
$router->post('/admin/destinations/store',     fn($req, $p) => $make(\App\Controllers\Admin\DestinationController::class)->store());
$router->post('/admin/destinations/update',    fn($req, $p) => $make(\App\Controllers\Admin\DestinationController::class)->update());
$router->post('/admin/destinations/delete',    fn($req, $p) => $make(\App\Controllers\Admin\DestinationController::class)->destroy());

// Tours / Packages
$router->get('/admin/tours',                   fn($req, $p) => $make(\App\Controllers\Admin\TourController::class)->index());
$router->get('/admin/tours/get',               fn($req, $p) => $make(\App\Controllers\Admin\TourController::class)->getOne());
$router->post('/admin/tours/store',            fn($req, $p) => $make(\App\Controllers\Admin\TourController::class)->store());
$router->post('/admin/tours/update',           fn($req, $p) => $make(\App\Controllers\Admin\TourController::class)->update());
$router->post('/admin/tours/delete',           fn($req, $p) => $make(\App\Controllers\Admin\TourController::class)->destroy());

// Articles
$router->get('/admin/articles',                fn($req, $p) => $make(\App\Controllers\Admin\ArticleController::class)->index());
$router->post('/admin/articles/store',         fn($req, $p) => $make(\App\Controllers\Admin\ArticleController::class)->store());
$router->post('/admin/articles/update',        fn($req, $p) => $make(\App\Controllers\Admin\ArticleController::class)->update());
$router->post('/admin/articles/delete',        fn($req, $p) => $make(\App\Controllers\Admin\ArticleController::class)->destroy());

// Services & benefits (the four icon cards)
$router->get('/admin/services',                fn($req, $p) => $make(\App\Controllers\Admin\ServiceController::class)->index());
$router->post('/admin/services/store',         fn($req, $p) => $make(\App\Controllers\Admin\ServiceController::class)->store());
$router->post('/admin/services/update',        fn($req, $p) => $make(\App\Controllers\Admin\ServiceController::class)->update());
$router->post('/admin/services/delete',        fn($req, $p) => $make(\App\Controllers\Admin\ServiceController::class)->destroy());

// Messages (contact form inbox)
$router->get('/admin/messages',                fn($req, $p) => $make(\App\Controllers\Admin\MessageController::class)->index());
$router->get('/admin/messages/{id}',           fn($req, $p) => $make(\App\Controllers\Admin\MessageController::class)->show($p['id']));
$router->post('/admin/messages/read',          fn($req, $p) => $make(\App\Controllers\Admin\MessageController::class)->toggleRead());
$router->post('/admin/messages/delete',        fn($req, $p) => $make(\App\Controllers\Admin\MessageController::class)->destroy());

// Newsletter subscribers
$router->get('/admin/newsletter',              fn($req, $p) => $make(\App\Controllers\Admin\NewsletterController::class)->index());
$router->post('/admin/newsletter/delete',      fn($req, $p) => $make(\App\Controllers\Admin\NewsletterController::class)->destroy());

// Site settings (hero text, contact info, socials, appearance, about page)
$router->get('/admin/settings',                fn($req, $p) => $make(\App\Controllers\Admin\SettingController::class)->index());
$router->post('/admin/settings/update',        fn($req, $p) => $make(\App\Controllers\Admin\SettingController::class)->update());

// Users (admins) management
$router->get('/admin/users',                   fn($req, $p) => $make(\App\Controllers\Admin\UserController::class)->index());
$router->post('/admin/users/store',            fn($req, $p) => $make(\App\Controllers\Admin\UserController::class)->store());
$router->post('/admin/users/update',           fn($req, $p) => $make(\App\Controllers\Admin\UserController::class)->update());
$router->post('/admin/users/delete',           fn($req, $p) => $make(\App\Controllers\Admin\UserController::class)->destroy());

