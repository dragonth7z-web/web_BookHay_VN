/**
 * Order Tracking
 * Migrated from public/js/order-tracking.js
 */
window.trackOrder = () => {
    const orderCode = document.getElementById('orderCode')?.value.trim();
    const customerInfo = document.getElementById('customerInfo')?.value.trim();
    if (!orderCode || !customerInfo) { alert('Vui lòng nhập đầy đủ thông tin'); return; }

    const resultDiv = document.getElementById('trackingResult');
    const errorDiv = document.getElementById('errorMessage');

    setTimeout(() => {
        resultDiv?.classList.remove('hidden');
        errorDiv?.classList.add('hidden');
        const displayCode = document.getElementById('displayOrderCode');
        const orderDate = document.getElementById('orderDate');
        if (displayCode) displayCode.textContent = orderCode;
        if (orderDate) orderDate.textContent = new Date().toLocaleString('vi-VN');
        resultDiv?.scrollIntoView({ behavior: 'smooth' });
    }, 1000);
};
