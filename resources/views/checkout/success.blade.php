@extends('layouts.app')

@section('title', 'Đặt hàng thành công - THLD')

@section('content')
<div class="max-w-main mx-auto px-2 py-8">
    <div class="text-center mb-8">
        <!-- Success Icon -->
        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <span class="material-symbols-outlined text-4xl text-green-600">check_circle</span>
        </div>
        
        <h1 class="text-3xl font-bold text-gray-800 mb-4">Đặt hàng thành công!</h1>
        <p class="text-gray-600 text-lg">Cảm ơn bạn đã đặt hàng tại THLD</p>
    </div>

    <!-- Order Info Card -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 mb-6">
        <div class="grid md:grid-cols-2 gap-8">
            <!-- Left Column -->
            <div class="space-y-6">
                <div>
                    <h3 class="font-semibold text-gray-800 mb-2">Mã đơn hàng</h3>
                    <p class="text-2xl font-bold text-primary" id="orderCode">THLD123456</p>
                </div>
                
                <div>
                    <h3 class="font-semibold text-gray-800 mb-2">Thông tin đơn hàng</h3>
                    <div class="space-y-2 text-sm text-gray-600">
                        <div class="flex justify-between">
                            <span>Ngày đặt hàng:</span>
                            <span class="font-medium text-gray-800">{{ date('d/m/Y H:i') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Phương thức thanh toán:</span>
                            <span class="font-medium text-gray-800">Thanh toán khi nhận hàng (COD)</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Phương thức vận chuyển:</span>
                            <span class="font-medium text-gray-800">Giao hàng tiêu chuẩn</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-6">
                <div>
                    <h3 class="font-semibold text-gray-800 mb-2">Địa chỉ giao hàng</h3>
                    <div class="text-sm text-gray-600">
                        <div class="font-medium text-gray-800">Nguyễn Văn A</div>
                        <div>123 Đường ABC, Quận 1</div>
                        <div>Phường Bến Nghé, TP. Hồ Chí Minh</div>
                        <div>0901234567</div>
                    </div>
                </div>
                
                <div>
                    <h3 class="font-semibold text-gray-800 mb-2">Tổng tiền</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between text-gray-600">
                            <span>Tạm tính:</span>
                            <span>236.400đ</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Phí vận chuyển:</span>
                            <span class="text-green-600">Miễn phí</span>
                        </div>
                        <div class="flex justify-between font-bold text-lg pt-2 border-t">
                            <span>Tổng cộng:</span>
                            <span class="text-primary">236.400đ</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Next Steps -->
    <div class="bg-blue-50 rounded-lg border border-blue-200 p-6 mb-6">
        <h3 class="font-semibold text-blue-800 mb-4">Các bước tiếp theo</h3>
        <div class="grid md:grid-cols-4 gap-4">
            <div class="text-center">
                <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center mx-auto mb-2">
                    <span class="material-symbols-outlined text-white">email</span>
                </div>
                <div class="text-sm text-blue-800">
                    <div class="font-medium">Xác nhận email</div>
                    <div class="text-xs">Kiểm tra hộp thư</div>
                </div>
            </div>
            <div class="text-center">
                <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center mx-auto mb-2">
                    <span class="material-symbols-outlined text-white">inventory</span>
                </div>
                <div class="text-sm text-blue-800">
                    <div class="font-medium">Chuẩn bị hàng</div>
                    <div class="text-xs">1-2 ngày làm việc</div>
                </div>
            </div>
            <div class="text-center">
                <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center mx-auto mb-2">
                    <span class="material-symbols-outlined text-white">local_shipping</span>
                </div>
                <div class="text-sm text-blue-800">
                    <div class="font-medium">Giao hàng</div>
                    <div class="text-xs">2-3 ngày làm việc</div>
                </div>
            </div>
            <div class="text-center">
                <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center mx-auto mb-2">
                    <span class="material-symbols-outlined text-white">check_circle</span>
                </div>
                <div class="text-sm text-blue-800">
                    <div class="font-medium">Nhận hàng</div>
                    <div class="text-xs">Kiểm tra trước khi thanh toán</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="flex flex-col sm:flex-row gap-4 justify-center">
        <a href="{{ route('orders.tracking') }}" 
           class="px-6 py-3 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors flex items-center justify-center gap-2">
            <span class="material-symbols-outlined">search</span>
            Theo dõi đơn hàng
        </a>
        <a href="{{ route('home') }}" 
           class="px-6 py-3 bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors flex items-center justify-center gap-2">
            <span class="material-symbols-outlined">shopping_bag</span>
            Tiếp tục mua sắm
        </a>
        <a href="{{ route('account.orders') }}" 
           class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors flex items-center justify-center gap-2">
            <span class="material-symbols-outlined">list</span>
            Xem lịch sử đơn hàng
        </a>
    </div>

    <!-- Additional Info -->
    <div class="mt-8 text-center text-sm text-gray-600">
        <p class="mb-2">
            <strong>Cần hỗ trợ?</strong> Liên hệ tổng đài 
            <a href="tel:19001234" class="text-primary font-medium">1900 1234</a> 
            hoặc email 
            <a href="mailto:support@thld.vn" class="text-primary font-medium">support@thld.vn</a>
        </p>
        <p>
            Email xác nhận đơn hàng đã được gửi đến địa chỉ email của bạn.
            Vui lòng kiểm tra cả hộp thư spam nếu không thấy email.
        </p>
    </div>
</div>

@push('scripts')
    @vite('resources/js/checkout-success.js')
@endpush

@endsection