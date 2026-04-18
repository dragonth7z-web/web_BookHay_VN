@extends('layouts.app')

@section('title', 'Chính sách bảo mật - THLD')

@section('content')
    <div class="max-w-main mx-auto px-2 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-4">Chính sách bảo mật</h1>
            <div class="h-1 w-20 bg-[#C92127] rounded-full"></div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 space-y-8">
            <section>
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Giới thiệu</h2>
                <p class="text-gray-600 leading-relaxed">
                    Tại THLD, chúng tôi cam kết bảo vệ thông tin cá nhân của bạn. Chính sách bảo mật này giải thích cách
                    chúng tôi thu thập, sử dụng và bảo vệ thông tin của bạn khi sử dụng website và dịch vụ của chúng tôi.
                </p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Thông tin chúng tôi thu thập</h2>
                <div class="space-y-4">
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">Thông tin cá nhân</h3>
                        <ul class="space-y-2 text-gray-600 ml-4">
                            <li>• Họ và tên</li>
                            <li>• Địa chỉ email</li>
                            <li>• Số điện thoại</li>
                            <li>• Địa chỉ giao hàng</li>
                            <li>• Ngày sinh (tùy chọn)</li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">Thông tin giao dịch</h3>
                        <ul class="space-y-2 text-gray-600 ml-4">
                            <li>• Lịch sử mua hàng</li>
                            <li>• Sách đã xem</li>
                            <li>• Sách yêu thích</li>
                            <li>• Thông tin thanh toán</li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">Thông tin kỹ thuật</h3>
                        <ul class="space-y-2 text-gray-600 ml-4">
                            <li>• Địa chỉ IP</li>
                            <li>• Loại trình duyệt</li>
                            <li>• Thời gian truy cập</li>
                            <li>• Cookie</li>
                        </ul>
                    </div>
                </div>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Cách chúng tôi sử dụng thông tin</h2>
                <ul class="space-y-3">
                    <li class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-primary mt-1">check_circle</span>
                        <div>
                            <strong>Cung cấp dịch vụ:</strong> Xử lý đơn hàng, giao sách, và thanh toán
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-primary mt-1">check_circle</span>
                        <div>
                            <strong>Cá nhân hóa:</strong> Gợi ý sách phù hợp với sở thích của bạn
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-primary mt-1">check_circle</span>
                        <div>
                            <strong>Giao tiếp:</strong> Gửi email thông báo, khuyến mãi, và tin tức
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-primary mt-1">check_circle</span>
                        <div>
                            <strong>Cải thiện dịch vụ:</strong> Phân tích dữ liệu để nâng cao trải nghiệm người dùng
                        </div>
                    </li>
                </ul>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Bảo mật thông tin</h2>
                <div class="space-y-4">
                    <div class="p-4 bg-green-50 rounded-lg border border-green-200">
                        <h3 class="font-semibold text-green-800 mb-2">Biện pháp bảo mật</h3>
                        <ul class="space-y-2 text-green-700">
                            <li>• Mã hóa SSL cho tất cả giao dịch</li>
                            <li>• Hệ thống firewall và chống xâm nhập</li>
                            <li>• Kiểm soát quyền truy cập dữ liệu</li>
                            <li>• Backup dữ liệu định kỳ</li>
                        </ul>
                    </div>
                </div>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Chia sẻ thông tin</h2>
                <p class="text-gray-600 mb-4">
                    Chúng tôi không bán, cho thuê hay chia sẻ thông tin cá nhân của bạn với bên thứ ba, trừ các trường hợp:
                </p>
                <ul class="space-y-3">
                    <li class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-primary mt-1">check_circle</span>
                        <div>
                            <strong>Đối tác vận chuyển:</strong> Cần thông tin để giao hàng
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-primary mt-1">check_circle</span>
                        <div>
                            <strong>Cơ quan pháp luật:</strong> Khi có yêu cầu từ cơ quan có thẩm quyền
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-primary mt-1">check_circle</span>
                        <div>
                            <strong>Bảo vệ quyền lợi:</strong> Khi cần bảo vệ quyền lợi của THLD và người dùng
                        </div>
                    </li>
                </ul>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Quyền của bạn</h2>
                <ul class="space-y-3">
                    <li class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-primary mt-1">edit</span>
                        <div>
                            <strong>Cập nhật thông tin:</strong> Bạn có thể sửa đổi thông tin cá nhân bất cứ lúc nào
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-primary mt-1">delete</span>
                        <div>
                            <strong>Xóa tài khoản:</strong> Yêu cầu xóa tài khoản và dữ liệu liên quan
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-primary mt-1">unsubscribe</span>
                        <div>
                            <strong>Hủy đăng ký:</strong> Ngừng nhận email marketing từ chúng tôi
                        </div>
                    </li>
                </ul>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Cookie</h2>
                <p class="text-gray-600 mb-4">
                    Chúng tôi sử dụng cookie để cải thiện trải nghiệm của bạn trên website:
                </p>
                <ul class="space-y-2 text-gray-600">
                    <li>• <strong>Cookie thiết yếu:</strong> Cần thiết để website hoạt động</li>
                    <li>• <strong>Cookie hiệu suất:</strong> Thu thập thông tin về cách sử dụng website</li>
                    <li>• <strong>Cookie quảng cáo:</strong> Hiển thị quảng cáo phù hợp</li>
                </ul>
                <p class="text-gray-600 mt-4">
                    Bạn có thể quản lý cookie trong cài đặt trình duyệt của mình.
                </p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Thay đổi chính sách</h2>
                <p class="text-gray-600">
                    Chúng tôi có thể cập nhật chính sách bảo mật này theo thời gian. Mọi thay đổi sẽ được thông báo trên
                    website và gửi email cho người dùng đã đăng ký.
                </p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Liên hệ</h2>
                <p class="text-gray-600 mb-4">
                    Nếu bạn có câu hỏi về chính sách bảo mật của chúng tôi, vui lòng liên hệ:
                </p>
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary">email</span>
                        <div>
                            <div class="font-semibold">Email</div>
                            <div class="text-gray-600">privacy@thld.vn</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary">phone</span>
                        <div>
                            <div class="font-semibold">Tổng đài</div>
                            <div class="text-gray-600">1900 1234</div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                <p class="text-gray-600 text-sm">
                    <strong>Ngày cập nhật:</strong> 29/03/2026<br>
                    Chính sách này có hiệu lực từ ngày đăng tải trên website THLD.
                </p>
            </section>
        </div>
    </div>
@endsection
