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
<style>
/* User Profile Dropdown Styles - Inline to bypass aggressive browser caching */
.profile-dropdown-container {
  position: relative;
  display: inline-block;
}

.profile-dropdown-trigger {
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 6px;
  padding: 6px 12px;
  border-radius: var(--border-radius-full);
  border: 1px solid rgba(44, 26, 17, 0.15);
  font-weight: 500;
  font-size: 0.9rem;
  color: var(--coffee-dark);
  transition: all var(--transition-fast);
}

.profile-dropdown-trigger:hover {
  border-color: var(--accent-gold);
  background-color: rgba(192, 130, 70, 0.05);
}

.profile-dropdown-menu {
  position: absolute;
  top: 100%;
  right: 0;
  margin-top: 10px;
  background-color: var(--white);
  border: 1px solid rgba(44, 26, 17, 0.08);
  border-radius: var(--border-radius-md);
  box-shadow: var(--shadow-lg);
  min-width: 180px;
  opacity: 0;
  visibility: hidden;
  transform: translateY(10px);
  transition: all var(--transition-normal);
  z-index: 1000;
  padding: 8px 0;
}

.profile-dropdown-container:hover .profile-dropdown-menu {
  opacity: 1;
  visibility: visible;
  transform: translateY(0);
}

.profile-dropdown-menu::before {
  content: '';
  position: absolute;
  bottom: 100%;
  right: 20px;
  border-width: 6px;
  border-style: solid;
  border-color: transparent transparent var(--white) transparent;
}

.profile-dropdown-menu::after {
  content: '';
  position: absolute;
  bottom: 100%;
  right: 19px;
  border-width: 7px;
  border-style: solid;
  border-color: transparent transparent rgba(44, 26, 17, 0.08) transparent;
  z-index: -1;
}

.profile-dropdown-item {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 10px 16px;
  color: var(--coffee-dark);
  font-size: 0.875rem;
  font-weight: 500;
  transition: all var(--transition-fast);
  text-align: left;
  white-space: nowrap;
}

.profile-dropdown-item:hover {
  background-color: rgba(192, 130, 70, 0.06);
  color: var(--accent-gold);
}

.profile-dropdown-item i {
  font-size: 0.95rem;
  width: 16px;
  text-align: center;
}

.profile-dropdown-divider {
  height: 1px;
  background-color: rgba(44, 26, 17, 0.06);
  margin: 6px 0;
}

.profile-dropdown-item.logout-item {
  color: var(--danger);
}

.profile-dropdown-item.logout-item:hover {
  background-color: rgba(198, 40, 40, 0.05);
  color: var(--danger);
}
</style>
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
                <div class="profile-dropdown-container">
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
                        <i class="fa-solid fa-chevron-down" style="font-size: 0.65rem; margin-left: 2px; opacity: 0.7;"></i>
                    </div>
                    <div class="profile-dropdown-menu">
                        <a href="<?php echo BASE_URL; ?>/pages/profile.php" class="profile-dropdown-item">
                            <i class="fa-solid fa-user"></i> My Profile
                        </a>
                        <?php if (isAdmin()): ?>
                            <a href="<?php echo BASE_URL; ?>/admin/dashboard.php" class="profile-dropdown-item" style="color:var(--accent-gold); font-weight:600;">
                                <i class="fa-solid fa-user-shield"></i> Admin Panel
                            </a>
                        <?php endif; ?>
                        <div class="profile-dropdown-divider"></div>
                        <a href="<?php echo BASE_URL; ?>/pages/login.php?logout=true" class="profile-dropdown-item logout-item">
                            <i class="fa-solid fa-right-from-bracket"></i> Sign Out
                        </a>
                    </div>
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
