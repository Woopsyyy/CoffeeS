<?php
/**
 * Cafe Espresso - System Configuration
 */

// Timezone setup
date_default_timezone_set('Asia/Manila');

// Database credentials
define('DB_HOST', 'localhost');
define('DB_NAME', 'coffees');
define('DB_USER', 'root');
define('DB_PASS', '');

// Dynamic BASE_URL Calculation (XAMPP & Apache friendly)
if (!defined('BASE_URL')) {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $script_name = $_SERVER['SCRIPT_NAME'] ?? '';
    
    // Auto-detect project folder in path (normally 'saji')
    $project_folder = 'saji';
    $pos = strrpos($script_name, '/' . $project_folder);
    if ($pos !== false) {
        $base_path = substr($script_name, 0, $pos + strlen($project_folder) + 1);
    } else {
        // Fallback for custom virtual hosts or nested roots
        $base_path = '/';
    }
    
    define('BASE_URL', rtrim($protocol . $host . $base_path, '/'));
}

// Global System Variables
define('TAX_RATE', 0.12); // 12% VAT
define('SHIPPING_FEE', 50.00); // Flat shipping fee in PHP
define('CURRENCY_SYMBOL', '₱');
define('LOW_STOCK_THRESHOLD', 10);

// Session lifetime configurations (2 hours)
define('SESSION_LIFETIME', 7200);
