<?php
/**
 * Cafe Espresso - Admin SaaS Dashboard Overview
 */
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/auth.php';

// Strict administrator verification
requireAdmin();

$currentUser = getCurrentUser();

// 1. Compute Metric Statistics dynamically from DB
try {
    // Total Revenue (Only completed paid orders)
    $revStmt = $pdo->query("SELECT SUM(total_amount) FROM orders WHERE status = 'completed'");
    $totalRevenue = (float)$revStmt->fetchColumn();

    // Total Orders count
    $orderCountStmt = $pdo->query("SELECT COUNT(*) FROM orders");
    $totalOrders = (int)$orderCountStmt->fetchColumn();

    // Total Customers registered
    $custStmt = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'customer'");
    $totalCustomers = (int)$custStmt->fetchColumn();

    // Low stock items count
    $stockStmt = $pdo->query("SELECT COUNT(*) FROM inventory WHERE stock_quantity <= low_stock_threshold");
    $lowStockItems = (int)$stockStmt->fetchColumn();

    // Recent 5 Orders list
    $recentOrdersStmt = $pdo->query("
        SELECT o.*, u.username 
        FROM orders o 
        JOIN users u ON o.user_id = u.id 
        ORDER BY o.created_at DESC 
        LIMIT 5
    ");
    $recentOrders = $recentOrdersStmt->fetchAll();
} catch (Exception $e) {
    die("SaaS system offline: " . $e->getMessage());
}

// 2. Handle quick order status update from dashboard
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'quick_status') {
    verifyPostCsrf();
    
    $orderId = isset($_POST['order_id']) ? (int)$_POST['order_id'] : 0;
    $status = sanitizeInput($_POST['status'] ?? 'pending');
    
    if ($orderId > 0) {
        try {
            $update = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
            $update->execute([$status, $orderId]);
            
            // If status changed to completed, set payment status to paid!
            if ($status === 'completed') {
                $payUpdate = $pdo->prepare("UPDATE orders SET payment_status = 'paid' WHERE id = ?");
                $payUpdate->execute([$orderId]);
            }
            
            setFlashMessage('success', "Order #{$orderId} status shifted to " . ucfirst($status));
        } catch (Exception $e) {
            setFlashMessage('error', 'Error updating order status.');
        }
    }
    header("Location: " . BASE_URL . "/admin/dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espresso SaaS - Dashboard Overview</title>
    
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
            <h2 class="admin-navbar-title">Overview Dashboard</h2>
            <div class="admin-navbar-actions">
                <div class="admin-user-profile">
                    <span class="color-muted text-xs">Logged in as Administrator</span>
                    <div class="admin-user-avatar"><?php echo strtoupper(substr($currentUser['username'], 0, 1)); ?></div>
                    <strong><?php echo sanitize($currentUser['username']); ?></strong>
                </div>
            </div>
        </header>

        <!-- Dashboard Content Body Workspace -->
        <main class="admin-content-body">
            <!-- Analytics Metrics Tiles Row -->
            <section class="metrics-row">
                <!-- Metric 1: Total Sales -->
                <div class="metric-card">
                    <div class="metric-info">
                        <h5>Total Revenue</h5>
                        <span class="metric-value"><?php echo formatPrice($totalRevenue); ?></span>
                    </div>
                    <div class="metric-icon-box"><i class="fa-solid fa-coins"></i></div>
                </div>

                <!-- Metric 2: Total Orders -->
                <div class="metric-card">
                    <div class="metric-info">
                        <h5>Total Orders</h5>
                        <span class="metric-value"><?php echo $totalOrders; ?></span>
                    </div>
                    <div class="metric-icon-box"><i class="fa-solid fa-receipt"></i></div>
                </div>

                <!-- Metric 3: Active Customers -->
                <div class="metric-card">
                    <div class="metric-info">
                        <h5>Customers</h5>
                        <span class="metric-value"><?php echo $totalCustomers; ?></span>
                    </div>
                    <div class="metric-icon-box"><i class="fa-solid fa-users"></i></div>
                </div>

                <!-- Metric 4: Inventory Low Stock Alerts -->
                <div class="metric-card <?php echo ($lowStockItems > 0) ? 'alert-card' : ''; ?>" onclick="location.href='<?php echo BASE_URL; ?>/admin/products.php'">
                    <div class="metric-info">
                        <h5>Stock Alerts</h5>
                        <span class="metric-value"><?php echo $lowStockItems; ?></span>
                    </div>
                    <div class="metric-icon-box">
                        <i class="fa-solid <?php echo ($lowStockItems > 0) ? 'fa-triangle-exclamation' : 'fa-check'; ?>"></i>
                    </div>
                </div>
            </section>

            <!-- Dashboard Analytics Chart Section -->
            <section class="analytics-grid">
                <!-- Sales Trend Area Graph -->
                <div class="chart-card">
                    <div class="chart-card-header">
                        <h4 class="chart-card-title">Monthly Revenue Trends</h4>
                        <span class="badge badge-primary">Analytical Log</span>
                    </div>
                    <div class="chart-container">
                        <canvas id="salesTrendChart"></canvas>
                    </div>
                </div>

                <!-- Category sales doughnut -->
                <div class="chart-card">
                    <div class="chart-card-header">
                        <h4 class="chart-card-title">Category Popularity</h4>
                    </div>
                    <div class="chart-container">
                        <canvas id="categoryShareChart"></canvas>
                    </div>
                </div>
            </section>

            <!-- Recent Orders Data Table -->
            <section class="admin-card">
                <div class="admin-card-header">
                    <h4 class="admin-card-title">Recent Customer Orders</h4>
                    <a href="<?php echo BASE_URL; ?>/admin/orders.php" class="btn btn-outline btn-sm">Manage All Orders</a>
                </div>

                <?php if (!empty($recentOrders)): ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Customer</th>
                                    <th>Placed Date</th>
                                    <th>Method</th>
                                    <th>Amount</th>
                                    <th>Quick Status Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentOrders as $order): ?>
                                    <tr>
                                        <td class="font-semibold" style="color:var(--accent-gold);">#<?php echo $order['id']; ?></td>
                                        <td><strong><?php echo sanitize($order['username']); ?></strong></td>
                                        <td><?php echo date('M d, Y &bull; h:i A', strtotime($order['created_at'])); ?></td>
                                        <td class="text-xs"><?php echo sanitize($order['payment_method']); ?></td>
                                        <td class="font-semibold"><?php echo formatPrice($order['total_amount']); ?></td>
                                        <td>
                                            <form method="POST" action="" class="flex align-center gap-xs">
                                                <?php csrfField(); ?>
                                                <input type="hidden" name="action" value="quick_status">
                                                <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                                
                                                <select name="status" id="quick-status-select-<?php echo $order['id']; ?>" class="status-select" onchange="this.form.submit()">
                                                    <option value="pending" <?php echo ($order['status'] === 'pending') ? 'selected' : ''; ?>>Pending</option>
                                                    <option value="processing" <?php echo ($order['status'] === 'processing') ? 'selected' : ''; ?>>Processing</option>
                                                    <option value="completed" <?php echo ($order['status'] === 'completed') ? 'selected' : ''; ?>>Completed</option>
                                                    <option value="cancelled" <?php echo ($order['status'] === 'cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                                                </select>
                                                
                                                <?php if ($order['status'] === 'pending'): ?>
                                                    <button type="submit" class="btn btn-success btn-sm" style="background:var(--success); color:#FFF; border:none; padding:6px 12px; font-size:0.7rem; font-weight:600; display:flex; align-items:center; gap:4px; border-radius:6px; transition: all var(--transition-fast); margin-left:5px;" onclick="document.getElementById('quick-status-select-<?php echo $order['id']; ?>').value='processing';">
                                                        <i class="fa-solid fa-check"></i> Accept
                                                    </button>
                                                <?php endif; ?>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="color-muted text-center py-4 font-light">No customer orders recorded in the system yet.</p>
                <?php endif; ?>
            </section>
        </main>
    </div>

    <!-- Core Chart.js CDN Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js" crossorigin="anonymous" defer></script>
    <!-- SaaS dashboard analytical scripts linked -->
    <script src="<?php echo BASE_URL; ?>/assets/js/app.js" defer></script>
    <script src="<?php echo BASE_URL; ?>/assets/js/admin.js" defer></script>

</body>
</html>
