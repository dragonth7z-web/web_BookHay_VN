/**
 * Book Series — add to cart + wishlist toggle
 * Migrated from inline script in home/book-series.blade.php
 */
export function initBookSeries() {
    window.addSeriesToCart = (seriesId, seriesName, event) => {
        event.preventDefault();
        event.stopPropagation();
        const btn = event.currentTarget;
        const orig = btn.innerHTML;
        btn.innerHTML = '<span class="material-symbols-outlined !text-[14px] animate-spin">refresh</span> Đang thêm...';
        btn.disabled = true;
        setTimeout(() => {
            btn.innerHTML = '<span class="material-symbols-outlined !text-[14px]">check_circle</span> Đã thêm!';
            btn.classList.add('bg-emerald-500');
            window.showToast?.(`Đã thêm bộ "${seriesName}" vào giỏ!`, 'success');
            setTimeout(() => { btn.innerHTML = orig; btn.disabled = false; btn.classList.remove('bg-emerald-500'); }, 2000);
        }, 700);
    };

    window.toggleSeriesWishlist = (seriesId, event) => {
        event.preventDefault();
        event.stopPropagation();
        const icon = event.currentTarget.querySelector('.material-symbols-outlined');
        const active = icon.textContent.trim() === 'bookmark';
        icon.textContent = active ? 'bookmark_border' : 'bookmark';
        window.showToast?.(active ? 'Đã thêm vào yêu thích!' : 'Đã xóa khỏi yêu thích!', 'info');
    };
}
