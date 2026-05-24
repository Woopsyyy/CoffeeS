<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coffee Shop - About Us</title>
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
   --- 2. FIXED / STICKY HEADER PART (Remains completely still) ---
   ========================================================================== */
.header-sticky-wrapper {
    position: -webkit-sticky;
    position: sticky;
    top: 0;
    z-index: 1000;
    background-color: var(--bg-main); /* Blocks out scrolling layers neatly underneath */
    padding: 30px 60px 0 60px;       
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
   --- 3. Scrolling Main Work Surface Layout ---
   ========================================================================== */
.landing-container {
    width: 100%;
    min-height: calc(100vh - 87px); 
    padding: 60px;
    display: flex;
    flex-direction: column;
    justify-content: center; /* Vertically centers content fields gracefully */
}

.about-layout-wrapper {
    width: 100%;
    max-width: 1300px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: 1.1fr 1.3fr; /* Balanced asymmetrical grid distribution */
    gap: 60px;
    align-items: center;
    position: relative;
}

/* ==========================================================================
   --- 4. Left Visual Frame Configurations ---
   ========================================================================== */
.about-visual-column {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 100%;
}

/* Abstract Badges matching the graphic ornament in your image */
.abstract-accent-badge {
    position: absolute;
    left: -35px;
    top: 15%;
    width: 75px;
    height: 75px;
    color: var(--accent-tan);
    opacity: 0.8;
    z-index: 5;
    animation: rotateSlowly 25s linear infinite; /* Subtle premium movement trait */
}

@keyframes rotateSlowly {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

/* Main Portrait Frame Setup */
.about-portrait-frame {
    width: 100%;
    max-width: 440px;
    height: 520px;
    border-radius: 4px;
    overflow: hidden;
    box-shadow: 0 20px 45px rgba(0, 0, 0, 0.35);
}

.about-portrait-frame img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    filter: grayscale(15%) sepia(10%) contrast(105%); /* Gives it an editorial look */
}

/* Bottom Profile Alignment Icons */
.about-footer-icons {
    display: flex;
    gap: 20px;
    margin-top: 25px;
    justify-content: center;
    width: 100%;
}

.about-footer-icons a {
    color: var(--text-muted);
    transition: color 0.3s ease, transform 0.3s ease;
}

.about-footer-icons a svg {
    width: 18px;
    height: 18px;
    fill: currentColor;
}

.about-footer-icons a:hover {
    color: var(--accent-tan);
    transform: translateY(-2px);
}

/* ==========================================================================
   --- 5. Right Typography Text Configurations ---
   ========================================================================== */
.about-text-column {
    position: relative;
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding-left: 20px;
}

/* Massive Overlapping Title Block Layout */
.about-main-title {
    font-family: 'Playfair Display', serif;
    font-size: clamp(54px, 7.5vw, 105px); /* Massive impactful fluid text scales safely */
    font-weight: 700;
    color: var(--text-light);
    letter-spacing: -2px;
    line-height: 0.85;
    margin-bottom: 35px;
    margin-left: -70px; /* Forces layout overlap trick modeled from the asset */
    position: relative;
    z-index: 2;
    pointer-events: none;
    opacity: 0.95;
}

/* Core Informational Narrative Layout */
.about-story-content {
    max-width: 520px;
    display: flex;
    flex-direction: column;
    gap: 24px;
    position: relative;
    z-index: 3;
}

.about-story-content p {
    font-size: 14px;
    font-weight: 300;
    color: var(--text-muted);
    line-height: 1.75;
    letter-spacing: 0.3px;
    text-align: justify;
}

/* Bottom Accent Line Rules */
.about-bottom-divider {
    width: 65px;
    height: 4px;
    background-color: #1c2e28; /* Monochromatic thick tab */
    margin-top: 45px;
    align-self: flex-end; /* Snaps safely toward bottom right bounds */
}

/* ==========================================================================
   --- 6. Responsive Adaptation Framework Viewports ---
   ========================================================================== */
@media (max-width: 1024px) {
    .about-main-title {
        margin-left: -30px; /* Tames intersections on smaller tablet fields */
    }
}

@media (max-width: 992px) {
    .header-sticky-wrapper {
        padding: 30px 30px 0 30px;
    }

    .landing-container {
        padding: 40px 30px;
        height: auto; /* Dissolves view constraints so workflows scroll smoothly */
    }

    .about-layout-wrapper {
        grid-template-columns: 1fr; /* Collapses grid elements into single row stack */
        gap: 40px;
    }

    .about-visual-column {
        order: 2; /* Content details take priority on compact screen systems */
    }

    .about-text-column {
        order: 1;
        padding-left: 0;
        align-items: center;
    }

    .about-main-title {
        margin-left: 0;
        text-align: center;
        margin-bottom: 25px;
    }

    .about-story-content {
        max-width: 100%;
    }

    .about-portrait-frame {
        max-width: 100%;
        height: 480px;
    }

    .abstract-accent-badge {
        left: 15px;
        top: -25px;
    }

    .about-bottom-divider {
        display: none; /* Simplifies layout elements across small devices */
    }
}

@media (max-width: 580px) {
    .nav-links {
        display: none; /* Collapses text navigation links neatly on small mobile screens */
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

    .about-portrait-frame {
        height: 380px;
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
                <a href="blog.php">Blog</a>
                <a href="about.php" class="active">About us</a>
                <a href="signin.php">Sign In</a>
            </div>
        </nav>
    </header>

    <main class="landing-container">
        <div class="about-layout-wrapper">
            
            <div class="about-visual-column">
                <div class="abstract-accent-badge">
                    <svg viewBox="0 0 100 100">
                        <circle cx="50" cy="50" r="40" stroke="currentColor" stroke-width="1.5" stroke-dasharray="6 4" fill="none"/>
                        <path d="M50 10 L50 90 M10 50 L90 50 M22 22 L78 78 M22 78 L78 22" stroke="currentColor" stroke-width="1.5"/>
                    </svg>
                </div>
                
                <div class="about-portrait-frame">
                    <img src="https://images.unsplash.com/photo-1442512595331-e89e73853f31?auto=format&fit=crop&q=80&w=800" alt="Barista working at counter">
                </div>

                <div class="about-footer-icons">
                    <a href="#"><svg viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg></a>
                    <a href="#"><svg viewBox="0 0 24 24"><path d="M12 2c5.52 0 10 4.48 10 10s-4.48 10-10 10S2 17.52 2 12 6.48 2 12 2zm1 10h3l-1-3h-2V8c0-.55.45-1 1-1h1V4h-2c-1.66 0-3 1.34-3 3v2H9v3h2v7h2v-7z"/></svg></a>
                    <a href="#"><svg viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.051.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg></a>
                </div>
            </div>

            <div class="about-text-column">
                <h1 class="about-main-title">ABOUT US</h1>
                
                <div class="about-story-content">
                    <p>Our journey started as a tiny, passionate roasting operation tucked away in downtown. Aiming to help coffee lovers look past commercial standard blends to explore the true, vibrant complexities hidden inside small-batch farm outputs, it soon became obvious that we could build something communal—a space built to look beyond basic counters to establish real human connections from the get-go.</p>
                    
                    <p>Currently, we offer micro-lot bean sourcing, sensory training sessions, and curated spatial environments in order to help our regular guests discover their perfect daily cup as seamlessly and transparently as possible. We value our growers and our community above everything else, meaning that we won't take compromises as an answer when roasting or brewing.</p>
                </div>

                <div class="about-bottom-divider"></div>
            </div>

        </div>
    </main>

</body>
</html>