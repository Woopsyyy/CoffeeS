<?php
/**
 * Cafe Espresso - Secure Session Management & CSRF Defense
 */

require_once __DIR__ . '/config.php';

// Safe cookie parameters
if (session_status() === PHP_SESSION_NONE) {
    // Set custom session name for safety
    session_name('cafe_espresso_session');
    
    // Set session cookie parameters
    session_set_cookie_params([
        'lifetime' => SESSION_LIFETIME,
        'path' => '/',
        'domain' => $_SERVER['HTTP_HOST'] ?? '',
        'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    
    session_start();
}

// Session Timeout Handler
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > SESSION_LIFETIME)) {
    session_unset();
    session_destroy();
    
    // Restart empty session
    session_name('cafe_espresso_session');
    session_start();
}
$_SESSION['last_activity'] = time();

// CSRF Token Generation
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

/**
 * Get current CSRF Token for input fields
 */
function getCsrfToken() {
    return $_SESSION['csrf_token'] ?? '';
}

/**
 * Output hidden CSRF HTML field
 */
function csrfField() {
    echo '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(getCsrfToken()) . '">';
}

/**
 * Validate CSRF Token
 */
function validateCsrfToken($token) {
    if (empty($_SESSION['csrf_token']) || empty($token)) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Verify incoming POST requests for CSRF safety
 */
function verifyPostCsrf() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $token = $_POST['csrf_token'] ?? '';
        if (!validateCsrfToken($token)) {
            // Log attack attempt
            error_log("CSRF Attack Blocked: User Agent: " . ($_SERVER['HTTP_USER_AGENT'] ?? 'Unknown'));
            
            // Render beautiful error
            die("<div style='font-family:sans-serif; text-align:center; padding:50px; background:#FDFBF7; color:#2C1A11;'>
                    <h2 style='color:#ea4335;'>Security Alert (CSRF Validation Failed)</h2>
                    <p>Your session may have expired or the request was unauthorized. Please go back, refresh, and try again.</p>
                    <a href='index.php' style='display:inline-block; margin-top:20px; padding:10px 20px; background:#9C663B; color:#fff; text-decoration:none; border-radius:5px;'>Back to Homepage</a>
                 </div>");
        }
    }
}
