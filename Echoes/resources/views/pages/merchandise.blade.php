@extends('layouts.app')

@section('title', 'Merchandise | Echoes')

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/merchandise.css') }}">
    <style>
        .pagination-wrap { display:flex; justify-content:center; align-items:center; gap:6px; margin:40px 0 20px; flex-wrap:wrap; }
        .page-btn { display:inline-flex; align-items:center; justify-content:center; min-width:38px; height:38px; padding:0 10px; border-radius:10px; background:#fff; border:1px solid #ddd; color:#111827; font-size:14px; font-weight:600; text-decoration:none; cursor:pointer; transition:all .2s; }
        .page-btn:hover { background:#74070d; color:#fff; border-color:#74070d; }
        .page-btn.active { background:#74070d; color:#fff; border-color:#74070d; }
        .page-btn.disabled { opacity:.4; cursor:not-allowed; pointer-events:none; }
    </style>
@endsection

@section('content')

<!-- HERO -->
<section class="page-hero">
    <div class="section-content">

        <div class="breadcrumb-pill">
            <a href="{{ url('/') }}">TRANG CHỦ</a> / MERCHANDISE
        </div>

        <h1 class="page-title">TẤT CẢ SẢN PHẨM</h1>

        <p class="page-desc">
            Những sản phẩm Merch từ những sự kiện âm nhạc bạn yêu thích.
        </p>

        <div class="soft-line"></div>

    </div>
</section>

<!-- PRODUCTS -->
<div class="container" style="padding-top:40px; padding-bottom:60px">

    <div class="music-grid">

        @forelse($products as $p)
            <x-product-card
                :name="$p->TenMerch"
                :price="$p->GiaBan"
                :image="'assets/images/merch/' . ($p->AnhSanPham ?? 'default.png')"
                :stock="$p->SoLuongTon ?? 0"
                :link="url('/merchandise/' . $p->MaMerch)"
            />
        @empty
            <div style="text-align:center; padding:60px 0; color:#999; width:100%">
                <p>Hiện chưa có sản phẩm nào đang bán.</p>
            </div>
        @endforelse

    </div>

    {{-- Pagination --}}
    @if($products->hasPages())
        <div class="pagination-wrap">
            @if($products->onFirstPage())
                <span class="page-btn disabled">&#8249;</span>
            @else
                <a class="page-btn" href="{{ $products->previousPageUrl() }}">&#8249;</a>
            @endif

            @foreach($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                @if($page == $products->currentPage())
                    <span class="page-btn active">{{ $page }}</span>
                @else
                    <a class="page-btn" href="{{ $url }}">{{ $page }}</a>
                @endif
            @endforeach

            @if($products->hasMorePages())
                <a class="page-btn" href="{{ $products->nextPageUrl() }}">&#8250;</a>
            @else
                <span class="page-btn disabled">&#8250;</span>
            @endif
        </div>
    @endif

</div>

@endsection
