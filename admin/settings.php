<?php
/**
 * Cafe Espresso - Admin SaaS Settings Configuration
 */
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/auth.php';

// Verification admin permissions
requireAdmin();

$currentUser = getCurrentUser();

// 1. Fetch current settings dynamically from database
try {
    $stmt = $pdo->query("SELECT setting_key, setting_value FROM settings");
    $settingsRaw = $stmt->fetchAll();
    
    $settings = [];
    foreach ($settingsRaw as $row) {
        $settings[$row['setting_key']] = $row['setting_value'];
    }
} catch (Exception $e) {
    die("Database fetch error: " . $e->getMessage());
}

// 2. Handle configuration updates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyPostCsrf();
    
    $updates = [
        'store_name' => sanitizeInput($_POST['store_name'] ?? 'Cafe Espresso'),
        'store_email' => sanitizeInput($_POST['store_email'] ?? ''),
        'store_phone' => sanitizeInput($_POST['store_phone'] ?? ''),
        'store_address' => sanitizeInput($_POST['store_address'] ?? ''),
        'store_hours' => sanitizeInput($_POST['store_hours'] ?? ''),
        'tax_rate' => sanitizeInput($_POST['tax_rate'] ?? '12.00'),
        'shipping_fee' => sanitizeInput($_POST['shipping_fee'] ?? '50.00'),
        'currency' => sanitizeInput($_POST['currency'] ?? '₱')
    ];
    
    // Start updates transaction
    $pdo->beginTransaction();
    try {
        $upStmt = $pdo->prepare("UPDATE settings SET setting_value = ? WHERE setting_key = ?");
        
        foreach ($updates as $key => $value) {
            $upStmt->execute([$value, $key]);
        }
        
        $pdo->commit();
        logAnalyticsEvent('admin_settings_save', null);
        setFlashMessage('success', 'Roastery configurations saved successfully!');
        
        // Reload settings
        header("Location: " . BASE_URL . "/admin/settings.php");
        exit;
    } catch (Exception $e) {
        $pdo->rollBack();
        setFlashMessage('error', 'Error saving configurations: ' . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espresso SaaS - System Settings</title>
    
    <!-- CSS Dependencies -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/base.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/utilities.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/components.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/admin.css">
</head>
<body class="admin-layout">

    <!-- SaaS Sidebar Navigation Component -->
    <?php include __DIR__ . '/../components/sidebar.php'; ?>

    <div class="admin-main">
        <!-- Dashboard Top Header bar -->
        <header class="admin-navbar">
            <h2 class="admin-navbar-title">System Settings</h2>
            <div class="admin-navbar-actions">
                <div class="admin-user-profile">
                    <div class="admin-user-avatar"><?php echo strtoupper(substr($currentUser['username'], 0, 1)); ?></div>
                    <strong><?php echo sanitize($currentUser['username']); ?></strong>
                </div>
            </div>
        </header>

        <!-- Dashboard Content Body Workspace -->
        <main class="admin-content-body">
            <!-- Settings Card panel -->
            <section class="admin-card" style="max-width:800px; margin:0 auto 30px auto;">
                <div class="admin-card-header">
                    <h4 class="admin-card-title">Shop Properties & Configurations</h4>
                </div>

                <form method="POST" action="">
                    <?php csrfField(); ?>

                    <div class="admin-form-row">
                        <!-- Left Block: Store Details -->
                        <div>
                            <h4 class="color-coffee mb-2" style="font-size:1rem; border-bottom:1px solid rgba(44,26,17,0.05); padding-bottom:4px;">Cafe Identity</h4>
                            
                            <!-- Store Name -->
                            <div class="form-group">
                                <label for="set-name" class="form-label">Store Brand Name:</label>
                                <input type="text" id="set-name" name="store_name" class="form-control" value="<?php echo sanitize($settings['store_name'] ?? 'Cafe Espresso'); ?>" required>
                            </div>

                            <!-- Store Email -->
                            <div class="form-group">
                                <label for="set-email" class="form-label">Store Email Contact:</label>
                                <input type="email" id="set-email" name="store_email" class="form-control" value="<?php echo sanitize($settings['store_email'] ?? ''); ?>" required>
                            </div>

                            <!-- Store Phone -->
                            <div class="form-group">
                                <label for="set-phone" class="form-label">Store Phone / Mobile:</label>
                                <input type="text" id="set-phone" name="store_phone" class="form-control" value="<?php echo sanitize($settings['store_phone'] ?? ''); ?>" required>
                            </div>

                            <!-- Operating Timings -->
                            <div class="form-group">
                                <label for="set-hours" class="form-label">Timing / Working Hours:</label>
                                <input type="text" id="set-hours" name="store_hours" class="form-control" value="<?php echo sanitize($settings['store_hours'] ?? ''); ?>" required>
                            </div>
                        </div>

                        <!-- Right Block: Ecommerce parameters -->
                        <div>
                            <h4 class="color-coffee mb-2" style="font-size:1rem; border-bottom:1px solid rgba(44,26,17,0.05); padding-bottom:4px;">Billing Parameters</h4>
                            
                            <!-- Base Tax Rate -->
                            <div class="form-group">
                                <label for="set-tax" class="form-label">Standard Tax Rate (% VAT):</label>
                                <input type="number" id="set-tax" name="tax_rate" class="form-control" value="<?php echo sanitize($settings['tax_rate'] ?? '12.00'); ?>" step="0.01" min="0" max="100" required>
                            </div>

                            <!-- Base Delivery shipping fee -->
                            <div class="form-group">
                                <label for="set-shipping" class="form-label">Base Delivery Shipping Fee (₱):</label>
                                <input type="number" id="set-shipping" name="shipping_fee" class="form-control" value="<?php echo sanitize($settings['shipping_fee'] ?? '50.00'); ?>" step="0.01" min="0" required>
                            </div>

                            <!-- Currency symbol -->
                            <div class="form-group">
                                <label for="set-currency" class="form-label">Site Billing Currency Symbol:</label>
                                <input type="text" id="set-currency" name="currency" class="form-control" value="<?php echo sanitize($settings['currency'] ?? '₱'); ?>" required>
                            </div>

                            <!-- Store Physical Address -->
                            <div class="form-group">
                                <label for="set-address" class="form-label">Physical Roastery Address:</label>
                                <textarea id="set-address" name="store_address" rows="3" class="form-control" required><?php echo sanitize($settings['store_address'] ?? ''); ?></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end mt-4" style="border-top:1px solid rgba(44,26,17,0.08); padding-top:20px;">
                        <button type="submit" class="btn btn-secondary shadow-sm">Save Store Settings</button>
                    </div>
                </form>
            </section>
        </main>
    </div>

    <!-- Script linked dependencies -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js" crossorigin="anonymous" defer></script>
    <script src="<?php echo BASE_URL; ?>/assets/js/app.js" defer></script>

</body>
</html>
