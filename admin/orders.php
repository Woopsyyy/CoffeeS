<?php
/**
 * Cafe Espresso - Admin SaaS Orders Management
 */
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/auth.php';

// Verification admin permissions
requireAdmin();

$currentUser = getCurrentUser();
$selectedStatus = isset($_GET['status']) ? sanitizeInput($_GET['status']) : 'all';

// 1. Handle Order status updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_order_status') {
    verifyPostCsrf();
    
    $orderId = isset($_POST['order_id']) ? (int)$_POST['order_id'] : 0;
    $status = sanitizeInput($_POST['status'] ?? 'pending');
    $paymentStatus = sanitizeInput($_POST['payment_status'] ?? 'pending');
    
    if ($orderId > 0) {
        try {
            $stmt = $pdo->prepare("UPDATE orders SET status = ?, payment_status = ? WHERE id = ?");
            $stmt->execute([$status, $paymentStatus, $orderId]);
            
            logAnalyticsEvent('admin_order_update', json_encode(['order_id' => $orderId, 'status' => $status, 'payment' => $paymentStatus]));
            setFlashMessage('success', "Order #{$orderId} credentials updated successfully.");
        } catch (Exception $e) {
            setFlashMessage('error', 'Error updating order records.');
        }
    }
    
    header("Location: " . BASE_URL . "/admin/orders.php?status=" . $selectedStatus);
    exit;
}

// 2. Fetch orders based on status filter
try {
    $query = "
        SELECT o.*, u.username, u.email 
        FROM orders o 
        JOIN users u ON o.user_id = u.id
    ";
    $params = [];
    
    if ($selectedStatus !== 'all') {
        $query .= " WHERE o.status = ?";
        $params[] = $selectedStatus;
    }
    
    $query .= " ORDER BY o.created_at DESC";
    
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $orders = $stmt->fetchAll();
} catch (Exception $e) {
    die("Database fetch error: " . $e->getMessage());
}

// Helper to fetch details of a specific order
function getAdminOrderItems($orderId) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("
            SELECT oi.quantity, oi.price, oi.sugar_level, p.name 
            FROM order_items oi
            JOIN products p ON oi.product_id = p.id
            WHERE oi.order_id = ?
        ");
        $stmt->execute([$orderId]);
        return $stmt->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espresso SaaS - Orders Management</title>
    
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
            <h2 class="admin-navbar-title">Orders Management</h2>
            <div class="admin-navbar-actions">
                <div class="admin-user-profile">
                    <div class="admin-user-avatar"><?php echo strtoupper(substr($currentUser['username'], 0, 1)); ?></div>
                    <strong><?php echo sanitize($currentUser['username']); ?></strong>
                </div>
            </div>
        </header>

        <!-- Dashboard Content Body Workspace -->
        <main class="admin-content-body">
            <!-- Filter Tabs panel -->
            <div class="blog-control-panel" style="border-bottom:none; margin-bottom:20px; padding-bottom:0;">
                <div class="blog-tabs">
                    <a href="<?php echo BASE_URL; ?>/admin/orders.php?status=all" class="blog-tab-btn <?php echo ($selectedStatus === 'all') ? 'active' : ''; ?>">All Orders</a>
                    <a href="<?php echo BASE_URL; ?>/admin/orders.php?status=pending" class="blog-tab-btn <?php echo ($selectedStatus === 'pending') ? 'active' : ''; ?>">Pending</a>
                    <a href="<?php echo BASE_URL; ?>/admin/orders.php?status=processing" class="blog-tab-btn <?php echo ($selectedStatus === 'processing') ? 'active' : ''; ?>">Processing</a>
                    <a href="<?php echo BASE_URL; ?>/admin/orders.php?status=completed" class="blog-tab-btn <?php echo ($selectedStatus === 'completed') ? 'active' : ''; ?>">Completed</a>
                    <a href="<?php echo BASE_URL; ?>/admin/orders.php?status=cancelled" class="blog-tab-btn <?php echo ($selectedStatus === 'cancelled') ? 'active' : ''; ?>">Cancelled</a>
                </div>
            </div>

            <!-- Orders Table List -->
            <section class="admin-card">
                <div class="admin-card-header">
                    <h4 class="admin-card-title"><?php echo ucfirst($selectedStatus); ?> Customer Orders</h4>
                </div>

                <?php if (!empty($orders)): ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Customer Info</th>
                                    <th>Placed Date</th>
                                    <th>Method</th>
                                    <th>Amount</th>
                                    <th>Delivery / Payment Statuses</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $order): 
                                    $items = getAdminOrderItems($order['id']);
                                ?>
                                    <!-- Header Row -->
                                    <tr style="cursor:pointer;" onclick="let d = document.getElementById('admin-order-detail-<?php echo $order['id']; ?>'); d.style.display = (d.style.display==='none') ? 'table-row' : 'none';">
                                        <td class="font-semibold" style="color:var(--accent-gold);">#<?php echo $order['id']; ?></td>
                                        <td>
                                            <strong style="color:var(--coffee-dark);"><?php echo sanitize($order['username']); ?></strong>
                                            <span class="color-muted text-xs" style="display:block;"><?php echo sanitize($order['email']); ?></span>
                                        </td>
                                        <td><?php echo date('M d, Y &bull; h:i A', strtotime($order['created_at'])); ?></td>
                                        <td class="text-xs"><?php echo sanitize($order['payment_method']); ?></td>
                                        <td class="font-semibold"><?php echo formatPrice($order['total_amount']); ?></td>
                                        <td>
                                            <!-- Simple inline editor -->
                                            <form method="POST" action="" class="flex align-center gap-xs" onclick="event.stopPropagation()">
                                                <?php csrfField(); ?>
                                                <input type="hidden" name="action" value="update_order_status">
                                                <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                                
                                                <!-- Delivery Status -->
                                                <select name="status" id="status-select-<?php echo $order['id']; ?>" class="status-select" style="background:#FFF;">
                                                    <option value="pending" <?php echo ($order['status'] === 'pending') ? 'selected' : ''; ?>>Pending</option>
                                                    <option value="processing" <?php echo ($order['status'] === 'processing') ? 'selected' : ''; ?>>Processing</option>
                                                    <option value="completed" <?php echo ($order['status'] === 'completed') ? 'selected' : ''; ?>>Completed</option>
                                                    <option value="cancelled" <?php echo ($order['status'] === 'cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                                                </select>

                                                <!-- Payment Status -->
                                                <select name="payment_status" class="status-select" style="background:#FFF;">
                                                    <option value="pending" <?php echo ($order['payment_status'] === 'pending') ? 'selected' : ''; ?>>Unpaid</option>
                                                    <option value="paid" <?php echo ($order['payment_status'] === 'paid') ? 'selected' : ''; ?>>Paid</option>
                                                    <option value="refunded" <?php echo ($order['payment_status'] === 'refunded') ? 'selected' : ''; ?>>Refunded</option>
                                                </select>

                                                <button type="submit" class="btn btn-secondary btn-sm" style="padding:6px 12px; font-size:0.7rem;"><i class="fa-solid fa-save"></i></button>

                                                <?php if ($order['status'] === 'pending'): ?>
                                                    <button type="submit" class="btn btn-success btn-sm" style="background:var(--success); color:#FFF; border:none; padding:6px 12px; font-size:0.7rem; font-weight:600; display:flex; align-items:center; gap:4px; border-radius:6px; transition: all var(--transition-fast);" onclick="document.getElementById('status-select-<?php echo $order['id']; ?>').value='processing';">
                                                        <i class="fa-solid fa-check"></i> Accept
                                                    </button>
                                                <?php endif; ?>
                                            </form>
                                        </td>
                                    </tr>

                                    <!-- Drawer Row Details -->
                                    <tr id="admin-order-detail-<?php echo $order['id']; ?>" style="display:none; background-color:#F8F9FA;">
                                        <td colspan="6" style="padding:20px; border-bottom:1px solid rgba(44,26,17,0.08);">
                                            <div class="grid grid-cols-2" style="grid-template-columns: 1.2fr 0.8fr; gap:30px;">
                                                <!-- Left: Items list -->
                                                <div>
                                                    <h4 class="color-coffee mb-2" style="font-size:1rem; border-bottom:1px solid rgba(44,26,17,0.05); padding-bottom:4px;">Products Ordered</h4>
                                                    <div style="display:flex; flex-direction:column; gap:8px;">
                                                        <?php foreach ($items as $item): ?>
                                                            <div class="flex justify-between" style="font-size:0.875rem; background:#FFF; padding:10px; border-radius:6px; border:1px solid rgba(44,26,17,0.04);">
                                                                <div>
                                                                    <strong><?php echo sanitize($item['name']); ?></strong>
                                                                    <span class="color-muted text-xs">&bull; <?php echo sanitize($item['sugar_level']); ?></span>
                                                                </div>
                                                                <span class="font-semibold"><?php echo $item['quantity']; ?> &times; <?php echo formatPrice($item['price']); ?></span>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                                
                                                <!-- Right: Shipping Log Details -->
                                                <div style="font-size:0.875rem;">
                                                    <h4 class="color-coffee mb-2" style="font-size:1rem; border-bottom:1px solid rgba(44,26,17,0.05); padding-bottom:4px;">Shipping Log</h4>
                                                    <p style="margin-bottom:6px;"><strong style="color:var(--coffee-dark);">Delivery Address:</strong><br><?php echo sanitize($order['shipping_address']); ?></p>
                                                    <p><strong style="color:var(--coffee-dark);">Customer Contact:</strong> <?php echo sanitize($order['contact_number']); ?></p>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="color-muted text-center py-4 font-light">No customer orders found matching this filter.</p>
                <?php endif; ?>
            </section>
        </main>
    </div>

    <!-- Script triggers linked -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js" crossorigin="anonymous" defer></script>
    <script src="<?php echo BASE_URL; ?>/assets/js/app.js" defer></script>

</body>
</html>
