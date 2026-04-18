<!DOCTYPE html>

<html lang="vi">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Quản lý Nhà Xuất Bản - THLD</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700;800;900&amp;display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=swap" rel="stylesheet" />
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#ec5b13",
                        "background-light": "#f8f6f6",
                        "background-dark": "#221610",
                    },
                    fontFamily: {
                        "display": ["Public Sans"]
                    },
                    borderRadius: { "DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px" },
                },
            },
        }
    </script>
</head>

<body
    class="bg-background-light dark:bg-background-dark font-display text-slate-900 dark:text-slate-100 transition-colors duration-200">
    <div class="relative flex min-h-screen flex-col overflow-x-hidden">
        <!-- Header / TopNavBar -->
        <header
            class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 bg-white dark:bg-background-dark px-6 py-3 lg:px-10 sticky top-0 z-50">
            <div class="flex items-center gap-8">
                <div class="flex items-center gap-3 text-primary">
                    <span class="material-symbols-outlined text-3xl font-bold">menu_book</span>
                    <h2
                        class="text-slate-900 dark:text-white text-lg font-black leading-tight tracking-tight uppercase">
                        THLD Admin</h2>
                </div>
                <nav class="hidden md:flex items-center gap-6">
                    <a class="text-slate-600 dark:text-slate-300 text-sm font-semibold hover:text-primary transition-colors"
                        href="#">Dashboard</a>
                    <a class="text-slate-600 dark:text-slate-300 text-sm font-semibold hover:text-primary transition-colors"
                        href="#">Sách</a>
                    <a class="text-primary text-sm font-bold border-b-2 border-primary pb-1" href="#">NXB</a>
                    <a class="text-slate-600 dark:text-slate-300 text-sm font-semibold hover:text-primary transition-colors"
                        href="#">Đơn hàng</a>
                </nav>
            </div>
            <div class="flex items-center gap-4">
                <div
                    class="hidden sm:flex items-center bg-slate-100 dark:bg-slate-800 rounded-xl px-3 py-1.5 border border-transparent focus-within:border-primary transition-all">
                    <span class="material-symbols-outlined text-slate-400 text-xl">search</span>
                    <input class="bg-transparent border-none focus:ring-0 text-sm placeholder:text-slate-400 w-48"
                        placeholder="Tìm kiếm NXB..." type="text" />
                </div>
                <div class="flex gap-2">
                    <button
                        class="flex items-center justify-center rounded-xl p-2 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-primary/10 hover:text-primary transition-all">
                        <span class="material-symbols-outlined">notifications</span>
                    </button>
                    <button
                        class="flex items-center justify-center rounded-xl p-2 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-primary/10 hover:text-primary transition-all">
                        <span class="material-symbols-outlined">settings</span>
                    </button>
                </div>
                <div
                    class="h-10 w-10 rounded-full bg-primary/20 border-2 border-primary flex items-center justify-center overflow-hidden">
                    <img alt="Admin Avatar" class="w-full h-full object-cover"
                        data-alt="Ảnh đại diện quản trị viên hệ thống"
                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuDC7z-mfQRibehATqFN9uHfqZthhQwzOF-TliR9BLewv5LHJvL7L88Xepp9oofo0yubARSklfEc-7OBsZHRy2YYJOVHDsG9UnN3rGI9pYXJmTXa5cRHPsXdmipgvNLcW8JN8gjF77I5iCpbNCccGyPmzbxcl8rV2PHg7b5WXi_fwap5CT9oA2LIEcmUYLIxp3u-IBVoXjdlS3sGjFw5R5reiL6iqqLIZQRLVEMOaG0aKgHWFz7d9kLJN9KVoziUq7UdEo6FDxAJBQM" />
                </div>
            </div>
        </header>
        <main class="flex-1 flex flex-col md:flex-row h-full">
            <!-- Sidebar Navigation -->
            <aside
                class="w-full md:w-64 border-r border-slate-200 dark:border-slate-800 bg-white dark:bg-background-dark p-6 hidden lg:block">
                <div class="flex flex-col gap-6">
                    <div class="px-2">
                        <h1 class="text-slate-900 dark:text-white text-base font-bold">Hệ Thống THLD</h1>
                        <p class="text-slate-500 dark:text-slate-400 text-xs">Phân quyền: Quản trị viên</p>
                    </div>
                    <nav class="flex flex-col gap-1">
                        <a class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-all"
                            href="#">
                            <span class="material-symbols-outlined">dashboard</span>
                            <span class="text-sm font-semibold">Tổng quan</span>
                        </a>
                        <a class="flex items-center gap-3 px-3 py-2.5 rounded-xl bg-primary text-white shadow-lg shadow-primary/20 transition-all"
                            href="#">
                            <span class="material-symbols-outlined">corporate_fare</span>
                            <span class="text-sm font-semibold">Nhà xuất bản</span>
                        </a>
                        <a class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-all"
                            href="#">
                            <span class="material-symbols-outlined">book_4</span>
                            <span class="text-sm font-semibold">Quản lý Sách</span>
                        </a>
                        <a class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-all"
                            href="#">
                            <span class="material-symbols-outlined">group</span>
                            <span class="text-sm font-semibold">Khách hàng</span>
                        </a>
                        <a class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-all"
                            href="#">
                            <span class="material-symbols-outlined">shopping_cart</span>
                            <span class="text-sm font-semibold">Đơn hàng</span>
                        </a>
                    </nav>
                    <div class="mt-10 border-t border-slate-100 dark:border-slate-800 pt-6">
                        <a class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-red-500 hover:bg-red-50 dark:hover:bg-red-950/20 transition-all"
                            href="#">
                            <span class="material-symbols-outlined">logout</span>
                            <span class="text-sm font-semibold">Đăng xuất</span>
                        </a>
                    </div>
                </div>
            </aside>
            <!-- Main Content Area -->
            <div class="flex-1 p-4 md:p-8 lg:p-10">
                <!-- Page Header -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
                    <div>
                        <h2 class="text-slate-900 dark:text-white text-3xl font-extrabold tracking-tight">Nhà Xuất Bản
                        </h2>
                        <p class="text-slate-500 dark:text-slate-400 mt-1">Quản lý thông tin và danh sách các đối tác
                            xuất bản sách.</p>
                    </div>
                    <button
                        class="flex items-center gap-2 px-5 py-2.5 bg-primary text-white rounded-xl font-bold text-sm shadow-lg shadow-primary/30 hover:bg-primary/90 transition-all">
                        <span class="material-symbols-outlined text-lg">add_circle</span>
                        Thêm NXB Mới
                    </button>
                </div>
                <!-- Stats Overview -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
                    <div
                        class="bg-white dark:bg-slate-800/50 p-6 rounded-2xl border border-slate-200 dark:border-slate-700/50 shadow-sm">
                        <div class="flex justify-between items-start mb-4">
                            <div class="p-2 bg-primary/10 rounded-lg text-primary">
                                <span class="material-symbols-outlined">apartment</span>
                            </div>
                            <span
                                class="text-emerald-500 text-xs font-bold bg-emerald-500/10 px-2 py-1 rounded-full">+5%</span>
                        </div>
                        <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">Tổng số NXB</p>
                        <h3 class="text-2xl font-black text-slate-900 dark:text-white mt-1">45</h3>
                    </div>
                    <div
                        class="bg-white dark:bg-slate-800/50 p-6 rounded-2xl border border-slate-200 dark:border-slate-700/50 shadow-sm">
                        <div class="flex justify-between items-start mb-4">
                            <div class="p-2 bg-blue-500/10 rounded-lg text-blue-500">
                                <span class="material-symbols-outlined">library_books</span>
                            </div>
                            <span
                                class="text-emerald-500 text-xs font-bold bg-emerald-500/10 px-2 py-1 rounded-full">+12%</span>
                        </div>
                        <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">Tổng đầu sách</p>
                        <h3 class="text-2xl font-black text-slate-900 dark:text-white mt-1">1,250</h3>
                    </div>
                    <div
                        class="bg-white dark:bg-slate-800/50 p-6 rounded-2xl border border-slate-200 dark:border-slate-700/50 shadow-sm">
                        <div class="flex justify-between items-start mb-4">
                            <div class="p-2 bg-amber-500/10 rounded-lg text-amber-500">
                                <span class="material-symbols-outlined">fiber_new</span>
                            </div>
                            <span class="text-slate-400 dark:text-slate-500 text-xs font-bold px-2 py-1">Ổn định</span>
                        </div>
                        <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">NXB mới (tháng này)</p>
                        <h3 class="text-2xl font-black text-slate-900 dark:text-white mt-1">02</h3>
                    </div>
                </div>
                <!-- Data Table -->
                <div
                    class="bg-white dark:bg-slate-800/40 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr
                                    class="bg-slate-50 dark:bg-slate-800/80 border-b border-slate-200 dark:border-slate-700">
                                    <th
                                        class="px-6 py-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                                        Mã NXB</th>
                                    <th
                                        class="px-6 py-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                                        Tên Nhà Xuất Bản</th>
                                    <th
                                        class="px-6 py-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                                        Số lượng đầu sách</th>
                                    <th
                                        class="px-6 py-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                                        Ngày hợp tác</th>
                                    <th
                                        class="px-6 py-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                                        Trạng thái</th>
                                    <th
                                        class="px-6 py-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider text-right">
                                        Thao tác</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors group">
                                    <td class="px-6 py-5 font-medium text-slate-600 dark:text-slate-300">NXB001</td>
                                    <td class="px-6 py-5">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-10 h-10 rounded-lg bg-slate-100 dark:bg-slate-700 flex items-center justify-center overflow-hidden">
                                                <img alt="Kim Đồng" class="w-full h-full object-cover"
                                                    data-alt="Logo nhà xuất bản Kim Đồng"
                                                    src="https://lh3.googleusercontent.com/aida-public/AB6AXuCzF7BJVyMaNCDl24oDsJ-xG_x1-dzQ2qWyzdzhaAsbaL4zT9jr83TNqJSjqZdc6cIzL0B0HI6JEHZaYsIBJWc0MTog-q-pLXbpkI7FiQvAkJRm1Ki2CFDs7_VCO3s69xUR8jpnSWvIQYLjWvCpBK5FAIuRUPW0W3FbqC1rxTx3aQKcLnh5nVi2osy-6PA6HsLA1Q13yQjLKPrMmiUblTxU1MqDRrcvU9ABTGb9T7r-H8R4ZblcBeH3fx0n9cW487HpbqIOl7XlFRs" />
                                            </div>
                                            <span class="font-bold text-slate-900 dark:text-white">Kim Đồng</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 text-slate-600 dark:text-slate-400 font-semibold">450</td>
                                    <td class="px-6 py-5 text-slate-500 dark:text-slate-400">12/05/2020</td>
                                    <td class="px-6 py-5">
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400">
                                            Hoạt động
                                        </span>
                                    </td>
                                    <td class="px-6 py-5 text-right">
                                        <div
                                            class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <button
                                                class="p-1.5 text-blue-500 hover:bg-blue-50 dark:hover:bg-blue-950/30 rounded-lg transition-all"
                                                title="Sửa">
                                                <span class="material-symbols-outlined text-xl">edit</span>
                                            </button>
                                            <button
                                                class="p-1.5 text-red-500 hover:bg-red-50 dark:hover:bg-red-950/30 rounded-lg transition-all"
                                                title="Xóa">
                                                <span class="material-symbols-outlined text-xl">delete</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors group">
                                    <td class="px-6 py-5 font-medium text-slate-600 dark:text-slate-300">NXB002</td>
                                    <td class="px-6 py-5">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-10 h-10 rounded-lg bg-slate-100 dark:bg-slate-700 flex items-center justify-center overflow-hidden">
                                                <img alt="Trẻ" class="w-full h-full object-cover"
                                                    data-alt="Logo nhà xuất bản Trẻ"
                                                    src="https://lh3.googleusercontent.com/aida-public/AB6AXuDZ4SuUtQE6QG7bS9g20EI5LQaC_0e4IPQ7DduWsiKqlIYxqcUd-6XxrlUP_rzVXsjiw1bXnOQgmFCuw9X-a-59-FrF-fHLpCs_adi_8O1J6thnMbCpCn21GqfHP_nNH6PdiZCTmy7CNzHT5zpiiyAljJD5K1KwdAbA_MFOvYqje26Ad_HdE4MCnKWzAIUiLKmflkWQqNZFIdB75_eFHw1NXzWE850695wj2RDYyHQQrT6OCojhjlx6S3gMGbfv31f6-VdqwTsyCIk" />
                                            </div>
                                            <span class="font-bold text-slate-900 dark:text-white">NXB Trẻ</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 text-slate-600 dark:text-slate-400 font-semibold">320</td>
                                    <td class="px-6 py-5 text-slate-500 dark:text-slate-400">15/06/2020</td>
                                    <td class="px-6 py-5">
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400">
                                            Hoạt động
                                        </span>
                                    </td>
                                    <td class="px-6 py-5 text-right">
                                        <div
                                            class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <button
                                                class="p-1.5 text-blue-500 hover:bg-blue-50 dark:hover:bg-blue-950/30 rounded-lg transition-all"
                                                title="Sửa">
                                                <span class="material-symbols-outlined text-xl">edit</span>
                                            </button>
                                            <button
                                                class="p-1.5 text-red-500 hover:bg-red-50 dark:hover:bg-red-950/30 rounded-lg transition-all"
                                                title="Xóa">
                                                <span class="material-symbols-outlined text-xl">delete</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors group">
                                    <td class="px-6 py-5 font-medium text-slate-600 dark:text-slate-300">NXB003</td>
                                    <td class="px-6 py-5">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-10 h-10 rounded-lg bg-slate-100 dark:bg-slate-700 flex items-center justify-center overflow-hidden">
                                                <img alt="Nhã Nam" class="w-full h-full object-cover"
                                                    data-alt="Logo nhà xuất bản Nhã Nam"
                                                    src="https://lh3.googleusercontent.com/aida-public/AB6AXuBjmwv2HtcnXseJP2jD-TDre-Ja9VYz717yHF0cwvoZxU2iEzlUUXi9WRWGkYOULbx9EEIdWaI6EV66ogMGihFrIyFU7rgKx1M-XGm_1ea6xC6YSvunjuKQfHKm3-N_raUl8ZoJ5f0lid6olhBO236Vcl-MlBnlqDHSbA5GvUmmWjZjmTIZwNwGQQhrqrZCo_0xGVSL-2j8gAtRS0m1iM0gq2B2HaeUdiExnljd2q2Nga5-WrTN_LNhEt5tO3qqzJ7nDg2woPTfOU0" />
                                            </div>
                                            <span class="font-bold text-slate-900 dark:text-white">Nhã Nam</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 text-slate-600 dark:text-slate-400 font-semibold">280</td>
                                    <td class="px-6 py-5 text-slate-500 dark:text-slate-400">20/01/2021</td>
                                    <td class="px-6 py-5">
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400">
                                            Hoạt động
                                        </span>
                                    </td>
                                    <td class="px-6 py-5 text-right">
                                        <div
                                            class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <button
                                                class="p-1.5 text-blue-500 hover:bg-blue-50 dark:hover:bg-blue-950/30 rounded-lg transition-all"
                                                title="Sửa">
                                                <span class="material-symbols-outlined text-xl">edit</span>
                                            </button>
                                            <button
                                                class="p-1.5 text-red-500 hover:bg-red-50 dark:hover:bg-red-950/30 rounded-lg transition-all"
                                                title="Xóa">
                                                <span class="material-symbols-outlined text-xl">delete</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors group">
                                    <td class="px-6 py-5 font-medium text-slate-600 dark:text-slate-300">NXB004</td>
                                    <td class="px-6 py-5">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-10 h-10 rounded-lg bg-slate-100 dark:bg-slate-700 flex items-center justify-center overflow-hidden">
                                                <img alt="Phụ Nữ" class="w-full h-full object-cover"
                                                    data-alt="Logo nhà xuất bản Phụ Nữ"
                                                    src="https://lh3.googleusercontent.com/aida-public/AB6AXuDgdj8WWNZAQqAUjUxmSHsG2Ve8fI99ji9GU1bPSN-lUesPKmgeWtCIvjmxirFgFS43mdCI9rNkRBYd4t-3aDhbmu8CTon9iBy1oUfQbdmNR6iw0Y9Q3cP__qb-6rfRhLydNciNhSNnP73wbDgO3HJ5jYSGY17ES6HJbrwBHQyXbdWYdmqRCO_i-77U4WbZ0_LQlOR2OzZcu4CJoTQzT5RXAgG5Chgt28ZkpX0OnrXZKCL9mQw9l8hypaoDnSDyOKv9cLusULHXLtc" />
                                            </div>
                                            <span class="font-bold text-slate-900 dark:text-white">Phụ Nữ Việt
                                                Nam</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 text-slate-600 dark:text-slate-400 font-semibold">200</td>
                                    <td class="px-6 py-5 text-slate-500 dark:text-slate-400">10/11/2022</td>
                                    <td class="px-6 py-5">
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-500 dark:bg-slate-700 dark:text-slate-400">
                                            Tạm dừng
                                        </span>
                                    </td>
                                    <td class="px-6 py-5 text-right">
                                        <div
                                            class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <button
                                                class="p-1.5 text-blue-500 hover:bg-blue-50 dark:hover:bg-blue-950/30 rounded-lg transition-all"
                                                title="Sửa">
                                                <span class="material-symbols-outlined text-xl">edit</span>
                                            </button>
                                            <button
                                                class="p-1.5 text-red-500 hover:bg-red-50 dark:hover:bg-red-950/30 rounded-lg transition-all"
                                                title="Xóa">
                                                <span class="material-symbols-outlined text-xl">delete</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors group">
                                    <td class="px-6 py-5 font-medium text-slate-600 dark:text-slate-300">NXB005</td>
                                    <td class="px-6 py-5">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-10 h-10 rounded-lg bg-slate-100 dark:bg-slate-700 flex items-center justify-center overflow-hidden">
                                                <img alt="Giáo Dục" class="w-full h-full object-cover"
                                                    data-alt="Logo nhà xuất bản Giáo Dục"
                                                    src="https://lh3.googleusercontent.com/aida-public/AB6AXuBM0_Bt_bLJn0OlgqaO_7ks0rFfdkVl9KpWUwHNpN_FE_ZwXQuKdWYgtQdDUdzBnQd9GWDPrTokW76jqS9Xokgy0FAMXOIOAMSpNFf2ts0H2qR0CUMbmjJ7IvcrMrArQdVQZCfJR5-TVDs9fl_KWtdxW5tTqfnf3LT_kO4eSDb1Ipih0MYjmP_rZxVXrmB3dSysn0clTcwa4H8_bNCTcrMD93gZVtXIYuJDmo5WX2jE0lu9VBgBIi3TbGoUTMh8ZtklUGqDmlAFTok" />
                                            </div>
                                            <span class="font-bold text-slate-900 dark:text-white">Giáo Dục Việt
                                                Nam</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 text-slate-600 dark:text-slate-400 font-semibold">185</td>
                                    <td class="px-6 py-5 text-slate-500 dark:text-slate-400">05/03/2019</td>
                                    <td class="px-6 py-5">
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400">
                                            Hoạt động
                                        </span>
                                    </td>
                                    <td class="px-6 py-5 text-right">
                                        <div
                                            class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <button
                                                class="p-1.5 text-blue-500 hover:bg-blue-50 dark:hover:bg-blue-950/30 rounded-lg transition-all"
                                                title="Sửa">
                                                <span class="material-symbols-outlined text-xl">edit</span>
                                            </button>
                                            <button
                                                class="p-1.5 text-red-500 hover:bg-red-50 dark:hover:bg-red-950/30 rounded-lg transition-all"
                                                title="Xóa">
                                                <span class="material-symbols-outlined text-xl">delete</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination -->
                    <div
                        class="px-6 py-4 flex items-center justify-between border-t border-slate-100 dark:border-slate-700 bg-white/50 dark:bg-slate-800/20">
                        <p class="text-sm text-slate-500 dark:text-slate-400">
                            Hiển thị <span class="font-bold text-slate-900 dark:text-white">1</span> đến <span
                                class="font-bold text-slate-900 dark:text-white">5</span> trong số <span
                                class="font-bold text-slate-900 dark:text-white">45</span> kết quả
                        </p>
                        <div class="flex gap-1">
                            <button
                                class="w-9 h-9 flex items-center justify-center rounded-lg border border-slate-200 dark:border-slate-700 text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-all">
                                <span class="material-symbols-outlined">chevron_left</span>
                            </button>
                            <button
                                class="w-9 h-9 flex items-center justify-center rounded-lg bg-primary text-white font-bold text-sm">1</button>
                            <button
                                class="w-9 h-9 flex items-center justify-center rounded-lg border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-all font-bold text-sm">2</button>
                            <button
                                class="w-9 h-9 flex items-center justify-center rounded-lg border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-all font-bold text-sm">3</button>
                            <button
                                class="w-9 h-9 flex items-center justify-center rounded-lg border border-slate-200 dark:border-slate-700 text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-all">
                                <span class="material-symbols-outlined">chevron_right</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>

</html>

