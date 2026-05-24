<?php
/**
 * Cafe Espresso - Admin SaaS Advanced Analytics Reports
 */
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/auth.php';

// Verification admin permissions
requireAdmin();

$currentUser = getCurrentUser();

// 1. Fetch Dynamic Data for Chart.js
try {
    // A. Monthly Revenue sales trends (Past months)
    $salesStmt = $pdo->query("
        SELECT DATE_FORMAT(created_at, '%b %Y') as month_label, 
               SUM(total_amount) as monthly_revenue 
        FROM orders 
        WHERE status = 'completed' 
        GROUP BY DATE_FORMAT(created_at, '%Y-%m') 
        ORDER BY created_at ASC
    ");
    $salesData = $salesStmt->fetchAll();
    
    $months = [];
    $revenues = [];
    foreach ($salesData as $row) {
        $months[] = $row['month_label'];
        $revenues[] = (float)$row['monthly_revenue'];
    }
    
    // B. Category Share split (Product items sold per category)
    $catShareStmt = $pdo->query("
        SELECT c.name as category_label, 
               COUNT(oi.id) as item_count 
        FROM order_items oi 
        JOIN products p ON oi.product_id = p.id 
        JOIN categories c ON p.category_id = c.id 
        GROUP BY c.id
    ");
    $catShareData = $catShareStmt->fetchAll();
    
    $catLabels = [];
    $catCounts = [];
    foreach ($catShareData as $row) {
        $catLabels[] = $row['category_label'];
        $catCounts[] = (int)$row['item_count'];
    }
    
    // C. Popular Products list (Most ordered products)
    $popStmt = $pdo->query("
        SELECT p.name, c.name as category_name, 
               SUM(oi.quantity) as total_sold, 
               SUM(oi.quantity * oi.price) as total_revenue 
        FROM order_items oi 
        JOIN products p ON oi.product_id = p.id 
        JOIN categories c ON p.category_id = c.id 
        GROUP BY p.id 
        ORDER BY total_sold DESC 
        LIMIT 5
    ");
    $popularProducts = $popStmt->fetchAll();
} catch (Exception $e) {
    die("Database analytical logs error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espresso SaaS - Advanced Analytics</title>
    
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
            <h2 class="admin-navbar-title">Advanced Analytical Reports</h2>
            <div class="admin-navbar-actions">
                <div class="admin-user-profile">
                    <div class="admin-user-avatar"><?php echo strtoupper(substr($currentUser['username'], 0, 1)); ?></div>
                    <strong><?php echo sanitize($currentUser['username']); ?></strong>
                </div>
            </div>
        </header>

        <!-- Dashboard Content Body Workspace -->
        <main class="admin-content-body">
            <!-- Advanced charts grid system -->
            <section class="analytics-grid">
                <!-- Line Sales Trend -->
                <div class="chart-card">
                    <div class="chart-card-header">
                        <h4 class="chart-card-title">Live Revenue Trend Log</h4>
                        <span class="badge badge-success">Live Sync</span>
                    </div>
                    <div class="chart-container">
                        <!-- Data bindings using dataset attributes for secure app.js mapping -->
                        <canvas id="salesTrendChart" 
                                data-months="<?php echo sanitize(json_encode($months)); ?>"
                                data-revenues="<?php echo sanitize(json_encode($revenues)); ?>"></canvas>
                    </div>
                </div>

                <!-- Doughnut Share -->
                <div class="chart-card">
                    <div class="chart-card-header">
                        <h4 class="chart-card-title">Brews Popularity Split</h4>
                    </div>
                    <div class="chart-container">
                        <canvas id="categoryShareChart"
                                data-labels="<?php echo sanitize(json_encode($catLabels)); ?>"
                                data-counts="<?php echo sanitize(json_encode($catCounts)); ?>"></canvas>
                    </div>
                </div>
            </section>

            <!-- Most Popular Products Table panel -->
            <section class="admin-card">
                <div class="admin-card-header">
                    <h4 class="admin-card-title">Top Performing Coffee Brews & Pastries</h4>
                </div>

                <?php if (!empty($popularProducts)): ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Product Name</th>
                                    <th>Menu Category</th>
                                    <th>Items Ordered</th>
                                    <th>Total Revenue volume</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($popularProducts as $index => $prod): ?>
                                    <tr>
                                        <td>
                                            <span style="font-weight:700; color:var(--accent-gold); margin-right:8px;">0<?php echo $index + 1; ?></span>
                                            <strong><?php echo sanitize($prod['name']); ?></strong>
                                        </td>
                                        <td><span class="badge badge-primary"><?php echo sanitize($prod['category_name']); ?></span></td>
                                        <td class="font-semibold"><?php echo (int)$prod['total_sold']; ?> servings / items</td>
                                        <td class="font-semibold" style="color:var(--coffee-dark);"><?php echo formatPrice($prod['total_revenue']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="color-muted text-center py-4 font-light">No serves or sales registered in the roastery history.</p>
                <?php endif; ?>
            </section>
        </main>
    </div>

    <!-- Chart.js CDNs -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js" crossorigin="anonymous" defer></script>
    <!-- Administrative JavaScript linked -->
    <script src="<?php echo BASE_URL; ?>/assets/js/app.js" defer></script>
    <script src="<?php echo BASE_URL; ?>/assets/js/admin.js" defer></script>

</body>
</html>
