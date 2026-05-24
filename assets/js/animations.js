/**
 * Cafe Espresso - Visual Micro-Animations & Scroll Effects
 */

document.addEventListener('DOMContentLoaded', () => {
    // 1. Scroll-Reveal Animation Logic (Fade and Slide In)
    const revealElements = document.querySelectorAll('.product-card, .story-img-card, .story-content, .hero-text-block, .hero-frame-wrapper, .section-title-wrapper, .testimonial-card');
    
    // Set initial structural transitions
    revealElements.forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(25px)';
        el.style.transition = 'opacity 0.8s cubic-bezier(0.25, 1, 0.5, 1), transform 0.8s cubic-bezier(0.25, 1, 0.5, 1)';
    });

    const revealOnScroll = () => {
        revealElements.forEach(el => {
            const elementTop = el.getBoundingClientRect().top;
            const windowHeight = window.innerHeight;
            
            if (elementTop < windowHeight - 50) {
                el.style.opacity = '1';
                el.style.transform = 'translateY(0)';
            }
        });
    };

    window.addEventListener('scroll', revealOnScroll);
    // Trigger once on load to reveal elements currently inside viewports
    setTimeout(revealOnScroll, 100);

    // 2. Micro-interactions for buttons (Hover scales)
    const hoverScaleElements = document.querySelectorAll('.btn-primary, .btn-secondary, .product-card-fav, .footer-social-link');
    
    hoverScaleElements.forEach(el => {
        el.addEventListener('mouseenter', () => {
            el.style.transform = el.classList.contains('footer-social-link') ? 'translateY(-3px) scale(1.05)' : 'translateY(-2px) scale(1.02)';
        });
        
        el.addEventListener('mouseleave', () => {
            el.style.transform = 'translateY(0) scale(1)';
        });
    });
});
