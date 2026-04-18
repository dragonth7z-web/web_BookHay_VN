{{-- PREMIUM MOMENTUM DIALOG --}}
{{-- Use requestAnimationFrame for perceived <100ms performance --}} <div id="momentum-dialog"
    class="fixed inset-0 z-[999] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    {{-- Background overlay --}}
    <div id="momentum-overlay"
        class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity duration-300 opacity-0"
        onclick="window.THLD_Momentum.closeMomentumDialog()"></div>

    <div class="fixed inset-0 z-10 overflow-y-auto">
        {{-- Desktop: Center, Mobile: Bottom --}}
        <div class="flex min-h-full items-end justify-center text-center md:items-center md:p-4">

            <div id="momentum-content"
                class="relative transform overflow-hidden bg-white text-left shadow-2xl transition-all duration-300 w-full md:w-[480px] md:rounded-2xl rounded-t-2xl md:scale-95 md:opacity-0 translate-y-full md:translate-y-0">

                {{-- Drag Handle for Mobile --}}
                <div class="md:hidden flex justify-center pt-3 pb-1"
                    onclick="window.THLD_Momentum.closeMomentumDialog()">
                    <div class="w-12 h-1.5 bg-gray-200 rounded-full"></div>
                </div>

                {{-- Close Button (Desktop) --}}
                <div class="hidden md:flex absolute right-4 top-4 z-10">
                    <button type="button"
                        class="text-gray-400 hover:text-gray-600 bg-gray-50 hover:bg-gray-100 rounded-full p-2 transition-colors"
                        onclick="window.THLD_Momentum.closeMomentumDialog()">
                        <span class="material-symbols-outlined text-lg">close</span>
                    </button>
                </div>

                <div class="px-6 pb-6 pt-4 md:pt-8 bg-white">

                    {{-- Emotional Success Feedback --}}
                    <div
                        class="flex items-center gap-3 mb-6 bg-green-50/50 p-3 rounded-xl border border-green-100/50 animate-pop">
                        <div
                            class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center border border-green-200 flex-shrink-0">
                            <span class="material-symbols-outlined text-green-600 text-[20px] font-bold">check</span>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-green-700 leading-tight">Đã thêm vào giỏ hàng</h3>
                            <p class="text-xs text-green-600/80 font-medium">Chỉ còn 1 bước để hoàn tất</p>
                        </div>
                    </div>

                    {{-- Mini Cart Item --}}
                    <div class="flex items-center gap-4 mb-6">
                        <div
                            class="w-20 aspect-[3/4] bg-gray-50 rounded-lg overflow-hidden border border-gray-100 shadow-sm flex-shrink-0">
                            <img src="" alt="Product" class="md-product-img w-full h-full object-cover">
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4
                                class="md-product-title text-sm font-bold text-gray-800 line-clamp-2 leading-snug mb-2 font-['Inter']">
                                Tên sản phẩm</h4>
                            <div class="md-product-price text-primary font-bold text-lg font-['Outfit']">0đ</div>
                        </div>
                    </div>

                    {{-- Cart Value Momentum (Freeship Progress) --}}
                    <div class="md-progress-container mb-6 bg-gray-50 rounded-xl p-4 border border-gray-100"
                        style="display:none;">
                        <p class="md-progress-text text-xs font-bold text-gray-600 mb-2"></p>
                        <div class="w-full h-2.5 bg-gray-200 rounded-full overflow-hidden">
                            <div class="md-progress-fill h-full bg-primary rounded-full transition-all duration-1000 ease-out"
                                style="width: 0%"></div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex flex-col gap-3">
                        <a href="{{ route('cart.index') }}"
                            class="momentum-checkout-btn w-full bg-primary hover:bg-red-700 text-white font-bold py-3.5 px-4 rounded-xl shadow-md hover:shadow-lg transition-all text-center focus:ring-4 focus:ring-primary/30 flex items-center justify-center gap-2">
                            <span>Thanh toán ngay</span>
                            <span class="material-symbols-outlined text-[18px]">shopping_cart_checkout</span>
                        </a>
                        <button type="button"
                            class="w-full bg-white text-gray-600 font-bold py-3.5 px-4 rounded-xl border-2 border-gray-200 hover:bg-gray-50 transition-colors"
                            onclick="window.THLD_Momentum.closeMomentumDialog()">
                            Tiếp tục mua sắm
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>
    </div>

{{-- momentum-dialog.js is bundled in resources/js/app.js --}}