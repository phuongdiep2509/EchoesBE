@extends('layouts.app')

@section('title', 'Vé của tôi | Echoes')

@section('styles')
<style>
    .ticket-page { background:#f5f1ea; min-height:100vh; padding:80px 20px 80px; }
    .ticket-wrap { width:min(1180px,100%); margin:0 auto; }
    .ticket-hero { display:flex; justify-content:space-between; align-items:center; gap:16px; margin-bottom:20px; }
    .ticket-hero h1 { margin:0; font-size:26px; font-weight:800; color:#111827; }
    .ticket-action-link { color:#74070d; border:1px solid #74070d; border-radius:999px; padding:8px 16px; text-decoration:none; white-space:nowrap; font-size:13px; font-weight:600; flex-shrink:0; }
    .alert-echoes { border-radius:18px; padding:16px 18px; margin-bottom:16px; line-height:1.55; }
    .alert-echoes.success { background:#e7f8ed; color:#166534; border:1px solid #bcebc9; }
    .alert-echoes.error { background:#fff0f0; color:#b91c1c; border:1px solid #ffcaca; }
    .alert-echoes.warning { background:#fff8e6; color:#8a5b00; border:1px solid #ffe0a0; }

    /* ── Card dạng dòng ── */
    .tcard-list { display:flex; flex-direction:column; gap:14px; }
    .tcard {
        background:#fff;
        border:1px solid rgba(17,24,39,.09);
        border-radius:20px;
        padding:22px 26px;
        display:flex;
        align-items:center;
        gap:24px;
        box-shadow:0 4px 18px rgba(17,24,39,.06);
        border-left:4px solid #74070d;
    }
    .tcard-main { flex:1; min-width:0; }
    .tcard-title { font-size:17px; font-weight:800; color:#111827; margin:0 0 10px; line-height:1.3; }
    .tcard-meta { display:flex; flex-wrap:wrap; gap:6px 20px; font-size:13px; color:#4b5563; }
    .tcard-meta span { display:flex; align-items:center; gap:5px; }
    .tcard-price { font-size:17px; font-weight:800; color:#74070d; white-space:nowrap; margin-bottom:10px; }
    .tcard-badges { display:flex; flex-wrap:wrap; gap:6px; margin-top:10px; }
    .tcard-actions { display:flex; flex-direction:column; align-items:flex-end; gap:8px; flex-shrink:0; }

    .badge { display:inline-flex; border-radius:999px; padding:5px 10px; font-size:11px; font-weight:700; line-height:1; border:1px solid transparent; }
    .badge.ok   { background:#e7f8ed; color:#166534; border-color:#bcebc9; }
    .badge.wait { background:#fff8e6; color:#8a5b00; border-color:#ffe0a0; }
    .badge.fail { background:#fff0f0; color:#b91c1c; border-color:#ffcaca; }

    .btn-row { display:flex; gap:8px; flex-wrap:wrap; }
    .btn-echoes { border:0; border-radius:12px; padding:9px 16px; cursor:pointer; text-decoration:none; font-size:13px; font-weight:700; line-height:1; display:inline-flex; align-items:center; justify-content:center; white-space:nowrap; }
    .btn-primary  { background:#74070d; color:#fff; }
    .btn-light    { background:#f1ede5; color:#111827; }
    .btn-disabled { opacity:.45; cursor:not-allowed; }

    .history-title { font-size:26px; font-weight:800; margin:46px 0 16px; color:#111827; }
    .history-card { background:#fff; border-radius:24px; overflow:hidden; box-shadow:0 16px 44px rgba(17,24,39,.08); border:1px solid rgba(17,24,39,.08); }
    .history-table { width:100%; border-collapse:collapse; }
    .history-table th, .history-table td { padding:14px 16px; border-bottom:1px solid #f0ede7; text-align:left; vertical-align:top; font-size:14px; }
    .history-table th { background:#111827; color:#fff; }
    .empty-box { background:#fff; border-radius:26px; padding:36px; text-align:center; color:#4b5563; box-shadow:0 16px 44px rgba(17,24,39,.08); line-height:1.6; }

    @media(max-width:760px){
        .ticket-hero { flex-direction:column; align-items:flex-start; padding:28px 24px; }
        .ticket-hero h1 { font-size:28px; }
        .ticket-action-link { white-space:normal; }
        .tcard { flex-direction:column; align-items:flex-start; }
        .tcard-actions { align-items:flex-start; width:100%; }
        .history-card { overflow-x:auto; }
    }
</style>
@endsection

@section('content')
<section class="ticket-page">
    <div class="ticket-wrap">

        <div class="ticket-hero">
            <h1>Vé của tôi</h1>
            <a href="{{ route('ticket-gifts.history', ['account_id' => $accountId]) }}" class="ticket-action-link">Xem lịch sử tặng vé</a>
        </div>

        @if(session('success')) <div class="alert-echoes success">{{ session('success') }}</div> @endif
        @if(session('warning')) <div class="alert-echoes warning">{{ session('warning') }}</div> @endif
        @if(session('error'))   <div class="alert-echoes error">{{ session('error') }}</div>   @endif
        @if($errors->any())
            <div class="alert-echoes error">
                @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
            </div>
        @endif

        @if(!empty($needLogin))
            <div class="empty-box"><h2>Bạn cần đăng nhập để xem vé</h2></div>

        @elseif($tickets->isEmpty())
            <div class="empty-box">
                <h2>Chưa có vé hợp lệ</h2>
                <p>Vé sẽ xuất hiện tại đây sau khi đơn hàng được thanh toán thành công.</p>
            </div>

        @else
            <div class="tcard-list">
                @foreach($tickets as $ticket)
                    @php
                        $isUsable       = $ticket->TrangThaiVe === 'ChoSuDung';
                        $isGiftPending  = ($ticket->TrangThaiTangVe ?? null) === 'DangChoNhan';
                        $isGiftAccepted = ($ticket->TrangThaiTangVe ?? null) === 'DaNhan';
                        $isEventEnded   = $ticket->ThoiGianKetThuc
                                          && now()->greaterThan(\Carbon\Carbon::parse($ticket->ThoiGianKetThuc));
                        $canGift = $isUsable && !$isGiftPending && !$isGiftAccepted && !$isEventEnded
                                   && !in_array($ticket->TrangThaiSuKien, ['DaHuy','DaKetThuc'], true);
                        $start = \Carbon\Carbon::parse($ticket->ThoiGianBatDau);
                        $end   = $ticket->ThoiGianKetThuc
                                 ? \Carbon\Carbon::parse($ticket->ThoiGianKetThuc) : null;
                    @endphp

                    <div class="tcard">
                        {{-- Thông tin chính --}}
                        <div class="tcard-main">
                            <div class="tcard-title">{{ $ticket->TenSuKien }}</div>

                            <div class="tcard-meta">
                                @if(!empty($ticket->TenDiaDiem) || !empty($ticket->ThanhPho))
                                    <span>📍 {{ trim(($ticket->TenDiaDiem ?? '').($ticket->ThanhPho ? ' - '.$ticket->ThanhPho : '')) }}</span>
                                @endif
                                <span>🕐 {{ $start->format('H:i') }}{{ $end ? ' - '.$end->format('H:i') : '' }}, {{ $start->format('d/m/Y') }}</span>
                                <span>🎫 {{ $ticket->TenHangVe }}{{ $ticket->TenKhuVuc ? ' ('.$ticket->TenKhuVuc.')' : '' }}</span>
                                @if($ticket->HangGhe || $ticket->SoGhe)
                                    <span>💺 Ghế {{ trim(($ticket->HangGhe ?? '').($ticket->SoGhe ?? '')) }}</span>
                                @endif
                                <span style="color:#9ca3af;font-size:12px;">🪪 {{ $ticket->MaVeDienTu }}</span>
                            </div>

                            <div class="tcard-badges">
                                @if($ticket->TrangThaiVe === 'ChoSuDung')
                                    <span class="badge ok">Chờ sử dụng</span>
                                @elseif($ticket->TrangThaiVe === 'DaSuDung')
                                    <span class="badge fail">Đã sử dụng</span>
                                @else
                                    <span class="badge fail">Đã hủy</span>
                                @endif
                                @if($isGiftPending)  <span class="badge wait">Đã tặng – chờ nhận</span> @endif
                                @if($isGiftAccepted) <span class="badge ok">Đã được nhận</span>         @endif
                            </div>

                        </div>

                        {{-- Giá + nút --}}
                        <div class="tcard-actions">
                            <div class="tcard-price">{{ number_format((float)$ticket->GiaVe, 0, ',', '.') }}đ</div>
                            <div class="btn-row">
                                <a class="btn-echoes btn-light"
                                   href="{{ route('my-ticket.show', ['ticketId' => $ticket->MaVe, 'account_id' => $accountId]) }}">Chi tiết</a>
                                @if($canGift)
                                    <button type="button" class="btn-echoes btn-primary"
                                            onclick="openMyGiftModal({{ $ticket->MaVe }})">Tặng vé</button>
                                @elseif($isGiftPending)
                                    <span class="btn-echoes btn-light btn-disabled" style="cursor:default;">Đã tặng</span>
                                @elseif($isGiftAccepted)
                                    <span class="btn-echoes btn-light btn-disabled" style="cursor:default;">Đã nhận</span>
                                @else
                                    <span class="btn-echoes btn-light btn-disabled" style="cursor:default;">Không thể tặng</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Đơn hàng merchandise --}}
        @if(empty($needLogin))
            @php $merOrders = $merchandiseOrders ?? collect(); @endphp
            <h2 class="history-title">Đơn hàng merchandise</h2>
            @if($merOrders->isEmpty())
                <div class="empty-box">Bạn chưa có đơn hàng merchandise nào.</div>
            @else
                <div class="tcard-list">
                    @foreach($merOrders as $orderId => $items)
                        @php
                            $firstItem = $items->first();
                            $orderTotal = $items->sum(fn($i) => $i->DonGia * $i->SoLuong);
                        @endphp
                        <div class="tcard" style="border-left-color:#46462a;">
                            <div class="tcard-main">
                                <div class="tcard-title" style="font-size:14px;color:#6b7280;font-weight:600;margin-bottom:6px;">
                                    Đơn #{{ $orderId }} &nbsp;·&nbsp; {{ \Carbon\Carbon::parse($firstItem->NgayDat)->format('d/m/Y H:i') }}
                                </div>
                                <div style="display:flex;flex-direction:column;gap:8px;">
                                    @foreach($items as $item)
                                        <div style="display:flex;align-items:center;gap:12px;">
                                            @if($item->AnhSanPham)
                                                <img src="{{ asset('assets/images/merchandise/' . $item->AnhSanPham) }}"
                                                     style="width:44px;height:44px;object-fit:cover;border-radius:8px;flex-shrink:0;"
                                                     alt="{{ $item->TenMerch }}">
                                            @else
                                                <div style="width:44px;height:44px;background:#f1ede5;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0;">🛍️</div>
                                            @endif
                                            <div style="flex:1;min-width:0;">
                                                <div style="font-weight:700;font-size:14px;color:#111827;">{{ $item->TenMerch }}</div>
                                                <div style="font-size:12px;color:#6b7280;">x{{ $item->SoLuong }} &nbsp;·&nbsp; {{ number_format($item->DonGia, 0, ',', '.') }}đ/cái</div>
                                            </div>
                                            <div style="font-weight:700;color:#74070d;white-space:nowrap;font-size:13px;">
                                                {{ number_format($item->DonGia * $item->SoLuong, 0, ',', '.') }}đ
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="tcard-actions">
                                <div class="tcard-price">{{ number_format($orderTotal, 0, ',', '.') }}đ</div>
                                <span class="badge ok">Đã thanh toán</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        @endif

        {{-- Lịch sử tặng vé gần đây --}}
        @if(empty($needLogin))
            <h2 class="history-title">Lịch sử tặng vé gần đây</h2>
            @if($giftHistory->isEmpty())
                <div class="empty-box">Bạn chưa tặng vé nào.</div>
            @else
                <div class="history-card" style="overflow-x:auto;">
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
                                    <td>{{ $gift->TenSuKien }}<br><small>{{ $gift->TenHangVe }} – {{ $gift->TenKhuVuc }}</small></td>
                                    <td>{{ $gift->TenNguoiNhan }}<br><small>{{ $gift->EmailNguoiNhan }}</small></td>
                                    <td>
                                        @if($gift->TrangThai === 'DangChoNhan')     <span class="badge wait">Chờ nhận</span>
                                        @elseif($gift->TrangThai === 'DaNhan')      <span class="badge ok">Đã nhận</span>
                                        @else                                        <span class="badge fail">Đã hủy</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($gift->TrangThai === 'DangChoNhan')
                                            <form method="POST"
                                                  action="{{ route('ticket-gifts.cancel', ['giftId' => $gift->MaVeTang, 'account_id' => $accountId]) }}"
                                                  onsubmit="return confirm('Hủy lượt tặng vé này?')">
                                                @csrf
                                                <input type="hidden" name="account_id" value="{{ $accountId }}">
                                                <button class="btn-echoes btn-light" type="submit">Hủy tặng</button>
                                            </form>
                                        @else —
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

{{-- ── Modal tặng vé (từ trang Vé của tôi) ── --}}
<div id="myTicketGiftModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.55);z-index:9999;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:24px;padding:32px;width:min(480px,95vw);box-shadow:0 24px 60px rgba(0,0,0,.2);position:relative;">
        <button onclick="closeMyGiftModal()" style="position:absolute;top:16px;right:18px;background:none;border:none;font-size:22px;cursor:pointer;color:#6b7280;">✕</button>
        <h3 style="margin:0 0 6px;font-size:20px;font-weight:800;color:#111827;">🎁 Tặng vé cho người khác</h3>
        <p style="margin:0 0 22px;font-size:13px;color:#6b7280;">Điền thông tin người nhận vé.</p>

        <form id="myGiftForm" method="POST" action="">
            @csrf
            <input type="hidden" name="account_id" value="{{ $accountId }}">

            <div style="margin-bottom:14px;">
                <label style="display:block;font-size:13px;font-weight:700;color:#374151;margin-bottom:6px;">Tên người nhận *</label>
                <input name="TenNguoiNhan" type="text" required maxlength="255" placeholder="Nhập tên người nhận"
                       style="width:100%;border:1px solid #d1d5db;border-radius:12px;padding:10px 13px;font-size:14px;box-sizing:border-box;">
            </div>
            <div style="margin-bottom:14px;">
                <label style="display:block;font-size:13px;font-weight:700;color:#374151;margin-bottom:6px;">Email người nhận *</label>
                <input name="EmailNguoiNhan" type="email" required maxlength="255" placeholder="email@example.com"
                       style="width:100%;border:1px solid #d1d5db;border-radius:12px;padding:10px 13px;font-size:14px;box-sizing:border-box;">
            </div>
            <div style="margin-bottom:14px;">
                <label style="display:block;font-size:13px;font-weight:700;color:#374151;margin-bottom:6px;">Số điện thoại</label>
                <input name="SdtNguoiNhan" type="text" maxlength="15" placeholder="Không bắt buộc"
                       style="width:100%;border:1px solid #d1d5db;border-radius:12px;padding:10px 13px;font-size:14px;box-sizing:border-box;">
            </div>
            <div style="margin-bottom:14px;">
                <label style="display:block;font-size:13px;font-weight:700;color:#374151;margin-bottom:6px;">Loại thiệp</label>
                <select name="LoaiThiep"
                        style="width:100%;border:1px solid #d1d5db;border-radius:12px;padding:10px 13px;font-size:14px;box-sizing:border-box;">
                    <option value="">Không chọn</option>
                    <option value="Bạn bè">Bạn bè</option>
                    <option value="Sinh nhật">Sinh nhật</option>
                    <option value="Cảm ơn">Cảm ơn</option>
                    <option value="Bất ngờ">Bất ngờ</option>
                </select>
            </div>
            <div style="margin-bottom:22px;">
                <label style="display:block;font-size:13px;font-weight:700;color:#374151;margin-bottom:6px;">Lời chúc</label>
                <textarea name="LoiChuc" maxlength="1000" placeholder="Viết lời nhắn gửi người nhận..."
                          style="width:100%;border:1px solid #d1d5db;border-radius:12px;padding:10px 13px;font-size:14px;min-height:80px;resize:vertical;box-sizing:border-box;"></textarea>
            </div>
            <button type="submit"
                    style="width:100%;background:#74070d;color:#fff;border:none;border-radius:14px;padding:13px;font-size:15px;font-weight:700;cursor:pointer;">
                Gửi tặng vé
            </button>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
function openMyGiftModal(ticketId) {
    const routeBase = '{{ route("tickets.gift.store", ["ticketId" => "__ID__", "account_id" => $accountId]) }}';
    document.getElementById('myGiftForm').action = routeBase.replace('__ID__', ticketId);
    const modal = document.getElementById('myTicketGiftModal');
    modal.style.display = 'flex';
}

function closeMyGiftModal() {
    document.getElementById('myTicketGiftModal').style.display = 'none';
}

document.getElementById('myTicketGiftModal').addEventListener('click', function(e) {
    if (e.target === this) closeMyGiftModal();
});
</script>
@endsection
