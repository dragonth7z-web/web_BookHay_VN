@extends('layouts.app')

@section('title', 'Sổ địa chỉ - THLD')

@section('content')
<div class="min-h-screen bg-[#F0F2F5] py-8">
    <div class="max-w-7xl mx-auto px-4">

        <div class="flex flex-col lg:flex-row gap-6 min-h-[600px]">

            {{-- ── Sidebar ── --}}
            <aside class="w-full lg:w-64 flex-shrink-0">
                <div class="flex items-center gap-4 mb-6 px-2">
                    <div class="w-12 h-12 rounded-full overflow-hidden border-2 border-primary bg-gray-100">
                        @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" class="w-full h-full object-cover">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=C92127&color=fff" alt="Avatar" class="w-full h-full object-cover">
                        @endif
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Tài khoản của</p>
                        <p class="font-bold text-gray-900">{{ $user->name }}</p>
                    </div>
                </div>

                <nav class="space-y-1">
                    <a href="{{ route('account.profile') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm text-gray-600 font-medium transition-all duration-200 hover:bg-gray-50 hover:text-primary">
                        <span class="material-symbols-outlined text-xl">person</span>
                        <span>Thông tin cá nhân</span>
                    </a>
                    <a href="{{ route('account.orders') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm text-gray-600 font-medium transition-all duration-200 hover:bg-gray-50 hover:text-primary">
                        <span class="material-symbols-outlined text-xl">package_2</span>
                        <span>Đơn hàng của tôi</span>
                    </a>
                    <a href="{{ route('account.wishlist') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm text-gray-600 font-medium transition-all duration-200 hover:bg-gray-50 hover:text-primary">
                        <span class="material-symbols-outlined text-xl">favorite</span>
                        <span>Sách yêu thích</span>
                    </a>
                    <a href="{{ route('account.notifications') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm text-gray-600 font-medium transition-all duration-200 hover:bg-gray-50 hover:text-primary">
                        <span class="material-symbols-outlined text-xl">notifications</span>
                        <span>Thông báo</span>
                    </a>
                    {{-- Sổ địa chỉ – active --}}
                    <a href="{{ route('account.addresses') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold bg-primary text-white shadow-[0_6px_20px_rgba(201,33,39,0.25)] transition-all duration-200">
                        <span class="material-symbols-outlined text-xl">location_on</span>
                        <span>Sổ địa chỉ</span>
                    </a>
                    <a href="{{ route('account.coupons') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm text-gray-600 font-medium transition-all duration-200 hover:bg-gray-50 hover:text-primary">
                        <span class="material-symbols-outlined text-xl">confirmation_number</span>
                        <span>Kho Voucher</span>
                    </a>
                    <div class="pt-4 mt-4 border-t border-gray-100">
                        <form action="{{ route('logout') }}" method="POST" id="logout-form-addresses" class="hidden">@csrf</form>
                        <a href="javascript:void(0)"
                            onclick="document.getElementById('logout-form-addresses').submit();"
                            class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm text-red-500 font-medium transition-all duration-200 hover:bg-red-50 hover:text-red-600">
                            <span class="material-symbols-outlined text-xl">logout</span>
                            <span>Đăng xuất</span>
                        </a>
                    </div>
                </nav>
            </aside>

            {{-- ── Main Content ── --}}
            <section class="flex-1 min-w-0">

                {{-- Breadcrumb --}}
                <nav class="flex items-center gap-2 mb-5 text-sm">
                    <a href="{{ url('/') }}" class="text-gray-500 hover:text-primary font-medium transition-colors">Trang chủ</a>
                    <span class="material-symbols-outlined text-gray-400 text-base">chevron_right</span>
                    <a href="{{ route('account.profile') }}" class="text-gray-500 hover:text-primary font-medium transition-colors">Tài khoản</a>
                    <span class="material-symbols-outlined text-gray-400 text-base">chevron_right</span>
                    <span class="text-primary font-bold">Sổ địa chỉ</span>
                </nav>

                {{-- Flash message --}}
                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm font-medium flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">check_circle</span>
                        {{ session('success') }}
                    </div>
                @endif

                {{-- Page Header --}}
                <div class="bg-white rounded-[18px] border border-gray-200 shadow-[0_2px_12px_rgba(0,0,0,0.04)] px-7 py-5 mb-5">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Sổ địa chỉ</h1>
                            <p class="text-sm text-gray-500 mt-0.5">
                                Quản lý thông tin giao hàng và hóa đơn để trải nghiệm mua sắm mượt mà hơn.
                            </p>
                        </div>
                        <button
                            onclick="openAddressModal()"
                            class="inline-flex items-center gap-2 bg-primary text-white px-5 py-2.5 rounded-xl font-bold text-sm hover:bg-primary/90 transition-all shadow-[0_4px_12px_rgba(201,33,39,0.25)] whitespace-nowrap">
                            <span class="material-symbols-outlined text-[18px]">add</span>
                            Thêm địa chỉ mới
                        </button>
                    </div>
                </div>

                {{-- Address Grid --}}
                {{-- full_address and type_label come from ShippingAddress Model Accessors --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">

                    {{-- Add new card --}}
                    <button
                        onclick="openAddressModal()"
                        class="group flex flex-col items-center justify-center gap-3 bg-white rounded-[18px] border-2 border-dashed border-gray-200 hover:border-primary hover:bg-primary/5 transition-all duration-200 p-8 min-h-[200px] text-center">
                        <div class="w-12 h-12 rounded-full bg-gray-100 group-hover:bg-primary/10 flex items-center justify-center transition-colors">
                            <span class="material-symbols-outlined text-gray-400 group-hover:text-primary text-2xl transition-colors">add</span>
                        </div>
                        <div>
                            <p class="font-bold text-gray-600 group-hover:text-primary text-sm transition-colors">Thêm địa chỉ mới</p>
                            <p class="text-xs text-gray-400 mt-0.5">Lưu thông tin giao hàng khác</p>
                        </div>
                    </button>

                    @forelse($addresses as $address)
                        <div class="bg-white rounded-[18px] border border-gray-200 shadow-[0_2px_8px_rgba(0,0,0,0.04)] hover:shadow-[0_4px_16px_rgba(0,0,0,0.08)] transition-all duration-200 overflow-hidden"
                            id="address-card-{{ $address->id }}">

                            {{-- Card Header --}}
                            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between gap-2">
                                <div class="flex items-center gap-2 flex-wrap">
                                    @if($address->is_default)
                                        <span class="inline-flex items-center gap-1 bg-primary text-white text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wide">
                                            <span class="material-symbols-outlined text-[12px]">check_circle</span>
                                            Mặc định
                                        </span>
                                    @endif
                                    {{-- type_label is a Model Accessor --}}
                                    <span class="text-xs text-gray-500 font-medium">{{ $address->type_label }}</span>
                                </div>
                                <span class="material-symbols-outlined text-gray-300 text-xl">location_on</span>
                            </div>

                            {{-- Card Body --}}
                            <div class="px-5 py-4">
                                <h3 class="font-bold text-gray-900 text-sm">{{ $address->recipient_name }}</h3>
                                <p class="text-primary text-sm font-semibold mt-0.5">{{ $address->recipient_phone }}</p>
                                {{-- full_address is a Model Accessor --}}
                                <p class="text-gray-500 text-xs mt-2 leading-relaxed">{{ $address->full_address }}</p>
                            </div>

                            {{-- Card Footer --}}
                            <div class="px-5 py-3 bg-gray-50/60 border-t border-gray-100 flex items-center justify-between gap-3">
                                <div class="flex items-center gap-3">
                                    <button
                                        onclick="openEditModal({{ $address->id }}, '{{ addslashes($address->recipient_name) }}', '{{ addslashes($address->recipient_phone) }}', '{{ addslashes($address->province) }}', '{{ addslashes($address->district) }}', '{{ addslashes($address->ward) }}', '{{ addslashes($address->address_detail) }}', {{ $address->is_default ? 'true' : 'false' }})"
                                        class="flex items-center gap-1.5 text-xs font-bold text-primary hover:text-primary/80 transition-colors">
                                        <span class="material-symbols-outlined text-[15px]">edit</span>
                                        Chỉnh sửa
                                    </button>
                                    <span class="w-px h-3 bg-gray-200"></span>
                                    <button
                                        onclick="deleteAddress({{ $address->id }})"
                                        class="flex items-center gap-1.5 text-xs font-bold text-gray-400 hover:text-red-500 transition-colors">
                                        <span class="material-symbols-outlined text-[15px]">delete</span>
                                        Xóa
                                    </button>
                                </div>
                                @if(!$address->is_default)
                                    <button
                                        onclick="setDefault({{ $address->id }})"
                                        class="text-[10px] font-bold text-gray-500 hover:text-primary border border-gray-200 hover:border-primary px-3 py-1.5 rounded-lg transition-all">
                                        Đặt mặc định
                                    </button>
                                @endif
                            </div>
                        </div>
                    @empty
                    @endforelse
                </div>

                {{-- Info Banners --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-6">
                    <div class="bg-gradient-to-br from-blue-600 to-blue-700 rounded-[18px] p-6 text-white relative overflow-hidden">
                        <div class="relative z-10">
                            <p class="text-xs font-bold uppercase tracking-wider text-blue-200 mb-2">Ưu tiên giao hàng</p>
                            <h3 class="text-lg font-black mb-2">Ưu Tiên Giao Hàng</h3>
                            <p class="text-sm text-blue-100 leading-relaxed mb-4">
                                Địa chỉ mặc định giúp quy trình thanh toán diễn ra nhanh chóng hơn. Bạn có thể thay đổi bất cứ lúc nào.
                            </p>
                            <a href="{{ route('pages.shipping') }}"
                                class="inline-flex items-center gap-1.5 text-xs font-bold text-white border-b border-white/50 hover:border-white pb-0.5 transition-colors">
                                Xem chi tiết chính sách
                                <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                            </a>
                        </div>
                        <div class="absolute -bottom-4 -right-4 w-24 h-24 bg-white/10 rounded-full pointer-events-none"></div>
                        <div class="absolute -top-6 -right-6 w-16 h-16 bg-white/5 rounded-full pointer-events-none"></div>
                    </div>

                    <div class="bg-gradient-to-br from-primary to-rose-600 rounded-[18px] p-6 text-white relative overflow-hidden">
                        <div class="relative z-10">
                            <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center mb-3">
                                <span class="material-symbols-outlined text-white text-xl">verified_user</span>
                            </div>
                            <h3 class="text-lg font-black mb-2">Bảo mật</h3>
                            <p class="text-sm text-red-100 leading-relaxed">
                                Dữ liệu địa chỉ của bạn được mã hóa và bảo vệ theo tiêu chuẩn quốc tế GDPR.
                            </p>
                        </div>
                        <div class="absolute -bottom-4 -right-4 w-24 h-24 bg-white/10 rounded-full pointer-events-none"></div>
                    </div>
                </div>

            </section>
        </div>
    </div>
</div>

{{-- ── Add / Edit Address Modal ── --}}
<div id="address-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden" role="dialog" aria-modal="true">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeAddressModal()"></div>
    <div class="relative bg-white rounded-[20px] shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">

        <div class="px-7 py-5 border-b border-gray-100 flex items-center justify-between">
            <h2 id="modal-title" class="text-lg font-bold text-gray-900">Thêm địa chỉ mới</h2>
            <button onclick="closeAddressModal()" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition-colors">
                <span class="material-symbols-outlined text-gray-500 text-[18px]">close</span>
            </button>
        </div>

        <form id="address-form" method="POST" class="p-7 space-y-4">
            @csrf
            <input type="hidden" id="form-method" name="_method" value="POST">
            <input type="hidden" id="form-address-id" value="">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Họ và tên người nhận <span class="text-primary">*</span></label>
                    <input type="text" name="recipient_name" id="field-name"
                        class="w-full px-3.5 py-2.5 border-[1.5px] border-gray-200 rounded-xl text-sm outline-none focus:border-primary focus:ring-[3px] focus:ring-primary/10 transition-all"
                        placeholder="Nguyễn Văn A" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Số điện thoại <span class="text-primary">*</span></label>
                    <input type="text" name="recipient_phone" id="field-phone"
                        class="w-full px-3.5 py-2.5 border-[1.5px] border-gray-200 rounded-xl text-sm outline-none focus:border-primary focus:ring-[3px] focus:ring-primary/10 transition-all"
                        placeholder="0901 234 567" required>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tỉnh / Thành phố <span class="text-primary">*</span></label>
                    <input type="text" name="province" id="field-province"
                        class="w-full px-3.5 py-2.5 border-[1.5px] border-gray-200 rounded-xl text-sm outline-none focus:border-primary focus:ring-[3px] focus:ring-primary/10 transition-all"
                        placeholder="TP. Hồ Chí Minh" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Quận / Huyện <span class="text-primary">*</span></label>
                    <input type="text" name="district" id="field-district"
                        class="w-full px-3.5 py-2.5 border-[1.5px] border-gray-200 rounded-xl text-sm outline-none focus:border-primary focus:ring-[3px] focus:ring-primary/10 transition-all"
                        placeholder="Quận 1" required>
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Phường / Xã</label>
                <input type="text" name="ward" id="field-ward"
                    class="w-full px-3.5 py-2.5 border-[1.5px] border-gray-200 rounded-xl text-sm outline-none focus:border-primary focus:ring-[3px] focus:ring-primary/10 transition-all"
                    placeholder="Phường Bến Thành">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Địa chỉ cụ thể <span class="text-primary">*</span></label>
                <input type="text" name="address_detail" id="field-detail"
                    class="w-full px-3.5 py-2.5 border-[1.5px] border-gray-200 rounded-xl text-sm outline-none focus:border-primary focus:ring-[3px] focus:ring-primary/10 transition-all"
                    placeholder="Số nhà, tên đường..." required>
            </div>

            <label class="flex items-center gap-3 cursor-pointer group">
                <input type="checkbox" name="is_default" id="field-default" value="1"
                    class="w-4 h-4 rounded text-primary focus:ring-primary border-gray-300">
                <span class="text-sm font-medium text-gray-700 group-hover:text-primary transition-colors">
                    Đặt làm địa chỉ mặc định
                </span>
            </label>

            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeAddressModal()"
                    class="flex-1 py-3 border-2 border-gray-200 text-gray-600 font-bold text-sm rounded-xl hover:bg-gray-50 transition-all">
                    Hủy
                </button>
                <button type="submit"
                    class="flex-1 py-3 bg-primary text-white font-bold text-sm rounded-xl hover:bg-primary/90 transition-all shadow-[0_4px_12px_rgba(201,33,39,0.25)]">
                    Lưu địa chỉ
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const ROUTES = {
        store:      '{{ route('account.addresses.store') }}',
        update:     (id) => `/account/addresses/${id}`,
        destroy:    (id) => `/account/addresses/${id}`,
        setDefault: (id) => `/account/addresses/${id}/default`,
    };
    const CSRF = '{{ csrf_token() }}';

    // ── Modal helpers ──
    function openAddressModal() {
        document.getElementById('modal-title').textContent = 'Thêm địa chỉ mới';
        document.getElementById('address-form').action = ROUTES.store;
        document.getElementById('form-method').value   = 'POST';
        document.getElementById('form-address-id').value = '';
        clearForm();
        document.getElementById('address-modal').classList.remove('hidden');
    }

    function openEditModal(id, name, phone, province, district, ward, detail, isDefault) {
        document.getElementById('modal-title').textContent = 'Chỉnh sửa địa chỉ';
        document.getElementById('address-form').action = ROUTES.update(id);
        document.getElementById('form-method').value   = 'PUT';
        document.getElementById('form-address-id').value = id;
        document.getElementById('field-name').value     = name;
        document.getElementById('field-phone').value    = phone;
        document.getElementById('field-province').value = province;
        document.getElementById('field-district').value = district;
        document.getElementById('field-ward').value     = ward;
        document.getElementById('field-detail').value   = detail;
        document.getElementById('field-default').checked = isDefault;
        document.getElementById('address-modal').classList.remove('hidden');
    }

    function closeAddressModal() {
        document.getElementById('address-modal').classList.add('hidden');
    }

    function clearForm() {
        ['field-name','field-phone','field-province','field-district','field-ward','field-detail'].forEach(id => {
            document.getElementById(id).value = '';
        });
        document.getElementById('field-default').checked = false;
    }

    // ── Delete address ──
    function deleteAddress(id) {
        if (!confirm('Bạn có chắc muốn xóa địa chỉ này?')) return;

        fetch(ROUTES.destroy(id), {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                const card = document.getElementById(`address-card-${id}`);
                if (card) {
                    card.style.transition = 'all 0.3s ease';
                    card.style.opacity    = '0';
                    card.style.transform  = 'scale(0.9)';
                    setTimeout(() => card.remove(), 300);
                }
            }
        })
        .catch(console.error);
    }

    // ── Set default ──
    function setDefault(id) {
        fetch(ROUTES.setDefault(id), {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            }
        })
        .catch(console.error);
    }

    // Close modal on Escape
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closeAddressModal();
    });
</script>
@endpush
