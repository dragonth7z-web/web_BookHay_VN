/**
 * Combo — add to cart + wishlist toggle
 * Pattern matched with book-series.js
 */
export function initCombo() {
    window.addComboToCart = (comboId, comboName, event) => {
        event.preventDefault();
        event.stopPropagation();
        
        const btn = event.currentTarget;
        const icon = btn.querySelector('.material-symbols-outlined');
        const origIcon = icon ? icon.textContent : 'shopping_bag';

        // Feedback transition
        if (icon) icon.textContent = 'refresh';
        btn.classList.add('animate-pulse', 'bg-emerald-600');
        
        setTimeout(() => {
            if (icon) icon.textContent = 'check_circle';
            window.showToast?.(`Đã thêm combo "${comboName}" vào giỏ hàng!`, 'success');
            
            // Update cart badges
            document.querySelectorAll('.cart-count-badge').forEach(badge => {
                let count = parseInt(badge.textContent || '0', 10);
                badge.textContent = count + 1;
                badge.style.display = 'flex';
            });

            setTimeout(() => {
                if (icon) icon.textContent = origIcon;
                btn.classList.remove('animate-pulse', 'bg-emerald-600');
            }, 2000);
        }, 600);
    };

    window.toggleComboWishlist = (comboId, event) => {
        event.preventDefault();
        event.stopPropagation();
        
        const icon = event.currentTarget.querySelector('.material-symbols-outlined');
        const active = icon.textContent.trim() === 'bookmark';
        
        icon.textContent = active ? 'bookmark_border' : 'bookmark';
        icon.classList.add('scale-125');
        setTimeout(() => icon.classList.remove('scale-125'), 200);
        
        window.showToast?.(active ? 'Đã gỡ combo khỏi danh sách yêu thích' : 'Đã thêm combo vào yêu thích!', 'info');
    };
}
