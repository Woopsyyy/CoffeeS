<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CAFE ESPRESSO - Selection Menu</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@500;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* Shared Core Variable Alignment */
       * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

:root {
    --bg-main: #0f1e1a;            /* Dark forest green background from landing page */
    --slate-menu-bg: #232731;      /* Dark slate background matching your menu display template */
    --text-light: #edf2f0;         /* Off-white typography */
    --text-muted: #a3b3ac;         /* Soft green-gray descriptions */
    --muted-gray: #a2a8b5;         /* Soft slate-gray variant for control settings */
    --accent-cream: #e5dfd9;       /* Cream accent element framing colors */
    --accent-brown: #9c663b;       /* Main coffee brown canvas highlights */
    --accent-tan: #b07d4f;          /* Tan button backgrounds & menu links */
    --price-yellow: #dca842;       /* Golden yellow accent for item price text */
    --line-color: rgba(229, 223, 217, 0.15); 
}

/* Full Screen Viewport Setup */
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
   2. HEADER & INTEGRATED NAVIGATION BAR
   ========================================================================== */
header {
    width: 100%;
    position: absolute;
    top: 0;
    left: 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 25px 8%;
    z-index: 1000;
    border-bottom: 1px solid var(--line-color);
    background-color: rgba(15, 30, 26, 0.85);
    backdrop-filter: blur(10px);
}

.media-cluster {
    display: flex;
    gap: 12px;
}

.media-cluster a {
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

.media-cluster a:hover {
    background-color: #e3b27d;
    transform: translateY(-2px);
}

.menu-directory {
    display: flex;
    gap: 45px;
}

.menu-directory a {
    color: var(--accent-cream);
    text-decoration: none;
    font-size: 15px;
    font-weight: 400;
    letter-spacing: 0.5px;
    transition: color 0.3s ease;
}

.menu-directory a:hover, 
.menu-directory a.active {
    color: var(--accent-tan);
}

/* ==========================================================================
   3. HERO BANNER BACKGROUND ELEMENT
   ========================================================================== */
.menu-banner-hero {
    width: 100%;
    height: 320px;
    background-image: url('https://images.unsplash.com/photo-1506372023823-741c83b836fe?q=80&w=1920');
    background-size: cover;
    background-position: center;
    margin-top: 80px;
}

/* ==========================================================================
   4. MAIN SLATE WORKING DISPLAY CANVAS & HEADINGS
   ========================================================================== */
.menu-slate-canvas {
    background-color: var(--slate-menu-bg);
    width: 100%;
    padding: 60px 8% 100px 8%;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.menu-main-title {
    font-family: 'Oswald', sans-serif;
    font-size: 4.5rem;
    font-weight: 700;
    color: #fff;
    letter-spacing: 4px;
    text-transform: uppercase;
    margin-bottom: 60px;
    text-align: center;
}

/* ==========================================================================
   5. BEVERAGE MATRIX GRID LAYOUT & PRODUCT INTERFACES
   ========================================================================== */
.beverage-matrix-grid {
    max-width: 1100px;
    width: 100%;
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    column-gap: 40px;
    row-gap: 65px;
}

/* Individual Beverage Product Cards */
.glass-mug-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    background: rgba(255, 255, 255, 0.02);
    padding: 30px 20px;
    border-radius: 20px;
    border: 1px solid rgba(255, 255, 255, 0.05);
    transition: transform 0.3s ease, border-color 0.3s ease;
}

.glass-mug-card:hover {
    transform: translateY(-5px);
    border-color: rgba(176, 125, 79, 0.3);
}

/* Accurate Realistic Round Image Formatting Wrapper */
.mug-real-frame {
    width: 140px;
    height: 140px;
    border-radius: 50%;
    overflow: hidden;
    border: 4px solid rgba(255, 255, 255, 0.08);
    margin-bottom: 20px;
    transition: all 0.4s ease;
    box-shadow: 0 10px 20px rgba(0,0,0,0.3);
}

.glass-mug-card:hover .mug-real-frame {
    transform: scale(1.05);
    border-color: var(--accent-tan);
    box-shadow: 0 12px 25px rgba(0,0,0,0.5);
}

.mug-real-frame img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.glass-mug-card h3 {
    font-family: 'Oswald', sans-serif;
    font-size: 1.4rem;
    font-weight: 500;
    letter-spacing: 1.5px;
    color: #fff;
    text-transform: uppercase;
    margin-bottom: 8px;
}

.glass-mug-card p {
    font-size: 0.82rem;
    color: var(--text-muted);
    line-height: 1.5;
    max-width: 220px;
    min-height: 38px; 
    margin-bottom: 15px;
}

.glass-mug-card .item-cost-tag {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--price-yellow);
    margin-bottom: 15px;
}

/* ==========================================================================
   6. CUSTOM SUGAR CONTROL SELECTIONS & PRODUCT CTA ACTIONS
   ========================================================================== */
.sugar-control-pod {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 20px;
    background: rgba(0, 0, 0, 0.2);
    padding: 6px 12px;
    border-radius: 12px;
    border: 1px solid rgba(255, 255, 255, 0.05);
}

.sugar-control-pod label {
    font-size: 11px;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.sugar-control-pod select {
    background: transparent;
    color: #fff;
    border: none;
    font-size: 12px;
    font-family: 'Poppins', sans-serif;
    cursor: pointer;
    outline: none;
    font-weight: 500;
}

.sugar-control-pod select option {
    background-color: var(--slate-menu-bg);
    color: #fff;
}

/* Integrated Order Action Trigger Element */
.order-action-btn {
    display: inline-block;
    background-color: var(--accent-brown);
    color: #fff;
    padding: 10px 24px;
    font-size: 12px;
    font-weight: 600;
    letter-spacing: 1px;
    text-decoration: none;
    border-radius: 20px;
    border: none;
    cursor: pointer;
    font-family: 'Poppins', sans-serif;
    text-transform: uppercase;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.order-action-btn:hover {
    background-color: var(--accent-tan);
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.35);
}

/* ==========================================================================
   7. FOOTER BRAND LAYOUT LINES
   ========================================================================== */
.bottom-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
    padding: 30px 60px;
    background-color: var(--bg-main);
}

.footer-split-wire {
    flex-grow: 1;
    height: 1px;
    background-color: var(--line-color);
}

.footer-identity-center {
    text-align: center;
    padding: 0 40px;
}

.footer-brand-title {
    font-family: 'Playfair Display', serif;
    font-size: 26px;
    font-weight: 600;
    line-height: 1;
    letter-spacing: 0.5px;
}

.footer-brand-subtitle {
    font-size: 12px;
    color: var(--text-muted);
    letter-spacing: 2px;
    margin-top: 4px;
}

/* ==========================================================================
   8. ADAPTIVE RESPONSIVE MEDIA BREAKPOINTS
   ========================================================================== */
@media (max-width: 900px) {
    .beverage-matrix-grid {
        grid-template-columns: repeat(2, 1fr);
        row-gap: 40px;
        column-gap: 20px;
    }
    .menu-main-title {
        font-size: 3.5rem;
    }
    .menu-directory {
        gap: 25px;
    }
}

@media (max-width: 580px) {
    .menu-directory {
        display: none; /* Safely collapses layout on very narrow viewports */
    }
    header {
        justify-content: center;
    }
    .beverage-matrix-grid {
        grid-template-columns: 1fr;
        row-gap: 35px;
    }
    .menu-main-title {
        font-size: 3rem;
    }
}
    </style>
</head>
<body>

    <header>
        <div class="media-cluster">
            <a href="#"><i class="fab fa-facebook-f"></i></a>
            <a href="#"><i class="fab fa-instagram"></i></a>
            <a href="#"><i class="fab fa-twitter"></i></a>
        </div>
        <nav class="menu-directory">
            <a href="index.php">Home</a>
            <a href="menu.php" class="active">Menu</a>
            <a href="blog.php">Blog</a>           
            <a href="About.php">About us</a>
                <a href="#" class="sign-in">Sign In</a>
        </nav>
    </header>

    <div class="menu-banner-hero"></div>

    <main class="menu-slate-canvas">
        <h1 class="menu-main-title">Menu</h1>
        
        <div class="beverage-matrix-grid">
            
            <div class="glass-mug-card">
                <div class="mug-real-frame">
                    <img src="https://images.unsplash.com/photo-1510705253260-8046a11d729d?q=80&w=400" alt="Espresso Shot">
                </div>
                <h3>Espresso</h3>
                <p>1 shot of concentrated premium espresso</p>
                <span class="item-cost-tag">₱100.00</span>
                <div class="sugar-control-pod">
                    <label>Sugar:</label>
                    <select>
                        <option value="0">0% (None)</option>
                        <option value="25">25% (Low)</option>
                        <option value="50">50% (Less)</option>
                        <option value="100" selected>100% (Normal)</option>
                    </select>
                </div>
                <button class="order-action-btn">Order Now</button>
            </div>

            <div class="glass-mug-card">
                <div class="mug-real-frame">
                    <img src="https://images.unsplash.com/photo-1551046713-2d20d7be7309?q=80&w=400" alt="Americano Coffee">
                </div>
                <h3>Americano</h3>
                <p>1 shot of espresso poured over 3 oz. of hot water</p>
                <span class="item-cost-tag">₱120.00</span>
                <div class="sugar-control-pod">
                    <label>Sugar:</label>
                    <select>
                        <option value="0">0% (None)</option>
                        <option value="25">25% (Low)</option>
                        <option value="50">50% (Less)</option>
                        <option value="100" selected>100% (Normal)</option>
                    </select>
                </div>
                <button class="order-action-btn">Order Now</button>
            </div>

            <div class="glass-mug-card">
                <div class="mug-real-frame">
                    <img src="https://images.unsplash.com/photo-1592318780016-5bc77b94dbba?q=80&w=400" alt="Affogato Dessert Coffee">
                </div>
                <h3>Affogato</h3>
                <p>1-2 shots of espresso mixed over 1 scoop of vanilla ice cream</p>
                <span class="item-cost-tag">₱160.00</span>
                <div class="sugar-control-pod">
                    <label>Sugar:</label>
                    <select>
                        <option value="0">0% (None)</option>
                        <option value="25">25% (Low)</option>
                        <option value="50">50% (Less)</option>
                        <option value="100" selected>100% (Normal)</option>
                    </select>
                </div>
                <button class="order-action-btn">Order Now</button>
            </div>

            <div class="glass-mug-card">
                <div class="mug-real-frame">
                    <img src="https://images.unsplash.com/photo-1610889556528-9a770e32642f?q=80&w=400" alt="Double Espresso">
                </div>
                <h3>Double Espresso</h3>
                <p>2 full shots of rich espresso extract</p>
                <span class="item-cost-tag">₱140.00</span>
                <div class="sugar-control-pod">
                    <label>Sugar:</label>
                    <select>
                        <option value="0">0% (None)</option>
                        <option value="25">25% (Low)</option>
                        <option value="50">50% (Less)</option>
                        <option value="100" selected>100% (Normal)</option>
                    </select>
                </div>
                <button class="order-action-btn">Order Now</button>
            </div>

            <div class="glass-mug-card">
                <div class="mug-real-frame">
                    <img src="https://images.unsplash.com/photo-1534778101976-62847782c213?q=80&w=400" alt="Cappuccino">
                </div>
                <h3>Cappuccino</h3>
                <p>1-2 shots of espresso with 2 oz. of thick steamed milk froth</p>
                <span class="item-cost-tag">₱150.00</span>
                <div class="sugar-control-pod">
                    <label>Sugar:</label>
                    <select>
                        <option value="0">0% (None)</option>
                        <option value="25">25% (Low)</option>
                        <option value="50">50% (Less)</option>
                        <option value="100" selected>100% (Normal)</option>
                    </select>
                </div>
                <button class="order-action-btn">Order Now</button>
            </div>

            <div class="glass-mug-card">
                <div class="mug-real-frame">
                    <img src="https://images.unsplash.com/photo-1572490122747-3968b75cc699?q=80&w=400" alt="Blended Frappe">
                </div>
                <h3>Frappe</h3>
                <p>1-2 shots of blended espresso topped with 2 oz. of fresh cold milk</p>
                <span class="item-cost-tag">₱165.00</span>
                <div class="sugar-control-pod">
                    <label>Sugar:</label>
                    <select>
                        <option value="0">0% (None)</option>
                        <option value="25">25% (Low)</option>
                        <option value="50">50% (Less)</option>
                        <option value="100" selected>100% (Normal)</option>
                    </select>
                </div>
                <button class="order-action-btn">Order Now</button>
            </div>

            <div class="glass-mug-card">
                <div class="mug-real-frame">
                    <img src="https://images.unsplash.com/photo-1485808191679-5f86510681a2?q=80&w=400" alt="Espresso Macchiato">
                </div>
                <h3>Macchiato</h3>
                <p>1 shot of espresso with 1 to 2 teaspoons of textured milk</p>
                <span class="item-cost-tag">₱135.00</span>
                <div class="sugar-control-pod">
                    <label>Sugar:</label>
                    <select>
                        <option value="0">0% (None)</option>
                        <option value="25">25% (Low)</option>
                        <option value="50">50% (Less)</option>
                        <option value="100" selected>100% (Normal)</option>
                    </select>
                </div>
                <button class="order-action-btn">Order Now</button>
            </div>

            <div class="glass-mug-card">
                <div class="mug-real-frame">
                    <img src="https://images.unsplash.com/photo-1541167760496-1628856ab772?q=80&w=400" alt="Cafe Latte Art">
                </div>
                <h3>Cafe Latte</h3>
                <p>1 shot of espresso filled with 8-10 oz. of velvety hot milk</p>
                <span class="item-cost-tag">₱150.00</span>
                <div class="sugar-control-pod">
                    <label>Sugar:</label>
                    <select>
                        <option value="0">0% (None)</option>
                        <option value="25">25% (Low)</option>
                        <option value="50">50% (Less)</option>
                        <option value="100" selected>100% (Normal)</option>
                    </select>
                </div>
                <button class="order-action-btn">Order Now</button>
            </div>

            <div class="glass-mug-card">
                <div class="mug-real-frame">
                    <img src="https://images.unsplash.com/photo-1607687325211-ac62326303af?q=80&w=400" alt="Caffe Mocha">
                </div>
                <h3>Mocha</h3>
                <p>1 shot of espresso layered with 1-2 oz. of decadent chocolate sauce</p>
                <span class="item-cost-tag">₱155.00</span>
                <div class="sugar-control-pod">
                    <label>Sugar:</label>
                    <select>
                        <option value="0">0% (None)</option>
                        <option value="25">25% (Low)</option>
                        <option value="50">50% (Less)</option>
                        <option value="100" selected>100% (Normal)</option>
                    </select>
                </div>
                <button class="order-action-btn">Order Now</button>
            </div>

        </div>
    </main>

    <footer class="bottom-footer">
        <div class="footer-split-wire"></div>
        <div class="footer-identity-center">
            <div class="footer-brand-title">CAFE ESPRESSO</div>
            <div class="footer-brand-subtitle">EST. 2024</div>
        </div>
        <div class="footer-split-wire"></div>
    </footer>

</body>
</html>