<?php
/**
 * -------------------------------------------------------------------------
 *  Frontend layout (public site) — header + navbar + footer.
 *  Expects: $content, $settings, $baseUrl, $auth, $csrf  (shared by View).
 * -------------------------------------------------------------------------
 */
use App\Core\Session;

$siteName = $settings['site_name'] ?? 'بهادری تراول ایجنسی';
$tagline  = $settings['site_tagline'] ?? 'آژانس مسافرتی';
$phone    = $settings['contact_phone'] ?? '';
$primary  = $settings['color_primary'] ?? '#c9a24b';
$dark     = $settings['color_dark'] ?? '#101828';
$light    = $settings['color_light'] ?? '#f6f1e7';

$currentUri = $_SERVER['REQUEST_URI'] ?? '/';
$navActive = function ($path) use ($currentUri) {
    $path = trim($path, '/');
    return str_contains($currentUri, '/' . $path) ? 'active' : '';
};

$flashSuccess = Session::flash('success');
$flashError   = Session::flash('error');
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= !empty($pageTitle) ? e($pageTitle) . ' | ' . e($siteName) : e($siteName) . ' — ' . e($tagline) ?></title>
    <meta name="description" content="<?= e($settings['about_intro'] ?? 'آژانس مسافرتی بهادری — تور، هتل و بلیط هواپیما') ?>">

    <!-- Bootstrap 5 RTL -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <!-- Site theme -->
    <link rel="stylesheet" href="<?= e(asset('css/style.css')) ?>">
    <style>
        :root {
            --bt-primary: <?= e($primary) ?>;
            --bt-dark: <?= e($dark) ?>;
            --bt-light: <?= e($light) ?>;
        }
    </style>
    <!-- Persian web fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;700;900&display=swap" rel="stylesheet">
</head>
<body class="bt-front">

<!-- =======================  TOP BAR  ======================= -->
<div class="bt-topbar">
    <div class="container d-flex align-items-center justify-content-between">
        <a href="<?= e(base_url('contact')) ?>" class="bt-topbar-phone">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
            <?= e($phone) ?>
        </a>
        <div class="d-flex align-items-center gap-3">
            <?php if ($auth): ?>
                <a href="<?= e(base_url('admin')) ?>" class="bt-topbar-link">پنل مدیریت</a>
                <a href="<?= e(base_url('admin/logout')) ?>" class="bt-topbar-link">خروج</a>
            <?php else: ?>
                <a href="<?= e(base_url('admin/login')) ?>" class="bt-topbar-link">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    ورود
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- =======================  NAVBAR  ======================= -->
<nav class="navbar navbar-expand-lg bt-navbar sticky-top">
    <div class="container">
        <a class="navbar-brand bt-brand" href="<?= e(base_url('/')) ?>">
            <span class="bt-brand-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M21 16v-2l-8-5V3.5a1.5 1.5 0 0 0-3 0V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5L21 16z"/></svg>
            </span>
            <span class="bt-brand-text"><?= e($siteName) ?><small><?= e($tagline) ?></small></span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#btMainNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="btMainNav">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link <?= $navActive('home') ?>" href="<?= e(base_url('/')) ?>">خانه</a></li>
                <li class="nav-item"><a class="nav-link <?= $navActive('destinations') ?>" href="<?= e(base_url('destinations')) ?>">مقاصد</a></li>
                <li class="nav-item"><a class="nav-link <?= $navActive('tours') ?>" href="<?= e(base_url('tours')) ?>">تورها و پکیج‌ها</a></li>
                <li class="nav-item"><a class="nav-link <?= $navActive('about') ?>" href="<?= e(base_url('about')) ?>">درباره ما</a></li>
                <li class="nav-item"><a class="nav-link <?= $navActive('articles') ?>" href="<?= e(base_url('articles')) ?>">مقالات</a></li>
                <li class="nav-item"><a class="nav-link <?= $navActive('contact') ?>" href="<?= e(base_url('contact')) ?>">تماس با ما</a></li>
            </ul>
            <a href="<?= e(base_url('contact')) ?>" class="btn bt-btn-primary bt-nav-cta">مشاوره رایگان</a>
        </div>
    </div>
</nav>

<?php if ($flashSuccess || $flashError): ?>
    <div class="container mt-3">
        <div class="alert <?= $flashSuccess ? 'alert-success' : 'alert-danger' ?> alert-dismissible fade show" role="alert">
            <?= e($flashSuccess ?: $flashError) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
<?php endif; ?>

<main class="bt-main">
    <?= $content ?>
</main>

<!-- =======================  FOOTER  ======================= -->
<footer class="bt-footer" style="background:var(--bt-dark)">
    <div class="container py-5">
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <h5 class="bt-footer-title"><?= e($siteName) ?></h5>
                <p class="bt-footer-text"><?= e($settings['about_intro'] ?? '') ?></p>
                <div class="bt-social">
                    <a href="<?= e($settings['social_youtube'] ?? '#') ?>" aria-label="YouTube"><svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M23 12s0-3.9-.5-5.8a3 3 0 0 0-2.1-2.1C18.5 3.5 12 3.5 12 3.5s-6.5 0-8.4.6A3 3 0 0 0 1.5 6.2C1 8.1 1 12 1 12s0 3.9.5 5.8a3 3 0 0 0 2.1 2.1c1.9.6 8.4.6 8.4.6s6.5 0 8.4-.6a3 3 0 0 0 2.1-2.1C23 15.9 23 12 23 12zM9.8 15.5v-7l6 3.5-6 3.5z"/></svg></a>
                    <a href="<?= e($settings['social_twitter'] ?? '#') ?>" aria-label="Twitter"><svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53A4.48 4.48 0 0 0 12 7.5v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"/></svg></a>
                    <a href="<?= e($settings['social_instagram'] ?? '#') ?>" aria-label="Instagram"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5"/><circle cx="12" cy="12" r="4"/></svg></a>
                    <a href="<?= e($settings['social_telegram'] ?? '#') ?>" aria-label="Telegram"><svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M21.5 3.5 2.5 10.5l5 2 2 6 3-4 5 4 4-15z"/></svg></a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <h5 class="bt-footer-title">خدمات ما</h5>
                <ul class="bt-footer-links">
                    <li><a href="<?= e(base_url('tours')) ?>">تورهای خارجی</a></li>
                    <li><a href="<?= e(base_url('tours')) ?>">رزرو هتل</a></li>
                    <li><a href="<?= e(base_url('tours')) ?>">بلیط هواپیما</a></li>
                    <li><a href="<?= e(base_url('destinations')) ?>">ویزا</a></li>
                </ul>
            </div>
            <div class="col-lg-2 col-md-6">
                <h5 class="bt-footer-title">دسترسی سریع</h5>
                <ul class="bt-footer-links">
                    <li><a href="<?= e(base_url('/')) ?>">خانه</a></li>
                    <li><a href="<?= e(base_url('destinations')) ?>">مقاصد</a></li>
                    <li><a href="<?= e(base_url('about')) ?>">درباره ما</a></li>
                    <li><a href="<?= e(base_url('articles')) ?>">مقالات</a></li>
                </ul>
            </div>
            <div class="col-lg-3 col-md-6">
                <h5 class="bt-footer-title">خبرنامه ما را دریافت کنید</h5>
                <p class="bt-footer-text">از تخفیف‌ها و تورهای جدید باخبر شوید.</p>
                <form class="bt-newsletter" data-ajax-form action="<?= e(base_url('newsletter')) ?>" method="post">
                    <input type="hidden" name="_csrf" value="<?= e($csrf) ?>">
                    <input type="email" name="email" placeholder="ایمیل خود را وارد کنید" required>
                    <button type="submit" class="bt-btn-primary">عضویت</button>
                </form>
            </div>
        </div>
    </div>
    <div class="bt-footer-bottom text-center">
        <div class="container"><?= e($settings['footer_copyright'] ?? ('© ' . fa_number(date('Y')) . ' ' . $siteName)) ?></div>
    </div>
</footer>

<!-- Bootstrap JS bundle + site JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>window.BT_BASE_URL = <?= json_encode(rtrim($baseUrl, '/'), JSON_UNESCAPED_UNICODE) ?>;</script>
<script src="<?= e(asset('js/app.js')) ?>"></script>
</body>
</html>
