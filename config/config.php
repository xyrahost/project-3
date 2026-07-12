<?php

require_once __DIR__ . "/security.php";

// Nama Website
define('SITE_NAME', 'TechWeb');

// URL Website
define('BASE_URL', 'https://techweb.biz.id');

// Email Pengirim
define('MAIL_FROM', 'noreply@techweb.biz.id');
define('MAIL_FROM_NAME', 'TechWeb');

// Timezone
date_default_timezone_set('Asia/Jakarta');
// Session Timeout
define('SESSION_TIMEOUT', 1800);

// Jalankan Session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/remember.php";