/**
 * FAQ — accordion toggle
 * Migrated from public/js/pages/faq.js
 */
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.faq-question').forEach(btn => {
        btn.addEventListener('click', () => btn.nextElementSibling?.classList.toggle('hidden'));
    });
});
