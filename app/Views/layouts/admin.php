<?php
/**
 * -------------------------------------------------------------------------
 *  Admin layout — dark RTL sidebar + topbar (matches the dashboard design).
 *  Expects: $content, $title, $auth, $csrf, $baseUrl, $config
 * -------------------------------------------------------------------------
 */
use App\Core\Session;
use App\Models\Message;

$siteName = 'بهادری تور';
$unread = 0;
try { $unread = (new Message())->unreadCount(); } catch (\Throwable $e) {}

$currentUri = $_SERVER['REQUEST_URI'] ?? '';
$active = function ($needle) use ($currentUri) {
    $needle = trim($needle, '/');
    return str_contains($currentUri, '/' . $needle) ? 'active' : '';
};

$flashSuccess = Session::flash('success');
$flashError   = Session::flash('error');
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? e($title) . ' | ' : '' ?>پنل مدیریت</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@400;500;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= e(asset('css/admin.css')) ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</head>
<body class="bt-admin">
<div class="bt-admin-wrap">

    <!-- =======================  SIDEBAR  ======================= -->
    <aside class="bt-admin-side" id="btAdminSide">
        <div class="bt-admin-brand">
            <span class="bt-admin-logo"><i class="bi bi-airplane-engines-fill"></i></span>
            <span class="bt-admin-brandtext"><?= e($siteName) ?><small>آژانس بهزی</small></span>
        </div>

        <nav class="bt-admin-nav">
            <a class="bt-admin-link <?= ($currentUri === rtrim($baseUrl,'/').'/admin' || str_ends_with(rtrim($currentUri,'/'),'/admin')) ? 'active' : '' ?>" href="<?= e(base_url('admin')) ?>">
                <i class="bi bi-grid-1x2"></i> نمای کلی
            </a>
            <a class="bt-admin-link <?= $active('admin/bookings') ?>" href="<?= e(base_url('admin/bookings')) ?>">
                <i class="bi bi-calendar2-check"></i> درخواست‌های رزرو
            </a>
            <a class="bt-admin-link <?= $active('admin/destinations') ?>" href="<?= e(base_url('admin/destinations')) ?>">
                <i class="bi bi-geo-alt"></i> مقاصد
            </a>
            <a class="bt-admin-link <?= $active('admin/tours') ?>" href="<?= e(base_url('admin/tours')) ?>">
                <i class="bi bi-box-seam"></i> تورها و پکیج‌ها
            </a>
            <a class="bt-admin-link <?= $active('admin/services') ?>" href="<?= e(base_url('admin/services')) ?>">
                <i class="bi bi-stars"></i> خدمات و مزیت‌ها
            </a>
            <a class="bt-admin-link <?= $active('admin/articles') ?>" href="<?= e(base_url('admin/articles')) ?>">
                <i class="bi bi-journal-text"></i> مقالات
            </a>
            <a class="bt-admin-link <?= $active('admin/messages') ?>" href="<?= e(base_url('admin/messages')) ?>">
                <i class="bi bi-chat-dots"></i> پیام‌ها
                <?php if ($unread > 0): ?><span class="bt-admin-badge"><?= e(fa_number($unread)) ?></span><?php endif; ?>
            </a>
            <a class="bt-admin-link <?= $active('admin/newsletter') ?>" href="<?= e(base_url('admin/newsletter')) ?>">
                <i class="bi bi-envelope-paper"></i> خبرنامه
            </a>
            <a class="bt-admin-link <?= $active('admin/users') ?>" href="<?= e(base_url('admin/users')) ?>">
                <i class="bi bi-people"></i> مدیران
            </a>
            <a class="bt-admin-link <?= $active('admin/settings') ?>" href="<?= e(base_url('admin/settings')) ?>">
                <i class="bi bi-gear"></i> تنظیمات سایت
            </a>
        </nav>

        <div class="bt-admin-sidefoot">
            <a class="bt-admin-link" href="<?= e(base_url('/')) ?>" target="_blank"><i class="bi bi-box-arrow-up-left"></i> مشاهده سایت</a>
            <a class="bt-admin-link" href="<?= e(base_url('admin/logout')) ?>"><i class="bi bi-box-arrow-right"></i> خروج از حساب</a>
        </div>
    </aside>

    <!-- =======================  MAIN  ======================= -->
    <div class="bt-admin-main">
        <header class="bt-admin-topbar">
            <button class="bt-admin-toggle d-lg-none" id="btSideToggle"><i class="bi bi-list"></i></button>
            <h1 class="bt-admin-pagetitle"><?= isset($title) ? e($title) : 'داشبورد' ?></h1>
            <div class="bt-admin-user">
                <span class="bt-admin-avatar"><?= e(mb_substr($auth['name'] ?? 'A', 0, 1)) ?></span>
                <span class="bt-admin-uname"><?= e($auth['name'] ?? 'مدیر') ?></span>
            </div>
        </header>

        <div class="bt-admin-content">
            <?php if ($flashSuccess || $flashError): ?>
                <div class="alert <?= $flashSuccess ? 'alert-success' : 'alert-danger' ?> alert-dismissible fade show">
                    <?= e($flashSuccess ?: $flashError) ?>
                    <button class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?= $content ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.socket.io/4.7.5/socket.io.min.js"></script>
<script>window.BT_BASE_URL = <?= json_encode(rtrim($baseUrl,'/'), JSON_UNESCAPED_UNICODE) ?>; window.BT_CSRF = <?= json_encode($csrf, JSON_UNESCAPED_UNICODE) ?>; window.BT_NODE_URL = <?= json_encode('http://' . (config('node.host') ?? '127.0.0.1') . ':' . (config('node.port') ?? 3000), JSON_UNESCAPED_UNICODE) ?>;</script>
<script src="<?= e(asset('js/admin.js')) ?>"></script>
</body>
</html>
