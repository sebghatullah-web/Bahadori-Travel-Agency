-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 14, 2026 at 12:04 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bahadori_travel`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(120) NOT NULL,
  `email` varchar(160) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','editor') NOT NULL DEFAULT 'admin',
  `avatar` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `password`, `role`, `avatar`, `created_at`) VALUES
(1, 'مدیر سایت', 'admin@bahadori.travel', '$2y$10$EKez50NUb8zyP3Qjft7k5.O3MvRpFER.F8okSHaPEDtRroKqWvkty', 'admin', NULL, '2026-10-14 04:29:21');

-- --------------------------------------------------------

--
-- Table structure for table `articles`
--

CREATE TABLE `articles` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(200) NOT NULL,
  `slug` varchar(200) NOT NULL,
  `category` varchar(80) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `excerpt` varchar(400) DEFAULT NULL,
  `body` longtext DEFAULT NULL,
  `author` varchar(120) DEFAULT NULL,
  `views` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `articles`
--

INSERT INTO `articles` (`id`, `title`, `slug`, `category`, `image`, `excerpt`, `body`, `author`, `views`, `is_published`, `created_at`) VALUES
(1, '۱۰ نکتهٔ طلایی برای سفر با بودجهٔ محدود', '10-tips-budget-travel', 'راهنمای سفر', 'https://images.unsplash.com/photo-1488646953014-85cb44e25828?auto=format&fit=crop&w=800&q=80', 'با چند ترفند ساده هزینهٔ سفرتان را تا ۴۰٪ کاهش دهید.', 'محتوای کامل مقاله به‌زودی افزوده می‌شود.', 'تیم بهادری', 1, 1, '2026-10-14 04:29:21'),
(2, 'بهترین زمان برای بازدید از دبی کدام است؟', 'best-time-dubai', 'مقاصد', 'https://images.unsplash.com/photo-1512453979798-5ea266f8880c?auto=format&fit=crop&w=800&q=80', 'فصل‌بندی آب‌وهوایی دبی و انتخاب بهترین ماه سفر.', 'محتوای کامل مقاله به‌زودی افزوده می‌شود.', 'تیم بهادری', 1, 1, '2026-10-14 04:29:21');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(120) NOT NULL,
  `phone` varchar(40) DEFAULT NULL,
  `email` varchar(160) DEFAULT NULL,
  `tour_id` int(10) UNSIGNED DEFAULT NULL,
  `destination` varchar(160) DEFAULT NULL,
  `travel_date` date DEFAULT NULL,
  `return_date` date DEFAULT NULL,
  `travelers` int(11) NOT NULL DEFAULT 1,
  `notes` text DEFAULT NULL,
  `status` enum('pending','confirmed','cancelled') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `name`, `phone`, `email`, `tour_id`, `destination`, `travel_date`, `return_date`, `travelers`, `notes`, `status`, `created_at`) VALUES
(2, 'صبغت', '0791349150', 'sebghatullahbahadori03@gmail.com', 2, 'دبی', '2026-10-16', '2026-10-26', 1, 'یبشبیبسیشب', 'confirmed', '2026-10-14 05:35:15');

-- --------------------------------------------------------

--
-- Table structure for table `destinations`
--

CREATE TABLE `destinations` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(160) NOT NULL,
  `slug` varchar(160) NOT NULL,
  `country` varchar(120) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price_from` decimal(12,2) DEFAULT 0.00,
  `rating` decimal(2,1) DEFAULT 4.5,
  `is_featured` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `destinations`
--

INSERT INTO `destinations` (`id`, `title`, `slug`, `country`, `image`, `description`, `price_from`, `rating`, `is_featured`, `sort_order`, `created_at`) VALUES
(1, 'پاریس', 'paris', 'فرانسه، پاریس', 'https://images.unsplash.com/photo-1502602898657-3e91760cbb34?auto=format&fit=crop&w=800&q=80', 'شهر نور و عشق؛ از برج ایفل تا موزهٔ لوور.', '18800000.00', '4.7', 1, 1, '2026-10-14 04:29:21'),
(2, 'استانبول', 'istanbul', 'ترکیه، استانبول', 'https://images.unsplash.com/photo-1524231757912-21f4fe3a7200?auto=format&fit=crop&w=800&q=80', 'پیوند دو قارهٔ اروپا و آسیا.', '13600000.00', '4.8', 1, 2, '2026-10-14 04:29:21'),
(3, 'دبی', 'dubai', 'امارات، دبی', 'https://images.unsplash.com/photo-1512453979798-5ea266f8880c?auto=format&fit=crop&w=800&q=80', 'امارات متحدهٔ عربی با آسمان‌خراش‌های مدرن.', '14200000.00', '4.9', 1, 3, '2026-10-14 04:29:21'),
(4, 'مالدیو', 'maldives', 'مالدیو', 'https://images.unsplash.com/photo-1514282401047-d79a71a590e8?auto=format&fit=crop&w=800&q=80', 'جزیرهٔ افسانه‌ای با ساحل‌های طلایی.', '18800000.00', '4.7', 1, 4, '2026-10-14 04:29:21');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(120) NOT NULL,
  `email` varchar(160) NOT NULL,
  `phone` varchar(40) DEFAULT NULL,
  `subject` varchar(200) DEFAULT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `name`, `email`, `phone`, `subject`, `message`, `is_read`, `created_at`) VALUES
(2, 'صبغت', 'sebghatullahbahadori03@gmail.com', '0791349150', 'Sample text', 'dafdfhhjfjk', 1, '2026-10-14 05:08:22');

-- --------------------------------------------------------

--
-- Table structure for table `newsletter`
--

CREATE TABLE `newsletter` (
  `id` int(10) UNSIGNED NOT NULL,
  `email` varchar(160) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `newsletter`
--

INSERT INTO `newsletter` (`id`, `email`, `created_at`) VALUES
(2, 'sebghatullahbahadori03@gmail.com', '2026-10-14 05:07:46');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(10) UNSIGNED NOT NULL,
  `icon` varchar(80) NOT NULL DEFAULT 'star',
  `title` varchar(160) NOT NULL,
  `description` text DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `icon`, `title`, `description`, `sort_order`, `is_active`, `created_at`) VALUES
(1, 'compass', 'تجربه‌های دست‌چین‌شده', 'برنامه‌های سفر را با مشاورهٔ کارشناسان ماحی و مسافران با تجربه انتخاب و تنظیم می‌کنیم.', 1, 1, '2026-10-14 04:29:21'),
(2, 'shield', 'رزرو مطمئن و شفاف', 'هر رزرو با اسناد رسمی و تأیید کتبی انجام می‌شود و پس از خریدت، سفر شما تضمین شده است.', 2, 1, '2026-10-14 04:29:21'),
(3, 'headset', 'پشتیبانی ۲۴ ساعته', 'تیم پشتیبانی ما به‌صورت ۲۴ ساعته پاسخگوی شماست تا در هیچ مرحله‌ای از سفر تنها نمانید.', 3, 1, '2026-10-14 04:29:21'),
(4, 'tag', 'تضمین بهترین قیمت', 'قیمت‌ها و بلیط‌های ما را با هر جای دیگر مقایسه کنید؛ اگر ارزان‌تر بافتید همان قیمت را برایتان تطبیق می‌کنیم.', 4, 1, '2026-10-14 04:29:21');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `setting_key` varchar(120) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `setting_group` varchar(60) NOT NULL DEFAULT 'general',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`setting_key`, `setting_value`, `setting_group`, `updated_at`) VALUES
('about_intro', 'بیش از یک دهه تجربه در تنظیم سفرهای داخلی و بین‌المللی برای مسافران افغانستان با مشاورهٔ صادقانه و قیمت شفاف.', 'about', '2026-10-14 04:29:21'),
('about_story', 'مشاوران ما در رابطه مقصد، برنامه و زمان سفر صحبت کنید تا بهترین مسیر، از انتقالق تا بازگشت به پایتخت پیشنهاد شود.', 'about', '2026-10-14 04:29:21'),
('about_title', 'چرا بهادری تراول ایجنسی؟', 'about', '2026-10-14 04:29:21'),
('color_dark', '#101828', 'appearance', '2026-10-14 04:29:21'),
('color_light', '#f6f1e7', 'appearance', '2026-10-14 04:29:21'),
('color_primary', '#c9a24b', 'appearance', '2026-10-14 04:29:21'),
('contact_address', 'کابل، افغانستان — دشتی برجی', 'contact', '2026-10-14 07:45:04'),
('contact_email', 'sebghatullahbahadori03@gmail.com', 'contact', '2026-10-14 07:45:04'),
('contact_hours', 'شنبه تا پنجشنبه 8 صبح تا ۶ عصر', 'contact', '2026-10-14 07:45:04'),
('contact_phone', '0791349150', 'contact', '2026-10-14 07:45:04'),
('cta_button', 'تنظیم برنامهٔ سفر', 'cta', '2026-10-14 04:29:21'),
('cta_image', '', 'cta', '2026-10-14 04:29:21'),
('cta_subtitle', 'مشاوران ما در رابطه مقصد، بودجه و زمان سفرتان صحبت کنید تا بهترین مسیر و مناسب‌ترین قیمت را برایتان تنظیم کنیم.', 'cta', '2026-10-14 04:29:21'),
('cta_title', 'ماجراجویی بعدی شما اینجاست', 'cta', '2026-10-14 04:29:21'),
('footer_copyright', '© ۲۰۲۶ بهادری تراول ایجنسی — تمام حق محفوظ است.', 'general', '2026-10-14 04:29:21'),
('hero_badge', 'بست. آنز کن. سفر کن.', 'hero', '2026-10-14 04:29:21'),
('hero_cta1', 'مشاهده تورها', 'hero', '2026-10-14 04:36:35'),
('hero_cta2', 'رزرو تور', 'hero', '2026-10-14 04:36:35'),
('hero_image', '', 'hero', '2026-10-14 04:29:21'),
('hero_subtitle', 'بهترین تورها با بهترین قیمت', 'hero', '2026-10-14 04:36:35'),
('hero_title', 'سفر رویایی‌تان را با ما آغاز کنید', 'hero', '2026-10-14 04:36:35'),
('site_logo', '', 'general', '2026-10-14 04:29:21'),
('site_name', 'شرکت مسافرتی بهادری', 'general', '2026-10-14 07:49:57'),
('site_tagline', 'تور و سفر رویایی', 'general', '2026-10-14 04:36:35'),
('social_instagram', '#', 'social', '2026-10-14 04:29:21'),
('social_telegram', '#', 'social', '2026-10-14 04:29:21'),
('social_twitter', '#', 'social', '2026-10-14 04:29:21'),
('social_youtube', 'https://youtube.com/@pixelweb_studio?si=3x0mkcO4cLIGAV4s', 'social', '2026-10-14 07:45:04');

-- --------------------------------------------------------

--
-- Table structure for table `tours`
--

CREATE TABLE `tours` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(200) NOT NULL,
  `slug` varchar(200) NOT NULL,
  `destination_id` int(10) UNSIGNED DEFAULT NULL,
  `location` varchar(160) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `badge` varchar(60) DEFAULT NULL,
  `summary` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(12,2) DEFAULT 0.00,
  `old_price` decimal(12,2) DEFAULT NULL,
  `rating` decimal(2,1) DEFAULT 4.5,
  `duration_days` int(11) NOT NULL DEFAULT 1,
  `duration_nights` int(11) NOT NULL DEFAULT 0,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tours`
--

INSERT INTO `tours` (`id`, `title`, `slug`, `destination_id`, `location`, `image`, `badge`, `summary`, `description`, `price`, `old_price`, `rating`, `duration_days`, `duration_nights`, `is_featured`, `sort_order`, `created_at`) VALUES
(1, 'پکیج ۷ روزه استانبول و بورسا', 'istanbul-bursa-7day', 2, 'استانبول', 'https://images.unsplash.com/photo-1541432901042-2d8bd64b4a9b?auto=format&fit=crop&w=800&q=80', 'پکیج', '۷ روز / ۶ شب • استانبول', 'تور کامل استانبول با بازدید از بورسا، آبشار و کوهستان اولوداغ.', '14500000.00', '17000000.00', '4.8', 7, 6, 1, 1, '2026-10-14 04:29:21'),
(2, 'تور ۵ روزه دبی با اقامت هتل ۴ ستاره', 'dubai-5day-4star', 3, 'دبی', 'https://images.unsplash.com/photo-1512453979798-5ea266f8880c?auto=format&fit=crop&w=800&q=80', 'تور', '۵ روز / ۴ شب • دبی', 'ترکیبی از خرید، تفریح و سافاری کویر در دبی.', '14800000.00', '17500000.00', '4.9', 5, 4, 1, 2, '2026-10-14 04:29:21'),
(3, 'تور ۴ روزه پاریس و کاخ ورسای', 'paris-versailles-4day', 1, 'پاریس', 'https://images.unsplash.com/photo-1502602898657-3e91760cbb34?auto=format&fit=crop&w=800&q=80', 'پرفروش', '۴ روز / ۳ شب • پاریس', 'بازدید از برج ایفل، موزه لوور و کاخ ورسای با راهنمای فارسی‌زبان.', '19500000.00', '21000000.00', '4.7', 4, 3, 1, 3, '2026-10-14 04:29:21'),
(4, 'هفته عسل مالدیو با اقامت ویلا ساحلی', 'maldives-honeymoon', 4, 'مالدیو', 'https://images.unsplash.com/photo-1514282401047-d79a71a590e8?auto=format&fit=crop&w=800&q=80', 'ویژه', '۶ روز / ۵ شب • مالدیو', 'اقامت در ویلای ساحلی، صبحانه و شام رایگان و تورهای دریایی.', '23000000.00', '25000000.00', '5.0', 6, 5, 1, 4, '2026-10-14 04:29:21'),
(5, 'تور ۳ روزه کاپادوکیا و پرواز بالون', 'cappadocia-balloon-3day', 2, 'کاپادوکیا', 'https://images.unsplash.com/photo-1641128324972-af3212f0f6bd?auto=format&fit=crop&w=800&q=80', 'ماجرایی', '۳ روز / ۲ شب • کاپادوکیا', 'سواری بالون فضاپیما در غروب و اقامت در هتل غارگاهی.', '12500000.00', '14000000.00', '4.6', 3, 2, 0, 5, '2026-10-14 04:29:21'),
(6, 'تور اقتصادی ۴ روزه آنتالیا', 'antalya-budget-4day', 2, 'آنتالیا', 'https://images.unsplash.com/photo-1589308078059-be1415eab4c3?auto=format&fit=crop&w=800&q=80', 'اقتصادی', '۴ روز / ۳ شب • آنتالیا', 'ساحل و دریا با قیمت مناسب و اقامت هتل در مرکز شهر.', '9800000.00', '11500000.00', '4.4', 4, 3, 0, 6, '2026-10-14 04:29:21');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_tour` (`tour_id`);

--
-- Indexes for table `destinations`
--
ALTER TABLE `destinations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_read` (`is_read`);

--
-- Indexes for table `newsletter`
--
ALTER TABLE `newsletter`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`setting_key`);

--
-- Indexes for table `tours`
--
ALTER TABLE `tours`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_destination` (`destination_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `articles`
--
ALTER TABLE `articles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `destinations`
--
ALTER TABLE `destinations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `newsletter`
--
ALTER TABLE `newsletter`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tours`
--
ALTER TABLE `tours`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tours`
--
ALTER TABLE `tours`
  ADD CONSTRAINT `fk_tours_dest` FOREIGN KEY (`destination_id`) REFERENCES `destinations` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
