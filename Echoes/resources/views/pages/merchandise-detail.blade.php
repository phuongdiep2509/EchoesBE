@extends('layouts.app')

@section('title', ($product->TenMerch ?? 'Chi tiết sản phẩm') . ' | Echoes')

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/merchandiseDetail.css') }}">
@endsection

@section('content')
<main class="booking-page">
    <nav class="booking-breadcrumb">
        <a href="{{ url('/') }}">Trang chủ</a>
        <span>/</span>
        <a href="{{ url('/merchandise') }}">Merchandise</a>
        <span>/</span>
        <strong>{{ $product->TenMerch }}</strong>
    </nav>

    <section class="merch-detail">
        <div class="merch-detail-media">
            <img src="{{ asset($product->AnhSanPham) }}" alt="{{ $product->TenMerch }}">
        </div>
        <div class="merch-detail-info">
            <span class="booking-kicker">Merchandise</span>
            <h1>{{ $product->TenMerch }}</h1>
            <div class="merch-price">{{ number_format($product->GiaBan, 0, ',', '.') }}đ</div>
            <div class="merch-stock">Tồn kho: {{ $product->SoLuongTon }}</div>
            <p>{!! $product->MoTa !!}</p>
            <div class="merch-actions">
                <button class="booking-button" type="button">Thêm vào giỏ</button>
            </div>
        </div>
    </section>
</main>
@endsection