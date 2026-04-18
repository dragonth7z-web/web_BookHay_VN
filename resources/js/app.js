import './bootstrap';

// Core
import { initToast } from './components/toast';
import './components/cart';
import './core';

// Components (always loaded)
import { initHeader } from './components/header';
import { initSearchPalette } from './components/search-palette';
import { initFooter } from './components/footer';
import { initSocialProof } from './components/social-proof';
import { initMomentum } from './components/momentum-dialog';
import { initAnalytics } from './analytics';

// Home sections (loaded on all pages, guard by element existence)
import { initBanners } from './home/banners';
import { initLazyRender } from './home/lazy-render';
import { initRecentlyViewed } from './home/recently-viewed';
import { initCroTriggers } from './home/cro-triggers';
import { initStickyCta } from './home/sticky-cta';
import { initStickyFlashBar } from './home/sticky-flash-bar';
import { initPersonalization } from './home/personalization';
import { initFlashSale } from './home/flash-sale';
import { initWeeklyRanking } from './home/weekly-ranking';
import { initShoppingTrend } from './home/shopping-trend';
import { initBookSeries } from './home/book-series';
import { initCombo } from './home/combo';

document.addEventListener('DOMContentLoaded', () => {
    initToast();
    initHeader();
    initSearchPalette();
    initFooter();
    initSocialProof();
    initMomentum();
    initAnalytics();
    initTabScroll();

    // Home sections — each guards itself if element not present
    initBanners();
    initLazyRender();
    initRecentlyViewed();
    initCroTriggers();
    initStickyCta();
    initStickyFlashBar();
    initPersonalization();
    initFlashSale();
    initWeeklyRanking();
    initShoppingTrend();
    initBookSeries();
    initCombo();
});

function initTabScroll() {
    document.querySelectorAll('.tab-scroll-wrapper').forEach(wrapper => {
        const inner = wrapper.querySelector('.tab-scroll-inner');
        const btnLeft = wrapper.querySelector('.tab-scroll-left');
        const btnRight = wrapper.querySelector('.tab-scroll-right');
        if (!inner) return;

        const STEP = 200;

        const update = () => {
            if (btnLeft)  btnLeft.disabled  = inner.scrollLeft <= 0;
            if (btnRight) btnRight.disabled = inner.scrollLeft + inner.clientWidth >= inner.scrollWidth - 1;
        };

        btnLeft?.addEventListener('click',  () => { inner.scrollBy({ left: -STEP, behavior: 'smooth' }); });
        btnRight?.addEventListener('click', () => { inner.scrollBy({ left:  STEP, behavior: 'smooth' }); });
        inner.addEventListener('scroll', update, { passive: true });

        // Re-check when content changes (e.g. after AJAX)
        new ResizeObserver(update).observe(inner);
        update();
    });
}
