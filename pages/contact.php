<?php
/**
 * Cafe Espresso - Contact Us Page
 */
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/helpers.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cafe Espresso - Visit Our Roastery</title>
    
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
        <!-- Page headings -->
        <div class="text-center mb-5">
            <span class="section-pretitle">Get In Touch</span>
            <h1 class="text-huge font-semibold color-coffee mb-1">Visit Our Roastery</h1>
            <p class="color-muted text-base font-light" style="max-width: 600px; margin: 0 auto;">
                Drop by for a manual brew extraction or write us a note about catering, training, or wholesale bean queries.
            </p>
        </div>

        <div class="cart-split-layout" style="grid-template-columns: 1fr 1fr; gap: 40px;">
            <!-- Left Side: Operations Metas & Mock Google Map Card -->
            <div style="background:#FFF; padding:30px; border-radius:var(--border-radius-md); border:1px solid rgba(44,26,17,0.05); box-shadow:var(--shadow-sm); display:flex; flex-direction:column; justify-content:space-between;">
                <div>
                    <h3 class="color-coffee mb-3" style="border-bottom:2px solid rgba(44,26,17,0.05); padding-bottom:8px;">Roastery Contacts</h3>
                    
                    <div style="display:flex; flex-direction:column; gap:16px;">
                        <div class="footer-contact-item" style="color:var(--text-dark); font-size:0.95rem;">
                            <i class="fa-solid fa-location-dot" style="font-size:1.1rem;"></i>
                            <span>123 Brew Street, Espresso District, Manila, PH</span>
                        </div>
                        <div class="footer-contact-item" style="color:var(--text-dark); font-size:0.95rem;">
                            <i class="fa-solid fa-phone" style="font-size:1.1rem;"></i>
                            <span>+63 912 3456 789</span>
                        </div>
                        <div class="footer-contact-item" style="color:var(--text-dark); font-size:0.95rem;">
                            <i class="fa-solid fa-envelope" style="font-size:1.1rem;"></i>
                            <span>hello@cafeespresso.com &bull; catering@cafeespresso.com</span>
                        </div>
                    </div>
                </div>

                <!-- Mock Map Box -->
                <div style="margin-top:30px; border-radius:var(--border-radius-md); overflow:hidden; border:1px solid rgba(44,26,17,0.1); height:220px; position:relative; background-color:var(--cream-dark);">
                    <!-- A beautiful high-res Unsplash map backdrop for aesthetic realism! -->
                    <img src="https://images.unsplash.com/photo-1524661135-423995f22d0b?q=80&w=800" alt="Roastery Manila Map" style="width:100%; height:100%; object-fit:cover; opacity:0.85;">
                    <div style="position:absolute; inset:0; background:rgba(15,30,26,0.3); display:flex; justify-content:center; align-items:center; color:#FFF; text-align:center;">
                        <div style="background:rgba(15,30,26,0.85); padding:15px 25px; border-radius:var(--border-radius-md); box-shadow:var(--shadow-lg); backdrop-filter:blur(4px);">
                            <i class="fa-solid fa-location-crosshairs color-gold" style="font-size:1.5rem; margin-bottom:8px;"></i>
                            <h4 style="color:#FFF; font-size:1rem; margin-bottom:2px;">Manila Roastery</h4>
                            <span class="text-xs color-muted" style="color:var(--cream-dark) !important;">123 Brew Street, Manila</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Contact Mail form -->
            <div style="background:#FFF; padding:40px; border-radius:var(--border-radius-md); border:1px solid rgba(44,26,17,0.05); box-shadow:var(--shadow-sm);">
                <h3 class="color-coffee mb-3" style="border-bottom:2px solid rgba(44,26,17,0.05); padding-bottom:8px;">Write Us a Note</h3>
                
                <form action="#" method="POST" onsubmit="event.preventDefault(); showToast('success', 'Your contact message has been sent successfully!'); this.reset();">
                    
                    <!-- Sender Name -->
                    <div class="form-group">
                        <label for="form-name" class="form-label">Full Name:</label>
                        <input type="text" id="form-name" class="form-control" placeholder="Enter your name..." required>
                    </div>

                    <!-- Sender Email -->
                    <div class="form-group">
                        <label for="form-email" class="form-label">Email Address:</label>
                        <input type="email" id="form-email" class="form-control" placeholder="e.g. name@domain.com" required>
                    </div>

                    <!-- Sender Query Category -->
                    <div class="form-group">
                        <label for="form-subject" class="form-label">Purpose of Query:</label>
                        <select id="form-subject" class="form-control" style="background:#FFF; appearance:auto;" required>
                            <option value="Store Feedback">General Store Feedback</option>
                            <option value="Catering Query">Barista Catering / Events Query</option>
                            <option value="Wholesale Beans">Wholesale Organic Beans Sourcing</option>
                            <option value="Employment">Roastery Careers & Barista Jobs</option>
                        </select>
                    </div>

                    <!-- Sender Message -->
                    <div class="form-group">
                        <label for="form-message" class="form-label">Detailed Message:</label>
                        <textarea id="form-message" rows="4" class="form-control" placeholder="Type your message here..." required></textarea>
                    </div>

                    <button type="submit" class="btn btn-secondary btn-block shadow-sm">Send Message</button>
                </form>
            </div>
        </div>
    </main>

    <!-- Reusable Footer -->
    <?php include __DIR__ . '/../components/footer.php'; ?>

</body>
</html>
