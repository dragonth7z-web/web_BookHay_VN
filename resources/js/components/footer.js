/**
 * Footer — newsletter form validation
 * Migrated from public/js/components/footer.js
 */
export function initFooter() {
    const form = document.getElementById('footer-newsletter-form');
    if (!form) return;
    form.addEventListener('submit', (e) => {
        e.preventDefault();
        const input = form.querySelector('input[type="email"]');
        const email = input?.value.trim() ?? '';
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            if (input) { input.style.borderColor = '#C92127'; input.focus(); }
            return;
        }
        if (input) { input.style.borderColor = ''; input.value = ''; }
        window.showToast?.('Đăng ký thành công! Cảm ơn bạn.', 'success');
    });
}
