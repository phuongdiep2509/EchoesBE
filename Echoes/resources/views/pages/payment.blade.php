@extends('layouts.app')

@section('title', 'Echoes - Thanh toán đơn hàng')

@section('styles')
<style>
    .echoes-checkout {
        background: #f6f4ef;
        min-height: 100vh;
        padding: 56px 0 72px;
        color: #1f1f1f;
    }
    .echoes-checkout * { box-sizing: border-box; }
    .checkout-container { width: min(1180px, calc(100% - 32px)); margin: 0 auto; }
    .checkout-heading { margin-bottom: 28px; display: flex; align-items: flex-end; justify-content: space-between; gap: 24px; }
    .checkout-eyebrow { color: #b91c1c; font-size: 13px; letter-spacing: 0.16em; text-transform: uppercase; font-weight: 800; margin-bottom: 8px; }
    .checkout-title { margin: 0; font-size: clamp(32px, 4vw, 54px); line-height: 1.05; font-weight: 900; letter-spacing: -0.04em; }
    .checkout-subtitle { margin: 12px 0 0; color: #68625d; max-width: 640px; line-height: 1.7; font-size: 15px; }
    .checkout-order-badge { flex-shrink: 0; border: 1px solid #e7ddd1; background: #fffaf4; border-radius: 999px; padding: 12px 18px; font-weight: 800; color: #7f1d1d; box-shadow: 0 8px 24px rgba(61,38,18,.06); }
    .checkout-alert { border-radius: 18px; padding: 16px 18px; margin: 0 0 18px; line-height: 1.6; font-weight: 700; }
    .checkout-alert.success { background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; }
    .checkout-alert.error   { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
    .checkout-alert.warning { background: #fffbeb; color: #92400e; border: 1px solid #fde68a; }
    .checkout-grid { display: grid; grid-template-columns: minmax(0,1.45fr) minmax(360px,.8fr); gap: 26px; align-items: start; }
    .checkout-card { background: #fff; border: 1px solid #eadfd3; border-radius: 28px; box-shadow: 0 18px 46px rgba(58,40,20,.08); overflow: hidden; }
    .checkout-section { padding: 26px; }
    .checkout-section + .checkout-section { border-top: 1px solid #f0e7dc; }
    .section-title { display: flex; align-items: center; justify-content: space-between; gap: 16px; margin-bottom: 18px; }
    .section-title h2, .section-title h3 { margin: 0; font-size: 20px; font-weight: 900; letter-spacing: -0.02em; }
    .section-kicker { color: #b91c1c; font-size: 12px; font-weight: 900; letter-spacing: .12em; text-transform: uppercase; }
    .event-card { display: grid; grid-template-columns: 220px 1fr; gap: 22px; align-items: stretch; }
    .event-poster { width: 100%; min-height: 220px; border-radius: 22px; object-fit: cover; background: #efe7dd; }
    .event-name { margin: 8px 0 16px; font-size: 30px; line-height: 1.16; font-weight: 950; letter-spacing: -0.035em; }
    .info-list { display: grid; gap: 12px; }
    .info-row { display: grid; grid-template-columns: 130px 1fr; gap: 16px; padding: 13px 0; border-bottom: 1px dashed #eadfd3; }
    .info-label { color: #8a8179; font-size: 13px; font-weight: 800; text-transform: uppercase; letter-spacing: .05em; }
    .info-value { font-weight: 800; color: #26211d; line-height: 1.45; }
    .ticket-list { display: grid; gap: 14px; }
    .ticket-item { display: grid; grid-template-columns: 1fr auto; gap: 18px; padding: 18px; border: 1px solid #eee3d8; border-radius: 22px; background: linear-gradient(180deg,#fff,#fffaf5); }
    .ticket-main { display: grid; gap: 9px; }
    .ticket-name { font-size: 18px; font-weight: 950; color: #111827; margin: 0; }
    .ticket-meta { display: flex; flex-wrap: wrap; gap: 10px; color: #6b625a; font-size: 14px; line-height: 1.5; }
    .ticket-pill { display: inline-flex; align-items: center; gap: 6px; padding: 8px 10px; border-radius: 999px; background: #f7efe7; color: #5d4037; font-weight: 800; }
    .ticket-price { min-width: 150px; text-align: right; font-weight: 950; color: #b91c1c; font-size: 18px; }
    .payment-panel { position: sticky; top: 92px; }
    .summary-line { display: flex; justify-content: space-between; align-items: flex-start; gap: 18px; padding: 14px 0; border-bottom: 1px solid #f0e7dc; line-height: 1.5; }
    .summary-label { color: #766d65; font-weight: 800; }
    .summary-value { color: #161616; text-align: right; font-weight: 900; }
    .summary-total { margin-top: 18px; padding: 22px; border-radius: 22px; background: #111827; color: white; display: flex; justify-content: space-between; align-items: center; gap: 16px; }
    .summary-total span:first-child { color: #d1d5db; font-weight: 800; }
    .summary-total span:last-child  { font-size: 24px; font-weight: 950; color: #fef3c7; }
    .method-box { margin-top: 18px; padding: 18px; border-radius: 22px; border: 2px solid #b91c1c; background: #fff7ed; }
    .method-name { display: flex; align-items: center; justify-content: space-between; gap: 16px; font-weight: 950; color: #7f1d1d; margin-bottom: 10px; }
    .method-desc { color: #7c6255; line-height: 1.65; font-size: 14px; margin: 0; }
    .qr-box { margin-top: 18px; padding: 20px; border-radius: 24px; background: #f9fafb; border: 1px dashed #d1d5db; text-align: center; }
    .qr-image { width: 236px; max-width: 100%; aspect-ratio: 1/1; object-fit: contain; border-radius: 20px; background: #fff; padding: 12px; border: 1px solid #e5e7eb; box-shadow: 0 18px 34px rgba(17,24,39,.08); }
    .qr-note { margin: 16px auto 0; max-width: 320px; line-height: 1.65; color: #4b5563; font-size: 14px; }
    .timer-box { margin-top: 18px; display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
    .timer-card { border-radius: 18px; background: #fff; border: 1px solid #eee3d8; padding: 15px; }
    .timer-label { color: #8a8179; font-size: 12px; font-weight: 900; text-transform: uppercase; letter-spacing: .08em; margin-bottom: 6px; }
    .timer-value { font-size: 24px; font-weight: 950; color: #b91c1c; font-variant-numeric: tabular-nums; }
    .checkout-btn { width: 100%; border: 0; border-radius: 18px; padding: 16px 18px; font-weight: 950; cursor: pointer; transition: .18s ease; font-size: 15px; margin-top: 16px; font-family: var(--font, "Cal Sans", sans-serif); }
    .checkout-btn.primary { background: #b91c1c; color: #fff; box-shadow: 0 14px 28px rgba(185,28,28,.24); }
    .checkout-btn.primary:hover { transform: translateY(-1px); background: #991b1b; }
    .checkout-btn.dark { background: #111827; color: #fff; }
    .checkout-btn.dark:hover { transform: translateY(-1px); background: #020617; }
    .checkout-btn:disabled { cursor: not-allowed; opacity: .55; transform: none !important; }
    .status-badge { display: inline-flex; align-items: center; justify-content: center; border-radius: 999px; padding: 8px 12px; font-size: 12px; font-weight: 950; letter-spacing: .03em; }
    .status-success   { background: #dcfce7; color: #166534; }
    .status-waiting   { background: #fef3c7; color: #92400e; }
    .status-failed    { background: #fee2e2; color: #991b1b; }
    .status-cancelled { background: #e5e7eb; color: #374151; }
    .small-muted { color: #766d65; line-height: 1.7; font-size: 13px; }
    .empty-box { padding: 18px; border-radius: 18px; background: #f9fafb; color: #6b7280; line-height: 1.7; border: 1px dashed #d1d5db; }
    @media (max-width: 980px) {
        .checkout-heading { align-items: flex-start; flex-direction: column; }
        .checkout-grid { grid-template-columns: 1fr; }
        .payment-panel { position: static; }
        .event-card { grid-template-columns: 1fr; }
    }
    @media (max-width: 640px) {
        .echoes-checkout { padding-top: 32px; }
        .checkout-section { padding: 20px; }
        .ticket-item { grid-template-columns: 1fr; }
        .ticket-price { text-align: left; }
        .timer-box { grid-template-columns: 1fr; }
        .info-row { grid-template-columns: 1fr; gap: 4px; }
    }
</style>
@endsection

@section('content')
@php
    $money = fn($amount) => number_format((float) $amount, 0, ',', '.') . 'đ';
    $eventImage = $event && !empty($event->AnhBia)
        ? asset($event->AnhBia)
        : asset('assets/images/index/favicon.png');
    $eventName = $event->TenSuKien ?? 'Sự kiện Echoes';
    $paymentStatus = $payment->TrangThai ?? null;
    $paymentStatusLabel = match($paymentStatus) {
        'ThanhCong'    => 'Thành công',
        'ChoThanhToan' => 'Chờ thanh toán',
        'ThatBai'      => 'Thất bại',
        default        => 'Chưa tạo giao dịch',
    };
    $paymentStatusClass = match($paymentStatus) {
        'ThanhCong'    => 'status-success',
        'ChoThanhToan' => 'status-waiting',
        'ThatBai'      => 'status-failed',
        default        => 'status-cancelled',
    };
    $orderStatusLabel = match($order->TrangThai) {
        'DaThanhToan' => 'Đã thanh toán',
        'ChoThanhToan' => 'Chờ thanh toán',
        'DaHuy'       => 'Đã hủy',
        default       => $order->TrangThai,
    };
    $orderStatusClass = match($order->TrangThai) {
        'DaThanhToan' => 'status-success',
        'ChoThanhToan' => 'status-waiting',
        'DaHuy'       => 'status-cancelled',
        default       => 'status-cancelled',
    };
    $transferNote = ($paymentConfig['note_prefix'] ?? 'ECHOES') . '-ORDER-' . $order->MaDonHang;
@endphp

<section class="echoes-checkout">
    <div class="checkout-container">

        <div class="checkout-heading">
            <div>
                <div class="checkout-eyebrow">Echoes Checkout</div>
                <h1 class="checkout-title">Thanh toán đơn hàng</h1>
                <p class="checkout-subtitle">
                    Hoàn tất thanh toán toàn bộ đơn hàng để Echoes xác nhận vé và gửi email đặt vé thành công cho bạn.
                </p>
            </div>
            <div class="checkout-order-badge">Đơn hàng #{{ $order->MaDonHang }}</div>
        </div>

        @if(session('success'))
            <div class="checkout-alert success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="checkout-alert error">{{ session('error') }}</div>
        @endif
        @if(session('warning'))
            <div class="checkout-alert warning">{{ session('warning') }}</div>
        @endif

        <div class="checkout-grid">

            {{-- ─── LEFT ─── --}}
            <div>
                <div class="checkout-card">

                    {{-- Event info --}}
                    <div class="checkout-section">
                        <div class="section-title">
                            <div>
                                <div class="section-kicker">Sự kiện</div>
                                <h2>Thông tin vé đã đặt</h2>
                            </div>
                            <span class="status-badge {{ $orderStatusClass }}">{{ $orderStatusLabel }}</span>
                        </div>

                        <div class="event-card">
                            <img class="event-poster" src="{{ $eventImage }}" alt="{{ $eventName }}">
                            <div>
                                <h2 class="event-name">{{ $eventName }}</h2>
                                <div class="info-list">
                                    <div class="info-row">
                                        <div class="info-label">Thời gian</div>
                                        <div class="info-value">
                                            {{ $event && $event->ThoiGianBatDau
                                                ? \Carbon\Carbon::parse($event->ThoiGianBatDau)->format('H:i d/m/Y')
                                                : 'Đang cập nhật' }}
                                        </div>
                                    </div>
                                    <div class="info-row">
                                        <div class="info-label">Khách hàng</div>
                                        <div class="info-value">
                                            {{ $customerAccount->HoTen ?? $customerAccount->TenDangNhap ?? 'Khách hàng Echoes' }}
                                        </div>
                                    </div>
                                    <div class="info-row">
                                        <div class="info-label">Email</div>
                                        <div class="info-value">
                                            {{ $customerAccount->Email ?? 'Chưa có email' }}
                                        </div>
                                    </div>
                                    <div class="info-row">
                                        <div class="info-label">Giữ vé</div>
                                        <div class="info-value">
                                            @if($order->TrangThai === 'DaThanhToan')
                                                Đơn hàng đã thanh toán thành công.
                                            @elseif($order->TrangThai === 'DaHuy')
                                                Đơn hàng đã hết hạn hoặc đã bị hủy.
                                            @else
                                                Còn <span id="orderTimerText">--:--</span> trong thời gian giữ vé 10 phút.
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Tickets --}}
                    <div class="checkout-section">
                        <div class="section-title">
                            <div>
                                <div class="section-kicker">Danh sách vé</div>
                                <h3>Vé trong đơn hàng</h3>
                            </div>
                            <strong>{{ $ticketItems->count() }} vé</strong>
                        </div>

                        @if($ticketItems->isEmpty())
                            <div class="empty-box">
                                Chưa có dữ liệu vé trong đơn hàng này.
                                Khi phần đặt vé tạo vé vào bảng <strong>ve</strong>, danh sách vé sẽ hiển thị tại đây.
                            </div>
                        @else
                            <div class="ticket-list">
                                @foreach($ticketItems as $item)
                                    <div class="ticket-item">
                                        <div class="ticket-main">
                                            <h4 class="ticket-name">{{ $item->TenHangVe ?? 'Hạng vé' }}</h4>
                                            <div class="ticket-meta">
                                                <span class="ticket-pill">Khu vực: {{ $item->TenKhuVuc ?? 'Không phân khu' }}</span>
                                                <span class="ticket-pill">
                                                    Ghế:
                                                    @if($item->HangGhe || $item->SoGhe)
                                                        {{ trim(($item->HangGhe ?? '') . '-' . ($item->SoGhe ?? ''), '-') }}
                                                    @else
                                                        Tự do
                                                    @endif
                                                </span>
                                                <span class="ticket-pill">Mã vé: {{ $item->MaVeDienTu ?? '---' }}</span>
                                                <span class="ticket-pill">Trạng thái: {{ $item->TrangThaiVe ?? '---' }}</span>
                                            </div>
                                        </div>
                                        <div class="ticket-price">{{ $money($item->GiaVe ?? 0) }}</div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- Merchandise --}}
                    @if(isset($merchandiseItems) && $merchandiseItems->isNotEmpty())
                    <div class="checkout-section">
                        <div class="section-title">
                            <div>
                                <div class="section-kicker">Merchandise</div>
                                <h3>Merchandise trong đơn hàng</h3>
                            </div>
                            <strong>{{ $merchandiseItems->count() }} sản phẩm</strong>
                        </div>
                        <div class="ticket-list">
                            @foreach($merchandiseItems as $item)
                                <div class="ticket-item">
                                    <div class="ticket-main">
                                        <h4 class="ticket-name">{{ $item->TenMerch }}</h4>
                                        <div class="ticket-meta">
                                            <span class="ticket-pill">Số lượng: {{ $item->SoLuong }}</span>
                                            <span class="ticket-pill">Đơn giá: {{ $money($item->DonGia) }}</span>
                                        </div>
                                    </div>
                                    <div class="ticket-price">{{ $money($item->ThanhTien) }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                </div>
            </div>

            {{-- ─── RIGHT: Payment panel ─── --}}
            <aside class="checkout-card payment-panel">
                <div class="checkout-section">
                    <div class="section-title">
                        <div>
                            <div class="section-kicker">{{ $paymentConfig['brand_name'] ?? 'Echoes' }}</div>
                            <h3>Thanh toán</h3>
                        </div>
                        <span class="status-badge {{ $paymentStatusClass }}">{{ $paymentStatusLabel }}</span>
                    </div>

                    <div class="summary-line">
                        <span class="summary-label">Mã đơn hàng</span>
                        <span class="summary-value">#{{ $order->MaDonHang }}</span>
                    </div>
                    <div class="summary-line">
                        <span class="summary-label">Mã giao dịch</span>
                        <span class="summary-value">{{ $payment->MaGiaoDich ?? 'Chưa tạo' }}</span>
                    </div>
                    <div class="summary-line">
                        <span class="summary-label">Phương thức</span>
                        <span class="summary-value">{{ $paymentConfig['method_label'] ?? 'Chuyển khoản QR' }}</span>
                    </div>
                    <div class="summary-line">
                        <span class="summary-label">Hình thức</span>
                        <span class="summary-value">Thanh toán full 100%</span>
                    </div>
                    <div class="summary-total">
                        <span>Tổng tiền</span>
                        <span>{{ $money($order->TongTien) }}</span>
                    </div>

                    <div class="method-box">
                        <div class="method-name">
                            <span>QR chuyển khoản Echoes</span>
                        </div>
                        <p class="method-desc">
                            Tạo mã QR, chuyển khoản đúng số tiền và nội dung. Sau đó bấm
                            <strong>Tôi đã thanh toán</strong> trong thời gian hiệu lực.
                        </p>
                    </div>

                    @if($order->TrangThai === 'DaThanhToan')
                        <div class="checkout-alert success" style="margin-top:18px">
                            Đơn hàng đã thanh toán thành công. Email xác nhận đã được gửi.
                        </div>

                    @elseif($isOrderExpired)
                        <div class="checkout-alert error" style="margin-top:18px">
                            Đơn hàng đã hết thời gian giữ vé hoặc đã bị hủy. Vui lòng đặt vé lại.
                        </div>

                    @elseif($hasActiveQr)
                        <div class="qr-box">
                            <img class="qr-image" src="{{ asset($paymentConfig['qr_image']) }}" alt="QR thanh toán Echoes">
                            <p class="qr-note">
                                <strong>{{ $paymentConfig['bank_name'] }}</strong><br>
                                Chủ tài khoản: <strong>{{ $paymentConfig['account_name'] }}</strong><br>
                                Số tài khoản: <strong>{{ $paymentConfig['account_number'] }}</strong><br>
                                Nội dung: <strong>{{ $payment->MaGiaoDich ?: $transferNote }}</strong>
                            </p>
                        </div>

                        <div class="timer-box">
                            <div class="timer-card">
                                <div class="timer-label">QR còn hiệu lực</div>
                                <div class="timer-value" id="qrTimerText">--:--</div>
                            </div>
                            <div class="timer-card">
                                <div class="timer-label">Giữ vé còn lại</div>
                                <div class="timer-value" id="sideOrderTimerText">--:--</div>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('payment.qr.confirm', $order->MaDonHang) }}" id="confirmPaymentForm">
                            @csrf
                            <button type="submit" class="checkout-btn primary" id="confirmPaymentBtn">
                                Tôi đã thanh toán
                            </button>
                        </form>

                        <p class="small-muted" style="margin:14px 0 0">
                            Nếu quá 01 phút, mã QR sẽ hết hiệu lực và giao dịch chuyển sang trạng thái thất bại.
                            Bạn có thể tạo lại mã QR nếu đơn hàng vẫn còn thời gian giữ vé.
                        </p>

                    @else
                        <form method="POST" action="{{ route('payment.qr.create', $order->MaDonHang) }}">
                            @csrf
                            <input type="hidden" name="payment_method" value="ChuyenKhoanQR">
                            <button type="submit" class="checkout-btn dark">
                                Tạo mã QR thanh toán
                            </button>
                        </form>

                        <p class="small-muted" style="margin:14px 0 0">
                            Mã QR chỉ có hiệu lực 01 phút. Đơn hàng vẫn được giữ tối đa 10 phút kể từ thời điểm đặt vé.
                        </p>
                    @endif

                </div>
            </aside>

        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
(function () {
    const orderRemainingStart = Number(@json($orderRemainingSeconds));
    const qrRemainingStart    = Number(@json($qrRemainingSeconds));
    const hasActiveQr         = Boolean(@json($hasActiveQr));
    const expireUrl           = @json(route('payment.qr.expire', $order->MaDonHang));
    const csrfToken           = @json(csrf_token());

    const orderTimerText     = document.getElementById('orderTimerText');
    const sideOrderTimerText = document.getElementById('sideOrderTimerText');
    const qrTimerText        = document.getElementById('qrTimerText');
    const confirmButton      = document.getElementById('confirmPaymentBtn');
    const confirmForm        = document.getElementById('confirmPaymentForm');

    let orderRemaining = orderRemainingStart;
    let qrRemaining    = qrRemainingStart;
    let expireCalled   = false;

    function formatTime(seconds) {
        seconds = Math.max(0, Number(seconds) || 0);
        const m = Math.floor(seconds / 60).toString().padStart(2, '0');
        const s = Math.floor(seconds % 60).toString().padStart(2, '0');
        return `${m}:${s}`;
    }

    function setText(el, value) { if (el) el.textContent = value; }

    function disablePaymentButton(message) {
        if (!confirmButton) return;
        confirmButton.disabled = true;
        confirmButton.textContent = message || 'Mã QR đã hết hiệu lực';
    }

    function callExpireEndpoint() {
        if (expireCalled) return;
        expireCalled = true;
        fetch(expireUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
            body: JSON.stringify({})
        }).finally(() => setTimeout(() => window.location.reload(), 800));
    }

    function tick() {
        if (orderRemaining > 0) orderRemaining--;
        if (hasActiveQr && qrRemaining > 0) qrRemaining--;

        setText(orderTimerText,     formatTime(orderRemaining));
        setText(sideOrderTimerText, formatTime(orderRemaining));
        setText(qrTimerText,        formatTime(qrRemaining));

        if (hasActiveQr && qrRemaining <= 0) {
            disablePaymentButton('Mã QR đã hết hiệu lực');
            callExpireEndpoint();
        }
        if (orderRemaining <= 0 && confirmButton) {
            disablePaymentButton('Đơn hàng đã hết thời gian giữ vé');
        }
    }

    setText(orderTimerText,     formatTime(orderRemaining));
    setText(sideOrderTimerText, formatTime(orderRemaining));
    setText(qrTimerText,        formatTime(qrRemaining));

    if (confirmForm) {
        confirmForm.addEventListener('submit', function (e) {
            if (qrRemaining <= 0 || orderRemaining <= 0) {
                e.preventDefault();
                disablePaymentButton(qrRemaining <= 0 ? 'Mã QR đã hết hiệu lực' : 'Đơn hàng đã hết thời gian giữ vé');
            }
        });
    }

    setInterval(tick, 1000);
})();
</script>
@endsection
