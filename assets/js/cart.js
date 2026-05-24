/**
 * Cafe Espresso - Storefront Shopping Cart Logic
 */

document.addEventListener('DOMContentLoaded', () => {
    // 1. Customized Options Selector Modal Handling
    const modal = document.getElementById('product-options-modal');
    const quickAddBtns = document.querySelectorAll('.product-card-btn, .quick-add-trigger');
    const closeBtn = document.querySelector('.modal-close-btn');
    
    if (modal && quickAddBtns && closeBtn) {
        quickAddBtns.forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                
                // Get product attributes from dataset
                const productId = btn.dataset.productId;
                const productName = btn.dataset.productName;
                const productPrice = btn.dataset.productPrice;
                const productImage = btn.dataset.productImage;
                
                // Setup modal contents
                document.getElementById('modal-product-id').value = productId;
                document.getElementById('modal-product-name').textContent = productName;
                document.getElementById('modal-product-price').textContent = productPrice;
                
                const img = document.getElementById('modal-product-img');
                if (img) {
                    img.src = btn.dataset.productImageFull || productImage;
                }
                
                // Open modal
                modal.classList.add('open');
            });
        });
        
        closeBtn.addEventListener('click', () => {
            modal.classList.remove('open');
        });
        
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.classList.remove('open');
            }
        });
    }

    // 2. Cart Quantity Interactive Counters (Plus/Minus buttons)
    const quantityGroups = document.querySelectorAll('.cart-qty-counter');
    quantityGroups.forEach(group => {
        const minusBtn = group.querySelector('.qty-minus');
        const plusBtn = group.querySelector('.qty-plus');
        const input = group.querySelector('.cart-qty-num');
        const itemId = group.dataset.itemId;
        
        if (minusBtn && plusBtn && input) {
            minusBtn.addEventListener('click', () => {
                let val = parseInt(input.value) || 1;
                if (val > 1) {
                    val--;
                    input.value = val;
                    updateCartQuantity(itemId, val);
                }
            });
            
            plusBtn.addEventListener('click', () => {
                let val = parseInt(input.value) || 1;
                val++;
                input.value = val;
                updateCartQuantity(itemId, val);
            });
            
            input.addEventListener('change', () => {
                let val = parseInt(input.value) || 1;
                if (val < 1) val = 1;
                input.value = val;
                updateCartQuantity(itemId, val);
            });
        }
    });

    /**
     * Submit cart quantity change using standard AJAX
     */
    function updateCartQuantity(itemId, qty) {
        // We will perform a simple form POST to pages/cart.php to let the server handle it
        // Or we can perform an AJAX POST request and update elements in real-time
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'cart.php';
        
        const actionInput = document.createElement('input');
        actionInput.type = 'hidden';
        actionInput.name = 'action';
        actionInput.value = 'update';
        
        const itemIdInput = document.createElement('input');
        itemIdInput.type = 'hidden';
        itemIdInput.name = 'item_id';
        itemIdInput.value = itemId;
        
        const qtyInput = document.createElement('input');
        qtyInput.type = 'hidden';
        qtyInput.name = 'quantity';
        qtyInput.value = qty;
        
        // Grab csrf token if exists
        const token = document.querySelector('input[name="csrf_token"]');
        if (token) {
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = 'csrf_token';
            csrfInput.value = token.value;
            form.appendChild(csrfInput);
        }
        
        form.appendChild(actionInput);
        form.appendChild(itemIdInput);
        form.appendChild(qtyInput);
        
        document.body.appendChild(form);
        form.submit();
    }
});
