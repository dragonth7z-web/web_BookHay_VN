/**
 * Header — Dropdown manager + sticky + mobile nav
 * Migrated from public/js/components/header.js
 */
export function initHeader() {
    const wrappers = document.querySelectorAll('.dropdown-wrapper');
    const header = document.getElementById('main-header');
    let activeWrapper = null;
    let globalCloseTimeout = null;
    let lastScrollY = window.scrollY;
    const SCROLL_THRESHOLD = 50;

    const getMenu = (wrapper) => {
        const menu = wrapper.querySelector('.dropdown-menu');
        if (menu) return menu;
        const trigger = wrapper.querySelector('.dropdown-trigger');
        return trigger?.dataset.target ? document.getElementById(trigger.dataset.target) : null;
    };

    const closeAll = () => {
        clearTimeout(globalCloseTimeout);
        wrappers.forEach(w => {
            getMenu(w)?.classList.remove('dropdown-active');
            w.querySelector('.dropdown-trigger')?.classList.remove('text-primary', 'bg-white', 'shadow-md');
            w._isSticky = false;
        });
        activeWrapper = null;
    };

    const openOne = (wrapper, sticky = false) => {
        if (activeWrapper && activeWrapper !== wrapper) closeAll();
        const m = getMenu(wrapper);
        const t = wrapper.querySelector('.dropdown-trigger');
        m?.classList.add('dropdown-active');
        t?.classList.add('text-primary');
        if (t && (t.dataset.target === 'lang-menu' || t.id === 'lang-trigger')) {
            t.classList.add('bg-white', 'shadow-md');
        }
        wrapper._isSticky = sticky;
        activeWrapper = wrapper;
        clearTimeout(globalCloseTimeout);
    };

    wrappers.forEach(wrapper => {
        const trigger = wrapper.querySelector('.dropdown-trigger');
        if (!trigger) return;
        wrapper.addEventListener('mouseenter', () => openOne(wrapper, wrapper._isSticky));
        wrapper.addEventListener('mouseleave', () => {
            if (activeWrapper === wrapper && !wrapper._isSticky) {
                globalCloseTimeout = setTimeout(() => {
                    if (activeWrapper === wrapper && !wrapper._isSticky) closeAll();
                }, 400);
            }
        });
        trigger.addEventListener('click', (e) => {
            if (e.target.closest('a') && !e.target.closest('.dropdown-trigger')) return;
            e.preventDefault(); e.stopPropagation();
            wrapper._isSticky && activeWrapper === wrapper ? closeAll() : openOne(wrapper, true);
        });
    });

    window.addEventListener('scroll', () => {
        const y = window.scrollY;
        if (activeWrapper && Math.abs(y - lastScrollY) > SCROLL_THRESHOLD) closeAll();
        
        if (header) {
            header.classList.toggle('is-sticky', y > 80);
            
            // Trượt xuống -> Ẩn header, Trượt lên -> Hiện header
            if (y > 80) {
                if (y > lastScrollY && y > 150) {
                    header.classList.add('header-hidden');
                } else if (y < lastScrollY) {
                    header.classList.remove('header-hidden');
                }
            } else {
                header.classList.remove('header-hidden');
            }
        }
        
        lastScrollY = y;
    }, { passive: true });

    document.addEventListener('click', (e) => {
        if (!activeWrapper) return;
        const menu = getMenu(activeWrapper);
        if (!activeWrapper.contains(e.target) && !menu?.contains(e.target)) closeAll();
    });

    const searchInput = document.getElementById('search-input');
    if (searchInput) {
        let timer;
        searchInput.addEventListener('input', () => {
            clearTimeout(timer);
            if (searchInput.value.trim().length > 0) {
                timer = setTimeout(() => {
                    const sw = searchInput.closest('.dropdown-wrapper');
                    if (sw) openOne(sw, false);
                }, 500);
            }
        });
    }

    // ── Mega menu: left-column hover activates right panel ──
    initMegaMenuTabs();
}

function initMegaMenuTabs() {
    const menu = document.getElementById('mega-menu');
    if (!menu) return;

    const btns = menu.querySelectorAll('.mega-cat-btn');
    const panels = menu.querySelectorAll('.mega-sub-panel');

    const activate = (index) => {
        btns.forEach((b, i) => {
            b.classList.toggle('active', i === index);
        });
        panels.forEach((p, i) => {
            const on = i === index;
            p.classList.toggle('opacity-100', on);
            p.classList.toggle('pointer-events-auto', on);
            p.classList.toggle('opacity-0', !on);
            p.classList.toggle('pointer-events-none', !on);
        });
    };

    if (btns.length) activate(0);

    btns.forEach((btn, i) => {
        btn.addEventListener('mouseenter', () => activate(i));
        btn.addEventListener('focus', () => activate(i));
    });
}

// Mobile nav — exposed globally for inline onclick handlers
window.openMobileNav = () => {
    document.getElementById('mobile-nav')?.classList.add('open');
    document.getElementById('mobile-nav-overlay')?.classList.add('open');
    document.body.style.overflow = 'hidden';
};
window.closeMobileNav = () => {
    document.getElementById('mobile-nav')?.classList.remove('open');
    document.getElementById('mobile-nav-overlay')?.classList.remove('open');
    document.body.style.overflow = '';
};
