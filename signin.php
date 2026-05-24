<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coffee Shop - Sign In</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght=0,400;0,600;0,700;1,400&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
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
    background-color: var(--bg-main); 
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
   --- 3. Main Container Workspace ---
   ========================================================================== */
.landing-container {
    width: 100%;
    min-height: calc(100vh - 87px); 
    padding: 60px;
    display: flex;
    justify-content: center;
    align-items: center; /* Centers the login module inside the page viewport */
}

/* Split-Panel Window Card Container */
.auth-card-panel {
    width: 100%;
    max-width: 940px;
    min-height: 540px;
    background-color: #ffffff; /* Clean white canvas background for the form side */
    border-radius: 20px;
    overflow: hidden;
    display: grid;
    grid-template-columns: 1fr 1fr; /* Exact symmetrical split column framework */
    box-shadow: 0 25px 55px rgba(0, 0, 0, 0.45);
}

/* ==========================================================================
   --- 4. Left Side Entry Fields Structure ---
   ========================================================================== */
.auth-form-column {
    padding: 50px 60px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-align: center;
}

.auth-title {
    font-size: 32px;
    font-weight: 600;
    color: #1a1a1a;
    margin-bottom: 35px;
}

.auth-form {
    width: 100%;
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.input-field-group {
    width: 100%;
}

.input-field-group input {
    width: 100%;
    padding: 15px 20px;
    border: none;
    background-color: #f0f4f2; /* Soft grey background tint mimicking image */
    border-radius: 8px;
    font-family: 'Poppins', sans-serif;
    font-size: 14px;
    color: #333333;
    outline: none;
    transition: all 0.25s ease;
}

.input-field-group input:focus {
    background-color: #e6ece9;
    box-shadow: inset 0 0 0 2px rgba(15, 30, 26, 0.15);
}

/* Button style adapted from the green element in the reference */
.auth-submit-btn {
    width: 100%;
    padding: 14px;
    border: none;
    background-color: #0c805c; /* Deep emerald button core */
    color: #ffffff;
    font-family: 'Poppins', sans-serif;
    font-size: 15px;
    font-weight: 500;
    border-radius: 8px;
    cursor: pointer;
    margin-top: 10px;
    transition: background-color 0.25s ease, transform 0.15s ease;
}

.auth-submit-btn:hover {
    background-color: #096347;
}

.auth-submit-btn:active {
    transform: scale(0.99);
}

/* Divider Lines */
.social-divider {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 25px 0;
}

.social-divider::before,
.social-divider::after {
    content: "";
    flex: 1;
    height: 1px;
    background-color: #e0e0e0;
}

.social-divider span {
    padding: 0 15px;
    font-size: 13px;
    color: #8c8c8c;
}

/* Circular Social Links Row */
.social-auth-row {
    display: flex;
    gap: 16px;
    justify-content: center;
}

.social-auth-circle {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    text-decoration: none;
    transition: transform 0.2s ease, opacity 0.2s ease;
    box-shadow: 0 3px 8px rgba(0,0,0,0.08);
}

.social-auth-circle svg {
    width: 18px;
    height: 18px;
    fill: #ffffff;
}

.social-auth-circle:hover {
    transform: translateY(-2px);
    opacity: 0.9;
}

/* Reference Exact Brand Color Variables */
.facebook-blue { background-color: #3b5998; }
.google-red   { background-color: #ea4335; }
.linkedin-blue { background-color: #0077b5; }


/* ==========================================================================
   --- 5. Right Side Welcome Banner Structure ---
   ========================================================================== */
.auth-branding-column {
    background: linear-gradient(135deg, #0f1e1a 0%, #17302a 100%); /* Replaces bright green gradients with your branding colors */
    padding: 50px 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
}

/* Subtle decorative circles embedded under the background layer */
.auth-branding-column::before {
    content: '';
    position: absolute;
    width: 300px;
    height: 300px;
    border-radius: 50%;
    background: rgba(176, 125, 79, 0.04);
    top: -50px;
    right: -50px;
}

.branding-overlay-content {
    text-align: center;
    max-width: 360px;
    z-index: 2;
}

.branding-heading {
    font-family: 'Playfair Display', serif;
    font-size: 34px;
    font-weight: 700;
    color: #ffffff;
    margin-bottom: 20px;
}

.branding-paragraph {
    font-size: 14px;
    color: var(--text-muted);
    line-height: 1.65;
    margin-bottom: 35px;
    font-weight: 300;
}

/* Pill-shaped transparent action link matching reference */
.auth-toggle-action-btn {
    display: inline-block;
    padding: 12px 30px;
    border: 1px solid rgba(255, 255, 255, 0.25);
    color: #ffffff;
    text-decoration: none;
    font-size: 13px;
    font-weight: 400;
    border-radius: 25px;
    background-color: rgba(255, 255, 255, 0.03);
    transition: all 0.3s ease;
}

.auth-toggle-action-btn:hover {
    background-color: #ffffff;
    color: var(--bg-main);
    border-color: #ffffff;
    transform: translateY(-1px);
}


/* ==========================================================================
   --- 6. Responsive Adaptation Viewports ---
   ========================================================================== */
@media (max-width: 992px) {
    .header-sticky-wrapper {
        padding: 30px 30px 0 30px;
    }

    .landing-container {
        padding: 40px 30px;
    }

    .auth-card-panel {
        max-width: 500px;
        grid-template-columns: 1fr; /* Collapses split view to single main track stack */
        min-height: auto;
    }

    .auth-branding-column {
        padding: 45px 40px;
    }
    
    .auth-form-column {
        padding: 45px 40px;
    }
}

@media (max-width: 580px) {
    .nav-links {
        display: none; /* Safely contracts primary desktop option groups on smaller screens */
    }

    .navbar {
        justify-content: center;
    }

    .header-sticky-wrapper {
        padding: 20px 20px 0 20px;
    }

    .landing-container {
        padding: 20px 15px;
    }
    
    .auth-card-panel {
        border-radius: 14px;
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
                <a href="about.php">About us</a>
                <a href="signin.php" class="active">Sign In</a>
            </div>
        </nav>
    </header>

    <main class="landing-container">
        <div class="auth-card-panel">
            
            <div class="auth-form-column">
                <h2 class="auth-title">Sign in</h2>
                
                <form class="auth-form" action="#" method="POST">
                    <div class="input-field-group">
                        <input type="text" placeholder="Username" required>
                    </div>
                    
                    <div class="input-field-group">
                        <input type="password" placeholder="Password" required>
                    </div>
                    
                    <button type="submit" class="auth-submit-btn">Signin</button>
                </form>

                <div class="social-divider">
                    <span>or signin with</span>
                </div>

                <div class="social-auth-row">
                    <a href="#" class="social-auth-circle facebook-blue">
                        <svg viewBox="0 0 24 24"><path d="M22 12c0-5.52-4.48-10-10-10S2 6.48 2 12c0 4.84 3.44 8.87 8 9.8V15H8v-3h2V9.5C10 7.57 11.57 6 13.5 6H16v3h-2c-.55 0-1 .45-1 1v2h3v3h-3v6.95c4.56-.93 8-4.96 8-9.75z"/></svg>
                    </a>
                    <a href="#" class="social-auth-circle google-red">
                        <svg viewBox="0 0 24 24"><path d="M12.24 10.285V13.4h6.887c-.275 1.565-1.88 4.604-6.887 4.604-4.33 0-7.866-3.577-7.866-8s3.536-8 7.866-8c2.46 0 4.105 1.025 5.047 1.926l2.427-2.334C17.955 2.192 15.34 1 12.24 1 6.033 1 1 6.033 1 12.24s5.033 11.24 11.24 11.24c6.478 0 10.793-4.537 10.793-10.986 0-.746-.08-1.32-.176-1.884H12.24z"/></svg>
                    </a>
                    <a href="#" class="social-auth-circle linkedin-blue">
                        <svg viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                    </a>
                </div>
            </div>

            <div class="auth-branding-column">
                <div class="branding-overlay-content">
                    <h3 class="branding-heading">Welcome back!</h3>
                    <p class="branding-paragraph">Welcome back! We are so happy to have you here. It's great to see you again. We hope you had a safe and enjoyable time away.</p>
                    
                    <a href="#" class="auth-toggle-action-btn">No account yet? Signup.</a>
                </div>
            </div>

        </div>
    </main>

</body>
</html>