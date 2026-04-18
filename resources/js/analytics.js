/**
 * THLD Analytics Engine
 * Migrated from public/js/analytics.js
 */
export function initAnalytics() {
    const EVENTS = {
        CTA_CLICK: 'cta_click', ADD_TO_CART: 'add_to_cart', BUY_NOW: 'buy_now',
        SCROLL_BUY_ZONE: 'scroll_buy_zone', SCROLL_25: 'scroll_25', SCROLL_50: 'scroll_50',
        SCROLL_75: 'scroll_75', EXIT_INTENT: 'exit_intent', MOMENTUM_ACTION: 'momentum_action',
    };
    let queue = [];
    const triggered = { scroll_25: false, scroll_50: false, scroll_75: false, exit_intent: false };

    const flush = () => {
        if (!queue.length) return;
        try {
            const logs = JSON.parse(localStorage.getItem('analytics_logs') || '[]');
            localStorage.setItem('analytics_logs', JSON.stringify([...logs, ...queue]));
        } catch {}
        queue = [];
    };

    const track = (event, data = {}) => {
        queue.push({ event, data, time: Date.now() });
        if (queue.length >= 5) flush();
    };

    setInterval(() => { if (queue.length) flush(); }, 5000);

    document.addEventListener('mouseout', e => {
        if (e.clientY <= 0 && !triggered.exit_intent) { track(EVENTS.EXIT_INTENT); triggered.exit_intent = true; }
    });

    let scrollRaf;
    window.addEventListener('scroll', () => {
        cancelAnimationFrame(scrollRaf);
        scrollRaf = requestAnimationFrame(() => {
            const h = document.documentElement.scrollHeight - window.innerHeight;
            if (h <= 0) return;
            const pct = (window.scrollY / h) * 100;
            if (pct >= 25 && !triggered.scroll_25) { track(EVENTS.SCROLL_25); triggered.scroll_25 = true; }
            if (pct >= 50 && !triggered.scroll_50) { track(EVENTS.SCROLL_50); triggered.scroll_50 = true; }
            if (pct >= 75 && !triggered.scroll_75) { track(EVENTS.SCROLL_75); triggered.scroll_75 = true; }
            window.dispatchEvent(new CustomEvent('thld-scroll', { detail: { scrollY: window.scrollY, pct } }));
        });
    });

    window.THLD_Analytics = { EVENTS, trackEvent: track, flushEvents: flush };
}
