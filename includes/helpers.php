<?php
/**
 * Cafe Espresso - System Helpers
 */

require_once __DIR__ . '/config.php';

/**
 * Escapes output strings safely for HTML printing (Mitigates XSS)
 */
function sanitize($str) {
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Helper to clean and sanitize form inputs
 */
function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

/**
 * Formats decimal numerical figures into aesthetic peso currencies
 */
function formatPrice($amount) {
    return CURRENCY_SYMBOL . number_format((float)$amount, 2);
}

/**
 * Flash Messages Manager (Stackable banners)
 */
function setFlashMessage($type, $message) {
    $_SESSION['flash_alerts'][$type] = $message;
}

function getFlashMessage($type) {
    if (isset($_SESSION['flash_alerts'][$type])) {
        $msg = $_SESSION['flash_alerts'][$type];
        unset($_SESSION['flash_alerts'][$type]);
        return $msg;
    }
    return null;
}

function hasFlashMessages() {
    return !empty($_SESSION['flash_alerts']);
}

/**
 * Evaluates currently open page and appends "active" CSS token dynamically
 */
function activeClass($pageName) {
    $script = $_SERVER['SCRIPT_NAME'];
    $basename = basename($script);
    
    // Check if the current page base matches the requested base
    if ($basename === $pageName) {
        return 'active';
    }
    return '';
}

/**
 * Logs customer activities into administrative analytics grids
 */
function logAnalyticsEvent($type, $data = null) {
    global $pdo;
    
    // Check if connection is active
    if (!isset($pdo)) return;
    
    try {
        $stmt = $pdo->prepare("INSERT INTO analytics (event_type, event_data) VALUES (?, ?)");
        $stmt->execute([$type, $data]);
    } catch (Exception $e) {
        // Fail silently to avoid breaking page render on log issues
        error_log("Analytics error: " . $e->getMessage());
    }
}

/**
 * Calculate cart counts dynamically (Checks session or DB based on auth status)
 */
function getCartCount() {
    global $pdo;
    
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // Logged in user: pull from cart_items table
    if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
        if (!isset($pdo)) return 0;
        try {
            $stmt = $pdo->prepare("SELECT SUM(quantity) as count FROM cart_items WHERE user_id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $res = $stmt->fetch();
            return (int)($res['count'] ?? 0);
        } catch (Exception $e) {
            return 0;
        }
    }
    
    // Guest user: pull from session
    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
        return 0;
    }
    
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += (int)$item['quantity'];
    }
    return $total;
}

/**
 * Truncate long texts with elegant ellipsis
 */
function truncateText($text, $limit = 80) {
    if (strlen($text) <= $limit) return $text;
    return substr($text, 0, $limit) . '...';
}

/**
 * Render standard stock indicators
 */
function getStockBadge($qty, $threshold = LOW_STOCK_THRESHOLD) {
    if ($qty <= 0) {
        return '<span class="badge badge-danger">Out of Stock</span>';
    } elseif ($qty <= $threshold) {
        return '<span class="badge badge-warning">Low Stock (' . $qty . ')</span>';
    }
    return '<span class="badge badge-success">In Stock (' . $qty . ')</span>';
}
