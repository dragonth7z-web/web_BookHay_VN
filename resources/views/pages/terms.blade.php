@extends('layouts.app')

@section('title', 'Điều khoản sử dụng - THLD')

@section('content')
<div class="max-w-main mx-auto px-2 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-4">Điều khoản sử dụng</h1>
        <div class="h-1 w-20 bg-[#C92127] rounded-full"></div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 space-y-8">
        <section>
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Giới thiệu</h2>
            <p class="text-gray-600 leading-relaxed">
                Chào mừng đến với THLD! Khi sử dụng website và dịch vụ của chúng tôi, bạn đồng ý tuân thủ các điều khoản và điều kiện dưới đây. Vui lòng đọc kỹ trước khi tiếp tục.
            </p>
        </section>

        <section>
            <h2 class="text-xl font-semibold text-gray-800 mb-4">1. Định nghĩa</h2>
            <div class="space-y-3">
                <div>
                    <strong>THLD:</strong> Website thương mại điện tử bán sách trực tuyến
                </div>
                <div>
                    <strong>Người dùng:</strong> Bất kỳ cá nhân nào truy cập và sử dụng website
                </div>
                <div>
                    <strong>Khách hàng:</strong> Người dùng đã đăng ký tài khoản và mua hàng
                </div>
                <div>
                    <strong>Sản phẩm:</strong> Sách và các sản phẩm văn phòng phẩm được bán trên website
                </div>
            </div>
        </section>

        <section>
            <h2 class="text-xl font-semibold text-gray-800 mb-4">2. Điều kiện sử dụng</h2>
            <ul class="space-y-3">
                <li class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-primary mt-1">check_circle</span>
                    <div>
                        Bạn phải từ 18 tuổi hoặc có sự đồng ý của phụ huynh khi sử dụng dịch vụ
                    </div>
                </li>
                <li class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-primary mt-1">check_circle</span>
                    <div>
                        Cung cấp thông tin chính xác và đầy đủ khi đăng ký tài khoản
                    </div>
                </li>
                <li class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-primary mt-1">check_circle</span>
                    <div>
                        Chịu trách nhiệm về bảo mật tài khoản và mật khẩu của mình
                    </div>
                </li>
                <li class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-primary mt-1">check_circle</span>
                    <div>
                        Tuân thủ pháp luật Việt Nam khi sử dụng dịch vụ
                    </div>
                </li>
            </ul>
        </section>

        <section>
            <h2 class="text-xl font-semibold text-gray-800 mb-4">3. Đăng ký tài khoản</h2>
            <div class="space-y-4">
                <div>
                    <h3 class="font-semibold text-gray-800 mb-2">Thông tin bắt buộc</h3>
                    <ul class="space-y-2 text-gray-600 ml-4">
                        <li>• Họ và tên đầy đủ</li>
                        <li>• Địa chỉ email hợp lệ</li>
                        <li>• Số điện thoại liên lạc</li>
                        <li>• Địa chỉ giao hàng</li>
                    </ul>
                </div>
                <div class="p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                    <p class="text-yellow-800">
                        <strong>Lưu ý:</strong> Bạn chịu trách nhiệm hoàn toàn về tính chính xác của thông tin cung cấp.
                    </p>
                </div>
            </div>
        </section>

        <section>
            <h2 class="text-xl font-semibold text-gray-800 mb-4">4. Đặt hàng và thanh toán</h2>
            <div class="space-y-4">
                <div>
                    <h3 class="font-semibold text-gray-800 mb-2">Quy trình đặt hàng</h3>
                    <ol class="space-y-2 text-gray-600 ml-4">
                        <li>1. Chọn sản phẩm và thêm vào giỏ hàng</li>
                        <li>2. Xác nhận thông tin giỏ hàng</li>
                        <li>3. Nhập thông tin giao hàng</li>
                        <li>4. Chọn phương thức thanh toán</li>
                        <li>5. Xác nhận đơn hàng</li>
                    </ol>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800 mb-2">Phương thức thanh toán</h3>
                    <ul class="space-y-2 text-gray-600 ml-4">
                        <li>• Tiền mặt khi nhận hàng (COD)</li>
                        <li>• Thẻ tín dụng/ghi nợ</li>
                        <li>• Ví điện tử (MoMo, ZaloPay, etc.)</li>
                        <li>• Chuyển khoản ngân hàng</li>
                    </ul>
                </div>
            </div>
        </section>

        <section>
            <h2 class="text-xl font-semibold text-gray-800 mb-4">5. Giá cả và khuyến mãi</h2>
            <ul class="space-y-3">
                <li class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-primary mt-1">info</span>
                    <div>
                        Giá sản phẩm có thể thay đổi mà không cần thông báo trước
                    </div>
                </li>
                <li class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-primary mt-1">info</span>
                    <div>
                        Các chương trình khuyến mãi không áp dụng đồng thời
                    </div>
                </li>
                <li class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-primary mt-1">info</span>
                    <div>
                        THLD có quyền hủy khuyến mãi nếu phát hiện gian lận
                    </div>
                </li>
            </ul>
        </section>

        <section>
            <h2 class="text-xl font-semibold text-gray-800 mb-4">6. Vận chuyển và giao hàng</h2>
            <div class="space-y-4">
                <div>
                    <h3 class="font-semibold text-gray-800 mb-2">Thời gian giao hàng dự kiến</h3>
                    <ul class="space-y-2 text-gray-600 ml-4">
                        <li>• Nội thành Hà Nội, TP.HCM: 2-3 ngày làm việc</li>
                        <li>• Các tỉnh thành khác: 3-5 ngày làm việc</li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800 mb-2">Trách nhiệm khi nhận hàng</h3>
                    <ul class="space-y-2 text-gray-600 ml-4">
                        <li>• Kiểm tra tình trạng sản phẩm trước khi thanh toán</li>
                        <li>• Báo cáo ngay nếu có vấn đề về sản phẩm</li>
                        <li>• Ký nhận khi hàng đã được kiểm tra kỹ</li>
                    </ul>
                </div>
            </div>
        </section>

        <section>
            <h2 class="text-xl font-semibold text-gray-800 mb-4">7. Đổi trả và hoàn tiền</h2>
            <p class="text-gray-600 mb-4">
                Chính sách đổi trả được thực hiện theo điều khoản riêng. Vui lòng xem <a href="{{ route('pages.return') }}" class="text-primary underline">Chính sách đổi trả</a> để biết chi tiết.
            </p>
        </section>

        <section>
            <h2 class="text-xl font-semibold text-gray-800 mb-4">8. Sở hữu trí tuệ</h2>
            <ul class="space-y-3">
                <li class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-primary mt-1">copyright</span>
                    <div>
                        Toàn bộ nội dung website thuộc sở hữu của THLD
                    </div>
                </li>
                <li class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-primary mt-1">copyright</span>
                    <div>
                        Không được sao chép, phân phối nội dung mà không có sự cho phép
                    </div>
                </li>
                <li class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-primary mt-1">copyright</span>
                    <div>
                        Sản phẩm được bán đều có bản quyền từ nhà xuất bản
                    </div>
                </li>
            </ul>
        </section>

        <section>
            <h2 class="text-xl font-semibold text-gray-800 mb-4">9. Hành vi bị cấm</h2>
            <ul class="space-y-3">
                <li class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-red-500 mt-1">block</span>
                    <div>
                        Sử dụng website cho mục đích bất hợp pháp
                    </div>
                </li>
                <li class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-red-500 mt-1">block</span>
                    <div>
                        Cố ý gây lỗi hoặc tấn công hệ thống
                    </div>
                </li>
                <li class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-red-500 mt-1">block</span>
                    <div>
                        Đăng tải nội dung vi phạm pháp luật hoặc thuần phong mỹ tục
                    </div>
                </li>
                <li class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-red-500 mt-1">block</span>
                    <div>
                        Gian lận trong đặt hàng hoặc thanh toán
                    </div>
                </li>
            </ul>
        </section>

        <section>
            <h2 class="text-xl font-semibold text-gray-800 mb-4">10. Giới hạn trách nhiệm</h2>
            <p class="text-gray-600">
                THLD không chịu trách nhiệm cho các thiệt hại phát sinh từ:
            </p>
            <ul class="space-y-2 text-gray-600 ml-4">
                <li>• Lỗi kỹ thuật từ nhà cung cấp dịch vụ bên thứ ba</li>
                <li>• Sự cố mạng ngoài tầm kiểm soát</li>
                <li>• Sử dụng sai mục đích của người dùng</li>
                <li>• Mất mát thông tin do virus hoặc hacker</li>
            </ul>
        </section>

        <section>
            <h2 class="text-xl font-semibold text-gray-800 mb-4">11. Giải quyết tranh chấp</h2>
            <p class="text-gray-600 mb-4">
                Mọi tranh chấp phát sinh sẽ được giải quyết theo quy định sau:
            </p>
            <ol class="space-y-2 text-gray-600 ml-4">
                <li>1. Thương lượng, hòa giải giữa các bên</li>
                <li>2. Nếu không đồng ý, giải quyết tại Tòa án nhân dân có thẩm quyền</li>
                <li>3. Áp dụng pháp luật Việt Nam</li>
            </ol>
        </section>

        <section>
            <h2 class="text-xl font-semibold text-gray-800 mb-4">12. Thay đổi điều khoản</h2>
            <p class="text-gray-600">
                THLD có quyền thay đổi điều khoản sử dụng bất cứ lúc nào. Mọi thay đổi sẽ được thông báo trên website và có hiệu lực từ thời điểm thông báo.
            </p>
        </section>

        <section>
            <h2 class="text-xl font-semibold text-gray-800 mb-4">13. Liên hệ</h2>
            <p class="text-gray-600 mb-4">
                Nếu bạn có câu hỏi về điều khoản sử dụng, vui lòng liên hệ:
            </p>
            <div class="grid md:grid-cols-2 gap-6">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-primary">email</span>
                    <div>
                        <div class="font-semibold">Email</div>
                        <div class="text-gray-600">legal@thld.vn</div>
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
                Điều khoản này có hiệu lực từ ngày đăng tải trên website THLD.
            </p>
        </section>
    </div>
</div>
@endsection

