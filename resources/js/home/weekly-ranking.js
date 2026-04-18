/**
 * Weekly Ranking Manager — Dual-panel support (category + genre)
 * Constructor nhận scopeEl để bind vào đúng panel.
 */
class WeeklyRankingManager {
    constructor(sectionEl, scopeEl = null) {
        const scope = scopeEl || sectionEl;
        this.section = sectionEl;
        this.scope   = scope;
        // Dùng data-attr để phân biệt list/preview giữa các panel
        this.listEl    = scope.querySelector('[data-wr-list]');
        this.previewEl = scope.querySelector('[data-wr-preview]');
        this.tabsEl    = scope.querySelector('.wr-tabs');
        const bodyEl   = scope.querySelector('.wr-body');
        try {
            this.allBooks = JSON.parse(bodyEl?.dataset.books || '[]');
        } catch {
            this.allBooks = [];
        }
        this.filteredBooks   = [...this.allBooks];
        this.activeCategory  = 'all';
        this.activeBookIndex = 0;
        this._hasInteracted  = false;
        this._bindEvents();
        this._render();
    }

    /* ── API fetch per category ── */
    async selectCategory(categoryId) {
        if (this.activeCategory === categoryId) return;
        this.activeCategory = categoryId;
        this._updateTabUI();
        if (this.listEl)    this.listEl.innerHTML    = '<div class="wr-empty-category"><span class="material-symbols-outlined animate-spin">refresh</span><p>Đang tải...</p></div>';
        if (this.previewEl) this.previewEl.innerHTML = '';
        try {
            const res    = await fetch(`${window.APP_CONFIG.baseUrl}/api/weekly-ranking?category_id=${encodeURIComponent(categoryId)}`);
            const result = await res.json();
            if (result.success && Array.isArray(result.data)) {
                this.filteredBooks   = result.data;
                this.activeBookIndex = 0;
                this._hasInteracted  = false;
                if (categoryId === 'all') this.allBooks = [...this.filteredBooks];
                this._renderList();
                this._renderPreview();
            } else throw new Error('Invalid data');
        } catch {
            if (this.listEl) this.listEl.innerHTML = '<div class="wr-empty-category"><span class="material-symbols-outlined text-red-500">error</span><p>Lỗi kết nối. Vui lòng thử lại.</p></div>';
        }
    }

    selectBook(index) {
        if (index < 0 || index >= this.filteredBooks.length) return;
        this.activeBookIndex = index;
        this._hasInteracted  = true;
        this._renderList();
        this._renderPreview();
    }

    _bindEvents() {
        this.tabsEl?.addEventListener('click', e => {
            const tab = e.target.closest('[data-category]');
            if (tab) this.selectCategory(tab.dataset.category);
        });
        this.listEl?.addEventListener('click', e => {
            const item = e.target.closest('[data-index]');
            if (item) this.selectBook(parseInt(item.dataset.index, 10));
        });
        this.listEl?.addEventListener('keydown', e => {
            const item = e.target.closest('[data-index]');
            if (item && (e.key === 'Enter' || e.key === ' ')) {
                e.preventDefault();
                this.selectBook(parseInt(item.dataset.index, 10));
            }
        });
    }

    _render() { this._renderList(); this._renderPreview(); }

    _renderList() {
        if (!this.listEl) return;
        if (!this.filteredBooks.length) {
            this.listEl.innerHTML = '<div class="wr-empty-category"><span class="material-symbols-outlined">search_off</span><p>Chưa có dữ liệu</p></div>';
            return;
        }
        this.listEl.innerHTML = this.filteredBooks.map((book, i) => {
            const isActive = i === this.activeBookIndex;
            const author   = Array.isArray(book.authors) && book.authors.length ? book.authors[0] : '';
            const score    = book.sold_count ? book.sold_count.toLocaleString('vi-VN') + ' đã bán' : '—';
            return `<div class="wr-item${isActive ? ' wr-item--active' : ''}" data-index="${i}" role="button" tabindex="0" aria-label="Xem: ${this._esc(book.title)}">
                <span class="rank-number" aria-hidden="true">${i + 1}</span>
                <div class="wr-item__cover-wrap">
                    ${book.cover_image && !book.cover_image.includes('ui-avatars')
                        ? `<img src="${this._esc(book.cover_image)}" alt="${this._esc(book.title)}" class="wr-item__cover shadow-sm" loading="lazy">`
                        : this._placeholderSmall(book.title)}
                </div>
                <div class="wr-item__info">
                    <p class="wr-item__title">${this._esc(book.title)}</p>
                    <p class="wr-item__author">${this._esc(author)}</p>
                    <span class="wr-item__score">${score}</span>
                </div>
            </div>`;
        }).join('');
    }

    _renderPreview() {
        if (!this.previewEl) return;
        const book = this.filteredBooks[this.activeBookIndex];
        if (!book) { this.previewEl.innerHTML = ''; return; }
        this.previewEl.classList.add('wr-preview--fading');
        setTimeout(() => {
            this.previewEl.classList.remove('wr-preview--fading');
            this.previewEl.innerHTML = this._buildPreview(book);
        }, 200);
    }

    _buildPreview(book) {
        const authors    = Array.isArray(book.authors) ? book.authors.join(', ') : 'Đang cập nhật';
        const rating     = book.rating_avg  > 0 ? book.rating_avg.toFixed(1)                     : null;
        const reviews    = book.rating_count > 0 ? book.rating_count.toLocaleString('vi-VN')       : null;
        const stockCls   = book.stock > 0 ? 'bg-green-50 text-green-600' : 'bg-red-50 text-red-600';
        const stockLabel = book.stock > 0 ? 'Còn hàng' : 'Hết hàng';
        const maxSold    = Math.max(...(this.filteredBooks.map(b => b.sold_count || 0).filter(v => v > 0)), 1);
        const soldPct    = book.sold_count > 0 ? Math.min(98, Math.round((book.sold_count / maxSold) * 100)) : 0;
        const isHot      = book.sold_count > 200;
        const baseUrl    = window.APP_CONFIG?.baseUrl || '';

        return `<div class="wr-preview__inner p-4 flex flex-col gap-4">
            <div class="flex gap-4 items-start pb-4 border-b border-slate-100">
                <div class="shrink-0 w-[110px] lg:w-[130px]">
                    ${book.cover_image && !book.cover_image.includes('ui-avatars')
                        ? `<img src="${this._esc(book.cover_image)}" alt="${this._esc(book.title)}" class="w-full h-auto rounded-lg shadow-xl" style="aspect-ratio:2/3;object-fit:cover" loading="lazy">`
                        : this._placeholderLarge(book.title, authors)}
                </div>
                <div class="flex-1 min-w-0 flex flex-col gap-2">
                    <span class="inline-flex items-center gap-1 text-[10px] font-bold px-2 py-0.5 rounded-full w-fit ${stockCls}">${stockLabel}</span>
                    <h3 class="text-base lg:text-lg font-black text-slate-900 leading-snug line-clamp-2">${this._esc(book.title)}</h3>
                    <p class="text-xs text-slate-500 truncate">${this._esc(authors)}</p>
                    <div class="flex flex-wrap gap-1.5 mt-0.5">
                        ${book.publisher ? `<span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded bg-slate-100 text-slate-600 text-[9px] font-bold uppercase border border-slate-200/60"><span class="material-symbols-outlined text-[11px]">domain</span>${this._esc(book.publisher)}</span>` : ''}
                        ${(book.cover_type || book.pages) ? `<span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded bg-slate-100 text-slate-600 text-[9px] font-bold uppercase border border-slate-200/60"><span class="material-symbols-outlined text-[11px]">auto_stories</span>${this._esc(book.cover_type || 'Bìa mềm')}${book.pages ? ` • ${book.pages} trang` : ''}</span>` : ''}
                    </div>
                    ${rating ? `
                    <div class="flex items-center gap-1.5 mt-1">
                        <span class="text-brand-gold text-sm">★★★★★</span>
                        <span class="text-xs font-bold text-slate-700">${rating}/5</span>
                        ${reviews ? `<span class="text-[10px] text-slate-400">(${reviews} đánh giá)</span>` : ''}
                    </div>` : ''}
                    <div class="flex items-baseline gap-2 mt-1">
                        <span class="text-xl font-black text-brand-primary">${this._price(book.sale_price)}<span class="text-[0.7em] ml-0.5 align-top">đ</span></span>
                        ${book.discount_percent > 0 ? `<span class="bg-brand-primary text-white text-[9px] font-black px-1.5 py-0.5 rounded">-${book.discount_percent}%</span>` : ''}
                    </div>
                    ${book.original_price > book.sale_price ? `<span class="text-[11px] text-slate-400 line-through">${this._price(book.original_price)}đ</span>` : ''}
                </div>
            </div>

            <div class="flex items-center gap-2 flex-wrap">
                <div class="flex items-center gap-1.5 px-2.5 py-1 bg-amber-50 border border-amber-200 rounded-lg">
                    <span class="material-symbols-outlined text-[14px] text-amber-600">workspace_premium</span>
                    <span class="text-[10px] font-black text-amber-700 uppercase tracking-wider">Hạng ${book.rank} trong ${this._esc(book.category || 'Thể loại')}</span>
                </div>
                ${book.sold_count > 500 ? `
                    <div class="flex items-center gap-1.5 px-2.5 py-1 bg-rose-50 border border-rose-100 rounded-lg">
                        <span class="material-symbols-outlined text-[14px] text-rose-500">local_fire_department</span>
                        <span class="text-[10px] font-black text-rose-600 uppercase tracking-wider">Bán chạy nhất</span>
                    </div>` : ''}
            </div>

            ${book.sold_count > 0 ? `
            <div class="flex flex-col gap-1.5 mb-2">
                <div class="flex justify-between items-end">
                    <span class="text-[11px] font-bold text-slate-500 italic">Đã bán <span class="text-brand-primary">${book.sold_count.toLocaleString('vi-VN')}</span> bản</span>
                    ${isHot ? `<span class="text-[10px] font-black text-brand-primary uppercase tracking-tighter">Cực Hot</span>` : ''}
                </div>
                <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden shadow-inner">
                    <div class="h-full bg-gradient-to-r from-brand-primary to-rose-500 rounded-full" style="width:${soldPct}%"></div>
                </div>
            </div>` : ''}

            <div class="flex flex-col gap-2 my-1">
                ${book.short_description ? `
                    <div class="flex flex-col">
                        <span class="text-[9px] text-slate-400 uppercase font-black tracking-widest flex items-center gap-1 mb-0.5">
                            <span class="w-2 rounded-full h-[2px] bg-brand-primary/50"></span>Điểm nhấn
                        </span>
                        <p class="text-[11px] text-slate-800 font-medium leading-relaxed italic border-l-2 border-brand-primary/20 pl-2">"${this._esc(book.short_description)}"</p>
                    </div>` : ''}
                ${book.description ? `
                    <div class="flex flex-col mt-1">
                        <p class="text-[11px] text-slate-600 leading-relaxed line-clamp-3">${this._esc(book.description)}</p>
                    </div>` : ''}
            </div>

            <div class="flex flex-col gap-2.5 mt-auto pt-3 border-t border-slate-50">
                <a href="${baseUrl}/cart/add/${book.id}" class="group relative flex items-center justify-center w-full py-3.5 bg-gradient-to-br from-brand-primary to-rose-600 text-white rounded-xl font-black text-[12px] uppercase tracking-widest shadow-brand hover:shadow-xl hover:-translate-y-0.5 active:scale-95 transition-all duration-300">
                    <span class="relative z-10 flex items-center gap-2">Mua ngay <span class="material-symbols-outlined text-sm">arrow_forward</span></span>
                    <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 transition-opacity"></div>
                </a>
                <div class="flex gap-2">
                    <a href="${baseUrl}/cart/add/${book.id}" class="flex-1 flex items-center justify-center gap-2 py-3 bg-white border-2 border-slate-200 rounded-xl text-slate-700 font-bold text-[11px] uppercase hover:border-brand-primary hover:text-brand-primary hover:bg-brand-primary/5 transition-all">
                        <span class="material-symbols-outlined text-base">shopping_cart</span> Thêm giỏ hàng
                    </a>
                    <button class="w-12 h-12 flex items-center justify-center bg-gray-50 border border-gray-100 rounded-xl text-gray-400 hover:text-rose-500 hover:bg-rose-50 transition-all group/fav" onclick="if(typeof toggleWishlist==='function') toggleWishlist(${book.id})">
                        <span class="material-symbols-outlined text-xl transition-all group-hover/fav:scale-125">favorite</span>
                    </button>
                </div>
            </div>
        </div>`;
    }

    _updateTabUI() {
        this.tabsEl?.querySelectorAll('[data-category]').forEach(tab => {
            const active = tab.dataset.category === this.activeCategory;
            tab.classList.toggle('active', active);
            tab.setAttribute('aria-selected', active ? 'true' : 'false');
        });
    }

    _placeholderSmall(title) {
        return `<div class="wr-item__cover rounded shadow-sm flex items-center justify-center p-1 text-center text-white text-[7px] font-bold" style="background:linear-gradient(135deg,#1e293b,#334155)"><div class="line-clamp-3">${this._esc(title)}</div></div>`;
    }

    _placeholderLarge(title, author) {
        return `<div class="w-full rounded-lg shadow-xl flex flex-col items-center justify-center p-3 text-center text-white text-[9px] font-bold" style="aspect-ratio:2/3;background:linear-gradient(135deg,#1e293b,#334155)"><span class="material-symbols-outlined text-2xl mb-1 opacity-40">book_5</span><div class="line-clamp-3">${this._esc(title)}</div></div>`;
    }

    _price(n) { return n ? new Intl.NumberFormat('vi-VN').format(n) : '0'; }
    _esc(s) {
        if (!s) return '';
        return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#039;');
    }
}

/* ──────────────────────────────────────────────────────────────
   initWeeklyRanking — khởi tạo cả 2 panel, lazy-init genre panel
   ────────────────────────────────────────────────────────────── */
export function initWeeklyRanking() {
    const section = document.getElementById('weekly-ranking');
    if (!section) return;

    const catPanel   = section.querySelector('[data-panel="category"]');
    const genrePanel = section.querySelector('[data-panel="genre"]');

    // Khởi tạo ngay category panel
    if (catPanel) new WeeklyRankingManager(section, catPanel);

    // Lazy-init genre panel lần đầu khi tab được click
    let genreInited = false;

    /* ── Tab cấp 1 toggle ── */
    const mainTabs = section.querySelectorAll('.wr-main-tab');
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

            section.querySelectorAll('.wr-panel').forEach(p => {
                p.classList.toggle('hidden', p.dataset.panel !== this.dataset.mainTab);
            });

            // Lazy-init genre manager lần đầu
            if (this.dataset.mainTab === 'genre' && !genreInited && genrePanel) {
                genreInited = true;
                new WeeklyRankingManager(section, genrePanel);
            }
        });
    });

    /* ── Period ButtonGroup ── */
    const periodBtns = section.querySelectorAll('.wr-period-btn');
    periodBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            periodBtns.forEach(b => {
                b.classList.remove('bg-white', 'text-slate-800', 'shadow-sm');
                b.classList.add('text-white/70');
            });
            this.classList.add('bg-white', 'text-slate-800', 'shadow-sm');
            this.classList.remove('text-white/70');
        });
    });

    /* ── Genre tab active style (gradient color) ── */
    section.querySelectorAll('[data-color]').forEach(btn => {
        btn.addEventListener('click', function () {
            const color     = (this.dataset.color || '').split(' ').filter(Boolean);
            const container = this.closest('.wr-tabs');
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
    section.querySelectorAll('.wr-tag-btn').forEach(btn => {
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
