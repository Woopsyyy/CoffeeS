<?php
/**
 * Cafe Espresso - Admin Sidebar Navigation Component
 */
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/auth.php';

// Verify admin permission
requireAdmin();

$script = $_SERVER['SCRIPT_NAME'];
$basename = basename($script);

function adminActive($page) {
    global $basename;
    return ($basename === $page) ? 'active' : '';
}
?>
<!-- SaaS Admin Navigation Sidebar -->
<aside class="admin-sidebar">
    <!-- Header brand logo -->
    <div class="admin-sidebar-header">
        <i class="fa-solid fa-mug-hot"></i>
        <h3>Espresso SaaS</h3>
    </div>

    <!-- Administrative Directories list -->
    <nav class="admin-menu-list">
        <a href="<?php echo BASE_URL; ?>/admin/dashboard.php" class="admin-menu-link <?php echo adminActive('dashboard.php'); ?>">
            <i class="fa-solid fa-chart-line"></i>
            <span>Overview</span>
        </a>
        <a href="<?php echo BASE_URL; ?>/admin/products.php" class="admin-menu-link <?php echo adminActive('products.php'); ?>">
            <i class="fa-solid fa-box-open"></i>
            <span>Products</span>
        </a>
        <a href="<?php echo BASE_URL; ?>/admin/categories.php" class="admin-menu-link <?php echo adminActive('categories.php'); ?>">
            <i class="fa-solid fa-tags"></i>
            <span>Categories</span>
        </a>
        <a href="<?php echo BASE_URL; ?>/admin/orders.php" class="admin-menu-link <?php echo adminActive('orders.php'); ?>">
            <i class="fa-solid fa-receipt"></i>
            <span>Orders</span>
        </a>
        <a href="<?php echo BASE_URL; ?>/admin/customers.php" class="admin-menu-link <?php echo adminActive('customers.php'); ?>">
            <i class="fa-solid fa-users"></i>
            <span>Customers</span>
        </a>
        <a href="<?php echo BASE_URL; ?>/admin/analytics.php" class="admin-menu-link <?php echo adminActive('analytics.php'); ?>">
            <i class="fa-solid fa-chart-pie"></i>
            <span>Analytics</span>
        </a>
        <a href="<?php echo BASE_URL; ?>/admin/settings.php" class="admin-menu-link <?php echo adminActive('settings.php'); ?>">
            <i class="fa-solid fa-gears"></i>
            <span>Settings</span>
        </a>
    </nav>

    <!-- Sidebar footer portal controls -->
    <div class="admin-menu-footer">
        <a href="<?php echo BASE_URL; ?>/pages/home.php" class="admin-menu-link" style="color:var(--accent-gold);">
            <i class="fa-solid fa-house"></i>
            <span>Storefront</span>
        </a>
        <a href="<?php echo BASE_URL; ?>/pages/login.php?logout=true" class="admin-menu-link" style="color:var(--danger); margin-top:6px;">
            <i class="fa-solid fa-power-off"></i>
            <span>Sign Out</span>
        </a>
    </div>
</aside>
