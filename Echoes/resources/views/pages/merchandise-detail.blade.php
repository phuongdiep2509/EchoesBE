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
            <img src="{{ asset('assets/images/merch/' . $product->AnhSanPham) }}" alt="{{ $product->TenMerch }}">
        </div>
        <div class="merch-detail-info">
            <span class="booking-kicker">Merchandise</span>
            <h1>{{ $product->TenMerch }}</h1>
            <div class="merch-price">{{ number_format($product->GiaBan, 0, ',', '.') }}đ</div>
            <div class="merch-stock">Tồn kho: {{ $product->SoLuongTon }}</div>
            <p>{!! $product->MoTa !!}</p>
            <div class="merch-actions">
                @if($product->SoLuongTon > 0)
                    <form method="POST" action="{{ route('cart.merchandise.add', $product->MaMerch) }}">
                        @csrf
                        <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px">
                            <label for="SoLuong" style="font-weight:700">Số lượng:</label>
                            <input
                                type="number"
                                id="SoLuong"
                                name="SoLuong"
                                value="1"
                                min="1"
                                max="{{ $product->SoLuongTon }}"
                                style="width:70px;padding:8px;border:1px solid #ccc;border-radius:8px;font-size:15px"
                            >
                        </div>
                        <button class="booking-button" type="submit">Thêm vào giỏ</button>
                    </form>
                @else
                    <button class="booking-button" type="button" disabled style="opacity:.5;cursor:not-allowed">Hết hàng</button>
                @endif
            </div>
        </div>
    </section>
</main>
@endsection