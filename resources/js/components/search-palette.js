/**
 * Search Palette — Cmd+K modal search
 * Migrated from public/js/components/search-palette.js
 */
export function initSearchPalette() {
    const palette = document.getElementById('search-palette');
    const content = document.getElementById('palette-content');
    const input = document.getElementById('palette-search-input');
    const results = document.getElementById('palette-results');
    let debounceTimer;

    window.openSearchPalette = () => {
        if (!palette) return;
        palette.classList.remove('hidden');
        setTimeout(() => {
            content?.classList.remove('scale-95', 'opacity-0');
            content?.classList.add('scale-100', 'opacity-100');
            input?.focus();
        }, 10);
        document.body.style.overflow = 'hidden';
    };

    window.closeSearchPalette = () => {
        content?.classList.remove('scale-100', 'opacity-100');
        content?.classList.add('scale-95', 'opacity-0');
        setTimeout(() => palette?.classList.add('hidden'), 300);
        document.body.style.overflow = '';
    };

    input?.addEventListener('input', (e) => {
        clearTimeout(debounceTimer);
        const q = e.target.value.trim();
        if (q.length < 2) { results?.classList.add('hidden'); return; }
        debounceTimer = setTimeout(() => results?.classList.remove('hidden'), 300);
    });

    document.addEventListener('keydown', (e) => {
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') { e.preventDefault(); window.openSearchPalette(); }
        if (e.key === 'Escape' && !palette?.classList.contains('hidden')) window.closeSearchPalette();
    });
}
