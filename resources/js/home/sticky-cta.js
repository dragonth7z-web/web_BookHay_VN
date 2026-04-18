/**
 * Sticky Mobile CTA Bar
 * Migrated from public/js/home/sticky-cta.js
 */
export function initStickyCta() {
    const hidden = ['/cart', '/checkout', '/thanh-toan', '/gio-hang'];
    if (hidden.some(p => window.location.pathname.startsWith(p))) return;
    setTimeout(() => document.getElementById('sticky-cta-bar')?.classList.add('visible'), 2000);
}
