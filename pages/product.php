<?php
/**
 * Cafe Espresso - Product Detail Page
 */
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/auth.php';

$productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($productId <= 0) {
    header("Location: " . BASE_URL . "/pages/menu.php");
    exit;
}

// 1. Fetch main product details
try {
    $stmt = $pdo->prepare("
        SELECT p.*, c.name as category_name, c.slug as category_slug 
        FROM products p 
        JOIN categories c ON p.category_id = c.id 
        WHERE p.id = ?
    ");
    $stmt->execute([$productId]);
    $product = $stmt->fetch();
    
    if (!$product) {
        setFlashMessage('error', 'The requested product could not be found.');
        header("Location: " . BASE_URL . "/pages/menu.php");
        exit;
    }
} catch (Exception $e) {
    die("Data loading error: " . $e->getMessage());
}

// 2. Fetch reviews
try {
    $revStmt = $pdo->prepare("
        SELECT r.*, u.username 
        FROM reviews r 
        JOIN users u ON r.user_id = u.id 
        WHERE r.product_id = ? 
        ORDER BY r.created_at DESC
    ");
    $revStmt->execute([$productId]);
    $reviews = $revStmt->fetchAll();
    
    // Average rating
    $avgRating = 0;
    if (!empty($reviews)) {
        $ratingsSum = array_sum(array_column($reviews, 'rating'));
        $avgRating = round($ratingsSum / count($reviews), 1);
    }
} catch (Exception $e) {
    $reviews = [];
    $avgRating = 0;
}

// 3. Fetch related products (same category)
try {
    $relStmt = $pdo->prepare("
        SELECT p.*, c.name as category_name 
        FROM products p 
        JOIN categories c ON p.category_id = c.id 
        WHERE p.category_id = ? AND p.id != ? 
        LIMIT 3
    ");
    $relStmt->execute([$product['category_id'], $productId]);
    $related = $relStmt->fetchAll();
} catch (Exception $e) {
    $related = [];
}

// 4. Handle Review Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_review') {
    verifyPostCsrf();
    requireLogin();
    
    $user = getCurrentUser();
    $rating = isset($_POST['rating']) ? (int)$_POST['rating'] : 5;
    $comment = sanitizeInput($_POST['comment'] ?? '');
    
    if ($rating < 1 || $rating > 5) {
        setFlashMessage('error', 'Please provide a valid star rating between 1 and 5.');
    } else {
        try {
            $insert = $pdo->prepare("INSERT INTO reviews (user_id, product_id, rating, comment) VALUES (?, ?, ?, ?)");
            $insert->execute([$user['id'], $productId, $rating, $comment]);
            
            logAnalyticsEvent('review_add', json_encode(['user_id' => $user['id'], 'product_id' => $productId, 'rating' => $rating]));
            setFlashMessage('success', 'Thank you! Your review has been published.');
        } catch (Exception $e) {
            setFlashMessage('error', 'Unable to submit your review at this moment.');
        }
    }
    
    header("Location: " . BASE_URL . "/pages/product.php?id=" . $productId);
    exit;
}

// Image Mapping
$imageMap = [
    'Espresso' => 'https://images.unsplash.com/photo-1510705253260-8046a11d729d?q=80&w=800',
    'Americano' => 'https://images.unsplash.com/photo-1551046713-2d20d7be7309?q=80&w=800',
    'Double Espresso' => 'https://images.unsplash.com/photo-1610889556528-9a770e32642f?q=80&w=800',
    'Macchiato' => 'https://images.unsplash.com/photo-1485808191679-5f86510681a2?q=80&w=800',
    'Cappuccino' => 'https://images.unsplash.com/photo-1534778101976-62847782c213?q=80&w=800',
    'Cafe Latte' => 'https://images.unsplash.com/photo-1541167760496-1628856ab772?q=80&w=800',
    'Caffe Mocha' => 'https://images.unsplash.com/photo-1607687325211-ac62326303af?q=80&w=800',
    'Affogato' => 'https://images.unsplash.com/photo-1592318780016-5bc77b94dbba?q=80&w=800',
    'Cold Brew' => 'https://images.unsplash.com/photo-1511920170033-f8396924c348?q=80&w=800',
    'Frappe' => 'https://images.unsplash.com/photo-1572490122747-3968b75cc699?q=80&w=800',
    'Butter Croissant' => 'https://images.unsplash.com/photo-1555507036-ab1f4038808a?q=80&w=800',
    'Chocolate Fudge Cake' => 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?q=80&w=800'
];
$imageUrl = $imageMap[$product['name']] ?? '';
if (empty($imageUrl)) {
    if (!empty($product['image'])) {
        $imageUrl = BASE_URL . '/assets/uploads/' . $product['image'];
    } else {
        $imageUrl = 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?q=80&w=800';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cafe Espresso - Buy <?php echo sanitize($product['name']); ?></title>
    
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
        <!-- Back navigation link -->
        <a href="<?php echo BASE_URL; ?>/pages/menu.php" class="btn btn-text p-0 mb-3" style="display:inline-flex; align-items:center; gap:6px;">
            <i class="fa-solid fa-arrow-left"></i> Back to Selection Menu
        </a>

        <!-- Product Presentation Split Layout -->
        <div class="cart-split-layout" style="grid-template-columns: 1.1fr 0.9fr; gap: 50px;">
            <!-- Left Side visual imagery card -->
            <div style="background-color: var(--white); border-radius: var(--border-radius-lg); padding: 20px; box-shadow: var(--shadow-sm); border:1px solid rgba(44,26,17,0.05);">
                <div class="product-card-img-wrapper" style="aspect-ratio:1.2; border-radius:var(--border-radius-md); margin-bottom:0;">
                    <img src="<?php echo $imageUrl; ?>" alt="<?php echo sanitize($product['name']); ?>" style="width:100%; height:100%; object-fit:cover;">
                </div>
            </div>

            <!-- Right Side Purchase Configurations -->
            <div class="cart-summary-card" style="padding: 30px;">
                <span class="product-card-category"><?php echo sanitize($product['category_name']); ?></span>
                <h1 style="font-size:2.4rem; color:var(--coffee-dark); margin-top:4px; margin-bottom:12px;"><?php echo sanitize($product['name']); ?></h1>
                
                <!-- Ratings Summary -->
                <div class="flex align-center gap-xs mb-3">
                    <div style="color:var(--accent-gold); font-size:0.9rem;">
                        <?php 
                        $fullStars = floor($avgRating);
                        for($i=1; $i<=5; $i++) {
                            if ($i <= $fullStars) {
                                echo '<i class="fa-solid fa-star"></i>';
                            } else {
                                echo '<i class="fa-regular fa-star"></i>';
                            }
                        }
                        ?>
                    </div>
                    <span class="color-muted text-sm font-medium">(<?php echo count($reviews); ?> customer reviews)</span>
                </div>

                <div class="summary-row total" style="border-top:none; padding-top:0; margin-top:0;">
                    <span>Unit Price:</span>
                    <span><?php echo formatPrice($product['price']); ?></span>
                </div>

                <p class="color-muted font-light text-base mb-4" style="line-height:1.7;">
                    <?php echo sanitize($product['description']); ?>
                </p>

                <!-- Customized ordering form -->
                <form action="<?php echo BASE_URL; ?>/pages/cart.php" method="POST">
                    <?php csrfField(); ?>
                    <input type="hidden" name="action" value="add">
                    <input type="hidden" name="product_id" value="<?php echo $productId; ?>">

                    <!-- Sugar controls -->
                    <div class="mb-4">
                        <span class="form-label">Select Sugar Level:</span>
                        <div class="sugar-picker">
                            <div class="sugar-option">
                                <input type="radio" id="sugar-0" name="sugar_level" value="0% (None)">
                                <label for="sugar-0" class="sugar-option-label">
                                    <strong>0%</strong><span>None</span>
                                </label>
                            </div>
                            <div class="sugar-option">
                                <input type="radio" id="sugar-25" name="sugar_level" value="25% (Low)">
                                <label for="sugar-25" class="sugar-option-label">
                                    <strong>25%</strong><span>Low</span>
                                </label>
                            </div>
                            <div class="sugar-option">
                                <input type="radio" id="sugar-50" name="sugar_level" value="50% (Less)">
                                <label for="sugar-50" class="sugar-option-label">
                                    <strong>50%</strong><span>Less</span>
                                </label>
                            </div>
                            <div class="sugar-option">
                                <input type="radio" id="sugar-100" name="sugar_level" value="100% (Normal)" checked>
                                <label for="sugar-100" class="sugar-option-label">
                                    <strong>100%</strong><span>Normal</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Quantity selections -->
                    <div class="form-group mb-4">
                        <label for="detail-quantity" class="form-label">Select Quantity:</label>
                        <div class="cart-qty-counter" style="background:#FFF;">
                            <button type="button" class="cart-qty-btn" onclick="let input=document.getElementById('detail-quantity'); let val=parseInt(input.value)||1; if(val>1) input.value=val-1;"><i class="fa-solid fa-minus"></i></button>
                            <input type="number" id="detail-quantity" name="quantity" class="cart-qty-num" value="1" min="1" max="50">
                            <button type="button" class="cart-qty-btn" onclick="let input=document.getElementById('detail-quantity'); let val=parseInt(input.value)||1; input.value=val+1;"><i class="fa-solid fa-plus"></i></button>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">Add to Bag</button>
                </form>
            </div>
        </div>

        <!-- Section: Product Reviews -->
        <section class="mt-5" style="border-top:1px solid rgba(44,26,17,0.08); padding-top:var(--spacing-lg);">
            <div class="grid grid-cols-2 gap-lg" style="grid-template-columns: 1fr 1fr;">
                <!-- Left: Published reviews list -->
                <div>
                    <h3 class="color-coffee mb-3">Customer Feedback</h3>
                    
                    <?php if (!empty($reviews)): ?>
                        <div style="display:flex; flex-direction:column; gap:20px;">
                            <?php foreach ($reviews as $rev): ?>
                                <div style="background:#FFF; padding:20px; border-radius:var(--border-radius-md); border:1px solid rgba(44,26,17,0.04); box-shadow:var(--shadow-sm);">
                                    <div class="flex justify-between align-center mb-1">
                                        <strong style="color:var(--coffee-dark);"><?php echo sanitize($rev['username']); ?></strong>
                                        <span class="color-muted text-xs"><?php echo date('M d, Y', strtotime($rev['created_at'])); ?></span>
                                    </div>
                                    <div style="color:var(--accent-gold); font-size:0.75rem; margin-bottom:8px;">
                                        <?php 
                                        for($i=1; $i<=5; $i++) {
                                            echo $i <= (int)$rev['rating'] ? '<i class="fa-solid fa-star"></i>' : '<i class="fa-regular fa-star"></i>';
                                        }
                                        ?>
                                    </div>
                                    <p class="color-muted font-light text-sm" style="line-height:1.5; font-style:italic;">
                                        "<?php echo sanitize($rev['comment']); ?>"
                                    </p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="color-muted font-light" style="font-style:italic;">No reviews have been published for this item. Be the first to share your thoughts!</p>
                    <?php endif; ?>
                </div>

                <!-- Right: Submit feedback form (Require login) -->
                <div style="background:#FFF; padding:30px; border-radius:var(--border-radius-md); border:1px solid rgba(44,26,17,0.05); box-shadow:var(--shadow-sm); height:fit-content;">
                    <h3 class="color-coffee mb-3">Publish Your Review</h3>
                    
                    <?php if (isLoggedIn()): ?>
                        <form method="POST" action="">
                            <?php csrfField(); ?>
                            <input type="hidden" name="action" value="add_review">

                            <!-- Star selector -->
                            <div class="form-group">
                                <label for="form-rating" class="form-label">Rating Value:</label>
                                <select id="form-rating" name="rating" class="form-control" style="background:#FFF; appearance:auto;" required>
                                    <option value="5">⭐⭐⭐⭐⭐ (Excellent)</option>
                                    <option value="4">⭐⭐⭐⭐ (Great)</option>
                                    <option value="3">⭐⭐⭐ (Average)</option>
                                    <option value="2">⭐⭐ (Fair)</option>
                                    <option value="1">⭐ (Poor)</option>
                                </select>
                            </div>

                            <!-- Comment message -->
                            <div class="form-group">
                                <label for="form-comment" class="form-label">Review Comment:</label>
                                <textarea id="form-comment" name="comment" rows="4" class="form-control" placeholder="Share your extraction, milk texture, or service notes..." required></textarea>
                            </div>

                            <button type="submit" class="btn btn-secondary btn-block btn-sm">Publish Feedback</button>
                        </form>
                    <?php else: ?>
                        <div class="text-center py-3">
                            <i class="fa-solid fa-lock color-muted mb-2" style="font-size:2.5rem; opacity:0.3;"></i>
                            <h4 class="color-coffee">Sign In Required</h4>
                            <p class="color-muted text-sm mt-1">Please log in to submit your coffee shop experience.</p>
                            <a href="<?php echo BASE_URL; ?>/pages/login.php" class="btn btn-outline btn-sm mt-3">Sign In</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <!-- Section: Related coffee products -->
        <?php if (!empty($related)): ?>
            <section class="mt-5" style="border-top:1px solid rgba(44,26,17,0.08); padding-top:var(--spacing-lg);">
                <div class="section-title-wrapper" style="text-align:center; margin-bottom:30px;">
                    <span class="section-pretitle">Brews from Category</span>
                    <h3 class="color-coffee">You May Also Like</h3>
                </div>

                <div class="grid grid-cols-3 gap-md">
                    <?php 
                    require_once __DIR__ . '/../components/product-card.php';
                    foreach ($related as $relProd) {
                        renderProductCard($relProd);
                    }
                    ?>
                </div>
            </section>
        <?php endif; ?>
    </main>

    <!-- Options modal custom selectors -->
    <?php include __DIR__ . '/../components/modal.php'; ?>

    <!-- Reusable Footer -->
    <?php include __DIR__ . '/../components/footer.php'; ?>

</body>
</html>
