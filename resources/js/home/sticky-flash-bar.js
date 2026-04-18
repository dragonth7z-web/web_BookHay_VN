/**
 * Sticky Flash Bar — syncs countdown from main flash sale timer
 * Migrated from public/js/home/sticky-flash-bar.js
 */
export function initStickyFlashBar() {
    const bar = document.getElementById('sticky-flash-bar');
    const flashSection = document.getElementById('flash-sale');
    if (!bar || !flashSection) return;

    window.addEventListener('scroll', () => {
        if (window.scrollY > 800) {
            bar.classList.add('visible'); bar.classList.remove('hidden');
        } else {
            bar.classList.remove('visible');
            setTimeout(() => { if (!bar.classList.contains('visible')) bar.classList.add('hidden'); }, 400);
        }
    }, { passive: true });

    const getDigits = (wrapId) => {
        const wrap = document.getElementById(wrapId);
        if (!wrap) return '00';
        const digits = wrap.querySelectorAll('.fs-digital-digit');
        return digits.length >= 2 ? digits[0].textContent + digits[1].textContent : '00';
    };

    setInterval(() => {
        const h = document.getElementById('st-hours');
        const m = document.getElementById('st-minutes');
        const s = document.getElementById('st-seconds');
        if (h) h.innerText = getDigits('fs-hours-wrap');
        if (m) m.innerText = getDigits('fs-minutes-wrap');
        if (s) s.innerText = getDigits('fs-seconds-wrap');
    }, 1000);
}
