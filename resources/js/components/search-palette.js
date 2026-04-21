/**
 * Search Dropdown — inline dropdown dưới thanh tìm kiếm header
 * Tính năng: từ khóa hot, danh mục nổi bật, flash sale banner, live search
 */
export function initSearchPalette() {
    // ── Legacy palette (Cmd+K modal) ──────────────────────────────────────────
    const palette  = document.getElementById('search-palette');
    const content  = document.getElementById('palette-content');
    const paletteInput = document.getElementById('palette-search-input');

    window.openSearchPalette = () => {
        if (!palette) return;
        palette.classList.remove('hidden');
        setTimeout(() => {
            content?.classList.remove('scale-95', 'opacity-0');
            content?.classList.add('scale-100', 'opacity-100');
            paletteInput?.focus();
        }, 10);
        document.body.style.overflow = 'hidden';
    };

    window.closeSearchPalette = () => {
        content?.classList.remove('scale-100', 'opacity-100');
        content?.classList.add('scale-95', 'opacity-0');
        setTimeout(() => palette?.classList.add('hidden'), 300);
        document.body.style.overflow = '';
    };

    document.addEventListener('keydown', (e) => {
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            window.openSearchPalette();
        }
        if (e.key === 'Escape' && !palette?.classList.contains('hidden')) {
            window.closeSearchPalette();
        }
    });

    // ── Inline Search Dropdown ────────────────────────────────────────────────
    const input      = document.getElementById('search-input');
    const dropdown   = document.getElementById('search-dropdown');
    const submitBtn  = document.getElementById('search-submit-btn');

    if (!input || !dropdown) return;

    const BASE_URL = window.APP_CONFIG?.baseUrl ?? '';
    const SEARCH_URL = `${BASE_URL}/bookstore/search`;
    const API_URL    = `${BASE_URL}/api/search-suggestions`;

    let debounceTimer = null;
    let defaultData   = null; // cache default (no query) response

    // ── Helpers ───────────────────────────────────────────────────────────────
    const show = (el) => el?.classList.remove('hidden');
    const hide = (el) => el?.classList.add('hidden');

    const formatPrice = (n) =>
        new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(n);

    function openDropdown() {
        dropdown.classList.remove('hidden');
    }

    function closeDropdown() {
        dropdown.classList.add('hidden');
    }

    function navigate(q) {
        const url = new URL(SEARCH_URL);
        if (q) url.searchParams.set('q', q);
        window.location.href = url.toString();
    }

    // ── Render default view (hot keywords + categories) ───────────────────────
    function renderDefault(data) {
        hide(document.getElementById('sd-loading'));
        hide(document.getElementById('sd-results-section'));
        hide(document.getElementById('sd-empty'));

        // Flash sale banner
        const flashBanner = document.getElementById('sd-flash-banner');
        if (data.flashSale) {
            document.getElementById('sd-flash-name').textContent = data.flashSale.name;
            show(flashBanner);
        } else {
            hide(flashBanner);
        }

        // Hot keywords grid
        const kwGrid = document.getElementById('sd-keywords-grid');
        if (kwGrid && data.hotKeywords?.length) {
            kwGrid.innerHTML = data.hotKeywords.map(kw => `
                <button onclick="window.__searchDropdown.search(${JSON.stringify(kw.keyword)})"
                    class="flex items-center gap-2 p-2 rounded-lg hover:bg-gray-50 transition-colors text-left group">
                    ${kw.image
                        ? `<img src="${kw.image}" alt="" class="w-10 h-12 object-cover rounded flex-shrink-0 bg-gray-100">`
                        : `<div class="w-10 h-12 rounded bg-gray-100 flex items-center justify-center flex-shrink-0">
                               <span class="material-symbols-outlined text-gray-300 text-lg">menu_book</span>
                           </div>`
                    }
                    <span class="text-xs font-semibold text-gray-700 group-hover:text-brand-primary leading-tight line-clamp-2">${kw.keyword}</span>
                </button>
            `).join('');
            show(document.getElementById('sd-keywords-section'));
        } else {
            hide(document.getElementById('sd-keywords-section'));
        }

        // Categories grid
        const catGrid = document.getElementById('sd-categories-grid');
        if (catGrid && data.categories?.length) {
            catGrid.innerHTML = data.categories.map(cat => `
                <a href="${SEARCH_URL}?category=${cat.id}"
                    class="flex flex-col items-center gap-1.5 p-2 rounded-xl hover:bg-gray-50 transition-colors group text-center">
                    ${cat.image
                        ? `<img src="${cat.image}" alt="${cat.name}" class="w-14 h-14 object-cover rounded-xl bg-gray-100">`
                        : `<div class="w-14 h-14 rounded-xl bg-gradient-to-br from-brand-primary/10 to-rose-100 flex items-center justify-center">
                               <span class="material-symbols-outlined text-brand-primary text-2xl">menu_book</span>
                           </div>`
                    }
                    <span class="text-xs font-semibold text-gray-700 group-hover:text-brand-primary leading-tight line-clamp-2">${cat.name}</span>
                </a>
            `).join('');
            show(document.getElementById('sd-categories-section'));
        } else {
            hide(document.getElementById('sd-categories-section'));
        }

        openDropdown();
    }

    // ── Render live search results ────────────────────────────────────────────
    function renderResults(books, q) {
        hide(document.getElementById('sd-keywords-section'));
        hide(document.getElementById('sd-categories-section'));
        hide(document.getElementById('sd-flash-banner'));
        hide(document.getElementById('sd-loading'));
        hide(document.getElementById('sd-empty'));

        const section = document.getElementById('sd-results-section');
        const list    = document.getElementById('sd-results-list');
        const viewAll = document.getElementById('sd-view-all-link');

        if (!books.length) {
            hide(section);
            show(document.getElementById('sd-empty'));
            openDropdown();
            return;
        }

        if (viewAll) {
            viewAll.href = `${SEARCH_URL}?q=${encodeURIComponent(q)}`;
        }

        list.innerHTML = books.map(book => `
            <a href="${BASE_URL}/bookstore/${book.slug}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-gray-50 transition-colors group">
                <img src="${book.image}" alt="${book.title}"
                    class="w-10 h-13 object-cover rounded-lg flex-shrink-0 bg-gray-100"
                    style="height:52px"
                    onerror="this.src='https://ui-avatars.com/api/?name=${encodeURIComponent(book.title)}&background=f1f5f9&color=64748b&size=128'">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-800 group-hover:text-brand-primary truncate">${book.title}</p>
                    ${book.author ? `<p class="text-xs text-gray-400 truncate">${book.author}</p>` : ''}
                </div>
                <span class="text-sm font-bold text-brand-primary flex-shrink-0">${formatPrice(book.price)}</span>
            </a>
        `).join('');

        show(section);
        openDropdown();
    }

    // ── Fetch default data ────────────────────────────────────────────────────
    async function loadDefault() {
        if (defaultData) { renderDefault(defaultData); return; }
        show(document.getElementById('sd-loading'));
        openDropdown();
        try {
            const res  = await fetch(API_URL);
            const data = await res.json();
            defaultData = data;
            renderDefault(data);
        } catch {
            closeDropdown();
        }
    }

    // ── Fetch live results ────────────────────────────────────────────────────
    async function loadResults(q) {
        show(document.getElementById('sd-loading'));
        hide(document.getElementById('sd-results-section'));
        hide(document.getElementById('sd-empty'));
        openDropdown();
        try {
            const res  = await fetch(`${API_URL}?q=${encodeURIComponent(q)}`);
            const data = await res.json();
            renderResults(data.books ?? [], q);
        } catch {
            closeDropdown();
        }
    }

    // ── Public API ────────────────────────────────────────────────────────────
    window.__searchDropdown = {
        search: (kw) => { input.value = kw; navigate(kw); },
        refreshDefault: () => { defaultData = null; loadDefault(); },
    };

    // ── Events ────────────────────────────────────────────────────────────────
    input.addEventListener('focus', () => {
        const q = input.value.trim();
        if (q.length >= 2) loadResults(q);
        else loadDefault();
    });

    input.addEventListener('input', (e) => {
        clearTimeout(debounceTimer);
        const q = e.target.value.trim();
        if (q.length === 0) { loadDefault(); return; }
        if (q.length < 2) return;
        debounceTimer = setTimeout(() => loadResults(q), 300);
    });

    input.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
            e.preventDefault();
            const q = input.value.trim();
            navigate(q);
        }
        if (e.key === 'Escape') closeDropdown();
    });

    submitBtn?.addEventListener('click', () => navigate(input.value.trim()));

    // Close on outside click
    document.addEventListener('click', (e) => {
        if (!document.getElementById('search-wrapper')?.contains(e.target)) {
            closeDropdown();
        }
    });
}
