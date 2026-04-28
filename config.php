<?php

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'my_ticket');
define('DB_USER', 'root');
define('DB_PASS', '');

// Email Configuration (Resend)
// Try to get API key from environment variable first, then fallback to hardcoded value
define('RESEND_API_KEY', getenv('RESEND_API_KEY') ?: 're_H7CVor3w_6ApD2dEUHaY5KC3wNBByEuFm');

// Email domain for sending (must match your verified domain in Resend)
define('EMAIL_DOMAIN', 'my-ticket.al-warid.web.id');
define('FROM_EMAIL', 'noreply@' . EMAIL_DOMAIN);
define('FROM_NAME', 'MyTicket');

// Application Configuration
define('APP_NAME', 'MyTicket');

// App URL is different from email domain
define('APP_URL', 'https://wisata.al-warid.web.id/my-ticket/');
define('APP_DOMAIN', 'wisata.al-warid.web.id');

define('APP_ENV', 'production'); // 'development' or 'production'

// Security Configuration
define('PASSWORD_MIN_LENGTH', 8);
define('PASSWORD_RESET_EXPIRY', '1 hour'); // PHP strtotime format

// Error Reporting
if (APP_ENV === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Session Configuration
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', APP_ENV === 'production');

// Xendit Payment Configuration
// Get API key from environment variable first, then fallback (user should replace with actual key)
define('XENDIT_API_KEY', getenv('XENDIT_API_KEY') ?: 'xnd_development_EusZfzTPsK1G8JTXAX1fvf6uB31V9VcZE2QfPyhrpLyhvNAlxbhaa3wQE4516tj');
define('XENDIT_PUBLIC_KEY', getenv('XENDIT_PUBLIC_KEY') ?: 'xnd_public_development_F2pGKAyDXfQDvL1MQ0tGCCdpLz5bhk8Kaf3sT0qSoKpdyFRELnnZ80fVLYMygq');
define('XENDIT_WEBHOOK_TOKEN', getenv('XENDIT_WEBHOOK_TOKEN') ?: '6uUB55u1WMYNiEZrJek5s4TQGOi6CMknF7yEBEaikxAPcIae');
define('XENDIT_MODE', 'sandbox'); // 'sandbox' or 'production'

// Timezone
date_default_timezone_set('Asia/Jakarta');
