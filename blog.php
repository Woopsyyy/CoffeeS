<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coffee Shop - Browse Our Resources</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        /* ==========================================================================
   --- 1. Reset & Global Root Configurations ---
   ========================================================================== */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

:root {
    --bg-main: #0f1e1a;          /* Dark forest green background */
    --text-light: #edf2f0;       /* Off-white typography */
    --text-muted: #a3b3ac;       /* Soft green-gray description */
    --accent-cream: #e5dfd9;     /* Cream colors */
    --accent-brown: #9c663b;     /* Main coffee brown ring */
    --accent-tan: #b07d4f;        /* Tan button & social backgrounds */
    --line-color: rgba(229, 223, 217, 0.15); 
}

html, body {
    width: 100%;
    height: 100%;
    overflow-x: hidden;
}

body {
    font-family: 'Poppins', sans-serif;
    background-color: var(--bg-main);
    color: var(--text-light);
    display: flex;
    flex-direction: column;
}

/* ==========================================================================
   --- 2. FIXED / STICKY HEADER PART ---
   ========================================================================== */
.header-sticky-wrapper {
    position: -webkit-sticky;
    position: sticky;
    top: 0;
    z-index: 1000;
    background-color: var(--bg-main); /* Matches background color to seamlessly mask posts underneath */
    padding: 30px 60px 0 60px;       /* Top and side padding for clean window alignments */
}

.navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
    padding-bottom: 20px;
    border-bottom: 1px solid var(--line-color);
}

.social-icons {
    display: flex;
    gap: 12px;
}

.social-icons a {
    color: var(--bg-main);
    background-color: var(--accent-tan);
    width: 36px;
    height: 36px;
    display: inline-flex;
    justify-content: center;
    align-items: center;
    border-radius: 50%;
    text-decoration: none;
    transition: all 0.3s ease;
}

.social-icons a svg {
    width: 16px;
    height: 16px;
    fill: currentColor;
}

.social-icons a:hover {
    background-color: #e3b27d;
    transform: translateY(-2px);
}

.nav-links {
    display: flex;
    gap: 45px;
}

.nav-links a {
    color: var(--accent-cream);
    text-decoration: none;
    font-size: 15px;
    font-weight: 400;
    letter-spacing: 0.5px;
    transition: color 0.3s ease;
}

.nav-links a:hover, 
.nav-links a.active {
    color: var(--accent-tan);
}

/* ==========================================================================
   --- 3. Scrolling Main Main Content Workspace ---
   ========================================================================== */
.landing-container {
    width: 100%;
    min-height: calc(100vh - 87px); /* Fills remaining screenspace properly */
    padding: 40px 60px;
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
}

.blog-content-wrapper {
    width: 100%;
    max-width: 1400px;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
}

/* --- Introductions Segment --- */
.blog-intro {
    width: 100%;
    margin-bottom: 35px;
}

.blog-tagline {
    display: inline-block;
    color: var(--accent-tan);
    background-color: rgba(176, 125, 79, 0.1);
    padding: 6px 14px;
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 1px;
    border-radius: 6px;
    text-transform: uppercase;
    margin-bottom: 12px;
}

.blog-heading {
    font-family: 'Playfair Display', serif;
    font-size: clamp(32px, 4vw, 54px);
    font-weight: 700;
    color: var(--text-light);
    line-height: 1.1;
    letter-spacing: -0.5px;
}

.blog-subheading {
    font-size: clamp(13px, 1.1vw, 15px);
    color: var(--text-muted);
    max-width: 750px;
    margin-top: 12px;
    line-height: 1.6;
}

/* --- Navigation Control Panel Bars --- */
.blog-control-panel {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid var(--line-color);
    padding-bottom: 20px;
    margin-bottom: 40px;
    gap: 20px;
}

.blog-tabs {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.blog-tab-btn {
    background: transparent;
    border: 1px solid transparent;
    color: var(--text-muted);
    padding: 8px 18px;
    font-size: 13px;
    font-weight: 500;
    border-radius: 20px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.blog-tab-btn:hover, 
.blog-tab-btn.active {
    color: #fff;
    background-color: rgba(255, 255, 255, 0.05);
}

.blog-tab-btn.active {
    border-color: var(--accent-tan);
    color: var(--accent-tan);
}

.blog-search-bar {
    position: relative;
    max-width: 300px;
    width: 100%;
}

.blog-search-bar input {
    width: 100%;
    background: rgba(0, 0, 0, 0.2);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 20px;
    padding: 10px 15px 10px 20px;
    color: #fff;
    font-size: 13px;
    outline: none;
    transition: border-color 0.3s ease;
}

.blog-search-bar input:focus {
    border-color: var(--accent-tan);
}

/* ==========================================================================
   --- 4. Grid System Layout Structure ---
   ========================================================================== */
.blog-grid-system {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 45px;
    align-items: start;
}

.blog-main-column {
    display: flex;
    flex-direction: column;
    gap: 40px;
}

/* --- Hero Featured Card --- */
.featured-post-card {
    width: 100%;
    border-radius: 24px;
    overflow: hidden;
    position: relative;
    box-shadow: 0 15px 35px rgba(0,0,0,0.4);
}

.featured-img-holder {
    width: 100%;
    height: 460px;
    position: relative;
}

.featured-img-holder img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.6s cubic-bezier(0.25, 1, 0.5, 1);
}

.featured-post-card:hover .featured-img-holder img {
    transform: scale(1.03);
}

.featured-text-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(15, 30, 26, 1) 5%, rgba(15, 30, 26, 0.3) 65%, transparent 100%);
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
    padding: 40px;
    align-items: flex-start;
}

.featured-title {
    font-family: 'Playfair Display', serif;
    font-size: clamp(22px, 2.5vw, 32px);
    color: var(--text-light);
    font-weight: 600;
    line-height: 1.2;
    margin: 8px 0 20px 0;
}

/* --- Secondary Mini Post Cards --- */
.sub-posts-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 30px;
}

.standard-blog-card {
    background: rgba(255, 255, 255, 0.01);
    border: 1px solid rgba(255, 255, 255, 0.04);
    border-radius: 16px;
    overflow: hidden;
    transition: transform 0.3s ease, border-color 0.3s ease, box-shadow 0.3s ease;
}

.standard-blog-card:hover {
    transform: translateY(-4px);
    border-color: rgba(176, 125, 79, 0.2);
    box-shadow: 0 10px 20px rgba(0,0,0,0.2);
}

.card-img-holder {
    width: 100%;
    height: 200px;
    overflow: hidden;
}

.card-img-holder img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.standard-blog-card:hover .card-img-holder img {
    transform: scale(1.05);
}

.card-text-body {
    padding: 24px;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
}

.card-title {
    font-size: 18px;
    color: var(--text-light);
    line-height: 1.3;
    margin: 12px 0 10px 0;
    font-weight: 500;
}

.card-excerpt {
    font-size: 12px;
    color: var(--text-muted);
    line-height: 1.6;
    margin-bottom: 20px;
}

/* --- Component Profile Block --- */
.post-author-block {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-top: auto; /* Aligns profiles cleanly at bottom borders */
}

.author-avatar-img {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    object-fit: cover;
    border: 1px solid rgba(255, 255, 255, 0.15);
}

.author-details {
    display: flex;
    flex-direction: column;
}

.author-details .name {
    font-size: 13px;
    font-weight: 500;
    color: var(--text-light);
}

.author-details .date {
    font-size: 11px;
    color: var(--text-muted);
}

/* --- Right Sticky Sidebar Columns --- */
.blog-sidebar {
    width: 100%;
}

.sidebar-container {
    position: -webkit-sticky;
    position: sticky;
    top: 130px; /* Positions perfectly below your sticky navigation header bar */
    background: rgba(0, 0, 0, 0.12);
    border: 1px solid rgba(255, 255, 255, 0.03);
    border-radius: 20px;
    padding: 30px;
}

.sidebar-title {
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: var(--text-light);
    margin-bottom: 25px;
    border-left: 3px solid var(--accent-tan);
    padding-left: 12px;
}

.sidebar-row-item {
    padding-bottom: 20px;
    margin-bottom: 20px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
}

.sidebar-row-item:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

.sidebar-row-item h5 {
    font-size: 14px;
    font-weight: 400;
    line-height: 1.4;
    color: var(--text-light);
    margin-top: 8px;
    cursor: pointer;
    transition: color 0.2s ease;
}

.sidebar-row-item h5:hover {
    color: var(--accent-tan);
}

.results-counter-tag {
    display: inline-block;
    font-size: 11px;
    color: var(--accent-tan);
    font-weight: 500;
}


@media (max-width: 992px) {
    .header-sticky-wrapper {
        padding: 30px 30px 0 30px;
    }

    .landing-container {
        padding: 40px 30px;
    }

    .blog-grid-system {
        grid-template-columns: 1fr;
        gap: 50px;
    }

    .sidebar-container {
        position: relative;
        top: 0;
    }

    .nav-links {
        gap: 25px;
    }
}

@media (max-width: 680px) {
    .blog-control-panel {
        flex-direction: column;
        align-items: flex-start;
    }

    .blog-search-bar {
        max-width: 100%;
    }

    .sub-posts-grid {
        grid-template-columns: 1fr;
    }

    .featured-img-holder {
        height: 340px;
    }
}

@media (max-width: 580px) {
    .nav-links {
        display: none; /* Collapses text nav options gracefully on small mobile systems */
    }

    .navbar {
        justify-content: center;
    }

    .header-sticky-wrapper {
        padding: 20px 20px 0 20px;
    }

    .landing-container {
        padding: 30px 20px;
    }
}
</style>
</head>
<body>

    <header class="header-sticky-wrapper">
        <nav class="navbar">
            <div class="social-icons">
                <a href="#"><svg viewBox="0 0 24 24"><path d="M22 12c0-5.52-4.48-10-10-10S2 6.48 2 12c0 4.84 3.44 8.87 8 9.8V15H8v-3h2V9.5C10 7.57 11.57 6 13.5 6H16v3h-2c-.55 0-1 .45-1 1v2h3v3h-3v6.95c4.56-.93 8-4.96 8-9.75z"/></svg></a>
                <a href="#"><svg viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.051.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg></a>
                <a href="#"><svg viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg></a>
            </div>

            <div class="nav-links">
                <a href="index.php">Home</a>
                <a href="menu.php">Menu</a>
                <a href="blog.php" class="active">Blog</a>
                <a href="about.php">About us</a>
                <a href="signin.php">Sign In</a>
            </div>
        </nav>
    </header>

    <main class="landing-container">
        <div class="blog-content-wrapper">
            
            <section class="blog-intro">
                <span class="blog-tagline">Read Our Blog</span>
                <h1 class="blog-heading">Browse Our Resources</h1>
                <p class="blog-subheading">We provide seasonal brewing tips, barista insights, and coffee culture resources directly from industry leaders.</p>
            </section>

            <section class="blog-control-panel">
                <div class="blog-tabs">
                    <button class="blog-tab-btn active">All Stories</button>
                    <button class="blog-tab-btn">Coffee Craft</button>
                    <button class="blog-tab-btn">Barista Guides</button>
                    <button class="blog-tab-btn">Cafe Culture</button>
                </div>
                <div class="blog-search-bar">
                    <input type="text" placeholder="Search resources...">
                </div>
            </section>

            <div class="blog-grid-system">
                
                <div class="blog-main-column">
                    
                    <article class="featured-post-card">
                        <div class="featured-img-holder">
                            <img src="https://images.unsplash.com/photo-1511920170033-f8396924c348?auto=format&fit=cover&q=80&w=1200" alt="Featured Coffee Post">
                        </div>
                        <div class="featured-text-overlay">
                            <span class="blog-tagline">Coffee Craft</span>
                            <h2 class="featured-title">Design In The Age Of Caffeine: How to adapt your morning brewing routines lazily.</h2>
                            
                            <div class="post-author-block">
                                <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&q=80&w=100" alt="Author" class="author-avatar-img">
                                <div class="author-details">
                                    <span class="name">Azunyan U. Wu</span>
                                    <span class="date">Jun 25, 2026</span>
                                </div>
                            </div>
                        </div>
                    </article>

                    <div class="sub-posts-grid">
                        
                        <article class="standard-blog-card">
                            <div class="card-img-holder">
                                <img src="https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?auto=format&fit=cover&q=80&w=600" alt="Blog Post">
                            </div>
                            <div class="card-text-body">
                                <span class="blog-tagline" style="font-size: 9px; padding: 4px 10px;">Barista Guides</span>
                                <h3 class="card-title">The Art of Perfect Milk Texturing</h3>
                                <p class="card-excerpt">Learn the precise science behind creating microfoam silky enough to craft barista-level latte art patterns at home...</p>
                                <div class="post-author-block">
                                    <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&q=80&w=100" alt="Author" class="author-avatar-img">
                                    <div class="author-details">
                                        <span class="name">Osack Babanka</span>
                                        <span class="date">May 18, 2026</span>
                                    </div>
                                </div>
                            </div>
                        </article>

                        <article class="standard-blog-card">
                            <div class="card-img-holder">
                                <img src="https://images.unsplash.com/photo-1509042239860-f550ce710b93?auto=format&fit=cover&q=80&w=600" alt="Blog Post">
                            </div>
                            <div class="card-text-body">
                                <span class="blog-tagline" style="font-size: 9px; padding: 4px 10px;">Cafe Culture</span>
                                <h3 class="card-title">Sourcing Arabica Ethically</h3>
                                <p class="card-excerpt">Discover our transparent micro-lot supply framework operations connecting sustainable regional farms straight to your cup...</p>
                                <div class="post-author-block">
                                    <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&q=80&w=100" alt="Author" class="author-avatar-img">
                                    <div class="author-details">
                                        <span class="name">Kaori Miyazono</span>
                                        <span class="date">May 12, 2026</span>
                                    </div>
                                </div>
                            </div>
                        </article>

                    </div>
                </div>

                <aside class="blog-sidebar">
                    <div class="sidebar-container">
                        <h4 class="sidebar-title">Trending Material</h4>
                        
                        <div class="sidebar-row-item">
                            <span class="results-counter-tag">01 / Espresso Craft</span>
                            <h5>Understanding extracted pressures and flow-rate rules across commercial gear setups.</h5>
                        </div>

                        <div class="sidebar-row-item">
                            <span class="results-counter-tag">02 / Roasting Rules</span>
                            <h5>How light bean roasts retain complex original notes and floral acidity levels.</h5>
                        </div>

                        <div class="sidebar-row-item">
                            <span class="results-counter-tag">03 / Business</span>
                            <h5>Building communal spaces that encourage collaborative laptop workflows.</h5>
                        </div>
                    </div>
                </aside>

            </div>
        </div>
    </main>

</body>
</html>