@extends('layouts.app')

@section('title', 'Giỏ Hàng - THLD')

@section('content')
<main class="max-w-main mx-auto px-2 py-6">
    <div class="mb-6">
        <h1 class="text-xl font-bold uppercase text-gray-800">Giỏ Hàng ({{ $cart ? $cart->items->count() : 0 }} sản phẩm)</h1>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-12 gap-6 items-start">
        <div class="col-span-12 lg:col-span-9 space-y-4">
            @if($items->isNotEmpty())
                <div class="main-section">
                    <div class="bg-white p-4 border-b border-gray-100 grid grid-cols-12 gap-4 items-center hidden md:grid">
                        <div class="col-span-1 flex items-center">
                            <input class="rounded text-primary focus:ring-primary h-5 w-5 cursor-pointer" type="checkbox" id="select-all" />
                        </div>
                        <div class="col-span-5 text-sm font-bold text-gray-800 uppercase">Sản phẩm</div>
                        <div class="col-span-2 text-sm font-bold text-gray-800 uppercase text-center">Đơn giá</div>
                        <div class="col-span-2 text-sm font-bold text-gray-800 uppercase text-center">Số lượng</div>
                        <div class="col-span-2 text-sm font-bold text-gray-800 uppercase text-right">Thành tiền</div>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @foreach($items as $item)
                        <div class="p-4 grid grid-cols-1 md:grid-cols-12 gap-4 md:items-center bg-white hover:bg-gray-50/50 transition-colors">
                            <div class="col-span-1 hidden md:block">
                                <input class="rounded text-primary focus:ring-primary h-5 w-5 cursor-pointer item-checkbox" type="checkbox" name="items[]" value="{{ $item->id }}" />
                            </div>
                            <div class="col-span-5 flex gap-4 items-start md:items-center">
                                <div class="w-20 h-28 md:w-24 md:h-32 flex-shrink-0 bg-white border border-gray-100 rounded-md p-1">
                                    <img alt="{{ $item->book->title }}" class="w-full h-full object-contain"
                                        src="{{ $item->book->cover_image ? (Str::startsWith($item->book->cover_image, ['http', 'https']) ? $item->book->cover_image : asset('storage/' . $item->book->cover_image)) : 'https://placehold.co/400x600?text=No+Image' }}" />
                                </div>
                                <div class="flex-1">
                                    <a href="{{ route('books.show', $item->book->slug) }}" class="font-bold text-gray-800 text-sm mb-1 leading-snug hover:text-primary transition-colors block">
                                        {{ $item->book->title }}
                                    </a>
                                    <p class="text-xs text-charcoal font-medium hidden md:block italic">SKU: {{ $item->book->sku }}</p>
                                    
                                    <div class="mt-3 flex gap-4 text-[11px] text-gray-500">
                                        <form action="{{ route('cart.remove', $item->id) }}" method="POST" onsubmit="return confirm('Xác nhận xóa?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="hover:text-primary flex items-center gap-1 text-primary-dark font-medium">
                                                <span class="material-symbols-outlined text-sm">delete</span> Xóa
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-2 text-left md:text-center mt-2 md:mt-0">
                                <span class="text-sm font-bold text-gray-800 block">{{ number_format($item->book->sale_price, 0, ',', '.') }}đ</span>
                                @if($item->book->original_price > $item->book->sale_price)
                                    <span class="text-[10px] text-charcoal line-through block font-medium">{{ number_format($item->book->original_price, 0, ',', '.') }}đ</span>
                                @endif
                            </div>
                            <div class="col-span-2 flex justify-center">
                                <div class="flex items-center border border-gray-300 rounded-md bg-white">
                                    <form action="{{ route('cart.update', $item->id) }}" method="POST" class="inline-flex items-center">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="quantity" value="{{ $item->quantity - 1 }}">
                                        <button type="submit" {{ $item->quantity <= 1 ? 'disabled' : '' }} class="w-8 h-8 flex items-center justify-center text-gray-500 hover:text-primary hover:bg-gray-50 transition-colors disabled:opacity-30">
                                            <span class="material-symbols-outlined text-lg">remove</span>
                                        </button>
                                    </form>
                                    
                                    <span class="w-10 text-center text-sm font-bold text-gray-800 px-1">{{ $item->quantity }}</span>
                                    
                                    <form action="{{ route('cart.update', $item->id) }}" method="POST" class="inline-flex items-center">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="quantity" value="{{ $item->quantity + 1 }}">
                                        <button type="submit" class="w-8 h-8 flex items-center justify-center text-gray-500 hover:text-primary hover:bg-gray-50 transition-colors rounded-lg">
                                            <span class="material-symbols-outlined text-lg">add</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <div class="col-span-2 text-right hidden md:block">
                                <span class="text-base font-black text-primary">{{ number_format($item->quantity * $item->book->sale_price, 0, ',', '.') }}đ</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="main-section p-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div class="flex items-center gap-6">
                        <form action="{{ route('cart.clear') }}" method="POST" onsubmit="return confirm('Xác nhận xóa toàn bộ giỏ hàng?')">
                            @csrf
                            <button type="submit" class="text-sm font-bold text-charcoal hover:text-primary transition-colors uppercase flex items-center gap-1">
                                <span class="material-symbols-outlined text-[16px]">delete_sweep</span> Xóa toàn bộ
                            </button>
                        </form>
                    </div>
                    <div class="text-right w-full sm:w-auto">
                        <p class="text-sm text-gray-600 font-medium italic">Vận chuyển miễn phí cho đơn hàng từ 500k</p>
                    </div>
                </div>
            @else
                <div class="main-section p-20 text-center">
                    <div class="w-24 h-24 bg-gray-50 rounded-lg flex items-center justify-center mx-auto mb-6">
                        <span class="material-symbols-outlined text-5xl text-gray-300">shopping_cart_off</span>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 mb-2">Giỏ hàng của bạn đang trống</h3>
                    <p class="text-gray-500 mb-8">Hãy chọn thêm vài cuốn sách hay để tiếp tục nhé!</p>
                    <a href="{{ route('books.search') }}" class="inline-block bg-primary text-white px-8 py-3 rounded-lg font-bold hover:shadow-lg transition-all">
                        TÌM KIẾM SÁCH NGAY
                    </a>
                </div>
            @endif
        </div>
        
        <aside class="col-span-12 lg:col-span-3 space-y-4">
            <div class="main-section p-5">
                <h2 class="text-sm font-bold text-gray-800 uppercase mb-4 tracking-wider flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-xl">confirmation_number</span> 
                    Mã khuyến mãi
                </h2>
                <form action="{{ route('cart.apply_voucher') }}" method="POST" class="flex flex-col gap-2">
                    @csrf
                    <input name="code" class="flex-1 w-full border border-gray-300 rounded-lg text-sm px-3 py-2.5 focus:ring-primary focus:border-primary" placeholder="Nhập mã voucher..." type="text" />
                    <button type="submit" class="bg-gray-800 text-white text-xs font-bold px-4 py-2.5 rounded-lg hover:bg-black transition-all whitespace-nowrap">ÁP DỤNG</button>
                </form>
            </div>
            
            <div class="main-section p-5">
                <h2 class="text-sm font-bold text-gray-800 uppercase mb-4 tracking-wider">Chi tiết đơn hàng</h2>
                <div class="space-y-3 mb-6">
                    <div class="flex justify-between text-sm text-charcoal font-medium">
                        <span>Tạm tính ({{ $items->count() }} SP)</span>
                        <span class="text-gray-800 font-bold">{{ number_format($items->sum(fn($i) => $i->quantity * $i->book->sale_price), 0, ',', '.') }} đ</span>
                    </div>
                    <div class="flex justify-between text-sm text-charcoal font-medium">
                        <span>Phí vận chuyển</span>
                        <span class="text-green-600 font-bold uppercase text-[11px]">Miễn phí</span>
                    </div>
                    <div class="flex justify-between text-sm text-charcoal font-medium">
                        <span>Giảm giá voucher</span>
                        <span class="text-primary font-bold">0 đ</span>
                    </div>
                    <div class="pt-4 border-t border-gray-100 flex justify-between items-end mt-2">
                        <span class="text-sm font-bold text-gray-800 uppercase">Tổng cộng</span>
                        <span class="text-2xl font-black text-primary">{{ number_format($items->sum(fn($i) => $i->quantity * $i->book->sale_price), 0, ',', '.') }} đ</span>
                    </div>
                </div>
                
                @if($items->isNotEmpty())
                    <a href="{{ route('checkout.index') }}" class="block w-full text-center bg-primary text-white font-black py-4 rounded-lg text-base uppercase hover:bg-primary-dark transition-all shadow-md active:scale-[0.98]">
                        Thanh Toán
                    </a>
                @else
                    <button disabled class="block w-full text-center bg-gray-300 text-gray-500 font-black py-4 rounded-lg text-base uppercase cursor-not-allowed">
                        Thanh Toán
                    </button>
                @endif
                <p class="text-[10px] text-center text-charcoal font-medium mt-4 italic">
                    (Giá đã bao gồm VAT nếu có)
                </p>
            </div>
            
            <div class="main-section p-4 bg-green-50/50 border border-green-100 hidden lg:block">
                <div class="flex gap-3">
                    <span class="material-symbols-outlined text-green-600 mt-1">verified_user</span>
                    <div>
                        <p class="text-xs font-bold text-gray-800 mb-1">THLD Cam Kết</p>
                        <ul class="text-[10px] text-charcoal space-y-1 font-medium">
                            <li class="flex items-center gap-1"><span class="material-symbols-outlined text-[12px] text-green-600 font-bold">check_circle</span> 100% Sách chính hãng</li>
                            <li class="flex items-center gap-1"><span class="material-symbols-outlined text-[12px] text-green-600 font-bold">check_circle</span> Miễn phí đổi trả 30 ngày</li>
                        </ul>
                    </div>
                </div>
            </div>
        </aside>
    </div>
</main>
@endsection

