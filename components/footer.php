<?php
/**
 * Cafe Espresso - Footer Component
 */
require_once __DIR__ . '/../includes/config.php';
?>
<!-- Cozy Cafe Editorial Footer -->
<footer class="footer-editorial">
    <div class="container footer-grid">
        <!-- Brand Description Block -->
        <div class="footer-column">
            <a href="<?php echo BASE_URL; ?>/pages/home.php" class="nav-brand" style="display:inline-flex;">
                <i class="fa-solid fa-mug-hot nav-brand-logo"></i>
                <span class="nav-brand-text" style="color:var(--cream-light);">Cafe Espresso</span>
            </a>
            <p class="footer-brand-p">
                Crafting cinematic coffee moments since 2024. Experience ethically sourced single-origin roasts prepared by passionate local baristas.
            </p>
            <div class="footer-socials">
                <a href="#" class="footer-social-link" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                <a href="#" class="footer-social-link" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
                <a href="#" class="footer-social-link" aria-label="Twitter"><i class="fa-brands fa-twitter"></i></a>
                <a href="#" class="footer-social-link" aria-label="WhatsApp"><i class="fa-brands fa-whatsapp"></i></a>
            </div>
        </div>

        <!-- Quick Storefront Links -->
        <div class="footer-column">
            <h4>Quick Links</h4>
            <div class="footer-links-list">
                <a href="<?php echo BASE_URL; ?>/pages/home.php">Home Story</a>
                <a href="<?php echo BASE_URL; ?>/pages/menu.php">Selection Menu</a>
                <a href="<?php echo BASE_URL; ?>/pages/blog.php">Barista Guides</a>
                <a href="<?php echo BASE_URL; ?>/pages/about.php">Our Coffee Story</a>
                <a href="<?php echo BASE_URL; ?>/pages/contact.php">Physical Cafe</a>
            </div>
        </div>

        <!-- Operating Timing Details -->
        <div class="footer-column">
            <h4>Timing & Hours</h4>
            <div class="footer-links-list" style="color:var(--text-muted); font-size:0.9rem;">
                <p><strong style="color:var(--cream-light);">Monday - Friday:</strong><br>7:00 AM - 9:00 PM</p>
                <p style="margin-top:10px;"><strong style="color:var(--cream-light);">Saturday - Sunday:</strong><br>8:00 AM - 10:00 PM</p>
            </div>
        </div>

        <!-- Physical Address Contacts & Newsletter -->
        <div class="footer-column">
            <h4>Our Roastery</h4>
            <div class="footer-contact-item">
                <i class="fa-solid fa-location-dot"></i>
                <span>123 Brew Street, Espresso District, Manila, PH</span>
            </div>
            <div class="footer-contact-item">
                <i class="fa-solid fa-phone"></i>
                <span>+63 912 3456 789</span>
            </div>
            
            <!-- Newsletter Sign up -->
            <form class="newsletter-form" onsubmit="event.preventDefault(); showToast('success', 'Thank you for subscribing to our Roastery letter!');">
                <input type="email" placeholder="Your email..." class="newsletter-input" required>
                <button type="submit" class="newsletter-btn" aria-label="Subscribe"><i class="fa-solid fa-arrow-right"></i></button>
            </form>
        </div>
    </div>

    <!-- Copyright Bar -->
    <div class="container footer-copyright-bar">
        <span>&copy; <?php echo date('Y'); ?> Cafe Espresso. All rights reserved. Cozy Luxury Cafe Design.</span>
        <span>Made with <i class="fa-solid fa-heart" style="color:var(--accent-gold);"></i> for Coffee Patrons</span>
    </div>
</footer>

<!-- 4. Global Script Core Inclusions -->
<!-- FontAwesome 6 Core -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js" crossorigin="anonymous" defer></script>
<!-- System Scripts -->
<script src="<?php echo BASE_URL; ?>/assets/js/app.js" defer></script>
<script src="<?php echo BASE_URL; ?>/assets/js/animations.js" defer></script>
