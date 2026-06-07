@extends('layouts.app')

@section('title', 'Giỏ hàng')

@section('content')
<main class="booking-page">
    <nav class="booking-breadcrumb">
        <a href="{{ url('/') }}">Trang chủ</a>
        <span>/</span>
        <strong>Giỏ hàng</strong>
    </nav>

    <section class="booking-panel">
        <div class="booking-toolbar">
            <div>
                <span class="booking-kicker">Giữ chỗ vé</span>
                <h1 style="margin:10px 0 0;">Giỏ hàng</h1>
            </div>
            <form method="GET" action="{{ route('cart') }}">
                <div>
                    <label>Mã khách hàng</label>
                    <input class="booking-input" type="number" min="1" name="MaKhachHang" value="{{ $customerId }}">
                </div>
                <button class="booking-button">Xem giỏ</button>
                <a class="booking-button secondary" href="{{ route('my-ticket', ['MaKhachHang' => $customerId]) }}">Lịch sử đặt vé</a>
            </form>
        </div>

        @if(session('success'))
            <div class="booking-alert">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="booking-alert error">{{ session('error') }}</div>
        @endif

        @if(!$cart || $cart['ChiTiet']->isEmpty())
            <div class="booking-empty">Giỏ hàng đang trống hoặc đã hết hạn. Hãy chọn vé từ trang chi tiết sự kiện.</div>
        @else
            <p>Giữ chỗ đến: <strong>{{ $cart['ThoiGianHetHan'] }}</strong></p>

            <table class="booking-table">
                <thead>
                    <tr>
                        <th>Sự kiện</th>
                        <th>Hạng vé</th>
                        <th>Số lượng</th>
                        <th>Đơn giá</th>
                        <th>Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cart['ChiTiet'] as $item)
                        <tr>
                            <td>{{ $item->TenSuKien }}</td>
                            <td>{{ $item->TenHangVe }}</td>
                            <td>{{ $item->SoLuong }}</td>
                            <td>{{ number_format($item->GiaVe, 0, ',', '.') }}đ</td>
                            <td>{{ number_format($item->ThanhTien, 0, ',', '.') }}đ</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="booking-total">
                <h2 style="margin:0;">Tổng tiền: {{ number_format($cart['TongTien'], 0, ',', '.') }}đ</h2>
                <form method="POST" action="{{ route('orders.create') }}">
                    @csrf
                    <input type="hidden" name="MaKhachHang" value="{{ $customerId }}">
                    <button class="booking-button">Tạo đơn đặt vé</button>
                </form>
            </div>
        @endif
    </section>
</main>
@endsection
