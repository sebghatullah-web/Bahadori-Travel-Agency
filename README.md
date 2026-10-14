# آژانس مسافرتی بهادری — سایت داینامیک با پنل مدیریت

سایت کامل و استاندارد یک آژانس مسافرتی بر پایهٔ **PHP + MySQL** با معماری **MVC**،
کاملاً **ریسپانسیو (Responsive)** و **RTL (فارسی)**. تمام بخش‌ها و عناصر سایت
(متن‌ها، تصاویر، رنگ‌ها، تورها، مقاصد، مقالات، خدمات و…) از طریق **پنل مدیریت**
قابل کنترل هستند و هیچ‌چیز به‌صورت هاردکد در قالب‌ها ثابت نشده است.

---

## ✨ قابلیت‌ها

- **سایت عمومی (Frontend)**
  - صفحه اصلی داینامیک (هیرو، جستجو، مقصدهای ویژه، تورهای ویژه، خدمات، مقالات)
  - فهرست و صفحه جزئیات مقاصد
  - فهرست و صفحه جزئیات تورها + فرم رزرو
  - وبلاگ/مقالات + صفحه جزئیات مقاله
  - صفحه «درباره ما» و «تماس با ما» (فرم تماس واقعی)
  - جستجوی زنده (AJAX) در هدر
  - عضویت در خبرنامه (AJAX)
- **پنل مدیریت (Admin)**
  - ورود/خروج امن (رمز عبور هش‌شده با `password_hash`، محافظت CSRF)
  - نمای کلی داشبورد با آمار
  - مدیریت کامل: رزروها، مقاصد، تورها، مقالات، خدمات، پیام‌ها، خبرنامه، تنظیمات، کاربران
  - آپلود تصویر با اعتبارسنجی (نوع و حجم)
  - پاسخ‌های AJAX/JSON برای همهٔ عملیات CRUD
- **استانداردها**
  - معماری MVC (Model / View / Controller)
  - فرانت‌کنترلر واحد (`public/index.php`) + روتر + `.htaccess`
  - استفاده از Prepared Statements (امن در برابر SQL Injection)
  - خروجی امن‌شده با `htmlspecialchars` در همهٔ ویوها
  - فونت **B Nazanin** به‌عنوان فونت اصلی
  - Bootstrap 5 (RTL) + CSS اختصاصی + JavaScript بدون وابستگی اضافی
  - HTML/CSS/JS + AJAX + PHP + MySQL + Node.js

---

## 🚀 راه‌اندازی روی XAMPP (لوکال)

1. پوشهٔ پروژه را در `c:\xampp\htdocs\BahadoriTravelAgency` قرار دهید.
2. از XAMPP Control Panel ماژول‌های **Apache** و **MySQL** را استارت بزنید.
3. دیتابیس را بسازید و دادهٔ اولیه را وارد کنید:

   ```bat
   C:\xampp\mysql\bin\mysql.exe -u root -e "CREATE DATABASE IF NOT EXISTS bahadori_travel CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
   C:\xampp\mysql\bin\mysql.exe -u root --default-character-set=utf8mb4 bahadori_travel -e "source c:/xampp/htdocs/BahadoriTravelAgency/database/schema.sql"
   ```

4. سایت را باز کنید: `http://localhost/BahadoriTravelAgency/public/`
5. ورود به پنل مدیریت: `http://localhost/BahadoriTravelAgency/public/admin/login`
   - **ایمیل:** `admin@bahadori.travel`
   - **گذرواژه:** `admin1234`
   - (پس از اولین ورود، گذرواژه را از بخش «کاربران» تغییر دهید.)

---

## ⚙️ تنظیمات اتصال به دیتابیس

فایل `config/config.php`:

```php
'db' => [
    'host'     => '127.0.0.1',
    'port'     => '3306',
    'database' => 'bahadori_travel',
    'username' => 'root',
    'password' => '',
    'charset'  => 'utf8mb4',
],
```

هنگام انتقال به هاست واقعی، فقط مقادیر بالا (و در صورت نیاز `app.base_url`) را ویرایش کنید.

---

## 🌐 هاست کردن (انتقال به سرور واقعی)

این پروژه طوری نوشته شده که بدون تغییر کد روی هاست‌های معمولی کار کند:

1. کل پوشهٔ پروژه را روی هاست آپلود کنید.
2. دیتابیس را از phpMyAdmin بسازید (utf8mb4) و فایل `database/schema.sql` را ایمپورت کنید.
3. اطلاعات دیتابیس جدید را در `config/config.php` وارد کنید.
4. **Document Root** هاست را روی پوشهٔ `public/` تنظیم کنید.
   - اگر هاست اجازهٔ تغییر Document Root نداد، فایل `.htaccess` ریشه به‌طور خودکار
     درخواست‌ها را به `public/index.php` هدایت می‌کند.
5. فونت B Nazanin (توصیه‌شده): فایل‌های `BNazanin.woff2/.woff/.ttf` و
   `BNazanin-Bold.*` را در `public/assets/fonts/` بگذارید. راهنما: `public/assets/fonts/README.md`.

> نکته: آدرس‌ها به‌صورت خودکار ساخته می‌شوند (auto-detect base URL) و با
> `/index.php/...` نیز کار می‌کنند؛ بنابراین اگر روی بعضی هاست‌ها `mod_rewrite`
> فعال نباشد هم سایت بدون مشکل اجرا می‌شود.

---

## 🔔 سرور اطلاع‌رسانی لحظه‌ای (Node.js — اختیاری)

پنل مدیریت از طریق Node.js + Socket.IO رویدادها (رزرو جدید، پیام جدید و…) را
به‌صورت زنده نشان می‌دهد. برای فعال‌سازی:

```bat
cd node-server
npm install
npm start
```

آدرس و کلید مخفی در `config/config.php` → بخش `node` قابل تغییر است.
اگر این سرور را اجرا نکنید، سایت و پنل کاملاً کار می‌کنند و فقط بخش
اطلاع‌رسانی لحظه‌ای غیرفعال می‌ماند.

---

## 🗂 ساختار پروژه (MVC)

```
BahadoriTravelAgency/
├── app/
│   ├── Core/            # هستهٔ فریم‌ورک (Router, Request, Database, View, Session, Upload, ...)
│   ├── Controllers/     # کنترلرهای سایت عمومی
│   │   └── Admin/       # کنترلرهای پنل مدیریت
│   ├── Models/          # مدل‌ها (Tour, Destination, Article, Setting, ...)
│   ├── Views/           # ویوها
│   │   ├── frontend/    # صفحات سایت عمومی
│   │   ├── admin/       # صفحات پنل مدیریت
│   │   └── layouts/     # قالب‌های مشترک (frontend.php, admin.php)
│   └── helpers.php      # توابع کمکی (e, asset, base_url, format_price, fa_number, ...)
├── config/
│   ├── config.php       # تنظیمات برنامه و دیتابیس
│   └── routes.php       # تعریف مسیرها (روت‌ها)
├── database/
│   └── schema.sql       # ساختار جداول + دادهٔ اولیه
├── node-server/         # سرور اطلاع‌رسانی لحظه‌ای (Express + Socket.IO)
├── public/              # ریشهٔ وب (تنها پوشهٔ قابل دسترسی از وب)
│   ├── index.php        # فرانت‌کنترلر
│   ├── .htaccess        # بازنویسی URL
│   └── assets/          # css / js / images / fonts / uploads
├── .htaccess            # هدایت ریشه به public
└── storage/             # لاگ‌ها و فایل‌های موقت
```

---

## 🎨 مدیریت ظاهر از پنل

از بخش **تنظیمات** پنل مدیریت می‌توانید موارد زیر را بدون دست‌زدن به کد تغییر دهید:

- نام و شعار سایت، اطلاعات تماس، شبکه‌های اجتماعی
- متن‌های بخش هیرو (عنوان، زیرنویس، دکمه‌ها، تصویر پس‌زمینه)
- رنگ اصلی، رنگ تیره و رنگ روشن سایت
- محتوای صفحهٔ «درباره ما»
- متن‌های فوتر

---

## 🔐 امنیت

- رمز عبور با `password_hash()` / `password_verify()` نگهداری می‌شود.
- همهٔ فرم‌ها دارای توکن **CSRF** هستند.
- تمام کوئری‌ها با **Prepared Statements** اجرا می‌شوند.
- خروجی همهٔ داده‌های کاربر با `htmlspecialchars` ایمن‌سازی می‌شود.
- دسترسی مستقیم به پوشه‌های `app/`, `config/`, `database/` مسدود است.

---

## 🐞 اشکال‌زدایی (Debug)

برای دیدن پیام دقیق خطاها، در `config/config.php` مقدار `app.debug` را روی
`true` بگذارید (در محیط production روی `false` قرار دهید تا جزئیات خطا نمایش داده نشود).

#   B a h a d o r i - T r a v e l - A g e n c y  
 