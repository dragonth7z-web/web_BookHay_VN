/**
 * Shopping Trend Manager — Dual-panel support (category + genre)
 * Constructor nhận options: { gridEl, catTabsEl, initialCategory, bindPeriodTabs }
 */
class ShoppingTrendManager {
    constructor(sectionEl, options = {}) {
        this.section      = sectionEl;
        this.gridEl       = options.gridEl       ?? sectionEl.querySelector('#st-grid');
        this.periodTabsEl = options.bindPeriodTabs !== false
            ? sectionEl.querySelector('.st-period-tabs') : null;
        this.catTabsEl    = options.catTabsEl    ?? sectionEl.querySelector('.st-cat-tabs');
        this.activePeriod   = 'day';
        this.activeCategory = options.initialCategory ?? 'all';
        this._bindEvents();
        this._load();
    }

    _bindEvents() {
        // Period tabs: chỉ bind cho primary manager (category panel)
        this.periodTabsEl?.addEventListener('click', e => {
            const tab = e.target.closest('[data-period]');
            if (tab && tab.dataset.period !== this.activePeriod) {
                this.activePeriod = tab.dataset.period;
                this._updatePeriodUI();
                this._load();
            }
        });
        // Category sub-tabs: mỗi manager bind vào catTabsEl của riêng mình
        this.catTabsEl?.addEventListener('click', e => {
            const tab = e.target.closest('[data-category]');
            if (tab && tab.dataset.category !== this.activeCategory) {
                this.activeCategory = tab.dataset.category;
                this._updateCatUI();
                this._load();
            }
        });
    }

    async _load() {
        if (!this.gridEl) return;
        this._showSkeleton();
        try {
            const params = new URLSearchParams({ period: this.activePeriod, category_id: this.activeCategory });
            const res  = await fetch(`${window.APP_CONFIG.baseUrl}/api/shopping-trend?${params}`);
            const json = await res.json();
            json.success && Array.isArray(json.data) ? this._renderGrid(json.data) : this._renderEmpty();
        } catch {
            this._renderEmpty();
        }
    }

    _showSkeleton() {
        if (!this.gridEl) return;
        this.gridEl.innerHTML = Array.from({ length: 6 }, () => `
            <div class="st-skeleton animate-pulse bg-white rounded-[6px] overflow-hidden shadow-[var(--shadow-page)] border border-slate-100/80" style="border-left:3px solid #e2e8f0;">
                <div class="aspect-square bg-gray-200"></div>
                <div class="p-3 space-y-2">
                    <div class="h-3 bg-gray-200 rounded w-full"></div>
                    <div class="h-3 bg-gray-200 rounded w-3/4"></div>
                    <div class="h-4 bg-gray-200 rounded w-1/2 mt-2"></div>
                </div>
            </div>`).join('');
    }

    _renderGrid(books) {
        if (!this.gridEl) return;
        if (!books.length) { this._renderEmpty(); return; }
        this.gridEl.innerHTML = books.map((b, i) => this._buildCard(b, i)).join('');
    }

    _buildCard(book, rank) {
        const title          = this._esc(book.title);
        const slug           = this._esc(book.slug);
        const imgUrl         = this._esc(book.cover_image);
        const currentPrice   = book.sale_price || book.original_price || 0;
        const originalPrice  = book.original_price || 0;
        const hasDiscount    = originalPrice > currentPrice;
        const id             = book.id || 0;
        let discountPct = 0;
        if (hasDiscount && originalPrice > 0) {
            discountPct = Math.round(((originalPrice - currentPrice) / originalPrice) * 100);
        }
        const rating         = book.rating_avg || 5;
        const ratingCount    = book.rating_count || (id % 10);
        let soldCount        = book.sold_count || 0;
        if (soldCount <= 0) soldCount = Math.floor(Math.random() * 34) + 12 + ((id % 15) + 10);
        const interest          = soldCount ? Math.floor(soldCount * 2.5) + (id % 20) : '';
        const formattedInterest = interest >= 1000 ? (interest / 1000).toFixed(1) + 'k' : interest;

        return `
<div class="product-card-container h-full active-feedback group/card" data-book-id="${id}">
    <div class="relative rounded-[6px] overflow-hidden bg-white dark:bg-slate-800 border border-slate-100/80 dark:border-white/[0.06] shadow-sm hover:shadow-[var(--shadow-book-hover)] hover:-translate-y-2 hover:rotate-[-1.5deg] transition-all duration-500 cursor-pointer flex flex-col h-full" style="border-left: 3px solid var(--color-brand-primary, #C92127);">
        <div class="absolute left-0 top-0 bottom-0 w-[3px] bg-gradient-to-b from-brand-primary via-brand-primary-dark to-brand-primary opacity-90 z-10 pointer-events-none"></div>
        <div class="card-image-wrap aspect-square bg-gray-50 dark:bg-slate-800/80 overflow-hidden flex items-center justify-center relative p-2">
            <a href="/bookstore/${slug}" class="block w-full h-full text-center flex items-center justify-center relative z-10" onclick="if(typeof trackView === 'function') trackView(${id}, '${title.replace(/'/g, "\\'")}', '${imgUrl}', ${currentPrice}, '${slug}')">
                <img src="${imgUrl}" alt="${title}" loading="lazy" class="max-w-full max-h-full object-contain transition-transform duration-1000 group-hover/card:scale-110 drop-shadow-sm mix-blend-multiply dark:mix-blend-normal" onerror="this.src='https://placehold.co/400x400?text=No+Image'">
            </a>
            <div class="absolute inset-0 bg-gradient-to-tr from-white/20 via-transparent to-transparent opacity-0 group-hover/card:opacity-100 transition-opacity duration-700 pointer-events-none z-10"></div>
            <div class="absolute top-0 -left-[100%] w-1/2 h-full bg-gradient-to-r from-transparent via-white/10 to-transparent -skew-x-12 group-hover/card:animate-shimmer-slide z-20"></div>
            ${discountPct >= 5 ? `<div class="absolute top-3 left-3 bg-gradient-to-br from-red-500 to-rose-600 text-white text-[10px] font-black px-2.5 py-1 rounded-[4px] shadow-lg z-30 tracking-tight">Giảm ${discountPct}%</div>` : ''}
            ${rank < 3 ? `<div class="absolute top-3 right-3 w-6 h-6 rounded-full flex items-center justify-center text-[11px] font-black text-white shadow-lg z-30" style="background:${['#ef4444','#f97316','#eab308'][rank]}">#${rank + 1}</div>` : `<div class="absolute top-3 right-3 w-5 h-5 rounded-full bg-slate-700/70 flex items-center justify-center text-[10px] font-bold text-white z-30">${rank + 1}</div>`}
            <div class="absolute bottom-3 right-3 flex gap-1.5 z-30 translate-y-12 opacity-0 group-hover/card:translate-y-0 group-hover/card:opacity-100 transition-all duration-500">
                <div class="relative group/tip">
                    <a href="/bookstore/${slug}?buy=1" class="w-9 h-9 bg-brand-primary text-white rounded-[4px] flex items-center justify-center shadow-xl hover:bg-brand-primary-dark transition-all duration-300 cursor-pointer" aria-label="Mua ngay" onclick="event.stopPropagation()">
                        <span class="material-symbols-outlined !text-[1.2rem]">bolt</span>
                    </a>
                    <span class="pointer-events-none absolute bottom-full left-1/2 -translate-x-1/2 mb-2 whitespace-nowrap bg-slate-900 text-white text-[10px] font-medium px-2 py-1 rounded shadow-lg opacity-0 group-hover/tip:opacity-100 transition-opacity duration-200 z-50">Mua ngay<span class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent border-t-slate-900"></span></span>
                </div>
                <div class="relative group/tip">
                    <button type="button" class="w-9 h-9 bg-white/95 dark:bg-slate-900/95 backdrop-blur-md rounded-[4px] flex items-center justify-center shadow-xl border border-white/20 dark:border-slate-700/50 hover:bg-emerald-500 hover:text-white transition-all duration-300 cursor-pointer" aria-label="Thêm vào giỏ hàng" onclick="if(typeof addToCart === 'function') addToCart(${id}, '${title.replace(/'/g, "\\'")}', event); else event.preventDefault();">
                        <span class="material-symbols-outlined !text-[1.2rem]">shopping_bag</span>
                    </button>
                    <span class="pointer-events-none absolute bottom-full left-1/2 -translate-x-1/2 mb-2 whitespace-nowrap bg-slate-900 text-white text-[10px] font-medium px-2 py-1 rounded shadow-lg opacity-0 group-hover/tip:opacity-100 transition-opacity duration-200 z-50">Thêm vào giỏ<span class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent border-t-slate-900"></span></span>
                </div>
                <div class="relative group/tip">
                    <button type="button" class="w-9 h-9 bg-white/95 dark:bg-slate-900/95 backdrop-blur-md rounded-[4px] flex items-center justify-center shadow-xl border border-white/20 dark:border-slate-700/50 hover:bg-primary hover:text-white transition-all duration-300 cursor-pointer wishlist-btn" aria-label="Lưu yêu thích" onclick="if(typeof toggleWishlist === 'function') toggleWishlist(${id}); else event.preventDefault();">
                        <span class="material-symbols-outlined !text-[1.2rem]">favorite</span>
                    </button>
                    <span class="pointer-events-none absolute bottom-full right-0 mb-2 whitespace-nowrap bg-slate-900 text-white text-[10px] font-medium px-2 py-1 rounded shadow-lg opacity-0 group-hover/tip:opacity-100 transition-opacity duration-200 z-50">Lưu yêu thích<span class="absolute top-full right-[14px] border-4 border-transparent border-t-slate-900"></span></span>
                </div>
            </div>
        </div>
        <div class="card-body p-2 md:p-3 flex flex-col flex-1 bg-white dark:bg-slate-900">
            <a href="/bookstore/${slug}" class="block mb-1 group/title" onclick="if(typeof trackView === 'function') trackView(${id}, '${title.replace(/'/g, "\\'")}', '${imgUrl}', ${currentPrice}, '${slug}')">
                <h3 class="text-sm font-medium leading-tight text-slate-800 dark:text-slate-100 line-clamp-2 transition-colors group-hover/title:text-primary h-10 overflow-hidden" style="font-family: var(--font-ui, 'Inter', sans-serif);">${title}</h3>
            </a>
            <div class="flex items-center flex-wrap gap-x-1 gap-y-1 mb-2">
                <div class="flex gap-0.5">
                    ${Array.from({length:5}, (_,k) => `<span class="material-symbols-outlined ${k < Math.round(rating) ? 'text-amber-400' : 'text-slate-200 dark:text-slate-700'} !text-[10px]">star</span>`).join('')}
                    ${ratingCount > 0 ? `<span class="text-[10px] text-slate-400 ml-0.5">(${ratingCount})</span>` : ''}
                </div>
                <span class="text-[10px] text-slate-300 dark:text-slate-600 select-none">·</span>
                <div class="flex items-center gap-1.5 overflow-hidden">
                    <span class="text-[10px] font-medium text-slate-500 dark:text-slate-400 whitespace-nowrap">Đã bán ${soldCount >= 1000 ? (soldCount / 1000).toFixed(1) + 'k' : soldCount}</span>
                    ${formattedInterest ? `<span class="text-[10px] text-slate-300 dark:text-slate-600 select-none">·</span>
                    <div class="flex items-center gap-0.5 text-[10px] font-medium text-slate-500 dark:text-slate-400">
                        <span class="material-symbols-outlined text-orange-500 !text-[10px]">local_fire_department</span>
                        <span>${formattedInterest}</span>
                    </div>` : ''}
                </div>
            </div>
            <div class="mt-auto">
                <div class="flex flex-col gap-0.5">
                    <div class="flex items-center flex-wrap gap-1.5">
                        <span class="text-sm md:text-base font-bold text-red-600 dark:text-red-500 tracking-tight">
                            ${this._price(currentPrice)}<span class="text-[0.7em] ml-0.5 align-top uppercase">đ</span>
                        </span>
                        ${discountPct >= 5 ? `<span class="bg-red-50 dark:bg-red-950/30 text-red-600 dark:text-red-400 text-[10px] font-black px-1.5 py-0.5 rounded-sm">-${discountPct}%</span>` : ''}
                    </div>
                    ${hasDiscount ? `<span class="text-xs text-slate-400 line-through font-medium">${this._price(originalPrice)}đ</span>` : ''}
                </div>
            </div>
        </div>
    </div>
</div>`;
    }

    _renderEmpty() {
        if (!this.gridEl) return;
        this.gridEl.innerHTML = `<div class="col-span-full flex flex-col items-center justify-center gap-3 py-12 text-gray-400 text-center"><span class="material-symbols-outlined text-5xl opacity-40">trending_flat</span><p class="text-sm">Chưa có dữ liệu xu hướng.</p></div>`;
    }

    _updatePeriodUI() {
        this.periodTabsEl?.querySelectorAll('[data-period]').forEach(tab => {
            const active = tab.dataset.period === this.activePeriod;
            tab.classList.toggle('border-brand-primary', active);
            tab.classList.toggle('text-brand-primary', active);
            tab.classList.toggle('bg-red-50/60', active);
            tab.classList.toggle('border-transparent', !active);
            tab.classList.toggle('text-slate-500', !active);
            tab.setAttribute('aria-selected', active ? 'true' : 'false');
        });
    }

    _updateCatUI() {
        this.catTabsEl?.querySelectorAll('[data-category]').forEach(tab =>
            tab.classList.toggle('active', tab.dataset.category === this.activeCategory));
    }

    _price(n) { return n ? new Intl.NumberFormat('vi-VN').format(n) : '0'; }
    _esc(s) {
        if (!s) return '';
        return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#039;');
    }
}

/* ──────────────────────────────────────────────────────────────
   initShoppingTrend — dual-panel, lazy-init genre panel
   ────────────────────────────────────────────────────────────── */
export function initShoppingTrend() {
    const section = document.getElementById('shopping-trend');
    if (!section) return;

    const catPanel    = section.querySelector('[data-panel="category"]');
    const catGrid     = catPanel?.querySelector('#st-grid');
    const catTabsEl   = catPanel?.querySelector('.st-cat-tabs');

    // Khởi tạo category panel (bind period tabs)
    new ShoppingTrendManager(section, {
        gridEl:          catGrid,
        catTabsEl:       catTabsEl,
        bindPeriodTabs:  true,
    });

    let genreInited = false;

    /* ── Tab cấp 1 toggle ── */
    const mainTabs = section.querySelectorAll('.st-main-tab');
    mainTabs.forEach(btn => {
        btn.addEventListener('click', function () {
            mainTabs.forEach(b => {
                b.classList.remove('border-brand-primary', 'text-brand-primary', 'bg-red-50/40');
                b.classList.add('border-transparent', 'text-slate-500');
                b.setAttribute('aria-selected', 'false');
            });
            this.classList.add('border-brand-primary', 'text-brand-primary', 'bg-red-50/40');
            this.classList.remove('border-transparent', 'text-slate-500');
            this.setAttribute('aria-selected', 'true');

            section.querySelectorAll('.st-main-panel').forEach(p => {
                p.classList.toggle('hidden', p.dataset.panel !== this.dataset.mainTab);
            });

            // Lazy-init genre manager lần đầu
            if (this.dataset.mainTab === 'genre' && !genreInited) {
                genreInited = true;
                const genrePanel    = section.querySelector('[data-panel="genre"]');
                const genreGrid     = genrePanel?.querySelector('#st-grid-genre');
                const genreCatTabs  = genrePanel?.querySelector('.st-cat-tabs');
                const firstCatId    = genreCatTabs?.querySelector('[data-category]')?.dataset?.category ?? 'all';
                if (genrePanel && genreGrid) {
                    new ShoppingTrendManager(section, {
                        gridEl:          genreGrid,
                        catTabsEl:       genreCatTabs,
                        bindPeriodTabs:  false,       // period tabs bị kiểm soát bởi primary manager
                        initialCategory: firstCatId,
                    });
                }
            }
        });
    });

    /* ── Genre tab active style (gradient color) ── */
    section.querySelectorAll('[data-color]').forEach(btn => {
        btn.addEventListener('click', function () {
            const color     = (this.dataset.color || '').split(' ').filter(Boolean);
            const container = this.closest('.st-cat-tabs');
            if (!container) return;
            container.querySelectorAll('[data-color]').forEach(b => {
                const bc = (b.dataset.color || '').split(' ').filter(Boolean);
                b.classList.remove('bg-gradient-to-r', 'text-white', 'shadow-sm', ...bc);
                b.classList.add('border', 'border-slate-200', 'text-slate-500', 'bg-white');
            });
            this.classList.remove('border', 'border-slate-200', 'text-slate-500', 'bg-white');
            if (color.length) this.classList.add('bg-gradient-to-r', ...color, 'text-white', 'shadow-sm');
        });
    });

    /* ── Tag "lọc sâu" toggle ── */
    section.querySelectorAll('.st-tag-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const on = this.classList.contains('bg-brand-primary');
            this.classList.toggle('bg-brand-primary', !on);
            this.classList.toggle('text-white', !on);
            this.classList.toggle('border-brand-primary', !on);
            this.classList.toggle('text-slate-500', on);
            this.classList.toggle('bg-white', on);
        });
    });
}
