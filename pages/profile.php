<?php
/**
 * Cafe Espresso - Customer Profile Dashboard
 */
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/auth.php';

// Force customer login
requireLogin();

$currentUser = getCurrentUser();
$userId = $_SESSION['user_id'];
$updateError = '';
$updateSuccess = '';

// Handle Profile Info Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_profile') {
    verifyPostCsrf();
    
    $email = trim($_POST['email'] ?? '');
    
    if (empty($email)) {
        $updateError = 'Email address cannot be empty.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $updateError = 'Please enter a valid email address.';
    } else {
        try {
            // Check if email already taken by someone else
            $check = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
            $check->execute([$email, $userId]);
            if ($check->fetch()) {
                $updateError = 'Email address already in use by another account.';
            } else {
                $update = $pdo->prepare("UPDATE users SET email = ? WHERE id = ?");
                $update->execute([$email, $userId]);
                
                $_SESSION['user_email'] = $email;
                $updateSuccess = 'Profile details updated successfully!';
                
                // Reload current user details
                $currentUser = getCurrentUser();
            }
        } catch (Exception $e) {
            $updateError = 'Error updating account details: ' . $e->getMessage();
        }
    }
}

// 2. Fetch past orders
try {
    $orderStmt = $pdo->prepare("
        SELECT o.*, 
               (SELECT COUNT(*) FROM order_items WHERE order_id = o.id) as item_count
        FROM orders o 
        WHERE o.user_id = ? 
        ORDER BY o.created_at DESC
    ");
    $orderStmt->execute([$userId]);
    $orders = $orderStmt->fetchAll();
} catch (Exception $e) {
    $orders = [];
}

// Helper to fetch details of a specific order
function getOrderItemsList($orderId) {
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
    <title>Cafe Espresso - Customer Profile</title>
    
    <!-- CSS Dependencies -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/base.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/utilities.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/layout.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/components.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/pages.css">
</head>
<body class="app-wrapper">

    <!-- Reusable Navbar -->
    <?php include __DIR__ . '/../components/navbar.php'; ?>

    <main class="container py-4">
        <!-- Main page headings -->
        <div class="mb-4">
            <span class="section-pretitle">Patron Hub</span>
            <h1 class="color-coffee font-semibold">My Account</h1>
        </div>

        <div class="cart-split-layout" style="grid-template-columns: 0.85fr 2.15fr; gap: 40px;">
            <!-- Left Side: Profile Details edit box -->
            <div style="background:#FFF; padding:30px; border-radius:var(--border-radius-md); border:1px solid rgba(44,26,17,0.05); box-shadow:var(--shadow-sm); height:fit-content;">
                <div class="text-center mb-4">
                    <div class="admin-user-avatar" style="width:64px; height:64px; font-size:1.8rem; margin:0 auto 12px auto; box-shadow:var(--shadow-sm);">
                        <?php echo strtoupper(substr($currentUser['username'], 0, 1)); ?>
                    </div>
                    <h3 class="color-coffee font-bold"><?php echo sanitize($currentUser['username']); ?></h3>
                    <span class="badge badge-primary mt-1" style="font-size:0.65rem;"><?php echo ucfirst(sanitize($currentUser['role'])); ?> Account</span>
                    <?php if (isAdmin()): ?>
                        <div class="mt-3">
                            <a href="<?php echo BASE_URL; ?>/admin/dashboard.php" class="btn btn-primary btn-block btn-sm" style="background:var(--accent-gold); border-color:var(--accent-gold); color:var(--white); font-weight:600; display:inline-flex; align-items:center; justify-content:center; gap:6px; padding:10px;"><i class="fa-solid fa-user-shield"></i> Go to Admin Panel</a>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if (!empty($updateError)): ?>
                    <div class="badge badge-danger w-full p-2 mb-3" style="font-size:0.75rem; border-radius:var(--border-radius-sm); justify-content:center;">
                        <?php echo $updateError; ?>
                    </div>
                <?php endif; ?>
                <?php if (!empty($updateSuccess)): ?>
                    <div class="badge badge-success w-full p-2 mb-3" style="font-size:0.75rem; border-radius:var(--border-radius-sm); justify-content:center;">
                        <?php echo $updateSuccess; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="">
                    <?php csrfField(); ?>
                    <input type="hidden" name="action" value="update_profile">

                    <!-- Username (Readonly) -->
                    <div class="form-group">
                        <label class="form-label">Username:</label>
                        <input type="text" class="form-control" value="<?php echo sanitize($currentUser['username']); ?>" style="background-color:var(--cream-dark);" disabled>
                    </div>

                    <!-- Email Address -->
                    <div class="form-group">
                        <label for="profile-email" class="form-label">Email Address:</label>
                        <input type="email" id="profile-email" name="email" class="form-control" value="<?php echo sanitize($currentUser['email']); ?>" required>
                    </div>

                    <button type="submit" class="btn btn-secondary btn-block btn-sm mt-2">Save Profile Details</button>
                </form>
            </div>

            <!-- Right Side: Order history lists -->
            <div style="background:#FFF; padding:30px; border-radius:var(--border-radius-md); border:1px solid rgba(44,26,17,0.05); box-shadow:var(--shadow-sm); min-height:400px;">
                <h3 class="color-coffee mb-4" style="border-bottom:2px solid rgba(44,26,17,0.05); padding-bottom:8px;">Past Orders History</h3>
                
                <?php if (!empty($orders)): ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Placed Date</th>
                                    <th>Items Ordered</th>
                                    <th>Method</th>
                                    <th>Total Paid</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $order): 
                                    // Set status badge markup
                                    $statusBadge = '';
                                    if ($order['status'] === 'pending') {
                                        $statusBadge = '<span class="badge badge-warning">Pending</span>';
                                    } elseif ($order['status'] === 'processing') {
                                        $statusBadge = '<span class="badge badge-primary">Processing</span>';
                                    } elseif ($order['status'] === 'completed') {
                                        $statusBadge = '<span class="badge badge-success">Completed</span>';
                                    } else {
                                        $statusBadge = '<span class="badge badge-danger">Cancelled</span>';
                                    }
                                    
                                    // Fetch ordered items lists for dropdown expand
                                    $items = getOrderItemsList($order['id']);
                                ?>
                                    <!-- Header Row -->
                                    <tr style="cursor:pointer;" onclick="let d = document.getElementById('order-detail-<?php echo $order['id']; ?>'); d.style.display = (d.style.display==='none') ? 'table-row' : 'none';">
                                        <td class="font-semibold" style="color:var(--accent-gold);">#<?php echo $order['id']; ?></td>
                                        <td><?php echo date('M d, Y &bull; h:i A', strtotime($order['created_at'])); ?></td>
                                        <td><?php echo $order['item_count']; ?> brews / items</td>
                                        <td class="text-xs"><?php echo sanitize($order['payment_method']); ?></td>
                                        <td class="font-semibold" style="color:var(--coffee-dark);"><?php echo formatPrice($order['total_amount']); ?></td>
                                        <td><?php echo $statusBadge; ?></td>
                                    </tr>
                                    <!-- Detailed breakdown drawer row -->
                                    <tr id="order-detail-<?php echo $order['id']; ?>" style="display:none; background-color:var(--cream-light);">
                                        <td colspan="6" style="padding:20px; border-bottom: 1px solid rgba(44,26,17,0.08);">
                                            <div style="max-width:600px;">
                                                <h4 class="color-coffee mb-2" style="font-size:1.05rem;">Order Items Summary</h4>
                                                <div style="display:flex; flex-direction:column; gap:8px; background:#FFF; padding:15px; border-radius:var(--border-radius-sm); border:1px solid rgba(44,26,17,0.06);">
                                                    <?php foreach ($items as $item): ?>
                                                        <div class="flex justify-between" style="font-size:0.875rem;">
                                                            <div>
                                                                <strong style="color:var(--coffee-dark);"><?php echo sanitize($item['name']); ?></strong>
                                                                <span class="color-muted text-xs">&bull; <?php echo sanitize($item['sugar_level']); ?></span>
                                                            </div>
                                                            <span><?php echo $item['quantity']; ?> &times; <?php echo formatPrice($item['price']); ?></span>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                                <div class="mt-3 text-xs color-muted">
                                                    <strong>Shipping Address:</strong><br>
                                                    <?php echo sanitize($order['shipping_address']); ?><br>
                                                    <strong>Contact Mobile:</strong> <?php echo sanitize($order['contact_number']); ?>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5" style="border:1px dashed rgba(44,26,17,0.15); border-radius:var(--border-radius-md);">
                        <i class="fa-solid fa-receipt color-muted mb-2" style="font-size:3rem; opacity:0.3;"></i>
                        <h4 class="color-coffee">No Orders Found</h4>
                        <p class="color-muted text-sm mt-1">You haven't ordered any artisanal blends yet.</p>
                        <a href="<?php echo BASE_URL; ?>/pages/menu.php" class="btn btn-primary btn-sm mt-3">Order Now</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <!-- Reusable Footer -->
    <?php include __DIR__ . '/../components/footer.php'; ?>

</body>
</html>
