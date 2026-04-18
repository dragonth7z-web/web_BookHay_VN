/**
 * Add to Cart global helper
 * Migrated from public/js/app-core.js
 */
export function addToCart(bookId, bookTitle, event) {
    if (event) {
        event.preventDefault();
        event.stopPropagation();
        const btn = event.currentTarget;
        btn.classList.add('scale-95');
        setTimeout(() => btn.classList.remove('scale-95'), 100);
    }

    document.querySelectorAll('.cart-count-badge').forEach(badge => {
        let count = parseInt(badge.textContent || '0', 10);
        badge.textContent = count + 1;
        badge.style.display = 'flex';
        badge.classList.add('animate-bounce');
        setTimeout(() => badge.classList.remove('animate-bounce'), 1000);
    });

    if (window.showToast) {
        window.showToast(`Đã thêm "${bookTitle || 'sách'}" vào giỏ hàng!`, 'success');
    }
}

window.addToCart = addToCart;
