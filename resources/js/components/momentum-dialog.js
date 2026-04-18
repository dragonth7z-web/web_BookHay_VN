/**
 * Momentum Dialog — Add to Cart modal
 * Migrated from public/js/components/momentum-dialog.js + public/js/momentum.js
 */
export function initMomentum() {
    const TTL_MS = 30 * 60 * 1000;
    const FREESHIP = window.APP_CONFIG?.freeship ?? 500000;
    let idleTimer = null;

    // --- Return Momentum ---
    function initReturnMomentum() {
        try {
            const raw = localStorage.getItem('last_added_product');
            if (!raw) return;
            const data = JSON.parse(raw);
            if (Date.now() - data.time < TTL_MS && !sessionStorage.getItem('reentry_shown')) {
                showReentryNotification(data.product);
                sessionStorage.setItem('reentry_shown', 'true');
            } else if (Date.now() - data.time >= TTL_MS) {
                localStorage.removeItem('last_added_product');
            }
        } catch {}
    }

    function showReentryNotification(product) {
        const el = document.createElement('div');
        el.className = 'fixed bottom-4 right-4 bg-white p-4 rounded-xl shadow-2xl border border-gray-100 z-[100] transform transition-transform duration-500 translate-y-[150%] flex items-start gap-4';
        el.innerHTML = `
            <div class="w-12 h-16 rounded overflow-hidden flex-shrink-0 border border-gray-100 p-1 bg-gray-50">
                <img src="${product.image}" class="w-full h-full object-cover rounded-sm" alt="${product.title}">
            </div>
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">CHÀO MỪNG TRỞ LẠI</p>
                <p class="text-sm font-bold text-gray-800 mb-2">Bạn vẫn còn sản phẩm trong giỏ</p>
                <a href="/cart" class="text-xs font-bold text-white bg-primary px-4 py-2 rounded-lg inline-block shadow-sm">Thanh toán ngay</a>
            </div>
            <button class="absolute top-2 right-2 text-gray-400" onclick="this.parentElement.remove()">
                <span class="material-symbols-outlined text-[16px]">close</span>
            </button>`;
        document.body.appendChild(el);
        requestAnimationFrame(() => requestAnimationFrame(() => el.classList.remove('translate-y-[150%]')));
    }

    // --- Idle Detection ---
    function initIdleDetection(ctaSelector, zoneSelector) {
        const cta = document.querySelector(ctaSelector);
        const zone = document.querySelector(zoneSelector);
        if (!cta || !zone) return;
        const reset = () => {
            clearTimeout(idleTimer);
            idleTimer = setTimeout(() => {
                const r = zone.getBoundingClientRect();
                if (r.top <= window.innerHeight && r.bottom >= 0) {
                    cta.classList.add('ring-4', 'ring-primary/20', 'animate-pulse');
                    setTimeout(() => cta.classList.remove('ring-4', 'ring-primary/20', 'animate-pulse'), 3000);
                }
            }, 10000);
        };
        ['mousemove', 'mousedown', 'keydown', 'scroll', 'touchstart'].forEach(e =>
            document.addEventListener(e, reset, { passive: true }));
        reset();
    }

    // --- Dialog ---
    function openMomentumDialog(product, cartTotal) {
        const dialog = document.getElementById('momentum-dialog');
        const overlay = document.getElementById('momentum-overlay');
        const content = document.getElementById('momentum-content');
        if (!dialog) return;

        dialog.classList.remove('hidden');
        requestAnimationFrame(() => {
            overlay?.classList.remove('opacity-0');
            if (window.innerWidth >= 768) {
                content?.classList.remove('scale-95', 'opacity-0');
                content?.classList.add('scale-100', 'opacity-100');
            } else {
                content?.classList.remove('translate-y-full');
                content?.classList.add('translate-y-0');
            }
            dialog.querySelector('.momentum-checkout-btn')?.focus();
        });

        // Hydrate
        const img = dialog.querySelector('.md-product-img');
        const title = dialog.querySelector('.md-product-title');
        const price = dialog.querySelector('.md-product-price');
        if (img) img.src = product.image;
        if (title) title.textContent = product.title;
        if (price) price.textContent = new Intl.NumberFormat('vi-VN').format(product.price) + 'đ';

        const remaining = FREESHIP - cartTotal;
        const pc = dialog.querySelector('.md-progress-container');
        const pf = dialog.querySelector('.md-progress-fill');
        const pt = dialog.querySelector('.md-progress-text');
        if (pc && pf && pt) {
            if (cartTotal >= FREESHIP) {
                pc.style.display = 'block';
                pt.innerHTML = '🎉 <span class="text-green-600 font-bold">Bạn đã được Miễn phí vận chuyển!</span>';
                pf.style.width = '100%';
            } else if (remaining < 200000) {
                pc.style.display = 'block';
                pt.innerHTML = `Chỉ thêm <strong class="text-primary">${new Intl.NumberFormat('vi-VN').format(remaining)}đ</strong> để được Freeship`;
                pf.style.width = Math.max(10, (cartTotal / FREESHIP) * 100) + '%';
            } else {
                pc.style.display = 'none';
            }
        }

        localStorage.setItem('last_added_product', JSON.stringify({ product: { title: product.title, image: product.image }, time: Date.now() }));
        window.THLD_Analytics?.trackEvent('momentum_action', { product_id: product.id, new_total: cartTotal });
    }

    function closeMomentumDialog() {
        const dialog = document.getElementById('momentum-dialog');
        const overlay = document.getElementById('momentum-overlay');
        const content = document.getElementById('momentum-content');
        if (!dialog) return;
        overlay?.classList.add('opacity-0');
        if (window.innerWidth >= 768) {
            content?.classList.remove('scale-100', 'opacity-100');
            content?.classList.add('scale-95', 'opacity-0');
        } else {
            content?.classList.remove('translate-y-0');
            content?.classList.add('translate-y-full');
        }
        setTimeout(() => dialog.classList.add('hidden'), 300);
    }

    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeMomentumDialog(); });

    // Expose globally (used by blade inline handlers)
    window.THLD_Momentum = { openMomentumDialog, closeMomentumDialog, initIdleDetection, initReturnMomentum };

    initReturnMomentum();
}
