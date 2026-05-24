<?php
/**
 * Cafe Espresso - Storefront Checkout Portal
 */
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/auth.php';

// Force user login for checkouts
requireLogin();

$currentUser = getCurrentUser();
$userId = $_SESSION['user_id'];

// 1. Fetch current cart items to checkout
try {
    $stmt = $pdo->prepare("
        SELECT ci.id, ci.quantity, ci.sugar_level, p.id as product_id, p.name, p.price, inv.stock_quantity
        FROM cart_items ci
        JOIN products p ON ci.product_id = p.id
        JOIN inventory inv ON p.id = inv.product_id
        WHERE ci.user_id = ?
    ");
    $stmt->execute([$userId]);
    $cartItems = $stmt->fetchAll();
    
    if (empty($cartItems)) {
        setFlashMessage('error', 'Your shopping bag is empty. Please add items to checkout.');
        header("Location: " . BASE_URL . "/pages/menu.php");
        exit;
    }
} catch (Exception $e) {
    die("Error loading checkout details: " . $e->getMessage());
}

// 2. Calculate totals
$subtotal = 0.0;
foreach ($cartItems as $item) {
    $subtotal += (float)$item['price'] * (int)$item['quantity'];
}
$tax = $subtotal * TAX_RATE;
$shipping = SHIPPING_FEE;
$grandTotal = $subtotal + $tax + $shipping;

// 3. Handle Order Placement
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyPostCsrf();
    
    $address = sanitizeInput($_POST['address'] ?? '');
    $contact = sanitizeInput($_POST['contact'] ?? '');
    $payment = sanitizeInput($_POST['payment_method'] ?? 'Cash on Delivery');
    
    if (empty($address) || empty($contact)) {
        setFlashMessage('error', 'Please fill out all required shipping and contact details.');
    } else {
        // Start PDO Transaction for atomic safety!
        $pdo->beginTransaction();
        $transactionSuccess = true;
        $stockError = '';
        
        try {
            // Check inventory stocks before committing order!
            foreach ($cartItems as $item) {
                if ($item['stock_quantity'] < $item['quantity']) {
                    $transactionSuccess = false;
                    $stockError = "Sorry, {$item['name']} is currently low on stock (Only {$item['stock_quantity']} remaining). Please update your cart.";
                    break;
                }
            }
            
            if ($transactionSuccess) {
                // Step A: Insert Order record
                $orderStmt = $pdo->prepare("
                    INSERT INTO orders (user_id, total_amount, status, payment_status, payment_method, shipping_address, contact_number) 
                    VALUES (?, ?, 'pending', 'pending', ?, ?, ?)
                ");
                $orderStmt->execute([$userId, $grandTotal, $payment, $address, $contact]);
                $orderId = $pdo->lastInsertId();
                
                // Step B: Insert Items & Decrement Inventory
                $itemStmt = $pdo->prepare("
                    INSERT INTO order_items (order_id, product_id, quantity, price, sugar_level) 
                    VALUES (?, ?, ?, ?, ?)
                ");
                
                $invStmt = $pdo->prepare("
                    UPDATE inventory 
                    SET stock_quantity = stock_quantity - ? 
                    WHERE product_id = ?
                ");
                
                foreach ($cartItems as $item) {
                    // Insert order item
                    $itemStmt->execute([
                        $orderId, 
                        $item['product_id'], 
                        $item['quantity'], 
                        $item['price'], 
                        $item['sugar_level']
                    ]);
                    
                    // Deduct stock levels
                    $invStmt->execute([$item['quantity'], $item['product_id']]);
                }
                
                // Step C: Wipe clean user's DB cart items
                $clearCart = $pdo->prepare("DELETE FROM cart_items WHERE user_id = ?");
                $clearCart->execute([$userId]);
                
                // Commit the Transaction!
                $pdo->commit();
                
                logAnalyticsEvent('purchase', json_encode(['order_id' => $orderId, 'total' => $grandTotal]));
                
                setFlashMessage('success', "Order #{$orderId} placed successfully! We are roasting your brew.");
                header("Location: " . BASE_URL . "/pages/profile.php");
                exit;
            } else {
                $pdo->rollBack();
                setFlashMessage('error', $stockError);
            }
        } catch (Exception $e) {
            $pdo->rollBack();
            setFlashMessage('error', 'Checkout failed due to a roastery system error: ' . $e->getMessage());
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cafe Espresso - Checkout Portal</title>
    
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
        <!-- Page headings -->
        <div class="mb-4">
            <a href="<?php echo BASE_URL; ?>/pages/cart.php" class="btn btn-text p-0 mb-2" style="display:inline-flex; align-items:center; gap:6px;">
                <i class="fa-solid fa-arrow-left"></i> Back to Shopping Bag
            </a>
            <span class="section-pretitle">Order Portal</span>
            <h1 class="color-coffee font-semibold">Checkout Portal</h1>
        </div>

        <div class="cart-split-layout">
            <!-- Left: Shipping & Billing Form -->
            <div style="background:#FFF; padding:40px; border-radius:var(--border-radius-md); border:1px solid rgba(44,26,17,0.05); box-shadow:var(--shadow-sm); height:fit-content;">
                <h3 class="color-coffee mb-3" style="border-bottom:2px solid rgba(44,26,17,0.05); padding-bottom:8px;">Shipping Credentials</h3>
                
                <form method="POST" action="">
                    <?php csrfField(); ?>

                    <!-- Contact Name (Prefilled/Readonly for context) -->
                    <div class="form-group">
                        <label class="form-label">Customer Username:</label>
                        <input type="text" class="form-control" value="<?php echo sanitize($currentUser['username']); ?>" style="background-color:var(--cream-dark);" disabled>
                    </div>

                    <!-- Contact Number -->
                    <div class="form-group">
                        <label for="checkout-contact" class="form-label">Contact Mobile Number: <span style="color:var(--danger);">*</span></label>
                        <input type="tel" id="checkout-contact" name="contact" class="form-control" placeholder="e.g. 09171234567" required>
                    </div>

                    <!-- Shipping Address -->
                    <div class="form-group">
                        <label for="checkout-address" class="form-label">Full Shipping Address: <span style="color:var(--danger);">*</span></label>
                        <textarea id="checkout-address" name="address" rows="4" class="form-control" placeholder="Apt/Unit#, Building Name, Street Name, Barangay, City, Province" required></textarea>
                    </div>

                    <!-- Payment Method selector -->
                    <div class="form-group">
                        <label for="checkout-payment" class="form-label">Billing Payment Method: <span style="color:var(--danger);">*</span></label>
                        <select id="checkout-payment" name="payment_method" class="form-control" style="background:#FFF; appearance:auto;" required>
                            <option value="Cash on Delivery">Cash on Delivery (COD)</option>
                            <option value="Bank Transfer">Bank Transfer (Gcash/Paymaya)</option>
                            <option value="Credit Card">Credit/Debit Card (Mock Gate)</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block mt-3 shadow-glow">Confirm & Place Order</button>
                </form>
            </div>

            <!-- Right: Order Review Summaries -->
            <aside class="cart-summary-card">
                <h3 class="summary-heading">Review Your Order</h3>
                
                <!-- Small items list -->
                <div style="display:flex; flex-direction:column; gap:16px; margin-bottom:20px; max-height:220px; overflow-y:auto; border-bottom:1px solid rgba(44,26,17,0.08); padding-bottom:16px;">
                    <?php foreach ($cartItems as $item): ?>
                        <div class="flex justify-between align-center" style="font-size:0.9rem;">
                            <div style="max-width:180px;">
                                <strong style="color:var(--coffee-dark); display:block;"><?php echo sanitize($item['name']); ?></strong>
                                <span class="color-muted text-xs">Qty: <?php echo $item['quantity']; ?> &bull; <?php echo sanitize($item['sugar_level']); ?></span>
                            </div>
                            <span class="font-semibold" style="color:var(--coffee-dark);"><?php echo formatPrice((float)$item['price'] * (int)$item['quantity']); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="summary-row">
                    <span>Items Subtotal:</span>
                    <span><?php echo formatPrice($subtotal); ?></span>
                </div>
                <div class="summary-row">
                    <span>Estimated VAT (12%):</span>
                    <span><?php echo formatPrice($tax); ?></span>
                </div>
                <div class="summary-row">
                    <span>Shipping & Handling:</span>
                    <span><?php echo formatPrice($shipping); ?></span>
                </div>

                <div class="summary-row total">
                    <span>Amount Due:</span>
                    <span><?php echo formatPrice($grandTotal); ?></span>
                </div>

                <p class="color-muted text-xs font-light" style="line-height:1.4; text-align:center;">
                    By clicking Confirm Order, you agree to our roastery shipping guidelines. Fast, local logistics guaranteed.
                </p>
            </aside>
        </div>
    </main>

    <!-- Reusable Footer -->
    <?php include __DIR__ . '/../components/footer.php'; ?>

</body>
</html>
