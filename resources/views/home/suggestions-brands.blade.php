<section id="ai-suggestions" class="scroll-reveal bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden py-10 px-6 md:px-8 md:py-12 section-block">
    <x-section-header
        title="Gợi Ý Riêng Cho Bạn"
        subtitle="A.I phân tích sở thích để chọn lọc hàng đầu"
        icon="smart_toy"
    />

    {{-- Personalization label (JS will update) --}}
    <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-slate-400 font-medium mb-3" id="personalization-label">
        <span class="material-symbols-outlined">history</span>
        <span id="personalization-text">Sách nổi bật tuần này</span>
    </div>

    <div class="grid-book-layout mt-6">
        @if(isset($recommendedBooks) && $recommendedBooks->count() > 0)
            @foreach($recommendedBooks->take(15) as $index => $book)
                <div>
                    <x-book-card :book="$book" />
                </div>
            @endforeach
        @endif
    </div>

    {{-- Center Action Button --}}
    @if(isset($recommendedBooks) && $recommendedBooks->count() > 0)
    <div class="flex justify-center mt-12 pb-4">
        <a href="{{ route('book-preferences.index') }}" class="btn-view-all-premium group">
            <span class="material-symbols-outlined text-xl transition-transform group-hover:rotate-12">auto_stories</span>
            Tùy Chỉnh Sở Thích Sách
        </a>
    </div>
    @endif
</section>

@if(isset($partners) && $partners->isNotEmpty())
<section id="publisher-partners" class="scroll-reveal bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden py-10 px-6 md:px-8 md:py-12 section-block mt-8">
    <x-section-header
        title="Đối Tác Xuất Bản"
        subtitle="Cam kết 100% sách chính hãng bản quyền"
        icon="museum"
    />

    {{-- 1 hàng ngang, card tự giãn đều theo số lượng NXB, tên hiển thị đầy đủ --}}
    <div class="flex gap-3 mt-8">
        @foreach($partners as $index => $brand)
            @php 
                $partnerGradients = [
                    'from-[#111827] to-[#1f2937]',
                    'from-[#C92127] to-[#ef5350]',
                    'from-[#1e293b] to-[#334155]',
                    'from-[#0f172a] to-[#1e293b]',
                ];
                $gradient = $partnerGradients[$index % count($partnerGradients)]; 
            @endphp
            <div class="group relative flex flex-col items-center justify-center gap-2 p-4 rounded-2xl overflow-hidden cursor-pointer shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 bg-gradient-to-br {{ $gradient }} flex-1 min-w-0"
                 style="min-height:110px">
                <div class="absolute -top-4 -right-4 w-12 h-12 bg-white/10 rounded-full blur-xl group-hover:scale-150 transition-transform duration-700"></div>
                <div class="absolute -bottom-4 -left-4 w-8 h-8 bg-white/5 rounded-full blur-lg group-hover:scale-150 transition-transform duration-700 delay-100"></div>

                {{-- Logo hoặc Icon --}}
                <div class="relative z-10 w-full flex items-center justify-center" style="height:44px">
                    @if($brand->logo && !str_contains($brand->logo, 'ui-avatars'))
                        <img src="{{ asset($brand->logo) }}" alt="{{ $brand->name }}"
                             class="max-w-[85%] max-h-full object-contain filter brightness-0 invert opacity-90 group-hover:opacity-100 group-hover:scale-110 transition-all duration-300">
                    @else
                        <span class="material-symbols-outlined text-[36px] text-white/80 group-hover:text-white group-hover:scale-110 transition-all duration-300">{{ $brand->partner_icon ?? 'corporate_fare' }}</span>
                    @endif
                </div>

                {{-- Tên: 2 dòng tối đa, căn giữa, không cắt --}}
                <span class="text-[10px] font-black text-white text-center uppercase tracking-[0.08em] leading-snug relative z-10 opacity-75 group-hover:opacity-100 transition-opacity w-full line-clamp-2">{{ $brand->name }}</span>

                <div class="absolute top-0 -left-[100%] w-1/2 h-full bg-gradient-to-r from-transparent via-white/10 to-transparent -skew-x-[25deg] group-hover:animate-shimmer-slide"></div>
            </div>
        @endforeach
    </div>

    {{-- Center Action Button --}}
    <div class="flex justify-center mt-12 pb-4">
        <a href="{{ route('publishers.index') }}" class="btn-view-all-premium group">
            <span class="material-symbols-outlined text-xl transition-transform group-hover:rotate-12">museum</span>
            Xem tất cả đối tác xuất bản
        </a>
    </div>
</section>
@endif

