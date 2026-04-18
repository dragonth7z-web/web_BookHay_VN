@extends('layouts.app')

@section('title', 'Đặt hàng thất bại - THLD')

@section('content')
<div class="max-w-main mx-auto px-2 py-8">
    <div class="text-center mb-8">
        <!-- Failed Icon -->
        <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <span class="material-symbols-outlined text-4xl text-red-600">error</span>
        </div>
        
        <h1 class="text-3xl font-bold text-gray-800 mb-4">Đặt hàng thất bại!</h1>
        <p class="text-gray-600 text-lg">Rất tiếc, đã có lỗi xảy ra trong quá trình đặt hàng</p>
    </div>

    <!-- Error Info Card -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 mb-6">
        <div class="text-center mb-6">
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-red-50 border border-red-200 rounded-lg">
                <span class="material-symbols-outlined text-red-500">info</span>
                <span class="text-red-800 font-medium">Lỗi thanh toán hoặc kết nối mạng</span>
            </div>
        </div>

        <div class="bg-gray-50 rounded-lg p-6">
            <h3 class="font-semibold text-gray-800 mb-4">Nguyên nhân có thể xảy ra:</h3>
            <ul class="space-y-3 text-gray-600">
                <li class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-red-500 mt-0.5">error_outline</span>
                    <div>
                        <strong>Lỗi kết nối:</strong> Mất kết nối internet trong quá trình xử lý
                    </div>
                </li>
                <li class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-red-500 mt-0.5">error_outline</span>
                    <div>
                        <strong>Lỗi thanh toán:</strong> Thông tin thẻ không hợp lệ hoặc hết hạn
                    </div>
                </li>
                <li class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-red-500 mt-0.5">error_outline</span>
                    <div>
                        <strong>Hết hàng:</strong> Sản phẩm trong giỏ hàng đã hết
                    </div>
                </li>
                <li class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-red-500 mt-0.5">error_outline</span>
                    <div>
                        <strong>Lỗi hệ thống:</strong> Bảo trì hoặc lỗi kỹ thuật tạm thời
                    </div>
                </li>
            </ul>
        </div>
    </div>

    <!-- What to do -->
    <div class="bg-yellow-50 rounded-lg border border-yellow-200 p-6 mb-6">
        <h3 class="font-semibold text-yellow-800 mb-4">Bạn nên làm gì?</h3>
        <div class="grid md:grid-cols-2 gap-6">
            <div>
                <h4 class="font-medium text-yellow-800 mb-2">Kiểm tra lại</h4>
                <ul class="space-y-2 text-sm text-yellow-700">
                    <li>• Kiểm tra kết nối internet</li>
                    <li>• Xác nhận thông tin thanh toán</li>
                    <li>• Kiểm tra số dư tài khoản</li>
                    <li>• Xem lại giỏ hàng</li>
                </ul>
            </div>
            <div>
                <h4 class="font-medium text-yellow-800 mb-2">Thử lại</h4>
                <ul class="space-y-2 text-sm text-yellow-700">
                    <li>• Làm mới trang và thử lại</li>
                    <li>• Đăng xuất và đăng nhập lại</li>
                    <li>• Thử phương thức thanh toán khác</li>
                    <li>• Chờ vài phút rồi thử lại</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="flex flex-col sm:flex-row gap-4 justify-center mb-8">
        <a href="{{ route('checkout.index') }}" 
           class="px-6 py-3 bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors flex items-center justify-center gap-2">
            <span class="material-symbols-outlined">refresh</span>
            Thử lại đặt hàng
        </a>
        <a href="{{ route('cart.index') }}" 
           class="px-6 py-3 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors flex items-center justify-center gap-2">
            <span class="material-symbols-outlined">shopping_cart</span>
            Xem giỏ hàng
        </a>
        <a href="{{ route('home') }}" 
           class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors flex items-center justify-center gap-2">
            <span class="material-symbols-outlined">home</span>
            Về trang chủ
        </a>
    </div>

    <!-- Contact Support -->
    <div class="bg-primary/5 rounded-lg p-6">
        <div class="text-center">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Vẫn không thể đặt hàng?</h3>
            <p class="text-gray-600 mb-4">
                Đừng lo lắng, đội ngũ hỗ trợ của chúng tôi luôn sẵn sàng giúp bạn!
            </p>
            <div class="grid md:grid-cols-3 gap-6">
                <div class="flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-primary">phone</span>
                    <div>
                        <div class="font-medium">Tổng đài</div>
                        <div class="text-sm text-gray-600">1900 1234</div>
                    </div>
                </div>
                <div class="flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-primary">email</span>
                    <div>
                        <div class="font-medium">Email</div>
                        <div class="text-sm text-gray-600">support@thld.vn</div>
                    </div>
                </div>
                <div class="flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-primary">chat</span>
                    <div>
                        <div class="font-medium">Live Chat</div>
                        <div class="text-sm text-gray-600">8:00 - 22:00</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Save Cart Info -->
    <div class="mt-6 text-center text-sm text-gray-600">
        <p class="mb-2">
            <strong>Lưu ý:</strong> Giỏ hàng của bạn vẫn được lưu trong 30 ngày.
        </p>
        <p>
            Bạn có thể quay lại bất cứ lúc nào để hoàn tất đơn hàng.
        </p>
    </div>
</div>

@push('scripts')
    @vite('resources/js/checkout-failed.js')
@endpush

@endsection
