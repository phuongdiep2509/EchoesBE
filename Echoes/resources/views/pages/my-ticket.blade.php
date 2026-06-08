@extends('layouts.app')

@section('title', 'Vé của tôi | Echoes')

@section('styles')
<style>
    .ticket-page {
        background: #f5f1ea;
        min-height: 100vh;
        padding: 48px 20px 80px;
    }

    .ticket-wrap {
        width: min(1180px, 100%);
        margin: 0 auto;
    }

    .ticket-hero {
        background: #111827;
        color: #fff;
        border-radius: 28px;
        padding: 34px 38px;
        margin-bottom: 24px;
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        gap: 24px;
    }

    .ticket-hero h1 {
        margin: 0 0 10px;
        font-size: 36px;
        line-height: 1.15;
    }

    .ticket-hero p {
        margin: 0;
        color: #d1d5db;
        font-size: 16px;
        line-height: 1.6;
    }

    .ticket-action-link {
        color: #f4e5c6;
        border: 1px solid rgba(255,255,255,.2);
        border-radius: 999px;
        padding: 11px 18px;
        text-decoration: none;
        white-space: nowrap;
    }

    .alert-echoes {
        border-radius: 18px;
        padding: 16px 18px;
        margin-bottom: 16px;
        line-height: 1.55;
    }

    .alert-echoes.success {
        background: #e7f8ed;
        color: #166534;
        border: 1px solid #bcebc9;
    }

    .alert-echoes.error {
        background: #fff0f0;
        color: #b91c1c;
        border: 1px solid #ffcaca;
    }

    .alert-echoes.warning {
        background: #fff8e6;
        color: #8a5b00;
        border: 1px solid #ffe0a0;
    }

    .ticket-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(330px, 1fr));
        gap: 22px;
    }

    .ticket-card {
        background: #fff;
        border: 1px solid rgba(17,24,39,.08);
        border-radius: 26px;
        overflow: hidden;
        box-shadow: 0 18px 48px rgba(17,24,39,.08);
    }

    .ticket-img {
        width: 100%;
        height: 190px;
        object-fit: cover;
        background: #e6ded1;
        display: block;
    }

    .ticket-body {
        padding: 22px;
    }

    .ticket-body h2 {
        margin: 0 0 12px;
        font-size: 23px;
        line-height: 1.25;
        color: #161616;
    }

    .ticket-meta {
        display: grid;
        gap: 8px;
        margin: 14px 0;
        color: #4b5563;
        font-size: 14px;
        line-height: 1.45;
    }

    .ticket-code {
        background: #f7f3ec;
        border-radius: 16px;
        padding: 12px 14px;
        color: #111827;
        word-break: break-word;
        font-size: 14px;
    }

    .badge-row {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin: 15px 0;
    }

    .badge {
        display: inline-flex;
        border-radius: 999px;
        padding: 7px 11px;
        font-size: 12px;
        line-height: 1;
        border: 1px solid transparent;
    }

    .badge.ok {
        background: #e7f8ed;
        color: #166534;
        border-color: #bcebc9;
    }

    .badge.wait {
        background: #fff8e6;
        color: #8a5b00;
        border-color: #ffe0a0;
    }

    .badge.fail {
        background: #fff0f0;
        color: #b91c1c;
        border-color: #ffcaca;
    }

    .btn-row {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 16px;
    }

    .btn-echoes {
        border: 0;
        border-radius: 14px;
        padding: 11px 15px;
        cursor: pointer;
        text-decoration: none;
        font-size: 14px;
        line-height: 1;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .btn-primary {
        background: #74070d;
        color: #fff;
    }

    .btn-light {
        background: #f1ede5;
        color: #111827;
    }

    .btn-disabled {
        opacity: .45;
        cursor: not-allowed;
    }

    .gift-form {
        display: none;
        margin-top: 17px;
        padding: 18px;
        border-radius: 20px;
        background: #fbfaf7;
        border: 1px dashed #d8c8ad;
    }

    .gift-form.active {
        display: block;
    }

    .gift-form label {
        display: block;
        margin: 12px 0 7px;
        font-size: 13px;
        color: #374151;
        font-weight: 700;
    }

    .gift-form input,
    .gift-form select,
    .gift-form textarea {
        width: 100%;
        border: 1px solid #d9cfbd;
        border-radius: 14px;
        padding: 11px 13px;
        background: #fff;
        font-size: 14px;
    }

    .gift-form textarea {
        min-height: 86px;
        resize: vertical;
    }

    .section-title {
        font-size: 28px;
        margin: 46px 0 18px;
        color: #111827;
    }

    .history-card {
        background: #fff;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 16px 44px rgba(17,24,39,.08);
        border: 1px solid rgba(17,24,39,.08);
    }

    .history-table {
        width: 100%;
        border-collapse: collapse;
    }

    .history-table th,
    .history-table td {
        padding: 15px 16px;
        border-bottom: 1px solid #f0ede7;
        text-align: left;
        vertical-align: top;
        font-size: 14px;
    }

    .history-table th {
        background: #111827;
        color: #fff;
    }

    .empty-box {
        background: #fff;
        border-radius: 26px;
        padding: 36px;
        text-align: center;
        color: #4b5563;
        box-shadow: 0 16px 44px rgba(17,24,39,.08);
        line-height: 1.6;
    }

    .order-action-form {
        margin: 0;
    }

    @media(max-width: 760px) {
        .ticket-hero {
            flex-direction: column;
            align-items: flex-start;
            padding: 28px 24px;
        }

        .ticket-hero h1 {
            font-size: 28px;
        }

        .ticket-action-link {
            white-space: normal;
        }

        .history-card {
            overflow-x: auto;
        }
    }
</style>
@endsection

@section('content')
@php
    $accountId = $accountId ?? session('MaTaiKhoan') ?? null;
    $customer = $customer ?? null;

    $tickets = collect($tickets ?? $ticketItems ?? []);
    $ticketItems = collect($ticketItems ?? $tickets ?? []);

    $orders = collect($orders ?? []);

    $giftHistory = collect($giftHistory ?? $gifts ?? []);
    $gifts = collect($gifts ?? $giftHistory ?? []);
@endphp

<section class="ticket-page">
    <div class="ticket-wrap">
        <div class="ticket-hero">
            <div>
                <h1>Vé của tôi</h1>
                <p>Quản lý vé đã thanh toán, gửi tặng vé cho người khác và theo dõi trạng thái nhận vé.</p>
            </div>

            @if(Route::has('ticket-gifts.history'))
                <a href="{{ route('ticket-gifts.history') }}" class="ticket-action-link">
                    Xem lịch sử tặng vé
                </a>
            @endif
        </div>

        @if(session('success'))
            <div class="alert-echoes success">{{ session('success') }}</div>
        @endif

        @if(session('warning'))
            <div class="alert-echoes warning">{{ session('warning') }}</div>
        @endif

        @if(session('error'))
            <div class="alert-echoes error">{{ session('error') }}</div>
        @endif

        @if($errors->any())
            <div class="alert-echoes error">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        @if(!empty($needLogin))
            <div class="empty-box">
                <h2>Bạn cần đăng nhập để xem vé</h2>
                <p>Vui lòng đăng nhập tài khoản khách hàng để xem danh sách vé đã mua.</p>
            </div>
        @elseif($tickets->isEmpty())
            <div class="empty-box">
                <h2>Chưa có vé hợp lệ</h2>
                <p>Vé sẽ xuất hiện tại đây sau khi đơn hàng được thanh toán thành công.</p>
            </div>
        @else
            <div class="ticket-grid">
                @foreach($tickets as $ticket)
                    @php
                        $anhBia = $ticket->AnhBia ?? null;

                        $image = !empty($anhBia)
                            ? (\Illuminate\Support\Str::startsWith($anhBia, ['http://', 'https://']) ? $anhBia : asset($anhBia))
                            : asset('assets/images/index/logo (no back).png');

                        $trangThaiVe = $ticket->TrangThaiVe ?? null;
                        $trangThaiTang = $ticket->TrangThaiTang ?? $ticket->TrangThaiTangVe ?? null;

                        $tenSuKien = $ticket->TenSuKien ?? 'Sự kiện Echoes';
                        $maVeDienTu = $ticket->MaVeDienTu ?? 'Đang cập nhật';
                        $thoiGianBatDau = $ticket->ThoiGianBatDau ?? null;
                        $thoiGianKetThuc = $ticket->ThoiGianKetThuc ?? null;
                        $tenHangVe = $ticket->TenHangVe ?? 'Đang cập nhật';
                        $giaVe = (float)($ticket->GiaVe ?? 0);
                        $tenKhuVuc = $ticket->TenKhuVuc ?? '—';
                        $hangGhe = $ticket->HangGhe ?? '';
                        $soGhe = $ticket->SoGhe ?? '';
                        $gheNgoi = trim($hangGhe . $soGhe);
                        $trangThaiSuKien = $ticket->TrangThaiSuKien ?? null;

                        $isUsable = $trangThaiVe === 'ChoSuDung';
                        $isGiftPending = $trangThaiTang === 'DangChoNhan';
                        $isGiftAccepted = $trangThaiTang === 'DaNhan';

                        $isEventEnded = $thoiGianKetThuc
                            ? now()->greaterThan(\Carbon\Carbon::parse($thoiGianKetThuc))
                            : false;

                        $canGift = $isUsable
                            && !$isGiftPending
                            && !$isGiftAccepted
                            && !$isEventEnded
                            && !in_array($trangThaiSuKien, ['DaHuy', 'DaKetThuc'], true);
                    @endphp

                    <article class="ticket-card">
                        <img class="ticket-img" src="{{ $image }}" alt="{{ $tenSuKien }}">

                        <div class="ticket-body">
                            <h2>{{ $tenSuKien }}</h2>

                            <div class="ticket-code">
                                <strong>Mã vé:</strong> {{ $maVeDienTu }}
                            </div>

                            <div class="ticket-meta">
                                <div>
                                    <strong>Thời gian:</strong>
                                    @if($thoiGianBatDau)
                                        {{ \Carbon\Carbon::parse($thoiGianBatDau)->format('d/m/Y H:i') }}
                                    @else
                                        Đang cập nhật
                                    @endif
                                </div>

                                <div>
                                    <strong>Hạng vé:</strong>
                                    {{ $tenHangVe }} - {{ number_format($giaVe, 0, ',', '.') }}đ
                                </div>

                                <div>
                                    <strong>Khu vực:</strong> {{ $tenKhuVuc }}
                                </div>

                                <div>
                                    <strong>Ghế:</strong>
                                    {{ $gheNgoi !== '' ? 'Ghế ' . $gheNgoi : 'Chưa gán ghế' }}
                                </div>
                            </div>

                            <div class="badge-row">
                                @if($trangThaiVe === 'ChoSuDung')
                                    <span class="badge ok">Chờ sử dụng</span>
                                @elseif($trangThaiVe === 'DaSuDung')
                                    <span class="badge fail">Đã sử dụng</span>
                                @elseif($trangThaiVe === 'DaHuy')
                                    <span class="badge fail">Đã hủy</span>
                                @else
                                    <span class="badge wait">{{ $trangThaiVe ?? 'Đang cập nhật' }}</span>
                                @endif

                                @if($isGiftPending)
                                    <span class="badge wait">Đã tặng - chờ nhận</span>
                                @elseif($isGiftAccepted)
                                    <span class="badge ok">Đã được nhận</span>
                                @endif
                            </div>

                            @if($isGiftPending || $isGiftAccepted)
                                <div style="font-size:14px;color:#4b5563;line-height:1.6;margin:10px 0;">
                                    Người nhận:
                                    <strong>{{ $ticket->TenNguoiNhan ?? 'Đang cập nhật' }}</strong><br>
                                    Email: {{ $ticket->EmailNguoiNhan ?? 'Đang cập nhật' }}
                                </div>
                            @endif

                            <div class="btn-row">
                                @if(Route::has('my-ticket.show'))
                                    <a class="btn-echoes btn-light" href="{{ route('my-ticket.show', ['ticketId' => $ticket->MaVe]) }}">
                                        Chi tiết
                                    </a>
                                @endif

                                @if($canGift && Route::has('tickets.gift.store'))
                                    <button type="button" class="btn-echoes btn-primary" onclick="toggleGiftForm({{ $ticket->MaVe }})">
                                        Tặng vé
                                    </button>
                                @else
                                    <button type="button" class="btn-echoes btn-light btn-disabled" disabled>
                                        Không thể tặng
                                    </button>
                                @endif
                            </div>

                            @if($canGift && Route::has('tickets.gift.store'))
                                <form class="gift-form" id="gift-form-{{ $ticket->MaVe }}" method="POST" action="{{ route('tickets.gift.store', ['ticketId' => $ticket->MaVe]) }}">
                                    @csrf

                                    @if($accountId)
                                        <input type="hidden" name="account_id" value="{{ $accountId }}">
                                    @endif

                                    <label>Tên người nhận *</label>
                                    <input type="text" name="TenNguoiNhan" required maxlength="255" placeholder="Nhập tên người nhận">

                                    <label>Email người nhận *</label>
                                    <input type="email" name="EmailNguoiNhan" required maxlength="255" placeholder="email@example.com">

                                    <label>Số điện thoại người nhận</label>
                                    <input type="text" name="SdtNguoiNhan" maxlength="15" placeholder="Không bắt buộc">

                                    <label>Loại thiệp</label>
                                    <select name="LoaiThiep">
                                        <option value="">Không chọn</option>
                                        <option value="Bạn bè">Bạn bè</option>
                                        <option value="Sinh nhật">Sinh nhật</option>
                                        <option value="Cảm ơn">Cảm ơn</option>
                                        <option value="Bất ngờ">Bất ngờ</option>
                                    </select>

                                    <label>Lời chúc</label>
                                    <textarea name="LoiChuc" maxlength="1000" placeholder="Viết lời nhắn gửi người nhận..."></textarea>

                                    <button type="submit" class="btn-echoes btn-primary" style="margin-top:14px;width:100%;">
                                        Gửi tặng vé
                                    </button>
                                </form>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
        @endif

        <h2 class="section-title">Lịch sử đơn hàng</h2>

        @if($orders->isEmpty())
            <div class="empty-box">
                Chưa có đơn đặt vé.
            </div>
        @else
            <div class="history-card">
                <table class="history-table">
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
                        @foreach($orders as $order)
                            <tr>
                                <td>#{{ $order->MaDonHang }}</td>
                                <td>{{ $order->NgayDat }}</td>
                                <td>{{ $order->SoLuongVe ?? 0 }}</td>
                                <td>{{ number_format((float)($order->TongTien ?? 0), 0, ',', '.') }}đ</td>
                                <td>
                                    @if($order->TrangThai === 'DaThanhToan')
                                        <span class="badge ok">Đã thanh toán</span>
                                    @elseif($order->TrangThai === 'ChoThanhToan')
                                        <span class="badge wait">Chờ thanh toán</span>
                                    @elseif($order->TrangThai === 'DaHuy')
                                        <span class="badge fail">Đã hủy</span>
                                    @else
                                        <span class="badge wait">{{ $order->TrangThai }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($order->TrangThai === 'ChoThanhToan')
                                        <div class="btn-row">
                                            @if(Route::has('payment.show'))
                                                <a class="btn-echoes btn-primary" href="{{ route('payment.show', $order->MaDonHang) }}">
                                                    Thanh toán
                                                </a>
                                            @endif

                                            @if(Route::has('orders.cancel'))
                                                <form class="order-action-form" method="POST" action="{{ route('orders.cancel', $order->MaDonHang) }}">
                                                    @csrf
                                                    <button class="btn-echoes btn-light" type="submit" onclick="return confirm('Hủy đơn này?')">
                                                        Hủy đơn
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    @else
                                        —
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <h2 class="section-title">Lịch sử tặng vé gần đây</h2>

        @if($giftHistory->isEmpty())
            <div class="empty-box">
                Chưa có lịch sử tặng vé.
            </div>
        @else
            <div class="history-card">
                <table class="history-table">
                    <thead>
                        <tr>
                            <th>Mã vé</th>
                            <th>Sự kiện</th>
                            <th>Người nhận</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($giftHistory->take(5) as $gift)
                            @php
                                $giftStatus = $gift->TrangThai ?? null;
                            @endphp

                            <tr>
                                <td>{{ $gift->MaVeDienTu ?? '—' }}</td>
                                <td>
                                    {{ $gift->TenSuKien ?? '—' }}<br>
                                    <small>{{ $gift->TenHangVe ?? '—' }} - {{ $gift->TenKhuVuc ?? '—' }}</small>
                                </td>
                                <td>
                                    {{ $gift->TenNguoiNhan ?? '—' }}<br>
                                    <small>{{ $gift->EmailNguoiNhan ?? '—' }}</small>
                                </td>
                                <td>
                                    @if($giftStatus === 'DangChoNhan')
                                        <span class="badge wait">Chờ nhận</span>
                                    @elseif($giftStatus === 'DaNhan')
                                        <span class="badge ok">Đã nhận</span>
                                    @elseif($giftStatus === 'DaHuy')
                                        <span class="badge fail">Đã hủy</span>
                                    @else
                                        <span class="badge wait">{{ $giftStatus ?? 'Đang cập nhật' }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($giftStatus === 'DangChoNhan' && Route::has('ticket-gifts.cancel'))
                                        <form method="POST" action="{{ route('ticket-gifts.cancel', ['giftId' => $gift->MaVeTang]) }}" onsubmit="return confirm('Hủy lượt tặng vé này?')">
                                            @csrf

                                            @if($accountId)
                                                <input type="hidden" name="account_id" value="{{ $accountId }}">
                                            @endif

                                            <button class="btn-echoes btn-light" type="submit">
                                                Hủy tặng
                                            </button>
                                        </form>
                                    @else
                                        —
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</section>
@endsection

@section('scripts')
<script>
    function toggleGiftForm(ticketId) {
        const form = document.getElementById('gift-form-' + ticketId);

        if (!form) {
            return;
        }

        form.classList.toggle('active');
    }
</script>
@endsection