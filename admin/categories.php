<?php
/**
 * Cafe Espresso - Admin SaaS Category Management CRUD
 */
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/auth.php';

// Verification admin permissions
requireAdmin();

$currentUser = getCurrentUser();

// Helper slug generator
function generateSlug($str) {
    return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $str)));
}

// 1. Handle actions (Save / Delete)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyPostCsrf();
    
    $action = $_POST['action'] ?? '';
    
    // ACTION: SAVE CATEGORY (Add / Edit)
    if ($action === 'save') {
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        $name = sanitizeInput($_POST['name'] ?? '');
        $desc = sanitizeInput($_POST['description'] ?? '');
        $slug = generateSlug($name);
        
        if (empty($name)) {
            setFlashMessage('error', 'Category name cannot be empty.');
        } else {
            try {
                if ($id > 0) {
                    // Update
                    $stmt = $pdo->prepare("UPDATE categories SET name = ?, slug = ?, description = ? WHERE id = ?");
                    $stmt->execute([$name, $slug, $desc, $id]);
                    setFlashMessage('success', 'Category updated successfully!');
                } else {
                    // Insert
                    $stmt = $pdo->prepare("INSERT INTO categories (name, slug, description) VALUES (?, ?, ?)");
                    $stmt->execute([$name, $slug, $desc]);
                    setFlashMessage('success', 'Category added successfully!');
                }
                logAnalyticsEvent('category_crud_save', json_encode(['name' => $name]));
            } catch (Exception $e) {
                setFlashMessage('error', 'Error saving category. The name may already be registered.');
            }
        }
    }
    
    // ACTION: DELETE CATEGORY
    if ($action === 'delete') {
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        
        if ($id > 0) {
            try {
                // Check if any product belongs to this category first!
                $check = $pdo->prepare("SELECT COUNT(*) FROM products WHERE category_id = ?");
                $check->execute([$id]);
                $count = (int)$check->fetchColumn();
                
                if ($count > 0) {
                    setFlashMessage('error', "Could not delete category. There are {$count} products registered under it. Delete or reassign those products first.");
                } else {
                    $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
                    $stmt->execute([$id]);
                    setFlashMessage('success', 'Category deleted successfully.');
                    logAnalyticsEvent('category_crud_delete', json_encode(['category_id' => $id]));
                }
            } catch (Exception $e) {
                setFlashMessage('error', 'Error deleting category.');
            }
        }
    }
    
    header("Location: " . BASE_URL . "/admin/categories.php");
    exit;
}

// 2. Fetch all categories with dynamic count
try {
    $stmt = $pdo->query("
        SELECT c.*, COUNT(p.id) as product_count 
        FROM categories c 
        LEFT JOIN products p ON c.id = p.category_id 
        GROUP BY c.id
        ORDER BY c.id ASC
    ");
    $categories = $stmt->fetchAll();
} catch (Exception $e) {
    die("Database fetch error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espresso SaaS - Categories Management</title>
    
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
            <h2 class="admin-navbar-title">Categories Management</h2>
            <div class="admin-navbar-actions">
                <div class="admin-user-profile">
                    <div class="admin-user-avatar"><?php echo strtoupper(substr($currentUser['username'], 0, 1)); ?></div>
                    <strong><?php echo sanitize($currentUser['username']); ?></strong>
                </div>
            </div>
        </header>

        <!-- Dashboard Content Body Workspace -->
        <main class="admin-content-body">
            <!-- Categories split layout -->
            <div class="analytics-grid" style="grid-template-columns: 2fr 1fr; gap:30px;">
                <!-- Left Side: List categories table -->
                <div class="admin-card" style="margin-bottom:0;">
                    <div class="admin-card-header">
                        <h4 class="admin-card-title">Registered Coffee Categories</h4>
                    </div>

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Slug</th>
                                    <th>Description</th>
                                    <th>Count</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($categories as $cat): ?>
                                    <tr>
                                        <td class="font-semibold" style="color:var(--accent-gold);">#<?php echo $cat['id']; ?></td>
                                        <td><strong><?php echo sanitize($cat['name']); ?></strong></td>
                                        <td class="text-xs"><?php echo sanitize($cat['slug']); ?></td>
                                        <td class="color-muted text-xs" style="max-width:220px; text-overflow:ellipsis; overflow:hidden; white-space:nowrap;">
                                            <?php echo sanitize($cat['description']); ?>
                                        </td>
                                        <td><span class="badge badge-primary"><?php echo $cat['product_count']; ?> products</span></td>
                                        <td>
                                            <div class="flex gap-xs">
                                                <!-- Load properties in form below to edit -->
                                                <button class="btn btn-outline btn-sm" 
                                                        style="padding:6px 12px; font-size:0.75rem;"
                                                        onclick="editCategory(<?php echo htmlspecialchars(json_encode($cat)); ?>)">
                                                    <i class="fa-solid fa-pen"></i>
                                                </button>
                                                
                                                <!-- Delete -->
                                                <form method="POST" action="" onsubmit="return confirm('Completely remove category: <?php echo sanitize($cat['name']); ?>?');">
                                                    <?php csrfField(); ?>
                                                    <input type="hidden" name="action" value="delete">
                                                    <input type="hidden" name="id" value="<?php echo $cat['id']; ?>">
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
                </div>

                <!-- Right Side: Creation / Edit Actions Panel -->
                <div class="admin-card" style="margin-bottom:0; height:fit-content;">
                    <h3 id="form-heading-text" class="admin-card-title mb-3" style="border-bottom:2px solid rgba(44,26,17,0.05); padding-bottom:8px;">Add Category</h3>
                    
                    <form id="category-form" method="POST" action="">
                        <?php csrfField(); ?>
                        <input type="hidden" name="action" value="save">
                        <input type="hidden" name="id" id="form-cat-id" value="">

                        <!-- Name -->
                        <div class="form-group">
                            <label for="form-cat-name" class="form-label">Category Name: <span style="color:var(--danger);">*</span></label>
                            <input type="text" id="form-cat-name" name="name" class="form-control" placeholder="e.g. Loose Beans" required>
                        </div>

                        <!-- Description -->
                        <div class="form-group">
                            <label for="form-cat-desc" class="form-label">Description / Philosophy:</label>
                            <textarea id="form-cat-desc" name="description" rows="4" class="form-control" placeholder="Describe the category..."></textarea>
                        </div>

                        <div class="flex justify-between mt-3" style="border-top:1px solid rgba(44,26,17,0.08); padding-top:16px;">
                            <button type="button" class="btn btn-outline btn-sm" onclick="resetForm()">Clear Form</button>
                            <button type="submit" class="btn btn-secondary btn-sm">Confirm Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <!-- Script linked dependencies -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js" crossorigin="anonymous" defer></script>
    <script src="<?php echo BASE_URL; ?>/assets/js/app.js" defer></script>
    
    <script>
        function editCategory(cat) {
            document.getElementById('form-cat-id').value = cat.id;
            document.getElementById('form-cat-name').value = cat.name;
            document.getElementById('form-cat-desc').value = cat.description || '';
            document.getElementById('form-heading-text').textContent = 'Edit Category: ' + cat.name;
        }

        function resetForm() {
            document.getElementById('category-form').reset();
            document.getElementById('form-cat-id').value = '';
            document.getElementById('form-heading-text').textContent = 'Add Category';
        }
    </script>

</body>
</html>
