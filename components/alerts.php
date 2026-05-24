<?php
/**
 * Cafe Espresso - Reusable Flash Alert Banners Component
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!-- Flash Alert Banners Container -->
<div class="alert-container">
    <?php if ($successMsg = getFlashMessage('success')): ?>
        <div class="alert-toast alert-success">
            <i class="fa-solid fa-circle-check"></i>
            <div class="alert-toast-content"><?php echo sanitize($successMsg); ?></div>
            <button class="alert-toast-close"><i class="fa-solid fa-xmark"></i></button>
        </div>
    <?php endif; ?>

    <?php if ($errorMsg = getFlashMessage('error')): ?>
        <div class="alert-toast alert-error">
            <i class="fa-solid fa-circle-xmark"></i>
            <div class="alert-toast-content"><?php echo sanitize($errorMsg); ?></div>
            <button class="alert-toast-close"><i class="fa-solid fa-xmark"></i></button>
        </div>
    <?php endif; ?>

    <?php if ($infoMsg = getFlashMessage('info')): ?>
        <div class="alert-toast alert-info">
            <i class="fa-solid fa-circle-info"></i>
            <div class="alert-toast-content"><?php echo sanitize($infoMsg); ?></div>
            <button class="alert-toast-close"><i class="fa-solid fa-xmark"></i></button>
        </div>
    <?php endif; ?>
</div>
