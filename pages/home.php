<?php
/**
 * Cafe Espresso - Storefront Landing Homepage
 */
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/auth.php';

// Fetch featured products dynamically
try {
    $stmt = $pdo->prepare("
        SELECT p.*, c.name as category_name 
        FROM products p 
        JOIN categories c ON p.category_id = c.id 
        WHERE p.is_featured = 1 
        LIMIT 3
    ");
    $stmt->execute();
    $featuredProducts = $stmt->fetchAll();
} catch (Exception $e) {
    $featuredProducts = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cafe Espresso - Handcrafted Coffee Experience</title>
    
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

    <main>
        <!-- 1. Cinematic Hero Section -->
        <section class="container hero-editorial">
            <div class="hero-text-block">
                <span class="hero-subtitle">Est. 2024 &bull; Manila Roastery</span>
                <h1 class="hero-title">The Best Coffee <span class="text-italic font-light color-gold">For You</span></h1>
                <p class="hero-desc">
                    Great coffee is more than a drink—it’s a carefully crafted moment of calm, connection, and inspiration. Experience the rich complexities of ethically sourced single-origin roasts prepared to absolute perfection by passionate local baristas.
                </p>
                <div class="flex gap-sm">
                    <a href="<?php echo BASE_URL; ?>/pages/menu.php" class="btn btn-primary shadow-glow">Order Now</a>
                    <a href="<?php echo BASE_URL; ?>/pages/about.php" class="btn btn-outline">Our Story</a>
                </div>
            </div>
            
            <div class="hero-visual-block">
                <div class="hero-frame-wrapper">
                    <!-- High-res Unsplash image celebrating cozy cafe vibes -->
                    <img src="https://images.unsplash.com/photo-1509042239860-f550ce710b93?q=80&w=800" alt="Cinematic Coffee Cup Frame" class="shadow-lg">
                </div>
                <!-- Curved dashed ornament -->
                <div class="hero-frame-accent"></div>
            </div>
        </section>

        <!-- 2. Featured Showcase / Best Sellers -->
        <section class="section-showcase">
            <div class="container">
                <div class="section-title-wrapper">
                    <span class="section-pretitle">Patron Favourites</span>
                    <h2 class="section-title">This Season's Best Sellers</h2>
                    <p class="section-desc">Experience our signature brews, meticulously roasted in small batches to preserve original floral acidity and full-bodied chocolate crema.</p>
                </div>

                <div class="grid grid-cols-3 gap-lg">
                    <?php 
                    if (!empty($featuredProducts)) {
                        require_once __DIR__ . '/../components/product-card.php';
                        foreach ($featuredProducts as $product) {
                            renderProductCard($product);
                        }
                    } else {
                        echo "<p class='color-muted w-full' style='grid-column: 1/-1;'>No featured selections found. Please check back later!</p>";
                    }
                    ?>
                </div>

                <div class="mt-4">
                    <a href="<?php echo BASE_URL; ?>/pages/menu.php" class="btn btn-secondary">Browse Full Menu</a>
                </div>
            </div>
        </section>

        <!-- 3. Our Coffee Story Segment -->
        <section class="section-story">
            <div class="container story-grid">
                <div class="story-visuals">
                    <div class="story-img-card shadow-lg">
                        <img src="https://images.unsplash.com/photo-1442512595331-e89e73853f31?auto=format&fit=crop&q=80&w=600" alt="Roaster roasting beans">
                    </div>
                    <div class="story-img-card offset shadow-lg">
                        <img src="https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?q=80&w=600" alt="Textured latte art">
                    </div>
                </div>

                <div class="story-content">
                    <span class="section-pretitle">Craft & Commitment</span>
                    <h3>Handcrafted Coffee Sourced With Love</h3>
                    <p>
                        Our journey began as a passionate, micro-lot roasting operation tucked away in a quiet corner of Manila. Dedicated to helping coffee patrons discover the true, vibrant complexities hidden inside regional farms, we roast in small artisanal batches to let distinct seasonal profiles shine.
                    </p>
                    <p>
                        We build direct relationships with local coffee growers, ensuring sustainable fair-wage operations that honor the hands that harvest the cherry. Every cup at Cafe Espresso is a celebration of origin, precision extraction, and community.
                    </p>
                    <a href="<?php echo BASE_URL; ?>/pages/about.php" class="btn btn-outline mt-3">Read Full Narrative</a>
                </div>
            </div>
        </section>

        <!-- 4. Customer Testimonials Segment -->
        <section class="section-showcase" style="background-color: var(--cream-light); border-top: 1px solid rgba(44, 26, 17, 0.04);">
            <div class="container">
                <div class="section-title-wrapper">
                    <span class="section-pretitle">Cafe Reviews</span>
                    <h2 class="section-title">What Our Patrons Say</h2>
                    <p class="section-desc">We take pride in creating spatial atmospheres and exceptional coffee recipes that build communal connections.</p>
                </div>

                <div class="testimonials-row">
                    <!-- Review 1 -->
                    <div class="testimonial-card">
                        <div class="testimonial-stars">
                            <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                        </div>
                        <p class="testimonial-quote">
                            "The dynamic Latte here is unbelievable! Silky texture, perfect balance, and beautifully crafted barista art. The warm minimalist styling makes it my favorite remote work environment."
                        </p>
                        <div class="testimonial-client">
                            <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?q=80&w=150" alt="Sophia Patron" class="testimonial-client-img">
                            <div>
                                <span class="testimonial-client-name">Sophia Altea</span>
                                <span class="testimonial-client-role">Freelance Designer</span>
                            </div>
                        </div>
                    </div>

                    <!-- Review 2 -->
                    <div class="testimonial-card">
                        <div class="testimonial-stars">
                            <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                        </div>
                        <p class="testimonial-quote">
                            "An absolute coffee lover's sanctuary! Sourcing transparent single-origins is super rare, but their V60 pour over and 18-hour cold brew are consistently spectacular and clean."
                        </p>
                        <div class="testimonial-client">
                            <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=150" alt="Marcus Cole" class="testimonial-client-img">
                            <div>
                                <span class="testimonial-client-name">Marcus Cole</span>
                                <span class="testimonial-client-role">Software Architect</span>
                            </div>
                        </div>
                    </div>

                    <!-- Review 3 -->
                    <div class="testimonial-card">
                        <div class="testimonial-stars">
                            <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-regular fa-star"></i>
                        </div>
                        <p class="testimonial-quote">
                            "The affogato is pure luxury—hot concentrated espresso markings slowly melting thick gourmet vanilla bean ice cream. The staff are warm, knowledgeable, and treat you like family."
                        </p>
                        <div class="testimonial-client">
                            <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=150" alt="Elena Reyes" class="testimonial-client-img">
                            <div>
                                <span class="testimonial-client-name">Elena Reyes</span>
                                <span class="testimonial-client-role">Food Blogger</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Reusable Options Modal Component -->
    <?php include __DIR__ . '/../components/modal.php'; ?>

    <!-- Reusable Footer -->
    <?php include __DIR__ . '/../components/footer.php'; ?>

</body>
</html>
