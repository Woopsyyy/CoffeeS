<?php
/**
 * Cafe Espresso - Database Connection
 */

require_once __DIR__ . '/config.php';

try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
} catch (PDOException $e) {
    // Elegant, safe failure screen in production (avoids leaking credentials)
    die("<div style='font-family:sans-serif; text-align:center; padding:50px; background:#FDFBF7; color:#2C1A11;'>
            <h2 style='color:#9C663B;'>Database Connection Offline</h2>
            <p>Cafe Espresso database is currently undergoing maintenance. Please import <strong>database/coffees.sql</strong> via phpMyAdmin.</p>
            <p style='color:#a3b3ac; font-size:12px;'>Error details: " . htmlspecialchars($e->getMessage()) . "</p>
         </div>");
}
