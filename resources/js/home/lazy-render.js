/**
 * Lazy Render — IntersectionObserver for deferred sections
 * Migrated from public/js/home/lazy-render.js
 */
export function initLazyRender() {
    if (!('IntersectionObserver' in window)) return;
    const selectors = ['#weekly-ranking', '.features-section', '.qcat-section', '#book-series', '#ai-suggestions', '#publisher-partners'];
    const observer = new IntersectionObserver(entries => {
        entries.forEach(e => {
            if (e.isIntersecting) { e.target.classList.add('lazy-rendered'); observer.unobserve(e.target); }
        });
    }, { rootMargin: '200px' });
    selectors.forEach(s => { const el = document.querySelector(s); if (el) observer.observe(el); });
}
