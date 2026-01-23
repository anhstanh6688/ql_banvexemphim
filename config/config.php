<?php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'movie_booking');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// App Configuration
define('APPROOT', dirname(__DIR__));
define('URLROOT', 'http://localhost/web-ql-ve-xem-phim/public');
define('SITENAME', 'Movie Booking System');
// Legacy support if needed, but better to unify
define('APP_ROOT', dirname(__DIR__));
define('URL_ROOT', 'http://localhost/web-ql-ve-xem-phim/public');
define('SITE_NAME', 'Movie Booking System');

// Error Reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Timezone
date_default_timezone_set('Asia/Ho_Chi_Minh');

// Session Start (if not started)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
