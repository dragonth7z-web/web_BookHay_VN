@extends('layouts.admin')

@section('title', 'Thêm Tác Giả Mới')
@section('page-title', 'Thêm Tác Giả Mới')

@section('content')
<div class="max-w-[860px] mx-auto space-y-6">

    {{-- ── Breadcrumb ── --}}
    <nav class="flex items-center gap-2 text-xs text-slate-500 font-medium">
        <a href="{{ route('admin.dashboard') }}" class="hover:text-primary transition-colors">Dashboard</a>
        <span class="material-symbols-outlined text-[14px]">chevron_right</span>
        <a href="{{ route('admin.authors.index') }}" class="hover:text-primary transition-colors">Author Management</a>
        <span class="material-symbols-outlined text-[14px]">chevron_right</span>
        <span class="text-primary font-bold">Add New Author</span>
    </nav>

    {{-- ── Page Header ── --}}
    <div>
        <h1 class="text-3xl font-black text-primary"
            style="font-family: var(--font-heading, 'Lora', serif)">
            Thêm tác giả mới
        </h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
            Điền các thông tin chi tiết để cập nhật hồ sơ tác giả vào hệ thống lưu trữ.
        </p>
    </div>

    <form action="{{ route('admin.authors.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-2xl text-sm">
                @foreach($errors->all() as $error)
                    <p class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">error</span>
                        {{ $error }}
                    </p>
                @endforeach
            </div>
        @endif

        {{-- ── Section 1: Personal Information ── --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm p-7">
            <div class="flex items-center gap-2 mb-6 pb-4 border-b border-slate-100 dark:border-slate-800">
                <span class="material-symbols-outlined text-primary text-[20px]">person</span>
                <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-500">Personal Information</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                {{-- Họ và tên --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                        Họ và tên tác giả
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}"
                        class="admin-input w-full"
                        placeholder="Vd: Haruki Murakami">
                </div>

                {{-- Quốc tịch --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                        Quốc tịch
                    </label>
                    <input type="text" name="country" value="{{ old('country') }}"
                        class="admin-input w-full"
                        placeholder="Vd: Nhật Bản">
                </div>

                {{-- Slug --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                        Slug (URL)
                    </label>
                    <input type="text" name="slug" value="{{ old('slug') }}"
                        id="author-slug"
                        class="admin-input w-full"
                        placeholder="haruki-murakami">
                </div>

                {{-- Ngày sinh (optional) --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                        Ngày sinh
                    </label>
                    <input type="date" name="birth_date" value="{{ old('birth_date') }}"
                        class="admin-input w-full">
                </div>
            </div>
        </div>

        {{-- ── Section 2: Professional Bio ── --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm p-7">
            <div class="flex items-center gap-2 mb-6 pb-4 border-b border-slate-100 dark:border-slate-800">
                <span class="material-symbols-outlined text-primary text-[20px]">auto_stories</span>
                <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-500">Professional Bio</p>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                    Tiểu sử tác giả
                </label>

                {{-- Toolbar --}}
                <div class="flex items-center gap-1 px-3 py-2 border border-slate-200 dark:border-slate-700 border-b-0 rounded-t-xl bg-slate-50 dark:bg-slate-800">
                    <button type="button" onclick="formatText('bold')"
                        class="w-7 h-7 flex items-center justify-center rounded font-black text-sm text-slate-600 hover:bg-white hover:text-primary transition-all">
                        B
                    </button>
                    <button type="button" onclick="formatText('italic')"
                        class="w-7 h-7 flex items-center justify-center rounded italic text-sm text-slate-600 hover:bg-white hover:text-primary transition-all">
                        I
                    </button>
                    <button type="button" onclick="formatText('insertUnorderedList')"
                        class="w-7 h-7 flex items-center justify-center rounded text-slate-600 hover:bg-white hover:text-primary transition-all">
                        <span class="material-symbols-outlined text-[16px]">format_list_bulleted</span>
                    </button>
                    <button type="button" onclick="formatText('createLink')"
                        class="w-7 h-7 flex items-center justify-center rounded text-slate-600 hover:bg-white hover:text-primary transition-all">
                        <span class="material-symbols-outlined text-[16px]">link</span>
                    </button>
                    <button type="button" onclick="formatText('formatBlock', 'blockquote')"
                        class="w-7 h-7 flex items-center justify-center rounded text-slate-600 hover:bg-white hover:text-primary transition-all font-serif text-base">
                        "
                    </button>
                </div>

                {{-- Editor area --}}
                <div id="bio-editor"
                    contenteditable="true"
                    class="min-h-[160px] px-4 py-3 border border-slate-200 dark:border-slate-700 rounded-b-xl text-sm text-slate-700 dark:text-slate-300 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 transition-all bg-white dark:bg-slate-900"
                    data-placeholder="Viết tóm tắt về sự nghiệp, phong cách sáng tác và các tác phẩm tiêu biểu...">
                </div>
                <input type="hidden" name="biography" id="biography-input">
            </div>
        </div>

        {{-- ── Section 3: Media ── --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm p-7">
            <div class="flex items-center gap-2 mb-6 pb-4 border-b border-slate-100 dark:border-slate-800">
                <span class="material-symbols-outlined text-primary text-[20px]">image</span>
                <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-500">Media</p>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-3">
                    Ảnh đại diện tác giả
                </label>

                {{-- Drop zone --}}
                <label for="avatar-upload"
                    class="flex flex-col items-center justify-center gap-3 w-full min-h-[160px] border-2 border-dashed border-slate-200 dark:border-slate-700 rounded-2xl cursor-pointer hover:border-primary/50 hover:bg-primary/5 transition-all group"
                    id="drop-zone">
                    <div id="drop-preview" class="hidden w-full h-full">
                        <img id="preview-img" src="" alt="Preview"
                            class="w-full h-48 object-cover rounded-xl">
                    </div>
                    <div id="drop-placeholder" class="flex flex-col items-center gap-2 py-8">
                        <span class="material-symbols-outlined text-slate-400 group-hover:text-primary text-4xl transition-colors">cloud_upload</span>
                        <p class="text-sm text-slate-500">
                            Kéo và thả ảnh vào đây hoặc
                            <span class="text-primary font-semibold underline">Duyệt tệp</span>
                        </p>
                        <p class="text-[11px] text-slate-400">Hỗ trợ JPG, PNG (Tối đa 8MB). Tỉ lệ khuyến nghị: 3:4</p>
                    </div>
                    <input type="file" name="avatar" id="avatar-upload" accept="image/*" class="hidden">
                </label>
            </div>
        </div>

        {{-- ── Action Buttons ── --}}
        <div class="flex items-center justify-end gap-3 pb-8">
            <a href="{{ route('admin.authors.index') }}"
                class="px-6 py-2.5 border-2 border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 font-bold text-sm rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 transition-all">
                Hủy
            </a>
            <button type="submit"
                class="inline-flex items-center gap-2 bg-primary text-white font-bold px-7 py-2.5 rounded-xl hover:bg-primary/90 transition-all shadow-[0_4px_12px_rgba(201,33,39,0.3)] text-sm">
                <span class="material-symbols-outlined text-[18px]">save</span>
                Lưu tác giả
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    // ── Auto-generate slug from name ──
    document.querySelector('input[name="name"]')?.addEventListener('input', function () {
        const slug = this.value
            .toLowerCase()
            .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
            .replace(/đ/g, 'd')
            .replace(/[^a-z0-9\s-]/g, '')
            .trim()
            .replace(/\s+/g, '-');
        const slugInput = document.getElementById('author-slug');
        if (slugInput && !slugInput.dataset.manual) {
            slugInput.value = slug;
        }
    });

    document.getElementById('author-slug')?.addEventListener('input', function () {
        this.dataset.manual = 'true';
    });

    // ── Rich text editor ──
    function formatText(command, value = null) {
        document.execCommand(command, false, value);
        document.getElementById('bio-editor').focus();
    }

    // Sync editor content to hidden input on form submit
    document.querySelector('form')?.addEventListener('submit', function () {
        const editor = document.getElementById('bio-editor');
        const input  = document.getElementById('biography-input');
        if (editor && input) {
            input.value = editor.innerHTML;
        }
    });

    // Placeholder behavior
    const editor = document.getElementById('bio-editor');
    if (editor) {
        editor.addEventListener('focus', function () {
            if (this.textContent.trim() === '') this.innerHTML = '';
        });
        editor.addEventListener('blur', function () {
            if (this.textContent.trim() === '') {
                this.innerHTML = '';
            }
        });
    }

    // ── Image preview ──
    document.getElementById('avatar-upload')?.addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function (e) {
            document.getElementById('preview-img').src = e.target.result;
            document.getElementById('drop-preview').classList.remove('hidden');
            document.getElementById('drop-placeholder').classList.add('hidden');
        };
        reader.readAsDataURL(file);
    });

    // Drag & drop
    const dropZone = document.getElementById('drop-zone');
    if (dropZone) {
        dropZone.addEventListener('dragover', e => { e.preventDefault(); dropZone.classList.add('border-primary'); });
        dropZone.addEventListener('dragleave', () => dropZone.classList.remove('border-primary'));
        dropZone.addEventListener('drop', e => {
            e.preventDefault();
            dropZone.classList.remove('border-primary');
            const file = e.dataTransfer.files[0];
            if (file && file.type.startsWith('image/')) {
                const dt = new DataTransfer();
                dt.items.add(file);
                document.getElementById('avatar-upload').files = dt.files;
                document.getElementById('avatar-upload').dispatchEvent(new Event('change'));
            }
        });
    }
</script>
@endpush
