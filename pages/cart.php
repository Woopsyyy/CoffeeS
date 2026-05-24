<?php
/**
 * Cafe Espresso - Storefront Shopping Cart Page
 */
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/auth.php';

// Ensure session started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$currentUser = getCurrentUser();

// Handle Actions (Add, Update, Remove)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    verifyPostCsrf();
    
    $action = $_POST['action'];
    
    // ACTION: ADD ITEM
    if ($action === 'add') {
        $productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
        $qty = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
        $sugar = isset($_POST['sugar_level']) ? sanitizeInput($_POST['sugar_level']) : '100% (Normal)';
        
        if ($productId > 0 && $qty > 0) {
            // Verify product exists and get price
            $stmt = $pdo->prepare("SELECT price, name FROM products WHERE id = ?");
            $stmt->execute([$productId]);
            $product = $stmt->fetch();
            
            if ($product) {
                if (isLoggedIn()) {
                    // Logged in: DB Cart Items
                    $userId = $_SESSION['user_id'];
                    $check = $pdo->prepare("SELECT id, quantity FROM cart_items WHERE user_id = ? AND product_id = ? AND sugar_level = ?");
                    $check->execute([$userId, $productId, $sugar]);
                    $existing = $check->fetch();
                    
                    if ($existing) {
                        $newQty = $existing['quantity'] + $qty;
                        $update = $pdo->prepare("UPDATE cart_items SET quantity = ? WHERE id = ?");
                        $update->execute([$newQty, $existing['id']]);
                    } else {
                        $insert = $pdo->prepare("INSERT INTO cart_items (user_id, product_id, quantity, sugar_level) VALUES (?, ?, ?, ?)");
                        $insert->execute([$userId, $productId, $qty, $sugar]);
                    }
                } else {
                    // Guest: Session Cart Items
                    if (!isset($_SESSION['cart'])) {
                        $_SESSION['cart'] = [];
                    }
                    
                    // Unique key so same product with different sugars are separate rows
                    $cartKey = $productId . '_' . md5($sugar);
                    
                    if (isset($_SESSION['cart'][$cartKey])) {
                        $_SESSION['cart'][$cartKey]['quantity'] += $qty;
                    } else {
                        $_SESSION['cart'][$cartKey] = [
                            'product_id' => $productId,
                            'quantity' => $qty,
                            'sugar_level' => $sugar
                        ];
                    }
                }
                
                logAnalyticsEvent('cart_add', json_encode(['product_id' => $productId, 'quantity' => $qty]));
                setFlashMessage('success', "{$product['name']} added to your bag successfully!");
            }
        }
        
        header("Location: " . BASE_URL . "/pages/cart.php");
        exit;
    }
    
    // ACTION: UPDATE QUANTITY
    if ($action === 'update') {
        $itemId = isset($_POST['item_id']) ? sanitizeInput($_POST['item_id']) : '';
        $qty = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
        
        if (!empty($itemId) && $qty > 0) {
            if (isLoggedIn()) {
                // Logged In: Update database cart
                $userId = $_SESSION['user_id'];
                $update = $pdo->prepare("UPDATE cart_items SET quantity = ? WHERE id = ? AND user_id = ?");
                $update->execute([$qty, (int)$itemId, $userId]);
            } else {
                // Guest: Update session cart
                if (isset($_SESSION['cart'][$itemId])) {
                    $_SESSION['cart'][$itemId]['quantity'] = $qty;
                }
            }
            setFlashMessage('success', 'Quantity updated successfully.');
        }
        
        header("Location: " . BASE_URL . "/pages/cart.php");
        exit;
    }
    
    // ACTION: DELETE ITEM
    if ($action === 'delete') {
        $itemId = isset($_POST['item_id']) ? $_POST['item_id'] : '';
        
        if (!empty($itemId)) {
            if (isLoggedIn()) {
                // Logged In: Delete database cart item
                $userId = $_SESSION['user_id'];
                $delete = $pdo->prepare("DELETE FROM cart_items WHERE id = ? AND user_id = ?");
                $delete->execute([(int)$itemId, $userId]);
            } else {
                // Guest: Delete session item
                if (isset($_SESSION['cart'][$itemId])) {
                    unset($_SESSION['cart'][$itemId]);
                }
            }
            setFlashMessage('success', 'Item removed from your bag.');
        }
        
        header("Location: " . BASE_URL . "/pages/cart.php");
        exit;
    }
}

// 2. Fetch all cart items to display
$cartItems = [];
$subtotal = 0.0;

try {
    if (isLoggedIn()) {
        // Fetch from Database joining Products
        $stmt = $pdo->prepare("
            SELECT ci.id as cart_item_id, ci.quantity, ci.sugar_level, p.id as product_id, p.name, p.price, p.image, c.name as category_name 
            FROM cart_items ci
            JOIN products p ON ci.product_id = p.id
            JOIN categories c ON p.category_id = c.id
            WHERE ci.user_id = ?
            ORDER BY ci.created_at ASC
        ");
        $stmt->execute([$_SESSION['user_id']]);
        $rows = $stmt->fetchAll();
        
        foreach ($rows as $row) {
            $rowPrice = (float)$row['price'];
            $rowQty = (int)$row['quantity'];
            $itemTotal = $rowPrice * $rowQty;
            $subtotal += $itemTotal;
            
            $cartItems[] = [
                'item_id' => $row['cart_item_id'],
                'product_id' => $row['product_id'],
                'name' => $row['name'],
                'price' => $rowPrice,
                'image' => $row['image'],
                'category' => $row['category_name'],
                'quantity' => $rowQty,
                'sugar_level' => $row['sugar_level'],
                'total' => $itemTotal
            ];
        }
    } else {
        // Fetch from Session
        if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $key => $sessionItem) {
                $productId = (int)$sessionItem['product_id'];
                $qty = (int)$sessionItem['quantity'];
                $sugar = $sessionItem['sugar_level'];
                
                // Get product information
                $pStmt = $pdo->prepare("
                    SELECT p.id, p.name, p.price, p.image, c.name as category_name 
                    FROM products p 
                    JOIN categories c ON p.category_id = c.id 
                    WHERE p.id = ?
                ");
                $pStmt->execute([$productId]);
                $product = $pStmt->fetch();
                
                if ($product) {
                    $rowPrice = (float)$product['price'];
                    $itemTotal = $rowPrice * $qty;
                    $subtotal += $itemTotal;
                    
                    $cartItems[] = [
                        'item_id' => $key, // session key
                        'product_id' => $product['id'],
                        'name' => $product['name'],
                        'price' => $rowPrice,
                        'image' => $product['image'],
                        'category' => $product['category_name'],
                        'quantity' => $qty,
                        'sugar_level' => $sugar,
                        'total' => $itemTotal
                    ];
                }
            }
        }
    }
} catch (Exception $e) {
    die("Cart loading error: " . $e->getMessage());
}

// Calculate tax, shipping, total
$tax = $subtotal * TAX_RATE;
$shipping = ($subtotal > 0) ? SHIPPING_FEE : 0.0;
$grandTotal = $subtotal + $tax + $shipping;

// Setup image fallbacks
$imageMap = [
    'Espresso' => 'https://images.unsplash.com/photo-1510705253260-8046a11d729d?q=80&w=150',
    'Americano' => 'https://images.unsplash.com/photo-1551046713-2d20d7be7309?q=80&w=150',
    'Double Espresso' => 'https://images.unsplash.com/photo-1610889556528-9a770e32642f?q=80&w=150',
    'Macchiato' => 'https://images.unsplash.com/photo-1485808191679-5f86510681a2?q=80&w=150',
    'Cappuccino' => 'https://images.unsplash.com/photo-1534778101976-62847782c213?q=80&w=150',
    'Cafe Latte' => 'https://images.unsplash.com/photo-1541167760496-1628856ab772?q=80&w=150',
    'Caffe Mocha' => 'https://images.unsplash.com/photo-1607687325211-ac62326303af?q=80&w=150',
    'Affogato' => 'https://images.unsplash.com/photo-1592318780016-5bc77b94dbba?q=80&w=150',
    'Cold Brew' => 'https://images.unsplash.com/photo-1511920170033-f8396924c348?q=80&w=150',
    'Frappe' => 'https://images.unsplash.com/photo-1572490122747-3968b75cc699?q=80&w=150',
    'Butter Croissant' => 'https://images.unsplash.com/photo-1555507036-ab1f4038808a?q=80&w=150',
    'Chocolate Fudge Cake' => 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?q=80&w=150'
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cafe Espresso - Shopping Bag</title>
    
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
            <span class="section-pretitle">Your Bag</span>
            <h1 class="color-coffee font-semibold">Shopping Bag</h1>
        </div>

        <?php if (!empty($cartItems)): ?>
            <div class="cart-split-layout">
                <!-- Left: Interactive Items List -->
                <div class="cart-items-panel">
                    <?php foreach ($cartItems as $item): 
                        // Find matching image fallback
                        $thumbUrl = $imageMap[$item['name']] ?? '';
                        if (empty($thumbUrl)) {
                            if (!empty($item['image'])) {
                                $thumbUrl = BASE_URL . '/assets/uploads/' . $item['image'];
                            } else {
                                $thumbUrl = 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?q=80&w=150';
                            }
                        }
                    ?>
                        <div class="cart-item-card">
                            <!-- Thumbnail -->
                            <img src="<?php echo $thumbUrl; ?>" alt="<?php echo sanitize($item['name']); ?>" class="cart-item-thumb">
                            
                            <!-- Metas info -->
                            <div class="cart-item-info">
                                <span class="badge badge-primary" style="font-size:0.6rem; padding: 2px 6px;"><?php echo sanitize($item['category']); ?></span>
                                <h3 class="cart-item-title" style="margin-top:2px;"><?php echo sanitize($item['name']); ?></h3>
                                <div class="cart-item-meta">
                                    <span>Sugar Level: <strong><?php echo sanitize($item['sugar_level']); ?></strong></span>
                                </div>
                            </div>

                            <!-- Interactive Quantity Counter -->
                            <div class="cart-qty-counter" data-item-id="<?php echo $item['item_id']; ?>">
                                <button type="button" class="cart-qty-btn qty-minus"><i class="fa-solid fa-minus"></i></button>
                                <input type="number" class="cart-qty-num" value="<?php echo $item['quantity']; ?>" min="1" max="50">
                                <button type="button" class="cart-qty-btn qty-plus"><i class="fa-solid fa-plus"></i></button>
                            </div>

                            <!-- Row Price sum -->
                            <span class="cart-item-price"><?php echo formatPrice($item['total']); ?></span>

                            <!-- Trash Action Button -->
                            <form action="" method="POST" onsubmit="return confirm('Remove <?php echo sanitize($item['name']); ?> from your bag?');">
                                <?php csrfField(); ?>
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="item_id" value="<?php echo $item['item_id']; ?>">
                                <button type="submit" class="cart-item-remove" aria-label="Delete item"><i class="fa-solid fa-trash-can"></i></button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Right: Pricing Summaries Box -->
                <aside class="cart-summary-card">
                    <h3 class="summary-heading">Order Summary</h3>
                    
                    <div class="summary-row">
                        <span>Items Subtotal:</span>
                        <span><?php echo formatPrice($subtotal); ?></span>
                    </div>
                    <div class="summary-row">
                        <span>Estimated VAT (12%):</span>
                        <span><?php echo formatPrice($tax); ?></span>
                    </div>
                    <div class="summary-row">
                        <span>Delivery Fee:</span>
                        <span><?php echo formatPrice($shipping); ?></span>
                    </div>

                    <div class="summary-row total">
                        <span>Total Due:</span>
                        <span><?php echo formatPrice($grandTotal); ?></span>
                    </div>

                    <!-- Checkout triggers -->
                    <a href="<?php echo BASE_URL; ?>/pages/checkout.php" class="btn btn-primary btn-block">Proceed to Checkout</a>
                    <a href="<?php echo BASE_URL; ?>/pages/menu.php" class="btn btn-outline btn-block btn-sm mt-2">Continue Shopping</a>
                </aside>
            </div>
        <?php else: ?>
            <!-- Clean Empty Cart view -->
            <div class="text-center py-5" style="background:#FFF; border-radius:var(--border-radius-lg); border:1px solid rgba(44,26,17,0.05); box-shadow:var(--shadow-sm);">
                <i class="fa-solid fa-bag-shopping color-muted mb-2" style="font-size:4rem; opacity:0.3;"></i>
                <h2 class="color-coffee">Your shopping bag is empty</h2>
                <p class="color-muted font-light mt-1" style="max-width:380px; margin-left:auto; margin-right:auto;">
                    You haven't added any premium selections to your bag yet. Let's head over to the roastery selection and discover your perfect roast!
                </p>
                <a href="<?php echo BASE_URL; ?>/pages/menu.php" class="btn btn-primary mt-4 shadow-glow">Browse Menu</a>
            </div>
        <?php endif; ?>
    </main>

    <!-- Reusable Footer -->
    <?php include __DIR__ . '/../components/footer.php'; ?>

</body>
</html>
