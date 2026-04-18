/**
 * Contact — form submit loading state
 * Migrated from public/js/pages/contact.js
 */
document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('form[action*="contact"]');
    form?.addEventListener('submit', () => {
        const btn = form.querySelector('[type="submit"]');
        if (btn) btn.disabled = true;
    });
});
