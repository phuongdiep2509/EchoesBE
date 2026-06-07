@extends('layouts.app')

@section('title', 'Vé của tôi')

@section('content')
<main class="booking-page">
    <nav class="booking-breadcrumb">
        <a href="{{ url('/') }}">Trang chủ</a>
        <span>/</span>
        <strong>Vé của tôi</strong>
    </nav>

    <section class="booking-panel">
        <div class="booking-toolbar">
            <div>
                <span class="booking-kicker">Lịch sử đặt vé</span>
                <h1 style="margin:10px 0 0;">Vé của tôi</h1>
            </div>
            <form method="GET" action="{{ route('my-ticket') }}">
                <div>
                    <label>Mã khách hàng</label>
                    <input class="booking-input" type="number" min="1" name="MaKhachHang" value="{{ $customerId }}">
                </div>
                <button class="booking-button">Xem lịch sử</button>
                <a class="booking-button secondary" href="{{ route('cart', ['MaKhachHang' => $customerId]) }}">Giỏ hàng</a>
            </form>
        </div>

        @if(session('success'))
            <div class="booking-alert">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="booking-alert error">{{ session('error') }}</div>
        @endif

        <table class="booking-table">
            <thead>
                <tr>
                    <th>Mã đơn</th>
                    <th>Ngày đặt</th>
                    <th>Số vé</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
            @forelse($orders as $order)
                <tr>
                    <td>#{{ $order->MaDonHang }}</td>
                    <td>{{ $order->NgayDat }}</td>
                    <td>{{ $order->SoLuongVe }}</td>
                    <td>{{ number_format($order->TongTien, 0, ',', '.') }}đ</td>
                    <td><span class="booking-status">{{ $order->TrangThai }}</span></td>
                    <td>
                        @if($order->TrangThai === 'ChoThanhToan')
                            <form method="POST" action="{{ route('orders.cancel', $order->MaDonHang) }}">
                                @csrf
                                <input type="hidden" name="MaKhachHang" value="{{ $customerId }}">
                                <button class="booking-button secondary" onclick="return confirm('Hủy đơn này?')">Hủy đơn</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="6">Chưa có đơn đặt vé.</td></tr>
            @endforelse
            </tbody>
        </table>
    </section>
</main>
@endsection
