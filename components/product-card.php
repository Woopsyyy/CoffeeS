<?php
/**
 * Cafe Espresso - Reusable Product Card Component
 */
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/helpers.php';

if (!function_exists('renderProductCard')) {
    function renderProductCard($product) {
        $id = (int)$product['id'];
        $name = sanitize($product['name']);
        $desc = sanitize($product['description']);
        $price = (float)$product['price'];
        $category = sanitize($product['category_name'] ?? 'Beverage');
        
        // Premium High-Res Unsplash Mapping for Out-Of-The-Box visual perfection
        $imageMap = [
            'Espresso' => 'https://images.unsplash.com/photo-1595981267035-7b04ca84a82d?q=80&w=600',
            'Americano' => 'https://images.unsplash.com/photo-1507133750040-4a8f57021571?q=80&w=600',
            'Double Espresso' => 'https://images.unsplash.com/photo-1610889556528-9a770e32642f?q=80&w=600',
            'Macchiato' => 'https://images.unsplash.com/photo-1485808191679-5f86510681a2?q=80&w=600',
            'Cappuccino' => 'https://images.unsplash.com/photo-1534778101976-62847782c213?q=80&w=600',
            'Cafe Latte' => 'https://images.unsplash.com/photo-1541167760496-1628856ab772?q=80&w=600',
            'Caffe Mocha' => 'https://images.unsplash.com/photo-1578314675249-a6910f80cc4e?q=80&w=600',
            'Affogato' => 'https://images.unsplash.com/photo-1517093602195-b40af9688b46?q=80&w=600',
            'Cold Brew' => 'https://images.unsplash.com/photo-1511920170033-f8396924c348?q=80&w=600',
            'Frappe' => 'https://images.unsplash.com/photo-1572490122747-3968b75cc699?q=80&w=600',
            'Butter Croissant' => 'https://images.unsplash.com/photo-1555507036-ab1f4038808a?q=80&w=600',
            'Chocolate Fudge Cake' => 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?q=80&w=600'
        ];
        
        // Find matching image or use uploaded image, otherwise generic fallback
        $imageUrl = $imageMap[$name] ?? '';
        if (empty($imageUrl)) {
            if (!empty($product['image'])) {
                // If it is in uploads or assets
                if (file_exists(__DIR__ . '/../assets/uploads/' . $product['image'])) {
                    $imageUrl = BASE_URL . '/assets/uploads/' . $product['image'];
                } else {
                    $imageUrl = 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?q=80&w=600';
                }
            } else {
                $imageUrl = 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?q=80&w=600';
            }
        }
        
        // Is product featured?
        $featuredBadge = '';
        if (isset($product['is_featured']) && $product['is_featured']) {
            $featuredBadge = '<span class="product-card-tag">Roaster\'s Pick</span>';
        }
        ?>
        <article class="product-card">
            <!-- Featured Pick tag -->
            <?php echo $featuredBadge; ?>

            <!-- Add to Favorites button -->
            <button class="product-card-fav" onclick="showToast('info', '<?php echo $name; ?> added to your wishlist!');" aria-label="Add to Wishlist">
                <i class="fa-regular fa-heart"></i>
            </button>

            <!-- Product Visual -->
            <div class="product-card-img-wrapper">
                <img src="<?php echo $imageUrl; ?>" alt="<?php echo $name; ?>" class="product-card-img" loading="lazy">
            </div>

            <!-- Product Metas -->
            <span class="product-card-category"><?php echo $category; ?></span>
            <h3 class="product-card-title"><?php echo $name; ?></h3>
            <p class="product-card-desc"><?php echo truncateText($desc, 90); ?></p>

            <!-- Card Footer price & quick add button -->
            <div class="product-card-footer">
                <span class="product-card-price"><?php echo formatPrice($price); ?></span>
                
                <button class="product-card-btn quick-add-trigger" 
                        data-product-id="<?php echo $id; ?>"
                        data-product-name="<?php echo $name; ?>"
                        data-product-price="<?php echo formatPrice($price); ?>"
                        data-product-image="<?php echo $imageUrl; ?>"
                        aria-label="Order <?php echo $name; ?> Now">
                    <i class="fa-solid fa-plus"></i>
                </button>
            </div>
        </article>
        <?php
    }
}
?>
