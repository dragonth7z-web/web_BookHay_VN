/**
 * Checkout Success — display order code from URL
 * Migrated from public/js/checkout-success.js
 */
const orderCode = new URLSearchParams(window.location.search).get('order');
if (orderCode) {
    const el = document.getElementById('orderCode');
    if (el) el.textContent = orderCode;
}
