<?php
/**
 * Cafe Espresso - Admin SaaS Products CRUD Management
 */
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/auth.php';

// Verification admin permissions
requireAdmin();

$currentUser = getCurrentUser();

// 1. Handle Product ADD or EDIT actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyPostCsrf();
    
    $action = $_POST['action'] ?? '';
    
    if ($action === 'save') {
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        $name = sanitizeInput($_POST['name'] ?? '');
        $desc = sanitizeInput($_POST['description'] ?? '');
        $price = isset($_POST['price']) ? (float)$_POST['price'] : 0.0;
        $catId = isset($_POST['category_id']) ? (int)$_POST['category_id'] : 0;
        $stock = isset($_POST['stock_quantity']) ? (int)$_POST['stock_quantity'] : 0;
        $threshold = isset($_POST['low_stock_threshold']) ? (int)$_POST['low_stock_threshold'] : 10;
        $featured = isset($_POST['is_featured']) ? 1 : 0;
        
        $imageName = $_POST['existing_image'] ?? '';
        
        // Handle image upload if a file was selected
        if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['product_image']['tmp_name'];
            $fileName = $_FILES['product_image']['name'];
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
            
            if (in_array($fileExtension, $allowedExtensions)) {
                // Secure unique name
                $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
                
                // Create directory if not exists
                $uploadDir = __DIR__ . '/../assets/uploads/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                $destPath = $uploadDir . $newFileName;
                if (move_uploaded_file($fileTmpPath, $destPath)) {
                    $imageName = $newFileName;
                }
            }
        }
        
        if (empty($name) || $price <= 0.0 || $catId <= 0) {
            setFlashMessage('error', 'Please fill out all required fields and provide a valid price.');
        } else {
            // Begin Transaction
            $pdo->beginTransaction();
            try {
                if ($id > 0) {
                    // UPDATE PRODUCT
                    $stmt = $pdo->prepare("
                        UPDATE products 
                        SET category_id = ?, name = ?, description = ?, price = ?, image = ?, is_featured = ? 
                        WHERE id = ?
                    ");
                    $stmt->execute([$catId, $name, $desc, $price, $imageName, $featured, $id]);
                    
                    // UPDATE INVENTORY
                    $invStmt = $pdo->prepare("
                        UPDATE inventory 
                        SET stock_quantity = ?, low_stock_threshold = ? 
                        WHERE product_id = ?
                    ");
                    $invStmt->execute([$stock, $threshold, $id]);
                    
                    setFlashMessage('success', 'Product updated successfully!');
                } else {
                    // INSERT PRODUCT
                    $stmt = $pdo->prepare("
                        INSERT INTO products (category_id, name, description, price, image, is_featured) 
                        VALUES (?, ?, ?, ?, ?, ?)
                    ");
                    $stmt->execute([$catId, $name, $desc, $price, $imageName, $featured]);
                    $newProductId = $pdo->lastInsertId();
                    
                    // INSERT INVENTORY
                    $invStmt = $pdo->prepare("
                        INSERT INTO inventory (product_id, stock_quantity, low_stock_threshold) 
                        VALUES (?, ?, ?)
                    ");
                    $invStmt->execute([$newProductId, $stock, $threshold]);
                    
                    setFlashMessage('success', 'Product created successfully!');
                }
                $pdo->commit();
                logAnalyticsEvent('product_crud_save', json_encode(['name' => $name]));
            } catch (Exception $e) {
                $pdo->rollBack();
                setFlashMessage('error', 'Error saving product details: ' . $e->getMessage());
            }
        }
    }
    
    // ACTION: DELETE PRODUCT
    if ($action === 'delete') {
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        
        if ($id > 0) {
            $pdo->beginTransaction();
            try {
                // Delete inventory first due to FK constraints
                $delInv = $pdo->prepare("DELETE FROM inventory WHERE product_id = ?");
                $delInv->execute([$id]);
                
                // Delete product
                $delProd = $pdo->prepare("DELETE FROM products WHERE id = ?");
                $delProd->execute([$id]);
                
                $pdo->commit();
                logAnalyticsEvent('product_crud_delete', json_encode(['product_id' => $id]));
                setFlashMessage('success', 'Product removed successfully.');
            } catch (Exception $e) {
                $pdo->rollBack();
                setFlashMessage('error', 'Could not delete product. It may be linked to active customer orders.');
            }
        }
    }
    
    header("Location: " . BASE_URL . "/admin/products.php");
    exit;
}

// 2. Fetch all products and categories
try {
    $stmt = $pdo->query("
        SELECT p.*, c.name as category_name, inv.stock_quantity, inv.low_stock_threshold 
        FROM products p 
        JOIN categories c ON p.category_id = c.id 
        JOIN inventory inv ON p.id = inv.product_id
        ORDER BY p.id ASC
    ");
    $products = $stmt->fetchAll();
    
    $catStmt = $pdo->query("SELECT id, name FROM categories ORDER BY name ASC");
    $categories = $catStmt->fetchAll();
} catch (Exception $e) {
    die("Database fetch error: " . $e->getMessage());
}

// Set visual image mapping for fallback
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
    <title>Espresso SaaS - Products Directory</title>
    
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
            <h2 class="admin-navbar-title">Products CRUD Management</h2>
            <div class="admin-navbar-actions">
                <div class="admin-user-profile">
                    <div class="admin-user-avatar"><?php echo strtoupper(substr($currentUser['username'], 0, 1)); ?></div>
                    <strong><?php echo sanitize($currentUser['username']); ?></strong>
                </div>
            </div>
        </header>

        <!-- Dashboard Content Body Workspace -->
        <main class="admin-content-body">
            <!-- Products CRUD Table panel -->
            <section class="admin-card">
                <div class="admin-card-header">
                    <h4 class="admin-card-title">Artisanal Products Inventory</h4>
                    <!-- Dynamic Trigger Add Modal -->
                    <button class="btn btn-primary btn-sm" onclick="openAddModal()"><i class="fa-solid fa-plus"></i> Add Product</button>
                </div>

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Thumbnail</th>
                                <th>Name / Category</th>
                                <th>Unit Price</th>
                                <th>Stock Status</th>
                                <th>Featured</th>
                                <th>Action Panel</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products as $prod): 
                                // Find matching visual thumbnail
                                $thumb = $imageMap[$prod['name']] ?? '';
                                if (empty($thumb)) {
                                    if (!empty($prod['image'])) {
                                        $thumb = BASE_URL . '/assets/uploads/' . $prod['image'];
                                    } else {
                                        $thumb = 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?q=80&w=150';
                                    }
                                }
                            ?>
                                <tr>
                                    <td>
                                        <img src="<?php echo $thumb; ?>" alt="<?php echo sanitize($prod['name']); ?>" style="width:48px; height:48px; border-radius:6px; object-fit:cover;">
                                    </td>
                                    <td>
                                        <strong style="color:var(--coffee-dark);"><?php echo sanitize($prod['name']); ?></strong>
                                        <span class="color-muted text-xs" style="display:block;"><?php echo sanitize($prod['category_name']); ?></span>
                                    </td>
                                    <td class="font-semibold"><?php echo formatPrice($prod['price']); ?></td>
                                    <td><?php echo getStockBadge($prod['stock_quantity'], $prod['low_stock_threshold']); ?></td>
                                    <td>
                                        <?php if ($prod['is_featured']): ?>
                                            <span class="badge badge-primary">Yes</span>
                                        <?php else: ?>
                                            <span class="badge badge-success" style="background-color:rgba(44,26,17,0.04); color:var(--text-muted);">No</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="flex gap-xs">
                                            <!-- Edit Action -->
                                            <button class="btn btn-outline btn-sm" 
                                                    style="padding:6px 12px; font-size:0.75rem;" 
                                                    onclick="openEditModal(<?php echo htmlspecialchars(json_encode($prod)); ?>, '<?php echo $thumb; ?>')">
                                                <i class="fa-solid fa-pen"></i> Edit
                                            </button>
                                            
                                            <!-- Delete Action -->
                                            <form method="POST" action="" onsubmit="return confirm('Completely remove <?php echo sanitize($prod['name']); ?> from catalog?');">
                                                <?php csrfField(); ?>
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="id" value="<?php echo $prod['id']; ?>">
                                                <button type="submit" class="btn btn-outline btn-sm" style="padding:6px 12px; font-size:0.75rem; border-color:var(--danger); color:var(--danger);">
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>

    <!-- ==========================================================================
       CRUD MODAL OVERLAY (Saves Add & Edit configurations)
       ========================================================================== -->
    <div id="admin-product-modal" class="modal-overlay">
        <div class="modal-card" style="max-width: 720px;">
            <button class="modal-close-btn" onclick="closeCrudModal()">&times;</button>
            
            <form id="product-form" method="POST" action="" enctype="multipart/form-data" style="padding:30px;">
                <?php csrfField(); ?>
                <input type="hidden" name="action" value="save">
                <input type="hidden" name="id" id="form-product-id" value="">
                <input type="hidden" name="existing_image" id="form-existing-image" value="">

                <h3 id="modal-title-text" class="color-coffee mb-4" style="border-bottom:2px solid rgba(44,26,17,0.05); padding-bottom:8px;">Add New Artisanal Coffee</h3>

                <div class="admin-form-row">
                    <!-- Left Forms side -->
                    <div>
                        <!-- Product Name -->
                        <div class="form-group">
                            <label for="form-name" class="form-label">Coffee / Product Name: <span style="color:var(--danger);">*</span></label>
                            <input type="text" id="form-name" name="name" class="form-control" placeholder="e.g. Cafe Mocha" required>
                        </div>

                        <!-- Category Selector -->
                        <div class="form-group">
                            <label for="form-category" class="form-label">Menu Category: <span style="color:var(--danger);">*</span></label>
                            <select id="form-category" name="category_id" class="form-control" style="background:#FFF; appearance:auto;" required>
                                <option value="">-- Choose Category --</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?php echo $cat['id']; ?>"><?php echo sanitize($cat['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Unit Price -->
                        <div class="form-group">
                            <label for="form-price" class="form-label">Unit Price (₱): <span style="color:var(--danger);">*</span></label>
                            <input type="number" id="form-price" name="price" class="form-control" placeholder="150.00" step="0.01" min="1" required>
                        </div>

                        <!-- Stock Quantities -->
                        <div class="form-group">
                            <label for="form-stock" class="form-label">Available Stock Quantity:</label>
                            <input type="number" id="form-stock" name="stock_quantity" class="form-control" placeholder="50" min="0" required>
                        </div>
                    </div>

                    <!-- Right Forms side -->
                    <div>
                        <!-- Description -->
                        <div class="form-group">
                            <label for="form-desc" class="form-label">Artisanal Details / Description:</label>
                            <textarea id="form-desc" name="description" rows="3" class="form-control" placeholder="Type details..."></textarea>
                        </div>

                        <!-- Inventory Threshold Alert level -->
                        <div class="form-group">
                            <label for="form-threshold" class="form-label">Low Stock Alert Threshold:</label>
                            <input type="number" id="form-threshold" name="low_stock_threshold" class="form-control" placeholder="10" min="1" required>
                        </div>

                        <!-- Featured Select -->
                        <div class="form-group flex align-center gap-xs py-2">
                            <input type="checkbox" id="form-featured" name="is_featured" value="1">
                            <label for="form-featured" class="form-label" style="margin-bottom:0; cursor:pointer;">Promote as Roaster's Pick Featured Card</label>
                        </div>

                        <!-- Image upload controls -->
                        <div class="form-group">
                            <label for="admin-product-image" class="form-label">Upload Product Image (Optional):</label>
                            <input type="file" id="admin-product-image" name="product_image" class="form-control" style="padding:10px;">
                            <div class="image-preview-box" id="admin-image-preview">
                                <i class="fa-solid fa-image" style="font-size: 2.5rem; color: #8E847C;"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-between mt-3" style="border-top:1px solid rgba(44,26,17,0.08); padding-top:20px;">
                    <button type="button" class="btn btn-outline btn-sm" onclick="closeCrudModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Script triggers link -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js" crossorigin="anonymous" defer></script>
    <script src="<?php echo BASE_URL; ?>/assets/js/app.js" defer></script>
    <script src="<?php echo BASE_URL; ?>/assets/js/admin.js" defer></script>
    
    <script>
        const crudModal = document.getElementById('admin-product-modal');
        
        function openAddModal() {
            document.getElementById('product-form').reset();
            document.getElementById('form-product-id').value = '';
            document.getElementById('form-existing-image').value = '';
            document.getElementById('modal-title-text').textContent = 'Add New Artisanal Coffee';
            document.getElementById('admin-image-preview').innerHTML = `<i class="fa-solid fa-image" style="font-size: 2.5rem; color: #8E847C;"></i>`;
            crudModal.classList.add('open');
        }

        function openEditModal(prod, thumbUrl) {
            document.getElementById('form-product-id').value = prod.id;
            document.getElementById('form-name').value = prod.name;
            document.getElementById('form-category').value = prod.category_id;
            document.getElementById('form-price').value = prod.price;
            document.getElementById('form-stock').value = prod.stock_quantity;
            document.getElementById('form-desc').value = prod.description;
            document.getElementById('form-threshold').value = prod.low_stock_threshold;
            document.getElementById('form-featured').checked = (parseInt(prod.is_featured) === 1);
            document.getElementById('form-existing-image').value = prod.image || '';
            
            document.getElementById('modal-title-text').textContent = 'Edit Product: ' + prod.name;
            document.getElementById('admin-image-preview').innerHTML = `<img src="${thumbUrl}" alt="Preview">`;
            
            crudModal.classList.add('open');
        }

        function closeCrudModal() {
            crudModal.classList.remove('open');
        }
    </script>

</body>
</html>
