@extends('layouts.app')

@section('title', 'Thanh Toán - THLD')

@section('content')
<div class="max-w-main mx-auto px-2 py-8">
    {{-- Checkout Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white flex items-center gap-3">
                <span class="material-symbols-outlined text-primary text-4xl">payments</span>
                THANH TOÁN
            </h1>
            <p class="text-slate-500 dark:text-slate-400 mt-2">Hoàn tất đơn hàng của bạn với các bước đơn giản</p>
        </div>
        
        {{-- Progress Steps --}}
        <div class="flex items-center gap-2">
            <div class="flex items-center gap-2 text-primary font-bold">
                <span class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center text-sm">1</span>
                <span class="hidden sm:inline">Giỏ hàng</span>
            </div>
            <div class="w-8 h-px bg-slate-200 dark:bg-slate-700"></div>
            <div class="flex items-center gap-2 text-primary font-bold">
                <span class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center text-sm">2</span>
                <span class="hidden sm:inline">Thanh toán</span>
            </div>
            <div class="w-8 h-px bg-slate-200 dark:bg-slate-700"></div>
            <div class="flex items-center gap-2 text-slate-400">
                <span class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-sm border border-slate-200 dark:border-slate-700">3</span>
                <span class="hidden sm:inline">Hoàn tất</span>
            </div>
        </div>
    </div>

    <form action="{{ route('checkout.store') }}" method="POST" id="checkout-form">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            {{-- Left Column: Shipping & Payment --}}
            <div class="lg:col-span-8 space-y-6">
                
                {{-- 1. Shipping Address --}}
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm p-6 md:p-8">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary">location_on</span>
                            Địa Chỉ Giao Hàng
                        </h2>
                        <a href="{{ route('account.profile') }}" class="text-xs font-bold text-primary hover:underline">Thêm địa chỉ mới</a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @forelse($shippingAddresses as $address)
                            <label class="radio-card {{ $loop->first ? 'active border-primary bg-primary/[0.05]' : 'border-slate-100 dark:border-slate-800' }}">
                                <input type="radio" name="shipping_address_id" value="{{ $address->id }}" {{ $loop->first ? 'checked' : '' }} class="hidden">
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="font-bold text-slate-900 dark:text-white">{{ $address->receiver_name }}</span>
                                        @if($address->is_default)
                                            <span class="text-[10px] bg-primary/10 text-primary px-2 py-0.5 rounded-lg font-black uppercase">Mặc định</span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed">
                                        {{ $address->phone }}<br>
                                        {{ $address->full_address }}
                                    </p>
                                </div>
                                <div class="w-5 h-5 rounded-lg border-2 border-slate-200 dark:border-slate-700 flex items-center justify-center shrink-0 group-[.active]:border-primary">
                                    <div class="w-2.5 h-2.5 rounded-lg bg-primary opacity-0 group-[.active]:opacity-100 transition-opacity"></div>
                                </div>
                            </label>
                        @empty
                            <div class="col-span-full border-2 border-dashed border-slate-200 dark:border-slate-700 rounded-2xl p-8 text-center bg-slate-50 dark:bg-slate-800/50">
                                <span class="material-symbols-outlined text-slate-300 text-5xl mb-3">add_location_alt</span>
                                <p class="text-slate-500 dark:text-slate-400 mb-4 font-medium">Bạn chưa có địa chỉ nhận hàng nào</p>
                                <a href="{{ route('account.profile') }}" class="w-full text-center bg-primary text-white font-black py-4 rounded-lg text-base uppercase hover:bg-primary-dark transition-all shadow-md active:scale-[0.98]">Thêm ngay</a>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- 2. Shipping Method --}}
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm p-6 md:p-8">
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2 mb-6">
                        <span class="material-symbols-outlined text-primary">local_shipping</span>
                        Phương Thức Vận Chuyển
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <label class="radio-card active border-primary bg-primary/[0.05]">
                            <input type="radio" name="shipping_method" value="standard" checked class="hidden">
                            <div class="flex-1">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="font-bold text-slate-900 dark:text-white">Giao hàng tiêu chuẩn</span>
                                    <span class="text-xs font-black text-green-600 uppercase">Miễn phí</span>
                                </div>
                                <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">Thời gian nhận hàng: 3-5 ngày làm việc</p>
                            </div>
                        </label>
                        <label class="radio-card border-slate-100 dark:border-slate-800">
                            <input type="radio" name="shipping_method" value="express" class="hidden">
                            <div class="flex-1">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="font-bold text-slate-900 dark:text-white">Giao hàng hỏa tốc</span>
                                    <span class="text-xs font-black text-slate-900 dark:text-white uppercase">35.000đ</span>
                                </div>
                                <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">Thời gian nhận hàng: 1-2 ngày làm việc</p>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- 3. Payment Method --}}
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm p-6 md:p-8">
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2 mb-6">
                        <span class="material-symbols-outlined text-primary">account_balance_wallet</span>
                        Phương Thức Thanh Toán
                    </h2>
                    
                    <div class="grid grid-cols-1 gap-4">
                        <label class="radio-card active border-primary bg-primary/[0.05]">
                            <input type="radio" name="payment_method" value="cod" checked class="hidden">
                            <span class="material-symbols-outlined text-3xl text-primary">payments</span>
                            <div class="flex-1">
                                <span class="font-bold text-slate-900 dark:text-white block">Thanh toán khi nhận hàng (COD)</span>
                                <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed mt-1">Quý khách thanh toán bằng tiền mặt khi shipper giao hàng.</p>
                            </div>
                        </label>
                        <label class="radio-card border-slate-100 dark:border-slate-800">
                            <input type="radio" name="payment_method" value="vnpay" class="hidden">
                            <span class="material-symbols-outlined text-3xl text-blue-500">account_balance</span>
                            <div class="flex-1">
                                <span class="font-bold text-slate-900 dark:text-white block">Thanh toán qua VNPAY (ATM/QRCode/Visa)</span>
                                <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed mt-1">Cổng thanh toán an toàn, bảo mật tuyệt đối.</p>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            {{-- Right Column: Order Summary --}}
            <div class="lg:col-span-4 sticky top-24">
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-xl p-6 md:p-8">
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2 mb-6">
                        <span class="material-symbols-outlined text-primary">shopping_bag</span>
                        Đơn Hàng
                    </h2>

                    {{-- Item List --}}
                    <div class="space-y-4 mb-8 max-h-[300px] overflow-y-auto pr-2 hide-scrollbar">
                        @foreach($items as $item)
                        <div class="flex gap-4 items-center">
                            <div class="w-16 h-20 rounded-lg overflow-hidden bg-slate-50 dark:bg-slate-800 shrink-0 border border-slate-100 dark:border-slate-800">
                                <img src="{{ $item->book?->cover_image ? (Str::startsWith($item->book->cover_image, 'http') ? $item->book->cover_image : asset('storage/' . $item->book->cover_image)) : 'https://placehold.co/100x120' }}" 
                                     alt="{{ $item->book?->title }}" class="w-full h-full object-cover">
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-slate-900 dark:text-white truncate">{{ $item->book?->title }}</p>
                                <div class="flex items-center justify-between mt-1">
                                    <span class="text-xs text-slate-500">SL: x{{ $item->quantity }}</span>
                                    <span class="text-sm font-bold text-slate-900 dark:text-white">{{ number_format($item->price_snapshot * $item->quantity, 0, ',', '.') }}đ</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{-- Totals --}}
                    <div class="space-y-4 pt-6 border-t border-slate-100 dark:border-slate-800">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-slate-500 dark:text-slate-400">Tạm tính ({{ $items->count() }} mặt hàng)</span>
                            <span class="font-bold text-slate-900 dark:text-white">{{ number_format($subtotal, 0, ',', '.') }}đ</span>
                        </div>
                        @if($discountAmount > 0)
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-slate-500 dark:text-slate-400">Giảm giá (Voucher)</span>
                            <span class="font-black text-green-600">-{{ number_format($discountAmount, 0, ',', '.') }}đ</span>
                        </div>
                        @endif
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-slate-500 dark:text-slate-400">Phí vận chuyển</span>
                            <span class="font-black text-green-600 uppercase">Miễn phí</span>
                        </div>
                        
                        <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex justify-between items-center">
                            <span class="text-base font-bold text-slate-900 dark:text-white uppercase tracking-tight">Tổng thanh toán</span>
                            <span class="text-2xl font-black text-primary">{{ number_format(max(0, $subtotal - $discountAmount), 0, ',', '.') }}đ</span>
                        </div>
                    </div>

                    <button type="submit" class="w-full h-16 bg-primary text-white rounded-2xl mt-8 font-black text-lg shadow-lg shadow-primary/30 hover:bg-primary-dark hover:-translate-y-1 transition-all active:scale-95 flex items-center justify-center gap-3">
                        ĐẶT HÀNG NGAY
                        <span class="material-symbols-outlined">arrow_forward</span>
                    </button>
                    
                    <p class="text-[11px] text-center text-slate-400 mt-4 px-4 uppercase font-bold tracking-widest leading-relaxed">
                        Bằng việc nhấn đặt hàng, bạn đồng ý với các <a href="#" class="text-slate-500 underline">điều khoản dịch vụ</a> của THLD
                    </p>
                </div>

                {{-- Trust Badges --}}
                <div class="mt-6 flex items-center justify-between px-4 opacity-50">
                    <span class="material-symbols-outlined text-4xl">verified_user</span>
                    <span class="material-symbols-outlined text-4xl">security</span>
                    <span class="material-symbols-outlined text-4xl">local_shipping</span>
                    <span class="material-symbols-outlined text-4xl">sentiment_satisfied</span>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
    @vite('resources/js/checkout/checkout.js')
@endpush
@endsection
