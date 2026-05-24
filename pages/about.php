<?php
/**
 * Cafe Espresso - About Us Page
 */
require_once __DIR__ . '/../includes/config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cafe Espresso - Our Coffee Story</title>
    
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

    <main class="container py-5">
        <!-- Section: Header Introduction -->
        <div class="text-center mb-5">
            <span class="section-pretitle">Our Roots</span>
            <h1 class="text-huge font-semibold color-coffee mb-1">Our Coffee Story</h1>
            <p class="color-muted text-base font-light" style="max-width: 600px; margin: 0 auto;">
                Beyond concentrates and quick cups—we celebrate small-batch precision, organic farms, and real human community.
            </p>
        </div>

        <!-- Section: Narrative Grid -->
        <div class="story-grid mb-5" style="align-items:center;">
            <!-- Left Side visuals card -->
            <div class="hero-visual-block" style="flex:1;">
                <div class="hero-frame-wrapper" style="max-width:100%; aspect-ratio:1.2;">
                    <!-- Cozy roastery coffee image -->
                    <img src="https://images.unsplash.com/photo-1442512595331-e89e73853f31?auto=format&fit=crop&q=80&w=800" alt="Cafe Espresso Roastery Context" class="shadow-lg">
                </div>
            </div>

            <!-- Right Side Story content -->
            <div class="story-content" style="padding-left:10px;">
                <span class="section-pretitle">The Philosophy</span>
                <h3>Roasting With Precision, Serving With Heart</h3>
                <p>
                    Cafe Espresso was born in 2024 out of a simple, uncompromising desire: to break free from automated commercial blends and connect patrons straight to the vibrant, clean flavors hiding inside artisanal single-origin coffee.
                </p>
                <p>
                    Tucked away in the heart of Manila, our storefront serves as a sanctuary for remote workers, casual book lovers, and coffee enthusiasts alike. We carefully profile each micro-lot harvest in-house to reveal its original floral sweetness, clean fruit acidity, or decadent chocolate base.
                </p>
                <p>
                    We believe the perfect cup requires both precision science (carefully logged flow rates and temperatures) and generous human heart. We treat our team, our growers, and our patrons like family.
                </p>
            </div>
        </div>

        <!-- Section: Organic Farm sourcing details -->
        <div class="story-grid mb-4" style="grid-template-columns: 0.95fr 1.05fr; align-items:center; gap:60px;">
            <div class="story-content">
                <span class="section-pretitle">Direct Trade Sourcing</span>
                <h3>Ethically Grown, Sustainably Sourced</h3>
                <p>
                    Every bean we roast is purchased directly from farming families who maintain healthy, organic crop microclimates. By choosing direct trade frameworks, we pay 30% to 50% above fair-trade certified minimums.
                </p>
                <p>
                    This direct partnership ensures that regional farmers can continue to maintain their soil, support their local pickers, and cultivate exceptional, high-altitude coffee cherries. Transparency is absolute—we can trace every single bag of beans back to the exact hill it was harvested on.
                </p>
                <a href="<?php echo BASE_URL; ?>/pages/menu.php" class="btn btn-primary mt-2">Discover Our Menu</a>
            </div>

            <div class="hero-visual-block" style="flex:1;">
                <div class="hero-frame-wrapper" style="max-width:100%; aspect-ratio:1.2;">
                    <img src="https://images.unsplash.com/photo-1509042239860-f550ce710b93?auto=format&fit=crop&q=80&w=800" alt="Artisanal Latte Pour" class="shadow-lg">
                </div>
            </div>
        </div>
    </main>

    <!-- Reusable Footer -->
    <?php include __DIR__ . '/../components/footer.php'; ?>

</body>
</html>
