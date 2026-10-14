<?php
/**
 * -------------------------------------------------------------------------
 *  Application configuration
 * -------------------------------------------------------------------------
 *  Central place for all environment-specific settings. Edit the values
 *  below when you move the project to a live host.
 * -------------------------------------------------------------------------
 */

return [
    'app' => [
        // Human readable name of the travel agency (used as default fallback).
        'name'      => 'بهادری تراول ایجنسی',
        // Base URL of the application. Leave empty to auto-detect.
        'base_url'  => '',
        // 'production' hides detailed errors, 'local' shows them.
        'env'       => 'local',
        'debug'     => true,
        'timezone'  => 'Asia/Tehran',
        // Default locale / direction. This project is RTL (Persian).
        'locale'    => 'fa',
        'dir'       => 'rtl',
    ],

    'db' => [
        'host'     => '127.0.0.1',
        'port'     => '3306',
        'database' => 'bahadori_travel',
        'username' => 'root',
        'password' => '',
        'charset'  => 'utf8mb4',
    ],

    // Where uploaded files (tour / destination / article images) are stored.
    'upload' => [
        'dir'         => __DIR__ . '/../public/assets/uploads',
        'url_path'    => 'assets/uploads',
        'max_size'    => 4 * 1024 * 1024, // 4 MB
        'allowed'     => ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg'],
    ],

    // Optional Node.js real-time notification server (see /node-server).
    'node' => [
        'host' => '127.0.0.1',
        'port' => 3000,
        'secret' => 'change-this-secret-token',
    ],
];
