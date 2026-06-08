@extends('layouts.app')

@section('title', 'Echoes - Thanh toÃ¡n Ä‘Æ¡n hÃ ng')

@section('styles')
<style>
    .echoes-checkout {
        background: #f6f4ef;
        min-height: 100vh;
        padding: 56px 0 72px;
        color: #1f1f1f;
    }

    .echoes-checkout * {
        box-sizing: border-box;
    }

    .checkout-container {
        width: min(1180px, calc(100% - 32px));
        margin: 0 auto;
    }

    .checkout-heading {
        margin-bottom: 28px;
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 24px;
    }

    .checkout-eyebrow {
        color: #b91c1c;
        font-size: 13px;
        letter-spacing: 0.16em;
        text-transform: uppercase;
        font-weight: 800;
        margin-bottom: 8px;
    }

    .checkout-title {
        margin: 0;
        font-size: clamp(32px, 4vw, 54px);
        line-height: 1.05;
        font-weight: 900;
        letter-spacing: -0.04em;
    }

    .checkout-subtitle {
        margin: 12px 0 0;
        color: #68625d;
        max-width: 640px;
        line-height: 1.7;
        font-size: 15px;
    }

    .checkout-order-badge {
        flex-shrink: 0;
        border: 1px solid #e7ddd1;
        background: #fffaf4;
        border-radius: 999px;
        padding: 12px 18px;
        font-weight: 800;
        color: #7f1d1d;
        box-shadow: 0 8px 24px rgba(61, 38, 18, 0.06);
    }

    .checkout-alert {
        border-radius: 18px;
        padding: 16px 18px;
        margin: 0 0 18px;
        line-height: 1.6;
        font-weight: 700;
    }

    .checkout-alert.success {
        background: #ecfdf5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }

    .checkout-alert.error {
        background: #fef2f2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }

    .checkout-alert.warning {
        background: #fffbeb;
        color: #92400e;
        border: 1px solid #fde68a;
    }

    .checkout-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.45fr) minmax(360px, 0.8fr);
        gap: 26px;
        align-items: start;
    }

    .checkout-card {
        background: #ffffff;
        border: 1px solid #eadfd3;
        border-radius: 28px;
        box-shadow: 0 18px 46px rgba(58, 40, 20, 0.08);
        overflow: hidden;
    }

    .checkout-section {
        padding: 26px;
    }

    .checkout-section + .checkout-section {
        border-top: 1px solid #f0e7dc;
    }

    .section-title {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 18px;
    }

    .section-title h2,
    .section-title h3 {
        margin: 0;
        font-size: 20px;
        font-weight: 900;
        letter-spacing: -0.02em;
    }

    .section-kicker {
        color: #b91c1c;
        font-size: 12px;
        font-weight: 900;
        letter-spacing: .12em;
        text-transform: uppercase;
    }

    .event-card {
        display: grid;
        grid-template-columns: 220px 1fr;
        gap: 22px;
        align-items: stretch;
    }

    .event-poster {
        width: 100%;
        min-height: 220px;
        border-radius: 22px;
        object-fit: cover;
        background: #efe7dd;
    }

    .event-name {
        margin: 8px 0 16px;
        font-size: 30px;
        line-height: 1.16;
        font-weight: 950;
        letter-spacing: -0.035em;
    }

    .info-list {
        display: grid;
        gap: 12px;
    }

    .info-row {
        display: grid;
        grid-template-columns: 130px 1fr;
        gap: 16px;
        padding: 13px 0;
        border-bottom: 1px dashed #eadfd3;
    }

    .info-label {
        color: #8a8179;
        font-size: 13px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .05em;
    }

    .info-value {
        font-weight: 800;
        color: #26211d;
        line-height: 1.45;
    }

    .ticket-list {
        display: grid;
        gap: 14px;
    }

    .ticket-item {
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 18px;
        padding: 18px;
        border: 1px solid #eee3d8;
        border-radius: 22px;
        background: linear-gradient(180deg, #fff, #fffaf5);
    }

    .ticket-main {
        display: grid;
        gap: 9px;
    }

    .ticket-name {
        font-size: 18px;
        font-weight: 950;
        color: #111827;
        margin: 0;
    }

    .ticket-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        color: #6b625a;
        font-size: 14px;
        line-height: 1.5;
    }

    .ticket-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 10px;
        border-radius: 999px;
        background: #f7efe7;
        color: #5d4037;
        font-weight: 800;
    }

    .ticket-price {
        min-width: 150px;
        text-align: right;
        font-weight: 950;
        color: #b91c1c;
        font-size: 18px;
    }

    .payment-panel {
        position: sticky;
        top: 92px;
    }

    .summary-line {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 18px;
        padding: 14px 0;
        border-bottom: 1px solid #f0e7dc;
        line-height: 1.5;
    }

    .summary-label {
        color: #766d65;
        font-weight: 800;
    }

    .summary-value {
        color: #161616;
        text-align: right;
        font-weight: 900;
    }

    .summary-total {
        margin-top: 18px;
        padding: 22px;
        border-radius: 22px;
        background: #111827;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
    }

    .summary-total span:first-child {
        color: #d1d5db;
        font-weight: 800;
    }

    .summary-total span:last-child {
        font-size: 24px;
        font-weight: 950;
        color: #fef3c7;
    }

    .method-box {
        margin-top: 18px;
        padding: 18px;
        border-radius: 22px;
        border: 2px solid #b91c1c;
        background: #fff7ed;
    }

    .method-name {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        font-weight: 950;
        color: #7f1d1d;
        margin-bottom: 10px;
    }

    .method-desc {
        color: #7c6255;
        line-height: 1.65;
        font-size: 14px;
        margin: 0;
    }

    .qr-box {
        margin-top: 18px;
        padding: 20px;
        border-radius: 24px;
        background: #f9fafb;
        border: 1px dashed #d1d5db;
        text-align: center;
    }

    .qr-image {
        width: 236px;
        max-width: 100%;
        aspect-ratio: 1 / 1;
        object-fit: contain;
        border-radius: 20px;
        background: #fff;
        padding: 12px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 18px 34px rgba(17, 24, 39, .08);
    }

    .qr-note {
        margin: 16px auto 0;
        max-width: 320px;
        line-height: 1.65;
        color: #4b5563;
        font-size: 14px;
    }

    .timer-box {
        margin-top: 18px;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }

    .timer-card {
        border-radius: 18px;
        background: #fff;
        border: 1px solid #eee3d8;
        padding: 15px;
    }

    .timer-label {
        color: #8a8179;
        font-size: 12px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: .08em;
        margin-bottom: 6px;
    }

    .timer-value {
        font-size: 24px;
        font-weight: 950;
        color: #b91c1c;
        font-variant-numeric: tabular-nums;
    }

    .checkout-btn {
        width: 100%;
        border: 0;
        border-radius: 18px;
        padding: 16px 18px;
        font-weight: 950;
        cursor: pointer;
        transition: .18s ease;
        font-size: 15px;
        margin-top: 16px;
    }

    .checkout-btn.primary {
        background: #b91c1c;
        color: #fff;
        box-shadow: 0 14px 28px rgba(185, 28, 28, .24);
    }

    .checkout-btn.primary:hover {
        transform: translateY(-1px);
        background: #991b1b;
    }

    .checkout-btn.dark {
        background: #111827;
        color: #fff;
    }

    .checkout-btn.dark:hover {
        transform: translateY(-1px);
        background: #020617;
    }

    .checkout-btn:disabled {
        cursor: not-allowed;
        opacity: .55;
        transform: none !important;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 999px;
        padding: 8px 12px;
        font-size: 12px;
        font-weight: 950;
        letter-spacing: .03em;
    }

    .status-success { background: #dcfce7; color: #166534; }
    .status-waiting { background: #fef3c7; color: #92400e; }
    .status-failed { background: #fee2e2; color: #991b1b; }
    .status-cancelled { background: #e5e7eb; color: #374151; }

    .small-muted {
        color: #766d65;
        line-height: 1.7;
        font-size: 13px;
    }

    .empty-box {
        padding: 18px;
        border-radius: 18px;
        background: #f9fafb;
        color: #6b7280;
        line-height: 1.7;
        border: 1px dashed #d1d5db;
    }

    @media (max-width: 980px) {
        .checkout-heading {
            align-items: flex-start;
            flex-direction: column;
        }

        .checkout-grid {
            grid-template-columns: 1fr;
        }

        .payment-panel {
            position: static;
        }

        .event-card {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 640px) {
        .echoes-checkout {
            padding-top: 32px;
        }

        .checkout-section {
            padding: 20px;
        }

        .ticket-item {
            grid-template-columns: 1fr;
        }

        .ticket-price {
            text-align: left;
        }

        .timer-box {
            grid-template-columns: 1fr;
        }

        .info-row {
            grid-template-columns: 1fr;
            gap: 4px;
        }
    }
</style>
@endsection

@section('content')
@php
    $money = fn($amount) => number_format((float) $amount, 0, ',', '.') . 'Ä‘';
    $eventImage = $event && !empty($event->AnhBia)
        ? asset($event->AnhBia)
        : asset('assets/images/index/favicon.png');
    $eventName = $event->TenSuKien ?? 'Echoes Event';
    $paymentStatus = $payment->TrangThai ?? null;
    $paymentStatusLabel = match($paymentStatus) {
        'ThanhCong' => 'ThÃ nh cÃ´ng',
        'ChoThanhToan' => 'Chá» thanh toÃ¡n',
        'ThatBai' => 'Tháº¥t báº¡i',
        default => 'ChÆ°a táº¡o giao dá»‹ch',
    };
    $paymentStatusClass = match($paymentStatus) {
        'ThanhCong' => 'status-success',
        'ChoThanhToan' => 'status-waiting',
        'ThatBai' => 'status-failed',
        default => 'status-cancelled',
    };
    $orderStatusLabel = match($order->TrangThai) {
        'DaThanhToan' => 'ÄÃ£ thanh toÃ¡n',
        'ChoThanhToan' => 'Chá» thanh toÃ¡n',
        'DaHuy' => 'ÄÃ£ há»§y',
        default => $order->TrangThai,
    };
    $orderStatusClass = match($order->TrangThai) {
        'DaThanhToan' => 'status-success',
        'ChoThanhToan' => 'status-waiting',
        'DaHuy' => 'status-cancelled',
        default => 'status-cancelled',
    };
    $transferNote = ($paymentConfig['note_prefix'] ?? 'ECHOES') . '-ORDER-' . $order->MaDonHang;
@endphp

<section class="echoes-checkout">
    <div class="checkout-container">
        <div class="checkout-heading">
            <div>
                <div class="checkout-eyebrow">Echoes Checkout</div>
                <h1 class="checkout-title">Thanh toÃ¡n Ä‘Æ¡n hÃ ng</h1>
                <p class="checkout-subtitle">
                    HoÃ n táº¥t thanh toÃ¡n toÃ n bá»™ Ä‘Æ¡n hÃ ng Ä‘á»ƒ Echoes xÃ¡c nháº­n vÃ© vÃ  gá»­i email Ä‘áº·t vÃ© thÃ nh cÃ´ng cho báº¡n.
                </p>
            </div>
            <div class="checkout-order-badge">ÄÆ¡n hÃ ng #{{ $order->MaDonHang }}</div>
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
            <div class="checkout-card">
                <div class="checkout-section">
                    <div class="section-title">
                        <div>
                            <div class="section-kicker">Sá»± kiá»‡n</div>
                            <h2>ThÃ´ng tin vÃ© Ä‘Ã£ Ä‘áº·t</h2>
                        </div>
                        <span class="status-badge {{ $orderStatusClass }}">{{ $orderStatusLabel }}</span>
                    </div>

                    <div class="event-card">
                        <img class="event-poster" src="{{ $eventImage }}" alt="{{ $eventName }}">

                        <div>
                            <h2 class="event-name">{{ $eventName }}</h2>
                            <div class="info-list">
                                <div class="info-row">
                                    <div class="info-label">Thá»i gian</div>
                                    <div class="info-value">
                                        {{ $event && $event->ThoiGianBatDau ? \Carbon\Carbon::parse($event->ThoiGianBatDau)->format('H:i d/m/Y') : 'Äang cáº­p nháº­t' }}
                                    </div>
                                </div>
                                <div class="info-row">
                                    <div class="info-label">KhÃ¡ch hÃ ng</div>
                                    <div class="info-value">
                                        {{ $customerAccount->HoTen ?? $customerAccount->TenDangNhap ?? 'KhÃ¡ch hÃ ng Echoes' }}
                                    </div>
                                </div>
                                <div class="info-row">
                                    <div class="info-label">Email</div>
                                    <div class="info-value">
                                        {{ $customerAccount->Email ?? 'ChÆ°a cÃ³ email' }}
                                    </div>
                                </div>
                                <div class="info-row">
                                    <div class="info-label">Giá»¯ vÃ©</div>
                                    <div class="info-value">
                                        @if($order->TrangThai === 'DaThanhToan')
                                            ÄÆ¡n hÃ ng Ä‘Ã£ thanh toÃ¡n thÃ nh cÃ´ng.
                                        @elseif($order->TrangThai === 'DaHuy')
                                            ÄÆ¡n hÃ ng Ä‘Ã£ háº¿t háº¡n hoáº·c Ä‘Ã£ bá»‹ há»§y.
                                        @else
                                            CÃ²n <span id="orderTimerText">--:--</span> trong thá»i gian giá»¯ vÃ© 10 phÃºt.
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="checkout-section">
                    <div class="section-title">
                        <div>
                            <div class="section-kicker">Danh sÃ¡ch vÃ©</div>
                            <h3>VÃ© trong Ä‘Æ¡n hÃ ng</h3>
                        </div>
                        <strong>{{ $ticketItems->count() }} vÃ©</strong>
                    </div>

                    @if($ticketItems->isEmpty())
                        <div class="empty-box">
                            ChÆ°a cÃ³ dá»¯ liá»‡u vÃ© trong Ä‘Æ¡n hÃ ng nÃ y. Khi pháº§n Ä‘áº·t vÃ© táº¡o vÃ© vÃ o báº£ng <strong>ve</strong>, danh sÃ¡ch vÃ© sáº½ hiá»ƒn thá»‹ táº¡i Ä‘Ã¢y.
                        </div>
                    @else
                        <div class="ticket-list">
                            @foreach($ticketItems as $item)
                                <div class="ticket-item">
                                    <div class="ticket-main">
                                        <h4 class="ticket-name">{{ $item->TenHangVe ?? 'Háº¡ng vÃ©' }}</h4>
                                        <div class="ticket-meta">
                                            <span class="ticket-pill">Khu vá»±c: {{ $item->TenKhuVuc ?? 'KhÃ´ng phÃ¢n khu' }}</span>
                                            <span class="ticket-pill">
                                                Gháº¿:
                                                @if($item->HangGhe || $item->SoGhe)
                                                    {{ trim(($item->HangGhe ?? '') . '-' . ($item->SoGhe ?? ''), '-') }}
                                                @else
                                                    Tá»± do
                                                @endif
                                            </span>
                                            <span class="ticket-pill">MÃ£ vÃ©: {{ $item->MaVeDienTu ?? '---' }}</span>
                                            <span class="ticket-pill">Tráº¡ng thÃ¡i: {{ $item->TrangThaiVe ?? '---' }}</span>
                                        </div>
                                    </div>
                                    <div class="ticket-price">{{ $money($item->GiaVe ?? 0) }}</div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

                <div class="checkout-section">
                    <div class="section-title">
                        <div>
                            <div class="section-kicker">Merchandise</div>
                            <h3>Merchandise trong don hang</h3>
                        </div>
                        <strong>{{ $merchandiseItems->count() }} san pham</strong>
                    </div>

                    @if($merchandiseItems->isNotEmpty())
                        <div class="ticket-list">
                            @foreach($merchandiseItems as $item)
                                <div class="ticket-item">
                                    <div class="ticket-main">
                                        <h4 class="ticket-name">{{ $item->TenMerch }}</h4>
                                        <div class="ticket-meta">
                                            <span class="ticket-pill">So luong: {{ $item->SoLuong }}</span>
                                            <span class="ticket-pill">Don gia: {{ $money($item->DonGia) }}</span>
                                        </div>
                                    </div>
                                    <div class="ticket-price">{{ $money($item->ThanhTien) }}</div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

            <aside class="checkout-card payment-panel">
                <div class="checkout-section">
                    <div class="section-title">
                        <div>
                            <div class="section-kicker">{{ $paymentConfig['brand_name'] ?? 'Echoes' }}</div>
                            <h3>Thanh toÃ¡n</h3>
                        </div>
                        <span class="status-badge {{ $paymentStatusClass }}">{{ $paymentStatusLabel }}</span>
                    </div>

                    <div class="summary-line">
                        <span class="summary-label">MÃ£ Ä‘Æ¡n hÃ ng</span>
                        <span class="summary-value">#{{ $order->MaDonHang }}</span>
                    </div>
                    <div class="summary-line">
                        <span class="summary-label">MÃ£ giao dá»‹ch</span>
                        <span class="summary-value">{{ $payment->MaGiaoDich ?? 'ChÆ°a táº¡o' }}</span>
                    </div>
                    <div class="summary-line">
                        <span class="summary-label">PhÆ°Æ¡ng thá»©c</span>
                        <span class="summary-value">{{ $paymentConfig['method_label'] ?? 'Chuyá»ƒn khoáº£n QR' }}</span>
                    </div>
                    <div class="summary-line">
                        <span class="summary-label">HÃ¬nh thá»©c</span>
                        <span class="summary-value">Thanh toÃ¡n full 100%</span>
                    </div>
                    <div class="summary-total">
                        <span>Tá»•ng tiá»n</span>
                        <span>{{ $money($order->TongTien) }}</span>
                    </div>

                    <div class="method-box">
                        <div class="method-name">
                            <span>QR chuyá»ƒn khoáº£n Echoes</span>
                            <span>01:00</span>
                        </div>
                        <p class="method-desc">
                            Táº¡o mÃ£ QR, chuyá»ƒn khoáº£n Ä‘Ãºng sá»‘ tiá»n vÃ  ná»™i dung. Sau Ä‘Ã³ báº¥m <strong>TÃ´i Ä‘Ã£ thanh toÃ¡n</strong> trong thá»i gian hiá»‡u lá»±c.
                        </p>
                    </div>

                    @if($order->TrangThai === 'DaThanhToan')
                        <div class="checkout-alert success" style="margin-top:18px;">
                            ÄÆ¡n hÃ ng Ä‘Ã£ thanh toÃ¡n thÃ nh cÃ´ng. Email xÃ¡c nháº­n Ä‘Ã£ Ä‘Æ°á»£c gá»­i náº¿u cáº¥u hÃ¬nh email há»£p lá»‡.
                        </div>
                    @elseif($isOrderExpired)
                        <div class="checkout-alert error" style="margin-top:18px;">
                            ÄÆ¡n hÃ ng Ä‘Ã£ háº¿t thá»i gian giá»¯ vÃ© hoáº·c Ä‘Ã£ bá»‹ há»§y. Vui lÃ²ng Ä‘áº·t vÃ© láº¡i.
                        </div>
                    @elseif($hasActiveQr)
                        <div class="qr-box">
                            <img class="qr-image" src="{{ asset($paymentConfig['qr_image']) }}" alt="QR thanh toÃ¡n Echoes">
                            <p class="qr-note">
                                <strong>{{ $paymentConfig['bank_name'] }}</strong><br>
                                Chá»§ tÃ i khoáº£n: <strong>{{ $paymentConfig['account_name'] }}</strong><br>
                                Sá»‘ tÃ i khoáº£n: <strong>{{ $paymentConfig['account_number'] }}</strong><br>
                                Ná»™i dung: <strong>{{ $payment->MaGiaoDich ?: $transferNote }}</strong>
                            </p>
                        </div>

                        <div class="timer-box">
                            <div class="timer-card">
                                <div class="timer-label">QR cÃ²n hiá»‡u lá»±c</div>
                                <div class="timer-value" id="qrTimerText">--:--</div>
                            </div>
                            <div class="timer-card">
                                <div class="timer-label">Giá»¯ vÃ© cÃ²n láº¡i</div>
                                <div class="timer-value" id="sideOrderTimerText">--:--</div>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('payment.qr.confirm', $order->MaDonHang) }}" id="confirmPaymentForm">
                            @csrf
                            <button type="submit" class="checkout-btn primary" id="confirmPaymentBtn">
                                TÃ´i Ä‘Ã£ thanh toÃ¡n
                            </button>
                        </form>

                        <p class="small-muted" style="margin:14px 0 0;">
                            Náº¿u quÃ¡ 01 phÃºt, mÃ£ QR sáº½ háº¿t hiá»‡u lá»±c vÃ  giao dá»‹ch chuyá»ƒn sang tráº¡ng thÃ¡i tháº¥t báº¡i. Báº¡n cÃ³ thá»ƒ táº¡o láº¡i mÃ£ QR náº¿u Ä‘Æ¡n hÃ ng váº«n cÃ²n thá»i gian giá»¯ vÃ©.
                        </p>
                    @else
                        <form method="POST" action="{{ route('payment.qr.create', $order->MaDonHang) }}">
                            @csrf
                            <input type="hidden" name="payment_method" value="ChuyenKhoanQR">
                            <button type="submit" class="checkout-btn dark">
                                Táº¡o mÃ£ QR thanh toÃ¡n
                            </button>
                        </form>

                        <p class="small-muted" style="margin:14px 0 0;">
                            MÃ£ QR chá»‰ cÃ³ hiá»‡u lá»±c 01 phÃºt. ÄÆ¡n hÃ ng váº«n Ä‘Æ°á»£c giá»¯ tá»‘i Ä‘a 10 phÃºt ká»ƒ tá»« thá»i Ä‘iá»ƒm Ä‘áº·t vÃ©.
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
        const qrRemainingStart = Number(@json($qrRemainingSeconds));
        const hasActiveQr = Boolean(@json($hasActiveQr));
        const expireUrl = @json(route('payment.qr.expire', $order->MaDonHang));
        const csrfToken = @json(csrf_token());

        const orderTimerText = document.getElementById('orderTimerText');
        const sideOrderTimerText = document.getElementById('sideOrderTimerText');
        const qrTimerText = document.getElementById('qrTimerText');
        const confirmButton = document.getElementById('confirmPaymentBtn');
        const confirmForm = document.getElementById('confirmPaymentForm');

        let orderRemaining = orderRemainingStart;
        let qrRemaining = qrRemainingStart;
        let expireCalled = false;

        function formatTime(seconds) {
            seconds = Math.max(0, Number(seconds) || 0);
            const m = Math.floor(seconds / 60).toString().padStart(2, '0');
            const s = Math.floor(seconds % 60).toString().padStart(2, '0');
            return `${m}:${s}`;
        }

        function setText(el, value) {
            if (el) el.textContent = value;
        }

        function disablePaymentButton(message) {
            if (!confirmButton) return;
            confirmButton.disabled = true;
            confirmButton.textContent = message || 'MÃ£ QR Ä‘Ã£ háº¿t hiá»‡u lá»±c';
        }

        function callExpireEndpoint() {
            if (expireCalled) return;
            expireCalled = true;

            fetch(expireUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({})
            }).finally(function () {
                setTimeout(function () {
                    window.location.reload();
                }, 800);
            });
        }

        function tick() {
            if (orderRemaining > 0) orderRemaining--;
            if (hasActiveQr && qrRemaining > 0) qrRemaining--;

            setText(orderTimerText, formatTime(orderRemaining));
            setText(sideOrderTimerText, formatTime(orderRemaining));
            setText(qrTimerText, formatTime(qrRemaining));

            if (hasActiveQr && qrRemaining <= 0) {
                disablePaymentButton('MÃ£ QR Ä‘Ã£ háº¿t hiá»‡u lá»±c');
                callExpireEndpoint();
            }

            if (orderRemaining <= 0 && confirmButton) {
                disablePaymentButton('ÄÆ¡n hÃ ng Ä‘Ã£ háº¿t thá»i gian giá»¯ vÃ©');
            }
        }

        setText(orderTimerText, formatTime(orderRemaining));
        setText(sideOrderTimerText, formatTime(orderRemaining));
        setText(qrTimerText, formatTime(qrRemaining));

        if (confirmForm) {
            confirmForm.addEventListener('submit', function (event) {
                if (qrRemaining <= 0 || orderRemaining <= 0) {
                    event.preventDefault();
                    disablePaymentButton(qrRemaining <= 0 ? 'MÃ£ QR Ä‘Ã£ háº¿t hiá»‡u lá»±c' : 'ÄÆ¡n hÃ ng Ä‘Ã£ háº¿t thá»i gian giá»¯ vÃ©');
                }
            });
        }

        setInterval(tick, 1000);
    })();
</script>
@endsection