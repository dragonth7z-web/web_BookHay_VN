@extends('layouts.app')

@section('title', 'Hệ thống cửa hàng - THLD')

@section('content')
<div class="max-w-main mx-auto px-2 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-4">Hệ thống cửa hàng</h1>
        <div class="h-1 w-20 bg-[#C92127] rounded-full"></div>
    </div>

    <!-- Map Section -->
    <div class="mb-8">
        <div class="bg-gray-200 rounded-lg h-96 flex items-center justify-center">
            <div class="text-center">
                <span class="material-symbols-outlined text-6xl text-gray-400 mb-4">map</span>
                <p class="text-gray-600">Bản đồ hệ thống cửa hàng THLD</p>
            </div>
        </div>
    </div>

    <!-- Store List -->
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Store 1: Chi nhánh chính -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Chi nhánh chính</h3>
                    <span class="inline-block px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Trụ sở</span>
                </div>
                <span class="material-symbols-outlined text-primary">store</span>
            </div>
            
            <div class="space-y-3 text-sm">
                <div class="flex items-start gap-2">
                    <span class="material-symbols-outlined text-gray-500 text-lg mt-0.5">location_on</span>
                    <div>
                        <div class="font-medium">Địa chỉ</div>
                        <div class="text-gray-600">123 Nguyễn Huệ, Quận 1, TP.HCM</div>
                    </div>
                </div>
                
                <div class="flex items-start gap-2">
                    <span class="material-symbols-outlined text-gray-500 text-lg mt-0.5">schedule</span>
                    <div>
                        <div class="font-medium">Giờ mở cửa</div>
                        <div class="text-gray-600">Thứ 2 - Chủ nhật: 8:00 - 22:00</div>
                    </div>
                </div>
                
                <div class="flex items-start gap-2">
                    <span class="material-symbols-outlined text-gray-500 text-lg mt-0.5">phone</span>
                    <div>
                        <div class="font-medium">Điện thoại</div>
                        <div class="text-gray-600">(028) 1234 5678</div>
                    </div>
                </div>
                
                <div class="flex items-start gap-2">
                    <span class="material-symbols-outlined text-gray-500 text-lg mt-0.5">directions</span>
                    <div>
                        <div class="font-medium">Chỉ đường</div>
                        <button class="text-primary hover:underline text-sm">Xem bản đồ</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Store 2: Cầu Giấy -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Cầu Giấy</h3>
                    <span class="inline-block px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">Hà Nội</span>
                </div>
                <span class="material-symbols-outlined text-primary">store</span>
            </div>
            
            <div class="space-y-3 text-sm">
                <div class="flex items-start gap-2">
                    <span class="material-symbols-outlined text-gray-500 text-lg mt-0.5">location_on</span>
                    <div>
                        <div class="font-medium">Địa chỉ</div>
                        <div class="text-gray-600">456 Xuân Thủy, Cầu Giấy, Hà Nội</div>
                    </div>
                </div>
                
                <div class="flex items-start gap-2">
                    <span class="material-symbols-outlined text-gray-500 text-lg mt-0.5">schedule</span>
                    <div>
                        <div class="font-medium">Giờ mở cửa</div>
                        <div class="text-gray-600">Thứ 2 - Chủ nhật: 8:00 - 21:30</div>
                    </div>
                </div>
                
                <div class="flex items-start gap-2">
                    <span class="material-symbols-outlined text-gray-500 text-lg mt-0.5">phone</span>
                    <div>
                        <div class="font-medium">Điện thoại</div>
                        <div class="text-gray-600">(024) 8765 4321</div>
                    </div>
                </div>
                
                <div class="flex items-start gap-2">
                    <span class="material-symbols-outlined text-gray-500 text-lg mt-0.5">directions</span>
                    <div>
                        <div class="font-medium">Chỉ đường</div>
                        <button class="text-primary hover:underline text-sm">Xem bản đồ</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Store 3: Lê Đại Hành -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Lê Đại Hành</h3>
                    <span class="inline-block px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">Hà Nội</span>
                </div>
                <span class="material-symbols-outlined text-primary">store</span>
            </div>
            
            <div class="space-y-3 text-sm">
                <div class="flex items-start gap-2">
                    <span class="material-symbols-outlined text-gray-500 text-lg mt-0.5">location_on</span>
                    <div>
                        <div class="font-medium">Địa chỉ</div>
                        <div class="text-gray-600">789 Lê Đại Hành, Hai Bà Trưng, Hà Nội</div>
                    </div>
                </div>
                
                <div class="flex items-start gap-2">
                    <span class="material-symbols-outlined text-gray-500 text-lg mt-0.5">schedule</span>
                    <div>
                        <div class="font-medium">Giờ mở cửa</div>
                        <div class="text-gray-600">Thứ 2 - Chủ nhật: 8:00 - 21:00</div>
                    </div>
                </div>
                
                <div class="flex items-start gap-2">
                    <span class="material-symbols-outlined text-gray-500 text-lg mt-0.5">phone</span>
                    <div>
                        <div class="font-medium">Điện thoại</div>
                        <div class="text-gray-600">(024) 9876 5432</div>
                    </div>
                </div>
                
                <div class="flex items-start gap-2">
                    <span class="material-symbols-outlined text-gray-500 text-lg mt-0.5">directions</span>
                    <div>
                        <div class="font-medium">Chỉ đường</div>
                        <button class="text-primary hover:underline text-sm">Xem bản đồ</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Store 4: Nguyễn Văn Linh -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Nguyễn Văn Linh</h3>
                    <span class="inline-block px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Đà Nẵng</span>
                </div>
                <span class="material-symbols-outlined text-primary">store</span>
            </div>
            
            <div class="space-y-3 text-sm">
                <div class="flex items-start gap-2">
                    <span class="material-symbols-outlined text-gray-500 text-lg mt-0.5">location_on</span>
                    <div>
                        <div class="font-medium">Địa chỉ</div>
                        <div class="text-gray-600">321 Nguyễn Văn Linh, Hải Châu, Đà Nẵng</div>
                    </div>
                </div>
                
                <div class="flex items-start gap-2">
                    <span class="material-symbols-outlined text-gray-500 text-lg mt-0.5">schedule</span>
                    <div>
                        <div class="font-medium">Giờ mở cửa</div>
                        <div class="text-gray-600">Thứ 2 - Chủ nhật: 8:00 - 21:00</div>
                    </div>
                </div>
                
                <div class="flex items-start gap-2">
                    <span class="material-symbols-outlined text-gray-500 text-lg mt-0.5">phone</span>
                    <div>
                        <div class="font-medium">Điện thoại</div>
                        <div class="text-gray-600">(0236) 3456 7890</div>
                    </div>
                </div>
                
                <div class="flex items-start gap-2">
                    <span class="material-symbols-outlined text-gray-500 text-lg mt-0.5">directions</span>
                    <div>
                        <div class="font-medium">Chỉ đường</div>
                        <button class="text-primary hover:underline text-sm">Xem bản đồ</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Store 5: Trần Phú -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Trần Phú</h3>
                    <span class="inline-block px-2 py-1 bg-purple-100 text-purple-800 text-xs rounded-full">Huế</span>
                </div>
                <span class="material-symbols-outlined text-primary">store</span>
            </div>
            
            <div class="space-y-3 text-sm">
                <div class="flex items-start gap-2">
                    <span class="material-symbols-outlined text-gray-500 text-lg mt-0.5">location_on</span>
                    <div>
                        <div class="font-medium">Địa chỉ</div>
                        <div class="text-gray-600">654 Trần Phú, TP. Huế</div>
                    </div>
                </div>
                
                <div class="flex items-start gap-2">
                    <span class="material-symbols-outlined text-gray-500 text-lg mt-0.5">schedule</span>
                    <div>
                        <div class="font-medium">Giờ mở cửa</div>
                        <div class="text-gray-600">Thứ 2 - Chủ nhật: 8:00 - 20:30</div>
                    </div>
                </div>
                
                <div class="flex items-start gap-2">
                    <span class="material-symbols-outlined text-gray-500 text-lg mt-0.5">phone</span>
                    <div>
                        <div class="font-medium">Điện thoại</div>
                        <div class="text-gray-600">(0234) 5678 9012</div>
                    </div>
                </div>
                
                <div class="flex items-start gap-2">
                    <span class="material-symbols-outlined text-gray-500 text-lg mt-0.5">directions</span>
                    <div>
                        <div class="font-medium">Chỉ đường</div>
                        <button class="text-primary hover:underline text-sm">Xem bản đồ</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Store 6: Coming Soon -->
        <div class="bg-gray-50 rounded-lg border-2 border-dashed border-gray-300 p-6 flex flex-col items-center justify-center text-center">
            <span class="material-symbols-outlined text-4xl text-gray-400 mb-3">storefront</span>
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Sắp mở</h3>
            <p class="text-gray-600 text-sm mb-4">Cần Thơ</p>
            <button class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors text-sm">
                Nhận thông báo
            </button>
        </div>
    </div>

    <!-- Contact Info -->
    <div class="mt-12 bg-primary/5 rounded-lg p-6">
        <div class="text-center">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Cần hỗ trợ?</h3>
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
                        <div class="text-sm text-gray-600">info@thld.vn</div>
                    </div>
                </div>
                <div class="flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-primary">schedule</span>
                    <div>
                        <div class="font-medium">Giờ làm việc</div>
                        <div class="text-sm text-gray-600">8:00 - 22:00 hàng ngày</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

