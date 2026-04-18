<section id="all-products" class="scroll-reveal bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden py-10 px-6 md:px-8 md:py-12 section-block">
    <x-section-header icon="auto_stories" title="Sách Mới Dành Cho Bạn"
        subtitle="Khám phá những tác phẩm mới nhất vừa cập bến THLD" />

    @if($latestBooks->isNotEmpty())
        <div class="grid-book-layout mt-6">
            @foreach($latestBooks->take(15) as $book)
                <x-book-card :book="$book" />
            @endforeach
        </div>

        {{-- Center Action Button --}}
        <div class="flex justify-center mt-12 pb-4">
            <a href="{{ route('books.search') }}" class="btn-view-all-premium group">
                <span
                    class="material-symbols-outlined text-xl transition-transform group-hover:rotate-12">auto_stories</span>
                Xem trọn bộ sưu tập sách
            </a>
        </div>
    @else
        {{-- EMPTY STATE: Friendly Welcome UI --}}
        <div
            class="group/empty bg-white dark:bg-slate-800/50 rounded-[2.5rem] border border-slate-200/60 dark:border-slate-700/60 p-12 text-center shadow-xl shadow-slate-200/50 dark:shadow-none relative overflow-hidden transition-all duration-500">
            <div
                class="absolute -top-[100px] -right-[100px] w-[300px] h-[300px] bg-[radial-gradient(circle,rgba(201,33,39,0.05)_0%,transparent_70%)] z-1">
            </div>

            <div class="relative z-10">
                <div
                    class="w-24 h-24 bg-primary/5 rounded-full flex items-center justify-center mx-auto mb-8 animate-bounce relative z-2">
                    <span class="material-symbols-outlined text-primary text-5xl">book_5</span>
                </div>

                <h3 class="text-2xl font-black text-slate-900 dark:text-white mb-4">Chào mừng bạn đến với THLD
                    Bookstore!</h3>
                <p class="text-slate-500 dark:text-slate-400 max-w-lg mx-auto mb-10 leading-relaxed text-lg">
                    Hệ thống đang chuẩn bị những đầu sách tinh hoa và ưu đãi hấp dẫn nhất cho bạn.
                </p>

                <form action="{{ route('books.search') }}" method="GET" class="max-w-2xl mx-auto mb-10">
                    <div class="relative group">
                        <input type="text" name="keyword"
                            class="w-full h-16 pl-14 pr-32 rounded-2xl bg-slate-50 dark:bg-slate-900/50 border-2 border-slate-200 dark:border-slate-700 focus:border-primary focus:outline-none focus:ring-4 focus:ring-primary/10 transition-all text-lg dark:text-white"
                            placeholder="Nhập tên sách, tác giả bạn muốn tìm...">
                        <span
                            class="material-symbols-outlined absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary transition-colors text-2xl">search</span>
                        <button type="submit"
                            class="absolute right-3 top-1/2 -translate-y-1/2 bg-primary text-white px-8 py-3 rounded-xl font-bold hover:bg-primary-dark transition-all shadow-lg shadow-primary/20">
                            Tìm ngay
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</section>
