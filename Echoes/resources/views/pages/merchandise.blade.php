@extends('layouts.app')

@section('title', 'Merchandise | Echoes')

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/merchandise.css') }}">
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

</div>

@endsection
