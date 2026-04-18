/**
 * Personalization Engine
 * Migrated from public/js/home/personalization.js
 */
export function initPersonalization() {
    const engine = {
        trackCategoryClick(categoryId) {
            const viewed = JSON.parse(sessionStorage.getItem('viewed_categories') || '[]');
            const filtered = viewed.filter(id => id !== categoryId);
            sessionStorage.setItem('viewed_categories', JSON.stringify([categoryId, ...filtered].slice(0, 10)));
            const preferred = JSON.parse(localStorage.getItem('preferred_categories') || '[]');
            const pFiltered = preferred.filter(id => id !== categoryId);
            localStorage.setItem('preferred_categories', JSON.stringify([categoryId, ...pFiltered].slice(0, 5)));
        },
        getLabel() {
            if (JSON.parse(sessionStorage.getItem('viewed_categories') || '[]').length) return 'Dựa trên lịch sử xem của bạn';
            if (JSON.parse(localStorage.getItem('preferred_categories') || '[]').length) return 'Dựa trên sở thích của bạn';
            return 'Sách nổi bật tuần này';
        },
    };

    const el = document.getElementById('personalization-text');
    if (el) el.textContent = engine.getLabel();

    document.querySelectorAll('.category-card[data-cat-id]').forEach(card => {
        card.addEventListener('click', () => engine.trackCategoryClick(parseInt(card.dataset.catId)));
    });

    window.PersonalizationEngine = engine;
}
