@extends('layouts.app')

@section('title', 'Theo dõi đơn hàng - THLD')

@section('content')
    <div class="max-w-main mx-auto px-2 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-4">Theo dõi đơn hàng</h1>
            <div class="h-1 w-20 bg-[#C92127] rounded-full"></div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
            <!-- Form tra cứu đơn hàng -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Tra cứu đơn hàng</h2>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Mã đơn hàng</label>
                        <input type="text" id="orderCode" placeholder="VD: THLD123456"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email/Số điện thoại</label>
                        <input type="text" id="customerInfo" placeholder="Email hoặc SĐT đặt hàng"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                    </div>
                </div>
                <button onclick="trackOrder()"
                    class="mt-4 bg-primary text-white px-6 py-2 rounded-lg hover:bg-primary/90 transition-colors">
                    <span class="material-symbols-outlined inline mr-2">search</span>
                    Tra cứu
                </button>
            </div>

            <!-- Kết quả tra cứu -->
            <div id="trackingResult" class="hidden">
                <div class="border-t pt-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Thông tin đơn hàng</h3>

                    <!-- Thông tin cơ bản -->
                    <div class="grid md:grid-cols-2 gap-6 mb-6">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-medium text-gray-800 mb-2">Mã đơn hàng</h4>
                            <p class="text-lg font-bold text-primary" id="displayOrderCode">THLD123456</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-medium text-gray-800 mb-2">Ngày đặt hàng</h4>
                            <p class="text-lg" id="orderDate">29/03/2026 14:30</p>
                        </div>
                    </div>

                    <!-- Trạng thái đơn hàng -->
                    <div class="mb-6">
                        <h4 class="font-medium text-gray-800 mb-4">Trạng thái đơn hàng</h4>
                        <div class="relative">
                            <!-- Timeline -->
                            <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-gray-300"></div>

                            <div class="space-y-6">
                                <!-- Step 1: Đã đặt hàng -->
                                <div class="flex items-start gap-4">
                                    <div
                                        class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center text-white relative z-10">
                                        <span class="material-symbols-outlined text-sm">check</span>
                                    </div>
                                    <div class="flex-1">
                                        <div class="font-medium text-gray-800">Đã đặt hàng</div>
                                        <div class="text-sm text-gray-600">Đơn hàng của bạn đã được xác nhận</div>
                                        <div class="text-xs text-gray-500 mt-1">29/03/2026 14:30</div>
                                    </div>
                                </div>

                                <!-- Step 2: Đang xử lý -->
                                <div class="flex items-start gap-4">
                                    <div
                                        class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center text-white relative z-10">
                                        <span class="material-symbols-outlined text-sm">check</span>
                                    </div>
                                    <div class="flex-1">
                                        <div class="font-medium text-gray-800">Đang xử lý</div>
                                        <div class="text-sm text-gray-600">Đơn hàng đang được chuẩn bị</div>
                                        <div class="text-xs text-gray-500 mt-1">29/03/2026 16:00</div>
                                    </div>
                                </div>

                                <!-- Step 3: Đang vận chuyển -->
                                <div class="flex items-start gap-4">
                                    <div
                                        class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white relative z-10">
                                        <span class="material-symbols-outlined text-sm">local_shipping</span>
                                    </div>
                                    <div class="flex-1">
                                        <div class="font-medium text-gray-800">Đang vận chuyển</div>
                                        <div class="text-sm text-gray-600">Đơn hàng đang được giao đến bạn</div>
                                        <div class="text-xs text-gray-500 mt-1">30/03/2026 09:00</div>
                                        <div class="mt-2 p-3 bg-blue-50 rounded-lg border border-blue-200">
                                            <div class="text-sm text-blue-800">
                                                <strong>Đơn vị vận chuyển:</strong> Giao Hàng Nhanh<br>
                                                <strong>Mã vận đơn:</strong> GHN123456789
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Step 4: Đã giao hàng -->
                                <div class="flex items-start gap-4">
                                    <div
                                        class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center text-white relative z-10">
                                        <span class="material-symbols-outlined text-sm">home</span>
                                    </div>
                                    <div class="flex-1 opacity-60">
                                        <div class="font-medium text-gray-800">Đã giao hàng</div>
                                        <div class="text-sm text-gray-600">Đơn hàng đã được giao thành công</div>
                                        <div class="text-xs text-gray-500 mt-1">Dự kiến: 31/03/2026</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Thông tin sản phẩm -->
                    <div class="mb-6">
                        <h4 class="font-medium text-gray-800 mb-4">Sản phẩm</h4>
                        <div class="space-y-3">
                            <div class="flex items-center gap-4 p-3 bg-gray-50 rounded-lg">
                                <img src="https://via.placeholder.com/60x80/3B82F6/FFFFFF?text=Book" alt="Sách"
                                    class="w-12 h-16 object-cover rounded">
                                <div class="flex-1">
                                    <div class="font-medium text-gray-800">Tôi thấy hoa vàng trên cỏ xanh</div>
                                    <div class="text-sm text-gray-600">Nguyễn Nhật Ánh</div>
                                </div>
                                <div class="text-right">
                                    <div class="font-medium">x1</div>
                                    <div class="text-primary font-bold">86.400đ</div>
                                </div>
                            </div>
                            <div class="flex items-center gap-4 p-3 bg-gray-50 rounded-lg">
                                <img src="https://via.placeholder.com/60x80/3B82F6/FFFFFF?text=Book" alt="Sách"
                                    class="w-12 h-16 object-cover rounded">
                                <div class="flex-1">
                                    <div class="font-medium text-gray-800">Cho tôi xin một vé đi tuổi thơ</div>
                                    <div class="text-sm text-gray-600">Nguyễn Nhật Ánh</div>
                                </div>
                                <div class="text-right">
                                    <div class="font-medium">x1</div>
                                    <div class="text-primary font-bold">150.000đ</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Thông tin giao hàng -->
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-medium text-gray-800 mb-3">Địa chỉ giao hàng</h4>
                            <div class="text-sm text-gray-600">
                                <div class="font-medium text-gray-800">Nguyễn Văn A</div>
                                <div>123 Đường ABC, Quận 1</div>
                                <div>Phường Bến Nghé, TP. Hồ Chí Minh</div>
                                <div>0901234567</div>
                            </div>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-800 mb-3">Tổng tiền</h4>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span>Tạm tính:</span>
                                    <span>236.400đ</span>
                                </div>
                                <div class="flex justify-between">
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

            <!-- Thông báo -->
            <div id="errorMessage" class="hidden mt-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-red-500">error</span>
                    <div>
                        <div class="font-medium text-red-800">Không tìm thấy đơn hàng</div>
                        <div class="text-sm text-red-600">Vui lòng kiểm tra lại mã đơn hàng và thông tin liên hệ.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        @vite('resources/js/order-tracking.js')
    @endpush
@endsection
