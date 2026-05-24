<?php
/**
 * Cafe Espresso - Barista Resources & Coffee Blog Page
 */
require_once __DIR__ . '/../includes/config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cafe Espresso - Barista Resources & Guides</title>
    
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
        <!-- Section: Heading Intro -->
        <div class="mb-5">
            <span class="blog-tagline">Read Our Blog</span>
            <h1 class="blog-heading">Browse Barista Resources</h1>
            <p class="blog-subheading">
                Explore brewing ratios, milk texturing sciences, physical roastery updates, and ethical sourcing stories directly from our industry-leading team.
            </p>
        </div>

        <!-- Section: Control Filters -->
        <section class="blog-control-panel">
            <div class="blog-tabs">
                <button class="blog-tab-btn active" onclick="showToast('info', 'All categories loaded.');">All Columns</button>
                <button class="blog-tab-btn" onclick="showToast('info', 'Coffee Craft columns loaded.');">Coffee Craft</button>
                <button class="blog-tab-btn" onclick="showToast('info', 'Barista Guides columns loaded.');">Barista Guides</button>
                <button class="blog-tab-btn" onclick="showToast('info', 'Cafe Culture columns loaded.');">Cafe Culture</button>
            </div>
            <div class="blog-search-bar" style="max-width:320px;">
                <input type="text" placeholder="Search resources..." onkeyup="if(event.key==='Enter') showToast('info', 'Searching resources...');">
            </div>
        </section>

        <!-- Section: Grid system -->
        <div class="blog-grid-system">
            <!-- Left Column: Primary feeds -->
            <div class="blog-main-column">
                <!-- Featured primary hero article card -->
                <article class="featured-post-card">
                    <div class="featured-img-holder">
                        <img src="https://images.unsplash.com/photo-1511920170033-f8396924c348?auto=format&fit=cover&q=80&w=1200" alt="Specialty Coffee Craft">
                    </div>
                    <div class="featured-text-overlay">
                        <span class="blog-tagline">Coffee Craft</span>
                        <h2 class="featured-title">Design in the Age of Caffeine: How to Optimize Your Morning Extraction Routine</h2>
                        
                        <div class="post-author-block">
                            <!-- Author Avatar -->
                            <div class="admin-user-avatar" style="width:36px; height:36px; font-size:0.8rem; box-shadow:var(--shadow-sm); border:2px solid #FFF;">AU</div>
                            <div class="author-details">
                                <span class="name">Azunyan U. Wu</span>
                                <span class="date">Jun 25, 2026</span>
                            </div>
                        </div>
                    </div>
                </article>

                <!-- Subordinate columns list -->
                <div class="sub-posts-grid">
                    <!-- Standard Post 1 -->
                    <article class="standard-blog-card">
                        <div class="card-img-holder">
                            <img src="https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?auto=format&fit=cover&q=80&w=600" alt="Milk steaming froth">
                        </div>
                        <div class="card-text-body">
                            <span class="blog-tagline" style="font-size: 9px; padding: 4px 10px;">Barista Guides</span>
                            <h3 class="card-title">The Art of Perfect Milk Texturing</h3>
                            <p class="card-excerpt">Learn the precise thermal science behind stretching and texturing microfoam silky enough to create pouring art at home.</p>
                            
                            <div class="post-author-block">
                                <div class="admin-user-avatar" style="width:32px; height:32px; font-size:0.7rem;">OB</div>
                                <div class="author-details">
                                    <span class="name">Osack Babanka</span>
                                    <span class="date">May 18, 2026</span>
                                </div>
                            </div>
                        </div>
                    </article>

                    <!-- Standard Post 2 -->
                    <article class="standard-blog-card">
                        <div class="card-img-holder">
                            <img src="https://images.unsplash.com/photo-1509042239860-f550ce710b93?auto=format&fit=cover&q=80&w=600" alt="Arabica farming">
                        </div>
                        <div class="card-text-body">
                            <span class="blog-tagline" style="font-size: 9px; padding: 4px 10px;">Cafe Culture</span>
                            <h3 class="card-title">Sourcing Arabica Ethically</h3>
                            <p class="card-excerpt">Discover our transparent micro-lot supply chain operations connecting high altitude organic farms straight to your cup.</p>
                            
                            <div class="post-author-block">
                                <div class="admin-user-avatar" style="width:32px; height:32px; font-size:0.7rem;">KM</div>
                                <div class="author-details">
                                    <span class="name">Kaori Miyazono</span>
                                    <span class="date">May 12, 2026</span>
                                </div>
                            </div>
                        </div>
                    </article>
                </div>
            </div>

            <!-- Right Column: Sidebar summaries -->
            <aside class="blog-sidebar">
                <div class="sidebar-container" style="background:#FFF; border:1px solid rgba(44,26,17,0.05); box-shadow:var(--shadow-sm);">
                    <h4 class="sidebar-title" style="color:var(--coffee-dark);">Trending Material</h4>
                    
                    <div class="sidebar-row-item">
                        <span class="results-counter-tag">01 / Espresso Craft</span>
                        <h5 style="color:var(--text-dark);" onclick="showToast('info', 'Loading column article...');">Understanding extracted pressures and flow-rate rules across commercial gear.</h5>
                    </div>

                    <div class="sidebar-row-item">
                        <span class="results-counter-tag">02 / Roasting Rules</span>
                        <h5 style="color:var(--text-dark);" onclick="showToast('info', 'Loading column article...');">How light bean roasts retain complex original notes and floral acidity levels.</h5>
                    </div>

                    <div class="sidebar-row-item">
                        <span class="results-counter-tag">03 / Business</span>
                        <h5 style="color:var(--text-dark);" onclick="showToast('info', 'Loading column article...');">Building cozy coffee shop spaces that encourage collaborative laptop workflows.</h5>
                    </div>
                </div>
            </aside>
        </div>
    </main>

    <!-- Reusable Footer -->
    <?php include __DIR__ . '/../components/footer.php'; ?>

</body>
</html>
