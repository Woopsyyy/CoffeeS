<?php
/**
 * Cafe Espresso - Admin SaaS Customer Management Directory
 */
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/auth.php';

// Verification admin permissions
requireAdmin();

$currentUser = getCurrentUser();

// Fetch customer metrics from DB
try {
    $stmt = $pdo->query("
        SELECT u.id, u.username, u.email, u.created_at, 
               COUNT(o.id) as total_orders, 
               COALESCE(SUM(o.total_amount), 0.00) as total_spent 
        FROM users u 
        LEFT JOIN orders o ON u.id = o.user_id AND o.status = 'completed'
        WHERE u.role = 'customer'
        GROUP BY u.id
        ORDER BY u.id ASC
    ");
    $customers = $stmt->fetchAll();
} catch (Exception $e) {
    die("Database fetch error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espresso SaaS - Customers Directory</title>
    
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
            <h2 class="admin-navbar-title">Customers Directory</h2>
            <div class="admin-navbar-actions">
                <div class="admin-user-profile">
                    <div class="admin-user-avatar"><?php echo strtoupper(substr($currentUser['username'], 0, 1)); ?></div>
                    <strong><?php echo sanitize($currentUser['username']); ?></strong>
                </div>
            </div>
        </header>

        <!-- Dashboard Content Body Workspace -->
        <main class="admin-content-body">
            <!-- Customer profiles log table -->
            <section class="admin-card">
                <div class="admin-card-header">
                    <h4 class="admin-card-title">Registered Patrons profiles</h4>
                </div>

                <?php if (!empty($customers)): ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Customer ID</th>
                                    <th>Username</th>
                                    <th>Email Address</th>
                                    <th>Registration Date</th>
                                    <th>Completed Orders</th>
                                    <th>Total Sourced Volume</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($customers as $cust): ?>
                                    <tr>
                                        <td class="font-semibold" style="color:var(--accent-gold);">#<?php echo $cust['id']; ?></td>
                                        <td><strong><?php echo sanitize($cust['username']); ?></strong></td>
                                        <td><?php echo sanitize($cust['email']); ?></td>
                                        <td><?php echo date('M d, Y &bull; h:i A', strtotime($cust['created_at'])); ?></td>
                                        <td><span class="badge badge-success"><?php echo $cust['total_orders']; ?> orders</span></td>
                                        <td class="font-semibold" style="color:var(--coffee-dark);"><?php echo formatPrice($cust['total_spent']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="color-muted text-center py-4 font-light">No customer accounts registered in the database.</p>
                <?php endif; ?>
            </section>
        </main>
    </div>

    <!-- Script triggers linked -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js" crossorigin="anonymous" defer></script>
    <script src="<?php echo BASE_URL; ?>/assets/js/app.js" defer></script>

</body>
</html>
