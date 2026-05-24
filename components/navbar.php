<?php
/**
 * Cafe Espresso - Navbar Component
 */
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/auth.php';

$currentUser = getCurrentUser();
$cartCount = getCartCount();
?>
<!-- Navigation Header Sticky Bar -->
<header class="header-sticky">
    <div class="container navbar">
        <!-- Logo Brand -->
        <a href="<?php echo BASE_URL; ?>/pages/home.php" class="nav-brand">
            <i class="fa-solid fa-mug-hot nav-brand-logo"></i>
            <span class="nav-brand-text">Cafe Espresso</span>
        </a>

        <!-- Desktop Navigation Directory Links -->
        <nav class="nav-links-container">
            <a href="<?php echo BASE_URL; ?>/pages/home.php" class="nav-link <?php echo activeClass('home.php'); ?>">Home</a>
            <a href="<?php echo BASE_URL; ?>/pages/menu.php" class="nav-link <?php echo activeClass('menu.php'); ?>">Menu</a>
            <a href="<?php echo BASE_URL; ?>/pages/blog.php" class="nav-link <?php echo activeClass('blog.php'); ?>">Blog</a>
            <a href="<?php echo BASE_URL; ?>/pages/about.php" class="nav-link <?php echo activeClass('about.php'); ?>">About Us</a>
            <a href="<?php echo BASE_URL; ?>/pages/contact.php" class="nav-link <?php echo activeClass('contact.php'); ?>">Contact</a>
            <?php if (isAdmin()): ?>
                <a href="<?php echo BASE_URL; ?>/admin/dashboard.php" class="nav-link" style="color:var(--accent-gold); font-weight:600;"><i class="fa-solid fa-user-shield" style="margin-right:4px;"></i>Admin Panel</a>
            <?php endif; ?>
            
            <!-- Mobile Specific Accounts Links -->
            <?php if (isLoggedIn()): ?>
                <?php if (isAdmin()): ?>
                    <a href="<?php echo BASE_URL; ?>/admin/dashboard.php" class="nav-link mobile-only-link" style="color:var(--accent-gold);">Admin Panel</a>
                <?php endif; ?>
                <a href="<?php echo BASE_URL; ?>/pages/profile.php" class="nav-link mobile-only-link">My Profile</a>
                <a href="<?php echo BASE_URL; ?>/pages/login.php?logout=true" class="nav-link mobile-only-link" style="color:var(--danger);">Sign Out</a>
            <?php else: ?>
                <a href="<?php echo BASE_URL; ?>/pages/login.php" class="nav-link mobile-only-link">Sign In</a>
            <?php endif; ?>
        </nav>

        <!-- User Accounts & Dynamic Shopping Cart Interactions -->
        <div class="nav-actions">
            <!-- Shopping Cart Icon Indicator -->
            <a href="<?php echo BASE_URL; ?>/pages/cart.php" class="cart-icon-wrapper" aria-label="Shopping Cart">
                <i class="fa-solid fa-bag-shopping"></i>
                <?php if ($cartCount > 0): ?>
                    <span class="cart-badge"><?php echo $cartCount; ?></span>
                <?php endif; ?>
            </a>

            <!-- Desktop User Dropdown Portal -->
            <?php if (isLoggedIn()): ?>
                <div class="profile-dropdown-trigger" onclick="location.href='<?php echo BASE_URL; ?>/pages/profile.php'">
                    <div class="admin-user-avatar" style="width:28px; height:28px; font-size:0.75rem;">
                        <?php echo strtoupper(substr($currentUser['username'], 0, 1)); ?>
                    </div>
                    <span style="max-width: 90px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                        <?php echo sanitize($currentUser['username']); ?>
                    </span>
                    <?php if (isAdmin()): ?>
                        <i class="fa-solid fa-user-shield" style="color:var(--accent-gold); font-size:0.8rem;" title="Administrator"></i>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <a href="<?php echo BASE_URL; ?>/pages/login.php" class="btn btn-secondary btn-sm" style="padding: 8px 20px;">Sign In</a>
            <?php endif; ?>

            <!-- Mobile Hamburger Menu Button -->
            <button class="mobile-nav-toggle" aria-label="Toggle Menu">
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>
    </div>
</header>

<!-- Global Alert Stack Notification Container -->
<?php include __DIR__ . '/alerts.php'; ?>
