@extends('layouts.app')

@section('title', 'Gio hang')

@section('content')
@php
    $ticketItems = ($cart && isset($cart['ChiTiet'])) ? $cart['ChiTiet'] : collect();
    $merchItems = ($merchandiseCart && isset($merchandiseCart['ChiTiet'])) ? $merchandiseCart['ChiTiet'] : collect();
    $hasTicketItems = $ticketItems->isNotEmpty();
    $hasMerchItems = $merchItems->isNotEmpty();
    $total = (float) ($cart['TongTien'] ?? 0) + (float) ($merchandiseCart['TongTien'] ?? 0);
@endphp

<main class="booking-page">
    <nav class="booking-breadcrumb">
        <a href="{{ url('/') }}">Trang chu</a>
        <span>/</span>
        <strong>Gio hang</strong>
    </nav>

    <section class="booking-panel">
        <div class="booking-toolbar">
            <div>
                <span class="booking-kicker">Echoes cart</span>
                <h1 style="margin:10px 0 0;">Gio hang</h1>
            </div>
        </div>

        @if(session('success'))
            <div class="booking-alert">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="booking-alert error">{{ session('error') }}</div>
        @endif

        @if(!$hasTicketItems && !$hasMerchItems)
            <div class="booking-empty">Gio hang dang trong. Hay chon ve su kien hoac merchandise de tiep tuc.</div>
        @else
            @if($hasTicketItems)
                <p>Giu cho ve den: <strong>{{ $cart['ThoiGianHetHan'] }}</strong></p>

                <table class="booking-table">
                    <thead>
                        <tr>
                            <th>Su kien</th>
                            <th>Hang ve</th>
                            <th>So luong</th>
                            <th>Don gia</th>
                            <th>Thanh tien</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ticketItems as $item)
                            <tr>
                                <td>{{ $item->TenSuKien }}</td>
                                <td>{{ $item->TenHangVe }}</td>
                                <td>{{ $item->SoLuong }}</td>
                                <td>{{ number_format($item->GiaVe, 0, ',', '.') }}d</td>
                                <td>{{ number_format($item->ThanhTien, 0, ',', '.') }}d</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            @if($hasMerchItems)
                <h2 style="margin:28px 0 14px;">Merchandise</h2>
                <table class="booking-table">
                    <thead>
                        <tr>
                            <th>San pham</th>
                            <th>So luong</th>
                            <th>Don gia</th>
                            <th>Thanh tien</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($merchItems as $item)
                            <tr>
                                <td>{{ $item->TenMerch }}</td>
                                <td>{{ $item->SoLuong }}</td>
                                <td>{{ number_format($item->GiaBan, 0, ',', '.') }}d</td>
                                <td>{{ number_format($item->ThanhTien, 0, ',', '.') }}d</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            <div class="booking-total">
                <h2 style="margin:0;">Tong tien: {{ number_format($total, 0, ',', '.') }}d</h2>
                <form method="POST" action="{{ route('orders.create') }}">
                    @csrf
                    <button class="booking-button">Tao don va thanh toan</button>
                </form>
            </div>
        @endif
    </section>
</main>
@endsection
