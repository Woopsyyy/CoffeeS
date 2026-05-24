<?php
/**
 * Cafe Espresso - Authentication Engine
 */

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/session.php';
require_once __DIR__ . '/helpers.php';

/**
 * Check if a user is logged in
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Get current logged in user details
 */
function getCurrentUser() {
    global $pdo;
    if (!isLoggedIn()) return null;
    
    $stmt = $pdo->prepare("SELECT id, username, email, role, created_at FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch();
}

/**
 * Check if the logged-in user is an administrator
 */
function isAdmin() {
    return isLoggedIn() && isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

/**
 * Require general user login to access a page
 */
function requireLogin() {
    if (!isLoggedIn()) {
        setFlashMessage('error', 'Please sign in to access that page.');
        header("Location: " . BASE_URL . "/pages/login.php");
        exit;
    }
}

/**
 * Require administrator permissions to access a page
 */
function requireAdmin() {
    requireLogin();
    if (!isAdmin()) {
        setFlashMessage('error', 'Access denied. Administrator privileges are required.');
        header("Location: " . BASE_URL . "/pages/home.php");
        exit;
    }
}

/**
 * Log in a user with username/password check
 */
function loginUser($username, $password) {
    global $pdo;
    
    // Sanitize input
    $username = trim($username);
    
    // Check user table
    $stmt = $pdo->prepare("SELECT id, username, password, email, role FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $username]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        // Regenerate session ID for safety against session fixation
        session_regenerate_id(true);
        
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['username'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = $user['role'];
        
        // Log event
        logAnalyticsEvent('user_login', json_encode(['user_id' => $user['id'], 'username' => $user['username']]));
        
        // Merge guest cart to DB if exists
        if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
            mergeCartToDatabase($user['id']);
        }
        
        return $user;
    }
    
    return false;
}

/**
 * Merge session cart items into the database upon login
 */
function mergeCartToDatabase($userId) {
    global $pdo;
    
    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) return;
    
    foreach ($_SESSION['cart'] as $key => $item) {
        $productId = $item['product_id'];
        $qty = $item['quantity'];
        $sugar = $item['sugar_level'];
        
        // Check if item already exists in database cart
        $stmt = $pdo->prepare("SELECT id, quantity FROM cart_items WHERE user_id = ? AND product_id = ? AND sugar_level = ?");
        $stmt->execute([$userId, $productId, $sugar]);
        $existing = $stmt->fetch();
        
        if ($existing) {
            $newQty = $existing['quantity'] + $qty;
            $update = $pdo->prepare("UPDATE cart_items SET quantity = ? WHERE id = ?");
            $update->execute([$newQty, $existing['id']]);
        } else {
            $insert = $pdo->prepare("INSERT INTO cart_items (user_id, product_id, quantity, sugar_level) VALUES (?, ?, ?, ?)");
            $insert->execute([$userId, $productId, $qty, $sugar]);
        }
    }
    
    // Clear session cart
    unset($_SESSION['cart']);
}

/**
 * Log out current user
 */
function logoutUser() {
    if (isLoggedIn()) {
        logAnalyticsEvent('user_logout', json_encode(['user_id' => $_SESSION['user_id']]));
    }
    
    // Clear session data
    session_unset();
    session_destroy();
    
    // Start fresh clean session
    session_name('cafe_espresso_session');
    session_start();
    
    setFlashMessage('success', 'You have been logged out successfully.');
}

/**
 * Register a new user
 */
function registerUser($username, $email, $password) {
    global $pdo;
    
    $username = trim($username);
    $email = trim($email);
    
    // Check if username/email already taken
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $email]);
    if ($stmt->fetch()) {
        return 'Username or email already exists.';
    }
    
    // Hash password safely
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert new user
    $insert = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'customer')");
    $result = $insert->execute([$username, $email, $hashedPassword]);
    
    if ($result) {
        $userId = $pdo->lastInsertId();
        logAnalyticsEvent('user_register', json_encode(['user_id' => $userId, 'username' => $username]));
        return true;
    }
    
    return 'Registration failed due to a system error. Please try again.';
}
