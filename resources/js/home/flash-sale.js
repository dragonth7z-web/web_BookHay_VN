/**
 * Flash Sale — countdown timer + viewer count
 * Migrated from public/js/home/flash-sale.js
 */
export function initFlashSale() {
    // Countdown
    const metaEl = document.querySelector('meta[name="flash-sale-end"]');
    const endTime = metaEl ? new Date(metaEl.content).getTime() : null;

    if (endTime && !isNaN(endTime)) {
        const hWrap = document.getElementById('fs-hours-wrap');
        const mWrap = document.getElementById('fs-minutes-wrap');
        const sWrap = document.getElementById('fs-seconds-wrap');

        if (hWrap && mWrap && sWrap) {
            const pad = n => String(n).padStart(2, '0');
            const updateDigits = (container, value) => {
                const cells = container.querySelectorAll('.fs-digital-digit');
                if (cells.length >= 2) { cells[0].textContent = pad(value)[0]; cells[1].textContent = pad(value)[1]; }
            };
            const tick = () => {
                const diff = Math.max(0, endTime - Date.now());
                updateDigits(hWrap, Math.floor(diff / 3600000));
                updateDigits(mWrap, Math.floor((diff % 3600000) / 60000));
                updateDigits(sWrap, Math.floor((diff % 60000) / 1000));
                if (diff > 0) setTimeout(tick, 1000);
            };
            tick();
        }
    }

    // Viewer count simulation
    const viewerEl = document.getElementById('fs-viewer-num');
    if (viewerEl) {
        let count = parseInt(viewerEl.textContent, 10);
        const min = parseInt(viewerEl.dataset.min, 10) || 50;
        const max = parseInt(viewerEl.dataset.max, 10) || 200;
        const update = () => {
            count = Math.min(max, Math.max(min, count + Math.floor(Math.random() * 7) - 3));
            viewerEl.textContent = count;
            setTimeout(update, Math.floor(Math.random() * 5000) + 5000);
        };
        setTimeout(update, 5000);
    }
}
