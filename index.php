<?php
/**
 * Cafe Espresso - Root Front Gate Router
 */

require_once __DIR__ . '/includes/config.php';

// Dynamic out-of-the-box redirect to pages/home.php
header("Location: " . BASE_URL . "/pages/home.php");
exit;