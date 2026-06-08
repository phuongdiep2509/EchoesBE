@extends('layouts.app')

@section('title', $product->TenMerch ?? 'Merchandise')

@section('content')
<main class="booking-page">
    <nav class="booking-breadcrumb">
        <a href="{{ url('/') }}">Trang chá»§</a>
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
            <div class="merch-price">{{ number_format($product->GiaBan, 0, ',', '.') }}Ä‘</div>
            <div class="merch-stock">Tá»“n kho: {{ $product->SoLuongTon }}</div>
            <p>{!! $product->MoTa !!}</p>
            <form class="merch-actions" method="POST" action="{{ route('cart.merchandise.add', $product->MaMerch) }}">
                @csrf
                <input
                    type="number"
                    name="SoLuong"
                    value="1"
                    min="1"
                    max="{{ max(1, (int) $product->SoLuongTon) }}"
                    style="width:110px;padding:12px 14px;border:1px solid #d8c8ad;border-radius:0;background:#fff;font-weight:800;"
                    required
                >
                <button class="booking-button" type="submit" @disabled((int) $product->SoLuongTon <= 0)>Them vao gio</button>
            </form>
        </div>
    </section>
</main>
@endsection