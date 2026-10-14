<?php
/**
 * 404 page — rendered directly (not inside the frontend layout) by index.php.
 */
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>صفحه یافت نشد | ۴۰۴</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= e(asset('css/style.css')) ?>">
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@400;700;900&display=swap" rel="stylesheet">
</head>
<body class="bt-front">
    <div class="bt-404 d-flex align-items-center justify-content-center">
        <div class="text-center">
            <div class="bt-404-code">۴۰۴</div>
            <h2 class="mb-3">صفحه‌ای که به دنبالش بودید پیدا نشد</h2>
            <p class="text-muted mb-4">ممکن است آدرس اشتباه باشد یا صفحه حذف شده باشد.</p>
            <a href="<?= e(base_url('/')) ?>" class="btn bt-btn-primary btn-lg">بازگشت به خانه</a>
        </div>
    </div>
</body>
</html>
