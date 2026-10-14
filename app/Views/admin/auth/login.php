<?php
use App\Core\Session;
$flashError = Session::flash('error');
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ورود به پنل مدیریت</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= e(asset('css/admin.css')) ?>">
    <style>
        body.bt-admin { display: grid; place-items: center; min-height: 100vh; background: linear-gradient(135deg, #101828, #1d2a44); }
        .bt-login { width: 100%; max-width: 420px; padding: 20px; }
        .bt-login-card { background: #fff; border-radius: 20px; padding: 34px; box-shadow: 0 20px 60px rgba(0,0,0,.3); }
        .bt-login-logo { display: grid; place-items: center; width: 62px; height: 62px; border-radius: 16px; background: var(--bt-admin-primary); color: #fff; font-size: 1.8rem; margin: 0 auto 16px; }
        .bt-login h2 { text-align: center; font-weight: 800; margin-bottom: 4px; }
        .bt-login p { text-align: center; color: var(--bt-admin-muted); font-size: .9rem; margin-bottom: 22px; }
        .bt-login .bt-field { margin-bottom: 16px; }
    </style>
</head>
<body class="bt-admin">
    <div class="bt-login">
        <div class="bt-login-card">
            <div class="bt-login-logo"><i class="bi bi-airplane-engines-fill"></i></div>
            <h2>پنل مدیریت</h2>
            <p>بهادری تراول ایجنسی</p>

            <?php if ($flashError): ?>
                <div class="alert alert-danger py-2"><?= e($flashError) ?></div>
            <?php endif; ?>

            <form method="post" action="<?= e(base_url('admin/login')) ?>">
                <input type="hidden" name="_csrf" value="<?= e($csrf) ?>">
                <div class="bt-field">
                    <label class="bt-form-label">ایمیل</label>
                    <input class="bt-input" type="email" name="email" value="<?= e(old('email')) ?>" required autofocus placeholder="admin@bahadori.travel">
                </div>
                <div class="bt-field">
                    <label class="bt-form-label">گذرواژه</label>
                    <input class="bt-input" type="password" name="password" required placeholder="••••••••">
                </div>
                <button class="btn-bt w-100 mt-2" type="submit">ورود</button>
            </form>
            <p class="mt-3 mb-0" style="font-size:.78rem">حساب پیش‌فرض: admin@bahadori.travel / admin1234</p>
        </div>
        <div class="text-center mt-3">
            <a href="<?= e(base_url('/')) ?>" style="color:rgba(255,255,255,.7);font-size:.85rem">← بازگشت به سایت</a>
        </div>
    </div>
</body>
</html>
