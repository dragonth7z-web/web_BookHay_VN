/**
 * Recently Viewed — localStorage tracking + render
 * Migrated from public/js/home/recently-viewed.js
 */
export function initRecentlyViewed() {
    window.trackView = (bookId, title, cover, price, slug) => {
        let viewed = JSON.parse(localStorage.getItem('recently_viewed') || '[]');
        viewed = viewed.filter(i => i.id !== bookId);
        viewed.unshift({ id: bookId, title, cover, price, slug, date: Date.now() });
        if (viewed.length > 6) viewed.pop();
        localStorage.setItem('recently_viewed', JSON.stringify(viewed));
        render();
    };

    window.clearRecentlyViewed = () => { localStorage.removeItem('recently_viewed'); render(); };

    function render() {
        const viewed = JSON.parse(localStorage.getItem('recently_viewed') || '[]');
        const section = document.getElementById('recently-viewed-section');
        const list = document.getElementById('rv-list');
        if (!section) return;
        if (!viewed.length) { section.classList.add('hidden'); return; }
        section.classList.remove('hidden');
        list.innerHTML = viewed.map(b => {
             const title = String(b.title || '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
             const slug = b.slug || '#';
             const imgUrl = b.cover || '';
             const id = b.id || 0;
             const currentPrice = b.price || 0;
             const originalPrice = b.original_price || 0;
             const hasDiscount = originalPrice > currentPrice;
             let discountPct = 0;
             if (hasDiscount && originalPrice > 0) {
                 discountPct = Math.round(((originalPrice - currentPrice) / originalPrice) * 100);
             }
             
             const rating = b.rating_avg || 5;
             const ratingCount = b.rating_count || (id % 10);
             let soldCount = b.sold_count || 0;
             if (soldCount <= 0) soldCount = Math.floor(Math.random() * 34) + 12 + ((id % 15) + 10);
             
             const interest = soldCount ? Math.floor(soldCount * 2.5) + (id % 20) : '';
             const formattedInterest = interest >= 1000 ? (interest / 1000).toFixed(1) + 'k' : interest;

             return `
<div class="product-card-container h-full active-feedback group/card" data-book-id="${id}">
    <div class="relative rounded-[6px] overflow-hidden bg-white dark:bg-slate-800 border border-slate-100/80 dark:border-white/[0.06] shadow-sm hover:shadow-[var(--shadow-book-hover)] hover:-translate-y-2 hover:rotate-[-1.5deg] transition-all duration-500 cursor-pointer flex flex-col h-full">

        <div class="card-image-wrap aspect-square bg-gray-50 dark:bg-slate-800/80 overflow-hidden flex items-center justify-center relative p-2">
            <a href="/bookstore/${slug}" class="block w-full h-full text-center flex items-center justify-center relative z-10" onclick="if(typeof trackView === 'function') trackView(${id}, '${title.replace(/'/g, "\\'")}', '${imgUrl}', ${currentPrice}, '${slug}')">
                <img src="${imgUrl}" alt="${title}" loading="lazy" class="max-w-full max-h-full object-contain transition-transform duration-1000 group-hover/card:scale-110 drop-shadow-sm mix-blend-multiply dark:mix-blend-normal" onerror="this.src='https://placehold.co/400x400?text=No+Image'">
            </a>
            
            <div class="absolute inset-0 bg-gradient-to-tr from-white/20 via-transparent to-transparent opacity-0 group-hover/card:opacity-100 transition-opacity duration-700 pointer-events-none z-10"></div>
            <div class="absolute top-0 -left-[100%] w-1/2 h-full bg-gradient-to-r from-transparent via-white/10 to-transparent -skew-x-12 group-hover/card:animate-shimmer-slide z-20"></div>
            
            <div class="absolute top-3 right-3 bg-white/90 dark:bg-slate-900/90 backdrop-blur-md text-slate-500 dark:text-slate-400 text-[10px] font-black px-2 py-1 rounded-[4px] shadow-md z-30 border border-slate-100 dark:border-slate-800 flex items-center gap-1">
                <span class="material-symbols-outlined !text-[12px] opacity-70 cursor-pointer">history</span>
            </div>

            ${discountPct >= 5 ? `<div class="absolute top-3 left-3 bg-gradient-to-br from-red-500 to-rose-600 text-white text-[10px] font-black px-2.5 py-1 rounded-[4px] shadow-lg z-30 tracking-tight">Giảm ${discountPct}%</div>` : ''}

            <div class="absolute bottom-3 right-3 flex gap-1.5 z-30 translate-y-12 opacity-0 group-hover/card:translate-y-0 group-hover/card:opacity-100 transition-all duration-500">
                <div class="relative group/tip">
                    <a href="/bookstore/${slug}?buy=1" class="w-9 h-9 bg-brand-primary text-white rounded-[4px] flex items-center justify-center shadow-xl hover:bg-brand-primary-dark transition-all duration-300 cursor-pointer" aria-label="Mua ngay" onclick="event.stopPropagation()">
                        <span class="material-symbols-outlined !text-[1.2rem]">bolt</span>
                    </a>
                    <span class="pointer-events-none absolute bottom-full left-1/2 -translate-x-1/2 mb-2 whitespace-nowrap bg-slate-900 text-white text-[10px] font-medium px-2 py-1 rounded shadow-lg opacity-0 group-hover/tip:opacity-100 transition-opacity duration-200 z-50">
                        Mua ngay
                        <span class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent border-t-slate-900"></span>
                    </span>
                </div>
                <div class="relative group/tip">
                    <button type="button" class="w-9 h-9 bg-white/95 dark:bg-slate-900/95 backdrop-blur-md rounded-[4px] flex items-center justify-center shadow-xl border border-white/20 dark:border-slate-700/50 hover:bg-emerald-500 hover:text-white transition-all duration-300 cursor-pointer" aria-label="Thêm vào giỏ hàng" onclick="if(typeof addToCart === 'function') addToCart(${id}, '${title.replace(/'/g, "\\'")}', event); else event.preventDefault();">
                        <span class="material-symbols-outlined !text-[1.2rem]">shopping_bag</span>
                    </button>
                    <span class="pointer-events-none absolute bottom-full left-1/2 -translate-x-1/2 mb-2 whitespace-nowrap bg-slate-900 text-white text-[10px] font-medium px-2 py-1 rounded shadow-lg opacity-0 group-hover/tip:opacity-100 transition-opacity duration-200 z-50">
                        Thêm vào giỏ
                        <span class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent border-t-slate-900"></span>
                    </span>
                </div>
                <div class="relative group/tip">
                    <button type="button" class="w-9 h-9 bg-white/95 dark:bg-slate-900/95 backdrop-blur-md rounded-[4px] flex items-center justify-center shadow-xl border border-white/20 dark:border-slate-700/50 hover:bg-primary hover:text-white transition-all duration-300 cursor-pointer wishlist-btn" aria-label="Lưu yêu thích" onclick="if(typeof toggleWishlist === 'function') toggleWishlist(${id}); else event.preventDefault();">
                        <span class="material-symbols-outlined !text-[1.2rem]">favorite</span>
                    </button>
                    <span class="pointer-events-none absolute bottom-full right-0 mb-2 whitespace-nowrap bg-slate-900 text-white text-[10px] font-medium px-2 py-1 rounded shadow-lg opacity-0 group-hover/tip:opacity-100 transition-opacity duration-200 z-50">
                        Lưu yêu thích
                        <span class="absolute top-full right-[14px] border-4 border-transparent border-t-slate-900"></span>
                    </span>
                </div>
            </div>
        </div>

        <div class="card-body p-2 md:p-3 flex flex-col flex-1 bg-white dark:bg-slate-900">
            <a href="/bookstore/${slug}" class="block mb-1 group/title" onclick="if(typeof trackView === 'function') trackView(${id}, '${title.replace(/'/g, "\\'")}', '${imgUrl}', ${currentPrice}, '${slug}')">
                <h3 class="text-sm font-medium leading-tight text-slate-800 dark:text-slate-100 line-clamp-2 transition-colors group-hover/title:text-primary h-10 overflow-hidden" style="font-family: var(--font-ui, 'Inter', sans-serif);">${title}</h3>
            </a>

            <div class="flex items-center flex-wrap gap-x-1 gap-y-1 mb-2">
                <div class="flex gap-0.5">
                    ${Array.from({length:5}, (_,k) => `<span class="material-symbols-outlined ${k < Math.round(rating) ? 'text-amber-400' : 'text-slate-200 dark:text-slate-700'} !text-[10px] !font-[FILL_1]">star</span>`).join('')}
                    ${ratingCount > 0 ? `<span class="text-[10px] text-slate-400 ml-0.5">(${ratingCount})</span>` : ''}
                </div>
                <span class="text-[10px] text-slate-300 dark:text-slate-600 select-none">·</span>
                <div class="flex items-center gap-1.5 overflow-hidden">
                    <span class="text-[10px] font-medium text-slate-500 dark:text-slate-400 whitespace-nowrap">Đã bán ${soldCount >= 1000 ? (soldCount / 1000).toFixed(1) + 'k' : soldCount}</span>
                    ${formattedInterest ? `<span class="text-[10px] text-slate-300 dark:text-slate-600 select-none">·</span>
                    <div class="flex items-center gap-0.5 text-[10px] font-medium text-slate-500 dark:text-slate-400">
                        <span class="material-symbols-outlined text-orange-500 !text-[10px] font-[FILL_1]">local_fire_department</span>
                        <span>${formattedInterest}</span>
                    </div>` : ''}
                </div>
            </div>

            <div class="mt-auto">
                <div class="flex flex-col gap-0.5">
                    <div class="flex items-center flex-wrap gap-1.5">
                        <span class="text-sm md:text-base font-bold text-red-600 dark:text-red-500 tracking-tight">${new Intl.NumberFormat('vi-VN').format(currentPrice)}<span class="text-[0.7em] ml-0.5 align-top uppercase">đ</span></span>
                        ${discountPct >= 5 ? `<span class="bg-red-50 dark:bg-red-950/30 text-red-600 dark:text-red-400 text-[10px] font-black px-1.5 py-0.5 rounded-sm">-${discountPct}%</span>` : ''}
                    </div>
                    ${hasDiscount ? `<span class="text-xs text-slate-400 line-through font-medium">${new Intl.NumberFormat('vi-VN').format(originalPrice)}đ</span>` : ''}
                </div>
            </div>
        </div>
    </div>
</div>`;
        }).join('');
    }

    render();
}
