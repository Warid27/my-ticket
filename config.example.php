<?php

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'my_ticket');
define('DB_USER', 'root');
define('DB_PASS', '');

// Email Configuration (Resend)
// Try to get API key from environment variable first, then fallback to hardcoded value
define('RESEND_API_KEY', getenv('RESEND_API_KEY') ?: 'your_resend_api_key_here');

// Email domain for sending (must match your verified domain in Resend)
define('EMAIL_DOMAIN', 'your-domain.com');
define('FROM_EMAIL', 'noreply@' . EMAIL_DOMAIN);
define('FROM_NAME', 'MyTicket');

// Application Configuration
define('APP_NAME', 'MyTicket');

// App URL is different from email domain
define('APP_URL', 'https://your-domain.com/my-ticket/');
define('APP_DOMAIN', 'your-domain.com');

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
define('XENDIT_API_KEY', getenv('XENDIT_API_KEY') ?: 'your_xendit_api_key_here');
define('XENDIT_PUBLIC_KEY', getenv('XENDIT_PUBLIC_KEY') ?: 'your_xendit_public_key_here');
define('XENDIT_WEBHOOK_TOKEN', getenv('XENDIT_WEBHOOK_TOKEN') ?: 'your_xendit_webhook_token_here');
define('XENDIT_MODE', 'sandbox'); // 'sandbox' or 'production'

// Timezone
date_default_timezone_set('Asia/Jakarta');
