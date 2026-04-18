@extends('layouts.app')

@section('title', 'Chính sách vận chuyển - THLD')

@section('content')
<div class="max-w-main mx-auto px-2 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-4">Chính sách vận chuyển</h1>
        <div class="h-1 w-20 bg-[#C92127] rounded-full"></div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 space-y-8">
        <section>
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Phạm vi vận chuyển</h2>
            <p class="text-gray-600 leading-relaxed">
                Chúng tôi hiện đang giao hàng trên toàn quốc 63 tỉnh thành tại Việt Nam. Đơn hàng sẽ được xử lý và giao đến địa chỉ của bạn trong thời gian sớm nhất.
            </p>
        </section>

        <section>
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Phí vận chuyển</h2>
            <div class="space-y-3">
                <div class="flex items-center justify-between p-4 bg-green-50 rounded-lg border border-green-200">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-green-600">local_shipping</span>
                        <span class="font-medium text-gray-800">Miễn phí vận chuyển</span>
                    </div>
                    <span class="font-bold text-green-600">Đơn hàng từ 200.000đ</span>
                </div>
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-gray-600">delivery_dining</span>
                        <span class="font-medium text-gray-800">Phí vận chuyển tiêu chuẩn</span>
                    </div>
                    <span class="font-bold text-gray-700">30.000đ</span>
                </div>
            </div>
        </section>

        <section>
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Thời gian giao hàng</h2>
            <div class="grid md:grid-cols-2 gap-6">
                <div class="p-4 border border-gray-200 rounded-lg">
                    <h3 class="font-semibold text-gray-800 mb-2">Nội thành Hà Nội, TP.HCM</h3>
                    <p class="text-gray-600">2-3 ngày làm việc</p>
                </div>
                <div class="p-4 border border-gray-200 rounded-lg">
                    <h3 class="font-semibold text-gray-800 mb-2">Các tỉnh thành khác</h3>
                    <p class="text-gray-600">3-5 ngày làm việc</p>
                </div>
            </div>
        </section>

        <section>
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Phương thức vận chuyển</h2>
            <ul class="space-y-3">
                <li class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-primary mt-1">check_circle</span>
                    <div>
                        <strong>Giao hàng tiêu chuẩn:</strong> Giao hàng qua đối tác vận chuyển (Giao Hàng Nhanh, Viettel Post, J&T Express)
                    </div>
                </li>
                <li class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-primary mt-1">check_circle</span>
                    <div>
                        <strong>Giao hàng nhanh:</strong> Dịch vụ giao hàng hỏa tốc trong ngày (chỉ áp dụng nội thành Hà Nội, TP.HCM)
                    </div>
                </li>
            </ul>
        </section>

        <section>
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Theo dõi đơn hàng</h2>
            <p class="text-gray-600 mb-4">
                Bạn có thể theo dõi tình trạng đơn hàng của mình thông qua:
            </p>
            <ul class="space-y-2">
                <li class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">email</span>
                    Email thông báo tự động khi có cập nhật
                </li>
                <li class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">phone</span>
                    Tổng đài hỗ trợ: 1900 1234
                </li>
                <li class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">language</span>
                    Trang theo dõi đơn hàng trên website
                </li>
            </ul>
        </section>
    </div>
</div>
@endsection

