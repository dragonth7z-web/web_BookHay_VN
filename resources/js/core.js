/**
 * THLD Bookstore — Core JS
 * Migrated from public/js/app-core.js
 * Handles: scroll reveal, reading progress, mobile nav, magnetic buttons
 */

// Low-perf device detection
if (navigator.hardwareConcurrency && navigator.hardwareConcurrency < 4) {
    document.documentElement.classList.add('low-perf');
}

// Scroll Reveal + Reading Progress
(function () {
    const readingProgress = document.getElementById('reading-progress');
    const revealElements = document.querySelectorAll('.scroll-reveal');

    if (revealElements.length > 0) {
        const observer = new IntersectionObserver(
            entries => entries.forEach(e => {
                if (e.isIntersecting) { e.target.classList.add('visible'); observer.unobserve(e.target); }
            }),
            { threshold: 0.01, rootMargin: '0px 0px -50px 0px' }
        );
        revealElements.forEach(el => observer.observe(el));
        setTimeout(() => revealElements.forEach(el => el.classList.add('visible')), 2500);
    }

    window.addEventListener('scroll', () => {
        if (!readingProgress) return;
        const docHeight = document.documentElement.scrollHeight - window.innerHeight;
        if (docHeight > 0) readingProgress.style.width = ((window.scrollY / docHeight) * 100) + '%';
    }, { passive: true });
})();

// Mobile Nav
window.openMobileNav = function () {
    document.getElementById('mobile-nav')?.classList.add('open');
    document.getElementById('mobile-nav-overlay')?.classList.add('open');
    document.body.style.overflow = 'hidden';
};
window.closeMobileNav = function () {
    document.getElementById('mobile-nav')?.classList.remove('open');
    document.getElementById('mobile-nav-overlay')?.classList.remove('open');
    document.body.style.overflow = '';
};

// Notify Me
window.notifyMe = function (targetName) {
    window.showToast?.(`Chúng tôi sẽ thông báo về ${targetName || 'ưu đãi này'} sớm nhất!`, 'info');
};

// Magnetic Buttons
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.magnetic-btn').forEach(btn => {
        btn.addEventListener('mousemove', function (e) {
            const rect = this.getBoundingClientRect();
            const dx = (e.clientX - rect.left - rect.width / 2) * 0.3;
            const dy = (e.clientY - rect.top - rect.height / 2) * 0.3;
            this.style.transform = `translate(${dx}px, ${dy}px)`;
            const icon = this.querySelector('.material-symbols-outlined');
            if (icon) icon.style.transform = `translate(${dx * 0.33}px, ${dy * 0.33}px)`;
        });
        btn.addEventListener('mouseleave', function () {
            this.style.transform = '';
            this.querySelector('.material-symbols-outlined')?.style.setProperty('transform', '');
        });
    });
});
