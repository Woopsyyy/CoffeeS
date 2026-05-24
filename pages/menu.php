<?php
/**
 * Cafe Espresso - Storefront Selection Menu Page
 */
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/auth.php';

// Capture filters
$selectedCategory = isset($_GET['category']) ? sanitizeInput($_GET['category']) : 'all';
$searchQuery = isset($_GET['search']) ? sanitizeInput($_GET['search']) : '';

// 1. Fetch categories
try {
    $catStmt = $pdo->query("SELECT c.*, COUNT(p.id) as product_count FROM categories c LEFT JOIN products p ON c.id = p.category_id GROUP BY c.id");
    $categories = $catStmt->fetchAll();
    
    // Calculate total count
    $totalCountStmt = $pdo->query("SELECT COUNT(*) FROM products");
    $totalProductsCount = $totalCountStmt->fetchColumn();
} catch (Exception $e) {
    $categories = [];
    $totalProductsCount = 0;
}

// 2. Fetch products based on filters
try {
    $query = "
        SELECT p.*, c.name as category_name, c.slug as category_slug 
        FROM products p 
        JOIN categories c ON p.category_id = c.id
    ";
    $params = [];
    $where = [];

    if ($selectedCategory !== 'all') {
        $where[] = "c.slug = ?";
        $params[] = $selectedCategory;
    }

    if (!empty($searchQuery)) {
        $where[] = "(p.name LIKE ? OR p.description LIKE ?)";
        $params[] = "%$searchQuery%";
        $params[] = "%$searchQuery%";
    }

    if (!empty($where)) {
        $query .= " WHERE " . implode(" AND ", $where);
    }

    $query .= " ORDER BY p.id ASC";
    
    $prodStmt = $pdo->prepare($query);
    $prodStmt->execute($params);
    $products = $prodStmt->fetchAll();
} catch (Exception $e) {
    $products = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CAFE ESPRESSO - Signature Menu Selection</title>
    
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
        <div class="text-center mb-4">
            <span class="section-pretitle">The Selection</span>
            <h1 class="text-huge font-semibold color-coffee mb-1">Our Craft Menu</h1>
            <p class="color-muted text-base font-light" style="max-width: 600px; margin: 0 auto;">
                Every roast is selected ethically, roasted in micro-batches in-house, and brewed by passionate baristas using state-of-the-art precision gear.
            </p>
        </div>

        <div class="shop-wrapper">
            <!-- Left Category Sidebar Filters -->
            <aside class="shop-sidebar">
                <h4 class="shop-sidebar-title">Categories</h4>
                <div class="shop-filter-list">
                    <!-- All Category link -->
                    <a href="<?php echo BASE_URL; ?>/pages/menu.php" class="shop-filter-link <?php echo ($selectedCategory === 'all') ? 'active' : ''; ?>">
                        <span>All Brews & Bites</span>
                        <span class="shop-filter-count"><?php echo $totalProductsCount; ?></span>
                    </a>
                    
                    <?php foreach ($categories as $cat): ?>
                        <a href="<?php echo BASE_URL; ?>/pages/menu.php?category=<?php echo $cat['slug']; ?>" class="shop-filter-link <?php echo ($selectedCategory === $cat['slug']) ? 'active' : ''; ?>">
                            <span><?php echo sanitize($cat['name']); ?></span>
                            <span class="shop-filter-count"><?php echo (int)$cat['product_count']; ?></span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </aside>

            <!-- Right Catalog List -->
            <section>
                <!-- Shop Header Controls -->
                <div class="shop-grid-header">
                    <span class="color-muted text-sm font-medium">
                        Showing <strong class="color-coffee"><?php echo count($products); ?></strong> signature items
                    </span>

                    <!-- Search Filter Form -->
                    <form class="shop-search-box" method="GET" action="<?php echo BASE_URL; ?>/pages/menu.php">
                        <?php if ($selectedCategory !== 'all'): ?>
                            <input type="hidden" name="category" value="<?php echo $selectedCategory; ?>">
                        <?php endif; ?>
                        <input type="text" name="search" placeholder="Search selection..." class="form-control shop-search-control" value="<?php echo sanitize($searchQuery); ?>" required>
                        <button type="submit" class="shop-search-btn" aria-label="Search Catalog"><i class="fa-solid fa-magnifying-glass"></i></button>
                    </form>
                </div>

                <!-- Product Catalog Cards Grid -->
                <?php if (!empty($products)): ?>
                    <div class="grid grid-cols-3 gap-md">
                        <?php 
                        require_once __DIR__ . '/../components/product-card.php';
                        foreach ($products as $product) {
                            renderProductCard($product);
                        }
                        ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5" style="background:#FFF; border-radius:var(--border-radius-md); border:1px solid rgba(44,26,17,0.05);">
                        <i class="fa-solid fa-mug-hot color-muted mb-2" style="font-size:3rem; opacity:0.4;"></i>
                        <h3 class="color-coffee">No brews match your query</h3>
                        <p class="color-muted text-sm mt-1">Try resetting filters or checking your search query.</p>
                        <a href="<?php echo BASE_URL; ?>/pages/menu.php" class="btn btn-outline btn-sm mt-3">Reset Filters</a>
                    </div>
                <?php endif; ?>
            </section>
        </div>
    </main>

    <!-- Custom Customized Option Modal overlay component -->
    <?php include __DIR__ . '/../components/modal.php'; ?>

    <!-- Reusable Footer -->
    <?php include __DIR__ . '/../components/footer.php'; ?>

</body>
</html>
