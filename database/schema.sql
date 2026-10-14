-- =========================================================================
--  Bahadori Travel Agency — MySQL schema & seed data
--  Engine: InnoDB | Charset: utf8mb4 (full Persian support + emoji)
--  Import this file once to create everything the CMS needs.
-- =========================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;
SET time_zone = "+03:30";

CREATE DATABASE IF NOT EXISTS `bahadori_travel`
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `bahadori_travel`;

-- -------------------------------------------------------------------------
--  Admins (users who can log into the admin panel)
-- -------------------------------------------------------------------------
DROP TABLE IF EXISTS `admins`;
CREATE TABLE `admins` (
  `id`         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name`       VARCHAR(120)  NOT NULL,
  `email`      VARCHAR(160)  NOT NULL UNIQUE,
  `password`   VARCHAR(255)  NOT NULL,          -- bcrypt hash
  `role`       ENUM('admin','editor') NOT NULL DEFAULT 'admin',
  `avatar`     VARCHAR(255)  DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Default login:  admin@bahadori.travel  /  admin1234
INSERT INTO `admins` (`name`,`email`,`password`,`role`) VALUES
('مدیر سایت', 'admin@bahadori.travel',
 '$2y$10$EKez50NUb8zyP3Qjft7k5.O3MvRpFER.F8okSHaPEDtRroKqWvkty', 'admin');
-- (password: admin1234)

-- -------------------------------------------------------------------------
--  Site settings (key/value store for every editable text on the site)
-- -------------------------------------------------------------------------
DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `setting_key`   VARCHAR(120) PRIMARY KEY,
  `setting_value` TEXT,
  `setting_group` VARCHAR(60) NOT NULL DEFAULT 'general',
  `updated_at`    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `settings` (`setting_key`,`setting_value`,`setting_group`) VALUES
-- General / brand
('site_name','بهادری تراول ایجنسی','general'),
('site_tagline','آژانس مسافرتی','general'),
('site_logo','','general'),
('footer_copyright','© ۲۰۲۶ بهادری تراول ایجنسی — تمام حق محفوظ است.','general'),
-- Contact info
('contact_phone','+۹۷۹ ۷۹ ۱۳۴ ۹۵۱۰','contact'),
('contact_email','info@bahadoritravel.af','contact'),
('contact_address','کابل، افغانستان — دشتی بهجی','contact'),
('contact_hours','شنبه تا پنجشنبه ۹ صبح تا ۶ عصر','contact'),
-- Hero section
('hero_badge','بست. آنز کن. سفر کن.','hero'),
('hero_title','با ما جاهای شگفت‌انگیز جهان را کشف کنید','hero'),
('hero_subtitle','بهترین تورها، هتل‌ها و بلیط‌های هواپیما را با مقایسهٔ تخصصی و بیمه‌تی ۲۴ ساعته بدهید. تراول ایجنسی باید کنید.','hero'),
('hero_cta1','همین حالا کاوش کنید','hero'),
('hero_cta2','هتل','hero'),
('hero_image','','hero'),
-- About page
('about_title','چرا بهادری تراول ایجنسی؟','about'),
('about_intro','بیش از یک دهه تجربه در تنظیم سفرهای داخلی و بین‌المللی برای مسافران افغانستان با مشاورهٔ صادقانه و قیمت شفاف.','about'),
('about_story','مشاوران ما در رابطه مقصد، برنامه و زمان سفر صحبت کنید تا بهترین مسیر، از انتقالق تا بازگشت به پایتخت پیشنهاد شود.','about'),
-- Newsletter CTA
('cta_title','ماجراجویی بعدی شما اینجاست','cta'),
('cta_subtitle','مشاوران ما در رابطه مقصد، بودجه و زمان سفرتان صحبت کنید تا بهترین مسیر و مناسب‌ترین قیمت را برایتان تنظیم کنیم.','cta'),
('cta_button','تنظیم برنامهٔ سفر','cta'),
('cta_image','','cta'),
-- Social links
('social_youtube','#','social'),
('social_twitter','#','social'),
('social_instagram','#','social'),
('social_telegram','#','social'),
-- Appearance (colour palette, editable from admin)
('color_primary','#c9a24b','appearance'),
('color_dark','#101828','appearance'),
('color_light','#f6f1e7','appearance');

-- -------------------------------------------------------------------------
--  Destinations (مقاصد) — featured location cards
-- -------------------------------------------------------------------------
DROP TABLE IF EXISTS `destinations`;
CREATE TABLE `destinations` (
  `id`          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `title`       VARCHAR(160) NOT NULL,
  `slug`        VARCHAR(160) NOT NULL UNIQUE,
  `country`     VARCHAR(120) DEFAULT NULL,
  `image`       VARCHAR(255) DEFAULT NULL,
  `description` TEXT,
  `price_from`  DECIMAL(12,2) DEFAULT 0,
  `rating`      DECIMAL(2,1) DEFAULT 4.5,
  `is_featured` TINYINT(1) NOT NULL DEFAULT 1,
  `sort_order`  INT NOT NULL DEFAULT 0,
  `created_at`  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `destinations` (`title`,`slug`,`country`,`image`,`description`,`price_from`,`rating`,`is_featured`,`sort_order`) VALUES
('پاریس','paris','فرانسه، پاریس','https://images.unsplash.com/photo-1502602898657-3e91760cbb34?auto=format&fit=crop&w=800&q=80','شهر نور و عشق؛ از برج ایفل تا موزهٔ لوور.',18800000,4.7,1,1),
('استانبول','istanbul','ترکیه، استانبول','https://images.unsplash.com/photo-1524231757912-21f4fe3a7200?auto=format&fit=crop&w=800&q=80','پیوند دو قارهٔ اروپا و آسیا.',13600000,4.8,1,2),
('دبی','dubai','امارات، دبی','https://images.unsplash.com/photo-1512453979798-5ea266f8880c?auto=format&fit=crop&w=800&q=80','امارات متحدهٔ عربی با آسمان‌خراش‌های مدرن.',14200000,4.9,1,3),
('مالدیو','maldives','مالدیو','https://images.unsplash.com/photo-1514282401047-d79a71a590e8?auto=format&fit=crop&w=800&q=80','جزیرهٔ افسانه‌ای با ساحل‌های طلایی.',18800000,4.7,1,4);

-- -------------------------------------------------------------------------
--  Tours / Packages (تورها و پکیج‌ها)
-- -------------------------------------------------------------------------
DROP TABLE IF EXISTS `tours`;
CREATE TABLE `tours` (
  `id`           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `title`        VARCHAR(200) NOT NULL,
  `slug`         VARCHAR(200) NOT NULL UNIQUE,
  `destination_id` INT UNSIGNED DEFAULT NULL,
  `location`     VARCHAR(160) DEFAULT NULL,
  `image`        VARCHAR(255) DEFAULT NULL,
  `badge`        VARCHAR(60)  DEFAULT NULL,
  `summary`      VARCHAR(255) DEFAULT NULL,
  `description`  TEXT,
  `price`        DECIMAL(12,2) DEFAULT 0,
  `old_price`    DECIMAL(12,2) DEFAULT NULL,
  `rating`       DECIMAL(2,1) DEFAULT 4.5,
  `duration_days` INT NOT NULL DEFAULT 1,
  `duration_nights` INT NOT NULL DEFAULT 0,
  `is_featured`  TINYINT(1) NOT NULL DEFAULT 0,
  `sort_order`   INT NOT NULL DEFAULT 0,
  `created_at`   TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY `idx_destination` (`destination_id`),
  CONSTRAINT `fk_tours_dest` FOREIGN KEY (`destination_id`)
    REFERENCES `destinations` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `tours` (`title`,`slug`,`destination_id`,`location`,`image`,`badge`,`summary`,`description`,`price`,`old_price`,`rating`,`duration_days`,`duration_nights`,`is_featured`,`sort_order`) VALUES
('پکیج ۷ روزه استانبول و بورسا','istanbul-bursa-7day',2,'استانبول','https://images.unsplash.com/photo-1541432901042-2d8bd64b4a9b?auto=format&fit=crop&w=800&q=80','پکیج','۷ روز / ۶ شب • استانبول','تور کامل استانبول با بازدید از بورسا، آبشار و کوهستان اولوداغ.',14500000,17000000,4.8,7,6,1,1),
('تور ۵ روزه دبی با اقامت هتل ۴ ستاره','dubai-5day-4star',3,'دبی','https://images.unsplash.com/photo-1512453979798-5ea266f8880c?auto=format&fit=crop&w=800&q=80','تور','۵ روز / ۴ شب • دبی','ترکیبی از خرید، تفریح و سافاری کویر در دبی.',14800000,17500000,4.9,5,4,1,2),
('تور ۴ روزه پاریس و کاخ ورسای','paris-versailles-4day',1,'پاریس','https://images.unsplash.com/photo-1502602898657-3e91760cbb34?auto=format&fit=crop&w=800&q=80','پرفروش','۴ روز / ۳ شب • پاریس','بازدید از برج ایفل، موزه لوور و کاخ ورسای با راهنمای فارسی‌زبان.',19500000,21000000,4.7,4,3,1,3),
('هفته عسل مالدیو با اقامت ویلا ساحلی','maldives-honeymoon',4,'مالدیو','https://images.unsplash.com/photo-1514282401047-d79a71a590e8?auto=format&fit=crop&w=800&q=80','ویژه','۶ روز / ۵ شب • مالدیو','اقامت در ویلای ساحلی، صبحانه و شام رایگان و تورهای دریایی.',23000000,25000000,5.0,6,5,1,4),
('تور ۳ روزه کاپادوکیا و پرواز بالون','cappadocia-balloon-3day',2,'کاپادوکیا','https://images.unsplash.com/photo-1641128324972-af3212f0f6bd?auto=format&fit=crop&w=800&q=80','ماجرایی','۳ روز / ۲ شب • کاپادوکیا','سواری بالون فضاپیما در غروب و اقامت در هتل غارگاهی.',12500000,14000000,4.6,3,2,0,5),
('تور اقتصادی ۴ روزه آنتالیا','antalya-budget-4day',2,'آنتالیا','https://images.unsplash.com/photo-1589308078059-be1415eab4c3?auto=format&fit=crop&w=800&q=80','اقتصادی','۴ روز / ۳ شب • آنتالیا','ساحل و دریا با قیمت مناسب و اقامت هتل در مرکز شهر.',9800000,11500000,4.4,4,3,0,6);


-- -------------------------------------------------------------------------
--  Articles / Blog (مقالات)
-- -------------------------------------------------------------------------
DROP TABLE IF EXISTS `articles`;
CREATE TABLE `articles` (
  `id`          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `title`       VARCHAR(200) NOT NULL,
  `slug`        VARCHAR(200) NOT NULL UNIQUE,
  `category`    VARCHAR(80) DEFAULT NULL,
  `image`       VARCHAR(255) DEFAULT NULL,
  `excerpt`     VARCHAR(400) DEFAULT NULL,
  `body`        LONGTEXT,
  `author`      VARCHAR(120) DEFAULT NULL,
  `views`       INT UNSIGNED NOT NULL DEFAULT 0,
  `is_published`TINYINT(1) NOT NULL DEFAULT 1,
  `created_at`  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `articles` (`title`,`slug`,`category`,`image`,`excerpt`,`body`,`author`,`is_published`) VALUES
('۱۰ نکتهٔ طلایی برای سفر با بودجهٔ محدود','10-tips-budget-travel','راهنمای سفر','https://images.unsplash.com/photo-1488646953014-85cb44e25828?auto=format&fit=crop&w=800&q=80','با چند ترفند ساده هزینهٔ سفرتان را تا ۴۰٪ کاهش دهید.','محتوای کامل مقاله به‌زودی افزوده می‌شود.','تیم بهادری',1),
('بهترین زمان برای بازدید از دبی کدام است؟','best-time-dubai','مقاصد','https://images.unsplash.com/photo-1512453979798-5ea266f8880c?auto=format&fit=crop&w=800&q=80','فصل‌بندی آب‌وهوایی دبی و انتخاب بهترین ماه سفر.','محتوای کامل مقاله به‌زودی افزوده می‌شود.','تیم بهادری',1);

-- -------------------------------------------------------------------------
--  Services / Benefits (خدمات و مزیت‌ها) — the four icon cards
-- -------------------------------------------------------------------------
DROP TABLE IF EXISTS `services`;
CREATE TABLE `services` (
  `id`          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `icon`        VARCHAR(80) NOT NULL DEFAULT 'star',
  `title`       VARCHAR(160) NOT NULL,
  `description` TEXT,
  `sort_order`  INT NOT NULL DEFAULT 0,
  `is_active`   TINYINT(1) NOT NULL DEFAULT 1,
  `created_at`  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `services` (`icon`,`title`,`description`,`sort_order`,`is_active`) VALUES
('compass','تجربه‌های دست‌چین‌شده','برنامه‌های سفر را با مشاورهٔ کارشناسان ماحی و مسافران با تجربه انتخاب و تنظیم می‌کنیم.',1,1),
('shield','رزرو مطمئن و شفاف','هر رزرو با اسناد رسمی و تأیید کتبی انجام می‌شود و پس از خریدت، سفر شما تضمین شده است.',2,1),
('headset','پشتیبانی ۲۴ ساعته','تیم پشتیبانی ما به‌صورت ۲۴ ساعته پاسخگوی شماست تا در هیچ مرحله‌ای از سفر تنها نمانید.',3,1),
('tag','تضمین بهترین قیمت','قیمت‌ها و بلیط‌های ما را با هر جای دیگر مقایسه کنید؛ اگر ارزان‌تر بافتید همان قیمت را برایتان تطبیق می‌کنیم.',4,1);

-- -------------------------------------------------------------------------
--  Bookings (درخواست‌های رزرو) — from hero search + tour "قیمت از" forms
-- -------------------------------------------------------------------------
DROP TABLE IF EXISTS `bookings`;
CREATE TABLE `bookings` (
  `id`          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name`        VARCHAR(120) NOT NULL,
  `phone`       VARCHAR(40)  DEFAULT NULL,
  `email`       VARCHAR(160) DEFAULT NULL,
  `tour_id`     INT UNSIGNED DEFAULT NULL,
  `destination` VARCHAR(160) DEFAULT NULL,
  `travel_date` DATE DEFAULT NULL,
  `return_date` DATE DEFAULT NULL,
  `travelers`   INT NOT NULL DEFAULT 1,
  `notes`       TEXT,
  `status`      ENUM('pending','confirmed','cancelled') NOT NULL DEFAULT 'pending',
  `created_at`  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY `idx_status` (`status`),
  KEY `idx_tour` (`tour_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------------------
--  Messages (پیام‌ها) — contact form inbox
-- -------------------------------------------------------------------------
DROP TABLE IF EXISTS `messages`;
CREATE TABLE `messages` (
  `id`         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name`       VARCHAR(120) NOT NULL,
  `email`      VARCHAR(160) NOT NULL,
  `phone`      VARCHAR(40)  DEFAULT NULL,
  `subject`    VARCHAR(200) DEFAULT NULL,
  `message`    TEXT NOT NULL,
  `is_read`    TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY `idx_read` (`is_read`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------------------
--  Newsletter subscribers (خبرنامه)
-- -------------------------------------------------------------------------
DROP TABLE IF EXISTS `newsletter`;
CREATE TABLE `newsletter` (
  `id`         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `email`      VARCHAR(160) NOT NULL UNIQUE,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
-- =========================  END OF FILE  =================================


