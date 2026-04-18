/**
 * Checkout Failed — auto redirect countdown
 * Migrated from public/js/checkout-failed.js
 */
let countdown = 10;
const countdownEl = document.createElement('div');
countdownEl.className = 'text-center text-sm text-gray-500 mt-4';
countdownEl.innerHTML = `Tự động chuyển về trang thanh toán sau <span id="countdown">${countdown}</span> giây...`;
document.querySelector('.bg-primary\\/5')?.appendChild(countdownEl);

const timer = setInterval(() => {
    countdown--;
    const span = document.getElementById('countdown');
    if (span) span.textContent = countdown;
    if (countdown <= 0) { clearInterval(timer); window.location.href = '/checkout'; }
}, 1000);

document.addEventListener('click', () => {
    clearInterval(timer);
    countdownEl.parentNode?.removeChild(countdownEl);
}, { once: true });
