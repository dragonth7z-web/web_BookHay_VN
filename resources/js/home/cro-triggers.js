/**
 * CRO Triggers — Viewer count simulation
 * Migrated from public/js/home/cro-triggers.js
 */
export function initCroTriggers() {
    const stored = JSON.parse(sessionStorage.getItem('view_counts') || '{}');

    document.querySelectorAll('[data-book-id]').forEach(card => {
        const id = card.dataset.bookId;
        if (!id) return;
        const badge = card.querySelector('.cro-viewer-badge');
        if (!badge) return;
        if (!stored[id]) stored[id] = Math.floor(Math.random() * 20) + 5;
        if (stored[id] > 8) {
            const el = badge.querySelector('.viewer-count');
            if (el) el.textContent = stored[id];
            badge.style.display = 'flex';
        }
    });

    sessionStorage.setItem('view_counts', JSON.stringify(stored));

    setInterval(() => {
        const s = JSON.parse(sessionStorage.getItem('view_counts') || '{}');
        Object.keys(s).forEach(id => { s[id] = Math.max(5, Math.min(25, s[id] + Math.floor(Math.random() * 5) - 2)); });
        sessionStorage.setItem('view_counts', JSON.stringify(s));
        document.querySelectorAll('[data-book-id]').forEach(card => {
            const id = card.dataset.bookId;
            const badge = card.querySelector('.cro-viewer-badge');
            const el = badge?.querySelector('.viewer-count');
            if (el && s[id] > 8) { el.textContent = s[id]; badge.style.display = 'flex'; }
        });
    }, 60000);
}
