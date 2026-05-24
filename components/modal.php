<?php
/**
 * Cafe Espresso - Reusable Product Custom Options Modal
 */
require_once __DIR__ . '/../includes/session.php';
?>
<!-- Customized Options Selector Modal Overlay -->
<div id="product-options-modal" class="modal-overlay">
    <div class="modal-card">
        <!-- Close Button -->
        <button class="modal-close-btn" aria-label="Close Options">&times;</button>
        
        <form id="cart-add-form" action="<?php echo BASE_URL; ?>/pages/cart.php" method="POST">
            <!-- Hidden inputs to identify selection -->
            <?php csrfField(); ?>
            <input type="hidden" name="action" value="add">
            <input type="hidden" name="product_id" id="modal-product-id" value="">

            <div class="flex flex-row" style="min-height:300px; flex-wrap:nowrap;">
                <!-- Left visuals side -->
                <div style="flex:1; max-width:260px; overflow:hidden; border-right:1px solid rgba(44, 26, 17, 0.08); background-color:var(--cream-dark); position:relative;">
                    <img id="modal-product-img" src="" alt="Selected Product" style="width:100%; height:100%; object-fit:cover;">
                </div>

                <!-- Right selection side -->
                <div style="flex:1.2; padding:30px; display:flex; flex-direction:column; justify-content:space-between; text-align:left;">
                    <div>
                        <span style="color:var(--accent-gold); font-size:0.75rem; font-weight:600; text-transform:uppercase; letter-spacing:1px;">Customize Selection</span>
                        <h3 id="modal-product-name" style="font-size:1.6rem; color:var(--coffee-dark); margin-top:4px; margin-bottom:8px;">Espresso</h3>
                        <span id="modal-product-price" style="font-size:1.25rem; font-weight:700; color:var(--coffee-dark);">₱100.00</span>
                    </div>

                    <!-- Options selections -->
                    <div style="margin-top:20px; margin-bottom:20px;">
                        <span class="form-label">Select Sugar Level:</span>
                        <div class="sugar-picker">
                            <!-- 0% (None) -->
                            <div class="sugar-option">
                                <input type="radio" id="sugar-0" name="sugar_level" value="0% (None)">
                                <label for="sugar-0" class="sugar-option-label">
                                    <strong>0%</strong>
                                    <span>None</span>
                                </label>
                            </div>
                            <!-- 25% (Low) -->
                            <div class="sugar-option">
                                <input type="radio" id="sugar-25" name="sugar_level" value="25% (Low)">
                                <label for="sugar-25" class="sugar-option-label">
                                    <strong>25%</strong>
                                    <span>Low</span>
                                </label>
                            </div>
                            <!-- 50% (Less) -->
                            <div class="sugar-option">
                                <input type="radio" id="sugar-50" name="sugar_level" value="50% (Less)">
                                <label for="sugar-50" class="sugar-option-label">
                                    <strong>50%</strong>
                                    <span>Less</span>
                                </label>
                            </div>
                            <!-- 100% (Normal) -->
                            <div class="sugar-option">
                                <input type="radio" id="sugar-100" name="sugar_level" value="100% (Normal)" checked>
                                <label for="sugar-100" class="sugar-option-label">
                                    <strong>100%</strong>
                                    <span>Normal</span>
                                </label>
                            </div>
                        </div>

                        <!-- Quantity Selector -->
                        <div class="form-group" style="margin-bottom:0;">
                            <label for="modal-quantity" class="form-label">Quantity:</label>
                            <div class="cart-qty-counter" style="background:#FFF;">
                                <button type="button" class="cart-qty-btn" onclick="let input = document.getElementById('modal-quantity'); let val=parseInt(input.value)||1; if(val>1) input.value=val-1;"><i class="fa-solid fa-minus"></i></button>
                                <input type="number" id="modal-quantity" name="quantity" class="cart-qty-num" value="1" min="1" max="50">
                                <button type="button" class="cart-qty-btn" onclick="let input = document.getElementById('modal-quantity'); let val=parseInt(input.value)||1; input.value=val+1;"><i class="fa-solid fa-plus"></i></button>
                            </div>
                        </div>
                    </div>

                    <!-- Action Trigger -->
                    <div>
                        <button type="submit" class="btn btn-primary btn-block">Add to Bag</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- Custom Scripts linked -->
<script src="<?php echo BASE_URL; ?>/assets/js/cart.js" defer></script>
