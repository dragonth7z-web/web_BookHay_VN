/**
 * Checkout — radio card active state
 * Migrated from public/js/checkout/checkout.js
 */
document.addEventListener('DOMContentLoaded', () => {
    ['payment_method', 'shipping'].forEach(name => {
        document.querySelectorAll(`input[name="${name}"]`).forEach(radio => {
            radio.addEventListener('change', function () {
                document.querySelectorAll(`input[name="${name}"]`).forEach(r => {
                    r.closest('.radio-card')?.classList.remove('active', 'border-primary', 'bg-red-50/50');
                    r.closest('.radio-card')?.classList.add('border-gray-200');
                });
                if (this.checked) {
                    this.closest('.radio-card')?.classList.remove('border-gray-200');
                    this.closest('.radio-card')?.classList.add('active', 'border-primary', 'bg-red-50/50');
                }
            });
        });
    });
});
