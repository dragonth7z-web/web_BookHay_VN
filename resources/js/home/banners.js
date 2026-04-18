/**
 * Hero Banners Slider
 * Migrated from public/js/home/banners.js
 */
export function initBanners() {
    const slides = document.querySelectorAll('.hero-slide');
    const dots = document.querySelectorAll('.slider-dot');
    const bar = document.getElementById('slideProgressBar');
    if (!slides.length) return;

    let current = 0, timer;

    const goTo = (idx) => {
        slides[current].classList.remove('active');
        dots[current]?.classList.remove('active');
        current = (idx + slides.length) % slides.length;
        slides[current].classList.add('active');
        dots[current]?.classList.add('active');
        if (bar) { bar.classList.remove('animating'); bar.style.width = '0%'; void bar.offsetWidth; bar.classList.add('animating'); }
    };

    const startAuto = () => { timer = setInterval(() => goTo(current + 1), 5000); };
    const resetAuto = () => { clearInterval(timer); startAuto(); };

    document.getElementById('heroPrev')?.addEventListener('click', () => { goTo(current - 1); resetAuto(); });
    document.getElementById('heroNext')?.addEventListener('click', () => { goTo(current + 1); resetAuto(); });
    dots.forEach(dot => dot.addEventListener('click', () => { goTo(+dot.dataset.slide); resetAuto(); }));
    document.addEventListener('keydown', e => {
        if (e.key === 'ArrowLeft') { goTo(current - 1); resetAuto(); }
        if (e.key === 'ArrowRight') { goTo(current + 1); resetAuto(); }
    });

    goTo(0);
    startAuto();
}
