@extends('layouts.app')

@section('title', 'Chính sách đổi trả - THLD')

@section('content')
    <div class="max-w-main mx-auto px-2 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-4">Chính sách đổi trả</h1>
            <div class="h-1 w-20 bg-[#C92127] rounded-full"></div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 space-y-8">
            <section class="p-6 bg-green-50 rounded-lg border border-green-200">
                <h2 class="text-xl font-semibold text-green-800 mb-3">Cam kết của THLD</h2>
                <p class="text-green-700 font-medium">Miễn phí đổi trả trong 30 ngày</p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Điều kiện đổi trả</h2>
                <ul class="space-y-3">
                    <li class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-primary mt-1">check_circle</span>
                        <div>
                            Sách còn mới, không bị trầy xước, ố vàng, cong vênh
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-primary mt-1">check_circle</span>
                        <div>
                            Còn đầy đủ bao bì, nhãn mác của nhà xuất bản
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-primary mt-1">check_circle</span>
                        <div>
                            Trong thời hạn 30 ngày kể từ ngày mua hàng
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-primary mt-1">check_circle</span>
                        <div>
                            Cung cấp hóa đơn mua hàng hoặc email xác nhận đơn hàng
                        </div>
                    </li>
                </ul>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Trường hợp không được đổi trả</h2>
                <ul class="space-y-3">
                    <li class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-red-500 mt-1">cancel</span>
                        <div>
                            Sách đã qua sử dụng, có dấu hiệu đọc qua
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-red-500 mt-1">cancel</span>
                        <div>
                            Sách bị hư hỏng do người dùng (bị ướt, rách, bẩn)
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-red-500 mt-1">cancel</span>
                        <div>
                            Quá thời hạn 30 ngày kể từ ngày mua
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-red-500 mt-1">cancel</span>
                        <div>
                            Sách đã được giảm giá đặc biệt (sách sale, sách cũ)
                        </div>
                    </li>
                </ul>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Quy trình đổi trả</h2>
                <div class="space-y-4">
                    <div class="flex gap-4">
                        <div
                            class="flex-shrink-0 w-8 h-8 bg-primary text-white rounded-full flex items-center justify-center font-bold">
                            1</div>
                        <div>
                            <h3 class="font-semibold text-gray-800">Liên hệ tổng đài</h3>
                            <p class="text-gray-600">Gọi 1900 1234 hoặc email support@thld.vn để thông báo yêu cầu đổi trả
                            </p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div
                            class="flex-shrink-0 w-8 h-8 bg-primary text-white rounded-full flex items-center justify-center font-bold">
                            2</div>
                        <div>
                            <h3 class="font-semibold text-gray-800">Gửi hàng về</h3>
                            <p class="text-gray-600">Gửi sách về địa chỉ kho của THLD cùng với hóa đơn mua hàng</p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div
                            class="flex-shrink-0 w-8 h-8 bg-primary text-white rounded-full flex items-center justify-center font-bold">
                            3</div>
                        <div>
                            <h3 class="font-semibold text-gray-800">Kiểm tra và xử lý</h3>
                            <p class="text-gray-600">Chúng tôi sẽ kiểm tra sách trong 2-3 ngày làm việc</p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div
                            class="flex-shrink-0 w-8 h-8 bg-primary text-white rounded-full flex items-center justify-center font-bold">
                            4</div>
                        <div>
                            <h3 class="font-semibold text-gray-800">Hoàn tiền hoặc đổi sách</h3>
                            <p class="text-gray-600">Hoàn tiền trong 5-7 ngày hoặc gửi sách mới cho bạn</p>
                        </div>
                    </div>
                </div>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Chi phí đổi trả</h2>
                <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                    <p class="text-blue-800 font-medium mb-2">THLD chịu chi phí vận chuyển cho:</p>
                    <ul class="space-y-1 text-blue-700">
                        <li>• Sách bị lỗi từ nhà xuất bản</li>
                        <li>• Gửi nhầm sách (khác với đơn đặt hàng)</li>
                    </ul>
                </div>
                <div class="bg-orange-50 p-4 rounded-lg border border-orange-200 mt-3">
                    <p class="text-orange-800 font-medium mb-2">Khách hàng chịu chi phí vận chuyển cho:</p>
                    <ul class="space-y-1 text-orange-700">
                        <li>• Không thích sách (đổi sang sách khác)</li>
                        <li>• Lý do cá nhân khác</li>
                    </ul>
                </div>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Thông tin liên hệ</h2>
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary">phone</span>
                        <div>
                            <div class="font-semibold">Tổng đài</div>
                            <div class="text-gray-600">1900 1234</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary">email</span>
                        <div>
                            <div class="font-semibold">Email</div>
                            <div class="text-gray-600">support@thld.vn</div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
