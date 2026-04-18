@extends('layouts.app')

@section('content')
    <div class="flex flex-col gap-4 md:gap-4">
        {{-- 1. HERO + TRUST STRIP --}}
        <div class="w-full relative">
            @include('home.banners')
        </div>

        {{-- 2. QUICK FEATURES (Navigation - Gọn gàng) --}}
        <div class="w-full relative">
            @include('home.features')
        </div>

        {{--3 Gift cards --}}
        @if(isset($vouchers) && $vouchers->count() > 0)
            <div class="w-full relative">
                @include('home.gift-cards')
            </div>
        @endif

        {{-- 6. SHOPPING TREND (Xu Hướng Mua Sắm - Tabs: Ngày / Tháng / Năm) --}}
        <div class="w-full relative">
            @include('home.shopping-trend')
        </div>

        {{-- 4. FLASH SALE (FOMO Mạnh) --}}
        @if(isset($activeFlashSale) && $activeFlashSale && isset($flashSaleBooks) && $flashSaleBooks->count() > 0)
            <div class="w-full relative">
                @include('home.flash-sale')
                @include('home.sticky-flash-bar')
            </div>
        @endif

        {{-- 5. BEST SELLER / WEEKLY RANKING (Social Proof) --}}
        @if(isset($weeklyRankings) && $weeklyRankings->count() > 0)
            <div class="w-full relative">
                @include('home.weekly-ranking')
            </div>
        @endif

        {{-- 6. COMBO TRENDING (Combo Trợ Giá) --}}
        @if(isset($combos) && $combos->count() > 0)
            <div class="w-full relative">
                @include('home.combo-trending')
            </div>
        @endif

        {{-- 6 . FEATURED COLLECTIONS (Dẫn luồng) --}}
        @if(isset($collections) && $collections->count() > 0)
            <div class="w-full relative">
                @include('home.featured-collections')
            </div>
        @endif

        {{-- 7. SUGGESTIONS & PARTNERS --}}
        <div class="w-full relative">
            @include('home.suggestions-brands')
        </div>

        {{-- 8. BOOK SERIES --}}
        @if(isset($bookSeries) && $bookSeries->count() > 0)
            <div class="w-full relative">
                @include('home.book-series')
            </div>
        @endif

        {{-- 9. CORE SECTION: ALL PRODUCTS / LATEST --}}
        <div class="w-full relative">
            @include('home.all-products')
        </div>

        {{-- Miscellaneous sections --}}
        @if((isset($featuredBooks) && $featuredBooks->count() > 0) || (isset($youngAuthorsBooks) && $youngAuthorsBooks->count() > 0))
            <div class="w-full relative">
                @include('home.young-authors')
            </div>
        @endif

        {{-- Quote section --}}
        <div class="w-full relative">
            @include('home.quote')
        </div>

        {{-- BEHAVIOR LOOP: RECENTLY VIEWED --}}
        @include('home.recently-viewed')
    </div>
@endsection