(function () {
    document.addEventListener('DOMContentLoaded', function() {
        const selects = document.querySelectorAll('.flash-sale-book-select');
        selects.forEach(sel => {
            sel.addEventListener('change', function () {
                const targetId = this.dataset.priceTarget;
                const input = document.getElementById(targetId);
                if (!input) return;

                const salePrice = this.options[this.selectedIndex]?.dataset?.salePrice;
                if (!salePrice) return;

                if (!input.value) {
                    input.value = salePrice;
                }
            });
        });
    });
})();
