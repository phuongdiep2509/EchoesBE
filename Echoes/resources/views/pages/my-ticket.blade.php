@extends('layouts.app')

@section('title', 'Vé của tôi | Echoes')

@section('styles')
<style>
    .ticket-page { background:#f5f1ea; min-height:100vh; padding:48px 20px 80px; }
    .ticket-wrap { width:min(1180px,100%); margin:0 auto; }
    .ticket-hero { background:#111827; color:#fff; border-radius:28px; padding:34px 38px; margin-bottom:24px; display:flex; justify-content:space-between; align-items:flex-end; gap:24px; }
    .ticket-hero h1 { margin:0 0 10px; font-size:36px; line-height:1.15; }
    .ticket-hero p { margin:0; color:#d1d5db; font-size:16px; line-height:1.6; }
    .ticket-action-link { color:#f4e5c6; border:1px solid rgba(255,255,255,.2); border-radius:999px; padding:11px 18px; text-decoration:none; white-space:nowrap; }
    .alert-echoes { border-radius:18px; padding:16px 18px; margin-bottom:16px; line-height:1.55; }
    .alert-echoes.success { background:#e7f8ed; color:#166534; border:1px solid #bcebc9; }
    .alert-echoes.error { background:#fff0f0; color:#b91c1c; border:1px solid #ffcaca; }
    .alert-echoes.warning { background:#fff8e6; color:#8a5b00; border:1px solid #ffe0a0; }
    .ticket-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(330px,1fr)); gap:22px; }
    .ticket-card { background:#fff; border:1px solid rgba(17,24,39,.08); border-radius:26px; overflow:hidden; box-shadow:0 18px 48px rgba(17,24,39,.08); }
    .ticket-img { width:100%; height:190px; object-fit:cover; background:#e6ded1; display:block; }
    .ticket-body { padding:22px; }
    .ticket-body h2 { margin:0 0 12px; font-size:23px; line-height:1.25; color:#161616; }
    .ticket-meta { display:grid; gap:8px; margin:14px 0; color:#4b5563; font-size:14px; line-height:1.45; }
    .ticket-code { background:#f7f3ec; border-radius:16px; padding:12px 14px; color:#111827; word-break:break-word; }
    .badge-row { display:flex; flex-wrap:wrap; gap:8px; margin:15px 0; }
    .badge { display:inline-flex; border-radius:999px; padding:7px 11px; font-size:12px; line-height:1; border:1px solid transparent; }
    .badge.ok { background:#e7f8ed; color:#166534; border-color:#bcebc9; }
    .badge.wait { background:#fff8e6; color:#8a5b00; border-color:#ffe0a0; }
    .badge.fail { background:#fff0f0; color:#b91c1c; border-color:#ffcaca; }
    .btn-row { display:flex; gap:10px; flex-wrap:wrap; margin-top:16px; }
    .btn-echoes { border:0; border-radius:14px; padding:11px 15px; cursor:pointer; text-decoration:none; font-size:14px; line-height:1; display:inline-flex; align-items:center; justify-content:center; }
    .btn-primary { background:#74070d; color:#fff; }
    .btn-light { background:#f1ede5; color:#111827; }
    .btn-disabled { opacity:.45; cursor:not-allowed; }
    .gift-form { display:none; margin-top:17px; padding:18px; border-radius:20px; background:#fbfaf7; border:1px dashed #d8c8ad; }
    .gift-form.active { display:block; }
    .gift-form label { display:block; margin:12px 0 7px; font-size:13px; color:#374151; font-weight:700; }
    .gift-form input, .gift-form select, .gift-form textarea { width:100%; border:1px solid #d9cfbd; border-radius:14px; padding:11px 13px; background:#fff; font-size:14px; }
    .gift-form textarea { min-height:86px; resize:vertical; }
    .history-title { font-size:28px; margin:46px 0 18px; color:#111827; }
    .history-card { background:#fff; border-radius:24px; overflow:hidden; box-shadow:0 16px 44px rgba(17,24,39,.08); border:1px solid rgba(17,24,39,.08); }
    .history-table { width:100%; border-collapse:collapse; }
    .history-table th, .history-table td { padding:15px 16px; border-bottom:1px solid #f0ede7; text-align:left; vertical-align:top; font-size:14px; }
    .history-table th { background:#111827; color:#fff; }
    .empty-box { background:#fff; border-radius:26px; padding:36px; text-align:center; color:#4b5563; box-shadow:0 16px 44px rgba(17,24,39,.08); line-height:1.6; }
    @media(max-width:760px){ .ticket-hero{flex-direction:column;align-items:flex-start;padding:28px 24px}.ticket-hero h1{font-size:28px}.ticket-action-link{white-space:normal}.history-card{overflow-x:auto} }
</style>
@endsection

@section('content')
<section class="ticket-page">
    <div class="ticket-wrap">
        <div class="ticket-hero">
            <div>
                <h1>Vé của tôi</h1>
                <p>Quản lý vé đã thanh toán, gửi tặng vé cho người khác và theo dõi trạng thái nhận vé.</p>
            </div>
            <a href="{{ route('ticket-gifts.history', ['account_id' => $accountId]) }}" class="ticket-action-link">Xem lịch sử tặng vé</a>
        </div>

        @if(session('success')) <div class="alert-echoes success">{{ session('success') }}</div> @endif
        @if(session('warning')) <div class="alert-echoes warning">{{ session('warning') }}</div> @endif
        @if(session('error')) <div class="alert-echoes error">{{ session('error') }}</div> @endif
        @if($errors->any())
            <div class="alert-echoes error">
                @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
            </div>
        @endif

        @if(!empty($needLogin))
            <div class="empty-box">
                <h2>Bạn cần đăng nhập để xem vé</h2>
                <p>Trong giai đoạn demo có thể thử bằng đường dẫn <strong>/my-ticket?account_id=1</strong>.</p>
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
                        $image = !empty($ticket->AnhBia)
                            ? (\Illuminate\Support\Str::startsWith($ticket->AnhBia, ['http://','https://']) ? $ticket->AnhBia : asset($ticket->AnhBia))
                            : asset('assets/images/index/logo (no back).png');
                        $isUsable = $ticket->TrangThaiVe === 'ChoSuDung';
                        $isGiftPending = $ticket->TrangThaiTang === 'DangChoNhan';
                        $isGiftAccepted = $ticket->TrangThaiTang === 'DaNhan';
                        $isEventEnded = $ticket->ThoiGianKetThuc && now()->greaterThan(\Carbon\Carbon::parse($ticket->ThoiGianKetThuc));
                        $canGift = $isUsable && !$isGiftPending && !$isGiftAccepted && !$isEventEnded && !in_array($ticket->TrangThaiSuKien, ['DaHuy','DaKetThuc'], true);
                    @endphp

                    <article class="ticket-card">
                        <img class="ticket-img" src="{{ $image }}" alt="{{ $ticket->TenSuKien }}">
                        <div class="ticket-body">
                            <h2>{{ $ticket->TenSuKien }}</h2>
                            <div class="ticket-code"><strong>Mã vé:</strong> {{ $ticket->MaVeDienTu }}</div>
                            <div class="ticket-meta">
                                <div><strong>Thời gian:</strong> {{ \Carbon\Carbon::parse($ticket->ThoiGianBatDau)->format('d/m/Y H:i') }}</div>
                                <div><strong>Hạng vé:</strong> {{ $ticket->TenHangVe }} - {{ number_format((float)$ticket->GiaVe, 0, ',', '.') }}đ</div>
                                <div><strong>Khu vực:</strong> {{ $ticket->TenKhuVuc ?? '—' }}</div>
                                <div><strong>Ghế:</strong> {{ ($ticket->HangGhe || $ticket->SoGhe) ? 'Ghế '.trim(($ticket->HangGhe ?? '').($ticket->SoGhe ?? '')) : 'Chưa gán ghế' }}</div>
                            </div>

                            <div class="badge-row">
                                @if($ticket->TrangThaiVe === 'ChoSuDung')
                                    <span class="badge ok">Chờ sử dụng</span>
                                @elseif($ticket->TrangThaiVe === 'DaSuDung')
                                    <span class="badge fail">Đã sử dụng</span>
                                @else
                                    <span class="badge fail">Đã hủy</span>
                                @endif

                                @if($isGiftPending)
                                    <span class="badge wait">Đã tặng - chờ nhận</span>
                                @elseif($isGiftAccepted)
                                    <span class="badge ok">Đã được nhận</span>
                                @endif
                            </div>

                            @if($isGiftPending || $isGiftAccepted)
                                <div style="font-size:14px;color:#4b5563;line-height:1.6;margin:10px 0;">
                                    Người nhận: <strong>{{ $ticket->TenNguoiNhan }}</strong><br>
                                    Email: {{ $ticket->EmailNguoiNhan }}
                                </div>
                            @endif

                            <div class="btn-row">
                                <a class="btn-echoes btn-light" href="{{ route('my-ticket.show', ['ticketId' => $ticket->MaVe, 'account_id' => $accountId]) }}">Chi tiết</a>
                                @if($canGift)
                                    <button type="button" class="btn-echoes btn-primary" onclick="toggleGiftForm({{ $ticket->MaVe }})">Tặng vé</button>
                                @else
                                    <button type="button" class="btn-echoes btn-light btn-disabled" disabled>Không thể tặng</button>
                                @endif
                            </div>

                            @if($canGift)
                                <form class="gift-form" id="gift-form-{{ $ticket->MaVe }}" method="POST" action="{{ route('tickets.gift.store', ['ticketId' => $ticket->MaVe, 'account_id' => $accountId]) }}">
                                    @csrf
                                    <input type="hidden" name="account_id" value="{{ $accountId }}">

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

                                    <button type="submit" class="btn-echoes btn-primary" style="margin-top:14px;width:100%;">Gửi tặng vé</button>
                                </form>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
        @endif

        @if(empty($needLogin))
            <h2 class="history-title">Lịch sử tặng vé gần đây</h2>
            @if($giftHistory->isEmpty())
                <div class="empty-box">Bạn chưa tặng vé nào.</div>
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
                                <tr>
                                    <td>{{ $gift->MaVeDienTu }}</td>
                                    <td>{{ $gift->TenSuKien }}<br><small>{{ $gift->TenHangVe }} - {{ $gift->TenKhuVuc }}</small></td>
                                    <td>{{ $gift->TenNguoiNhan }}<br><small>{{ $gift->EmailNguoiNhan }}</small></td>
                                    <td>
                                        @if($gift->TrangThai === 'DangChoNhan') <span class="badge wait">Chờ nhận</span>
                                        @elseif($gift->TrangThai === 'DaNhan') <span class="badge ok">Đã nhận</span>
                                        @else <span class="badge fail">Đã hủy</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($gift->TrangThai === 'DangChoNhan')
                                            <form method="POST" action="{{ route('ticket-gifts.cancel', ['giftId' => $gift->MaVeTang, 'account_id' => $accountId]) }}" onsubmit="return confirm('Hủy lượt tặng vé này?')">
                                                @csrf
                                                <input type="hidden" name="account_id" value="{{ $accountId }}">
                                                <button class="btn-echoes btn-light" type="submit">Hủy tặng</button>
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
        @endif
    </div>
</section>
@endsection

@section('scripts')
<script>
function toggleGiftForm(ticketId) {
    const form = document.getElementById('gift-form-' + ticketId);
    if (!form) return;
    form.classList.toggle('active');
}
</script>
@endsection
