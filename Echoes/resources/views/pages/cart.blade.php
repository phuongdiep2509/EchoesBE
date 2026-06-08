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
        </div>

    @if(session('success'))
        <div style="background:rgba(70,70,42,.1);border:1px solid var(--color-green,#46462a);
                    color:var(--color-green,#46462a);border-radius:8px;
                    padding:14px 20px;margin-bottom:24px;font-weight:600">
            ✓ {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div style="background:rgba(116,7,13,.08);border:1px solid var(--color-red,#74070d);
                    color:var(--color-red,#74070d);border-radius:8px;
                    padding:14px 20px;margin-bottom:24px;font-weight:600">
            ✗ {{ session('error') }}
        </div>
    @endif

    <div style="display:grid;grid-template-columns:1fr 340px;gap:32px;align-items:start">

        {{-- ─── LEFT: Cart items ─── --}}
        <div>
            <h1 style="font-size:1.75rem;font-weight:900;color:var(--color-green,#46462a);
                       margin-bottom:8px">Giỏ hàng của bạn</h1>
            <p style="font-size:0.875rem;color:#888;margin-bottom:28px">
                Vé được giữ trong <strong style="color:var(--color-red,#74070d)">10 phút</strong>.
                Vui lòng thanh toán trước khi hết thời gian.
            </p>

        {{-- Tickets --}}
        @if(!$cart || $cart['ChiTiet']->isEmpty())
            <div class="booking-empty">Giỏ hàng đang trống hoặc đã hết hạn. Hãy chọn vé từ trang chi tiết sự kiện.</div>
        @else
            <p>Giữ chỗ đến: <strong>{{ \Carbon\Carbon::parse($cart['ThoiGianHetHan'])->format('H:i:s d/m/Y') }}</strong></p>

            <table class="booking-table">
                <thead>
                    <tr>
                        <th>Sự kiện</th>
                        <th>Hạng vé</th>
                        <th>Số lượng</th>
                        <th>Đơn giá</th>
                        <th>Thành tiền</th>
                        <th></th>
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
                            <td>
                                <form method="POST" action="{{ route('cart.ticket.remove', $item->MaHangVe) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background:none;border:none;color:#b91c1c;cursor:pointer;font-size:13px;font-weight:700">Xóa</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        {{-- Merchandise --}}
        @if(isset($merchandiseCart) && $merchandiseCart['ChiTiet']->isNotEmpty())
            <h2 style="font-size:1.25rem;font-weight:900;color:var(--color-green,#46462a);margin:32px 0 12px">
                Merchandise
            </h2>
            <table class="booking-table">
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Số lượng</th>
                        <th>Đơn giá</th>
                        <th>Thành tiền</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($merchandiseCart['ChiTiet'] as $item)
                        <tr>
                            <td>{{ $item->TenMerch }}</td>
                            <td>{{ $item->SoLuong }}</td>
                            <td>{{ number_format($item->GiaBan, 0, ',', '.') }}đ</td>
                            <td>{{ number_format($item->ThanhTien, 0, ',', '.') }}đ</td>
                            <td>
                                <form method="POST" action="{{ route('cart.merchandise.remove', $item->MaMerch) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background:none;border:none;color:#b91c1c;cursor:pointer;font-size:13px;font-weight:700">Xóa</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        @php
            $hasTickets = $cart && $cart['ChiTiet']->isNotEmpty();
            $hasMerch = isset($merchandiseCart) && $merchandiseCart['ChiTiet']->isNotEmpty();
            $grandTotal = ($hasTickets ? $cart['TongTien'] : 0) + ($hasMerch ? $merchandiseCart['TongTien'] : 0);
        @endphp

        @if($hasTickets || $hasMerch)
            <div class="booking-total">
                <h2 style="margin:0;">Tổng tiền: {{ number_format($grandTotal, 0, ',', '.') }}đ</h2>
                <form method="POST" action="{{ route('orders.create') }}">
                    @csrf
                    <button class="booking-button">Tạo đơn đặt vé</button>
                </form>
            </div>
        @endif
        </div>

    </div>
</div>

@endsection

@section('scripts')
@if($cart && !$cart['ChiTiet']->isEmpty())
<script>
let seconds = {{ $secondsLeft ?? 0 }};
const el = document.getElementById('countdown');
if (el && seconds > 0) {
    const t = setInterval(() => {
        seconds--;
        if (seconds <= 0) {
            clearInterval(t);
            el.textContent = '00:00';
            el.closest('div').style.background = 'rgba(116,7,13,.15)';
            return;
        }
        const m = String(Math.floor(seconds / 60)).padStart(2, '0');
        const s = String(seconds % 60).padStart(2, '0');
        el.textContent = `${m}:${s}`;
    }, 1000);
}
</script>
@endif
@endsection
