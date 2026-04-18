<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Xác thực - THLD')</title>

    {{-- Fonts & Icons --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Montserrat:wght@400;500;600;700;800&family=Outfit:wght@400;500;600;700;800;900&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')

    <link rel="icon" type="image/png" href="{{ asset('images/logos/favicon_thld.png') }}">
</head>

<body class="bg-background-grey text-gray-800 relative min-h-screen">
    <main>
        @yield('content')
    </main>

    @stack('scripts')
</body>

</html>