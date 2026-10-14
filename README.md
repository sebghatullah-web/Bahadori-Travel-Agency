# Bahadori Travel Agency — Dynamic Website with Admin Panel

A complete, standard travel agency website built on **PHP + MySQL** with an **MVC** architecture,
fully **Responsive** and **RTL (Persian/Farsi)**. Every part and element of the site
(texts, images, colors, tours, destinations, articles, services, etc.) is controllable
through the **Admin Panel**, and nothing is hard-coded into the templates.

---

## ✨ Features

- **Public Site (Frontend)**
  - Dynamic homepage (hero, search, featured destinations, featured tours, services, articles)
  - Destinations list and detail pages
  - Tours list and detail pages + booking form
  - Blog / Articles + article detail page
  - "About Us" and "Contact Us" pages (working contact form)
  - Live search (AJAX) in the header
  - Newsletter subscription (AJAX)
- **Admin Panel**
  - Secure login / logout (password hashed with `password_hash`, CSRF protection)
  - Dashboard overview with statistics
  - Full management of: bookings, destinations, tours, articles, services, messages, newsletter, settings, users
  - Image upload with validation (file type and size)
  - AJAX/JSON responses for all CRUD operations
- **Standards**
  - MVC architecture (Model / View / Controller)
  - Single front controller (`public/index.php`) + Router + `.htaccess`
  - Use of Prepared Statements (safe against SQL Injection)
  - Output escaped with `htmlspecialchars` in all views
  - **B Nazanin** font as the primary font
  - Bootstrap 5 (RTL) + custom CSS + dependency-free JavaScript
  - HTML/CSS/JS + AJAX + PHP + MySQL + Node.js

---

## 🚀 Setup on XAMPP (Local)

1. Place the project folder in `c:\xampp\htdocs\BahadoriTravelAgency`.
2. From the XAMPP Control Panel, start the **Apache** and **MySQL** modules.
3. Create the database and import the initial data:

   ```bat
   C:\xampp\mysql\bin\mysql.exe -u root -e "CREATE DATABASE IF NOT EXISTS bahadori_travel CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
   C:\xampp\mysql\bin\mysql.exe -u root --default-character-set=utf8mb4 bahadori_travel -e "source c:/xampp/htdocs/BahadoriTravelAgency/database/schema.sql"
   ```

4. Open the site: `http://localhost/BahadoriTravelAgency/public/`
5. Admin panel login: `http://localhost/BahadoriTravelAgency/public/admin/login`
   - **Email:** `admin@bahadori.travel`
   - **Password:** `admin1234`
   - (After your first login, change the password from the "Users" section.)

---

## ⚙️ Database Connection Settings

The `config/config.php` file:

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

When moving to a real host, only edit the values above (and `app.base_url` if needed).

---

## 🌐 Hosting (Deploying to a Real Server)

This project is written to work on regular hosting without any code changes:

1. Upload the entire project folder to the host.
2. Create the database in phpMyAdmin (utf8mb4) and import the `database/schema.sql` file.
3. Enter the new database credentials in `config/config.php`.
4. Set the host's **Document Root** to the `public/` folder.
   - If the host doesn't allow changing the Document Root, the root `.htaccess` file
     automatically redirects requests to `public/index.php`.
5. B Nazanin font (recommended): place the `BNazanin.woff2/.woff/.ttf` and
   `BNazanin-Bold.*` files in `public/assets/fonts/`. Guide: `public/assets/fonts/README.md`.

> Note: URLs are generated automatically (auto-detect base URL) and also work with
> `/index.php/...`; so even if `mod_rewrite` isn't enabled on some hosts, the site
> runs without any issues.

---

## 🔔 Real-time Notification Server (Node.js — Optional)

The admin panel uses Node.js + Socket.IO to display events (new booking, new message, etc.)
in real time. To enable it:

```bat
cd node-server
npm install
npm start
```

The address and secret key can be changed in `config/config.php` → the `node` section.
If you don't run this server, the site and admin panel work fully and only the
real-time notification feature stays disabled.

---

## 🗂 Project Structure (MVC)

```
BahadoriTravelAgency/
├── app/
│   ├── Core/            # Framework core (Router, Request, Database, View, Session, Upload, ...)
│   ├── Controllers/     # Public site controllers
│   │   └── Admin/       # Admin panel controllers
│   ├── Models/          # Models (Tour, Destination, Article, Setting, ...)
│   ├── Views/           # Views
│   │   ├── frontend/    # Public site pages
│   │   ├── admin/       # Admin panel pages
│   │   └── layouts/     # Shared layouts (frontend.php, admin.php)
│   └── helpers.php      # Helper functions (e, asset, base_url, format_price, fa_number, ...)
├── config/
│   ├── config.php       # Application and database settings
│   └── routes.php       # Route definitions
├── database/
│   └── schema.sql       # Table structure + initial data
├── node-server/         # Real-time notification server (Express + Socket.IO)
├── public/              # Web root (the only publicly accessible folder)
│   ├── index.php        # Front controller
│   ├── .htaccess        # URL rewriting
│   └── assets/          # css / js / images / fonts / uploads
├── .htaccess            # Redirects root to public
└── storage/             # Logs and temporary files
```

---

## 🎨 Managing Appearance from the Panel

From the **Settings** section of the admin panel, you can change the following without touching any code:

- Site name and tagline, contact info, social networks
- Hero section texts (title, subtitle, buttons, background image)
- Primary color, dark color, and light color of the site
- "About Us" page content
- Footer texts

---

## 🔐 Security

- Passwords are stored using `password_hash()` / `password_verify()`.
- All forms include a **CSRF** token.
- All queries are executed with **Prepared Statements**.
- All user data output is escaped with `htmlspecialchars`.
- Direct access to the `app/`, `config/`, and `database/` folders is blocked.

---

## 🐞 Debugging

To see detailed error messages, set `app.debug` to `true` in `config/config.php`
(in production, keep it `false` so error details are not displayed).
