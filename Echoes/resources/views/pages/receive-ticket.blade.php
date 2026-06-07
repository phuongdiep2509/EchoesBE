@extends('layouts.app')

@section('title', 'Nhận vé được tặng | Echoes')

@section('styles')
<style>
    .receive-page { background:#f5f1ea; min-height:100vh; padding:48px 20px 80px; }
    .receive-wrap { width:min(980px,100%); margin:0 auto; }
    .receive-card { background:#fff; border-radius:28px; overflow:hidden; box-shadow:0 18px 50px rgba(17,24,39,.08); border:1px solid rgba(17,24,39,.08); }
    .receive-hero { background:#111827; color:#fff; padding:34px 38px; }
    .receive-hero h1 { margin:0 0 9px; font-size:34px; line-height:1.15; }
    .receive-hero p { margin:0; color:#d1d5db; line-height:1.6; }
    .body { padding:30px 38px; }
    .info-grid { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:14px; margin:22px 0; }
    .info-item { background:#f7f3ec; border-radius:16px; padding:14px 15px; }
    .info-item span { display:block; font-size:12px; color:#777; text-transform:uppercase; letter-spacing:.8px; margin-bottom:6px; }
    .info-item strong { display:block; font-size:15px; color:#111827; line-height:1.45; }
    .message { border-radius:16px; padding:15px 17px; margin-bottom:16px; background:#e7f8ed; color:#166534; border:1px solid #bcebc9; }
    .form-box { background:#fbfaf7; border:1px dashed #d8c8ad; border-radius:20px; padding:18px; margin-top:22px; }
    label { display:block; font-weight:700; margin-bottom:8px; color:#374151; }
    input { width:100%; border:1px solid #d9cfbd; border-radius:14px; padding:12px 14px; font-size:15px; }
    .btn { border:0; border-radius:14px; background:#74070d; color:#fff; padding:13px 18px; margin-top:14px; width:100%; cursor:pointer; font-size:16px; }
    .badge { display:inline-flex; border-radius:999px; padding:7px 11px; font-size:12px; border:1px solid transparent; margin:0 6px 12px 0; }
    .ok { background:#e7f8ed; color:#166534; border-color:#bcebc9; }
    .wait { background:#fff8e6; color:#8a5b00; border-color:#ffe0a0; }
    .fail { background:#fff0f0; color:#b91c1c; border-color:#ffcaca; }
    @media(max-width:720px){ .info-grid{grid-template-columns:1fr}.receive-hero,.body{padding:26px 22px}.receive-hero h1{font-size:28px} }
</style>
@endsection

@section('content')
<section class="receive-page">
    <div class="receive-wrap">
        <div class="receive-card">
            <div class="receive-hero">
                <h1>Nhận vé được tặng</h1>
                <p>Kiểm tra thông tin vé và xác nhận email để hoàn tất nhận vé trên Echoes.</p>
            </div>
            <div class="body">
                @if(!empty($message))
                    <div class="message">{{ $message }}</div>
                @endif

                @if($gift->TrangThai === 'DangChoNhan') <span class="badge wait">Đang chờ xác nhận</span>
                @elseif($gift->TrangThai === 'DaNhan') <span class="badge ok">Đã nhận vé</span>
                @else <span class="badge fail">Lượt tặng đã hủy</span>
                @endif

                <h2 style="margin:0 0 12px;font-size:28px;color:#111827;">{{ $ticket->TenSuKien ?? 'Vé sự kiện Echoes' }}</h2>
                <p style="color:#4b5563;line-height:1.7;margin:0;">Vé này được gửi tới email <strong>{{ $gift->EmailNguoiNhan }}</strong>.</p>

                <div class="info-grid">
                    <div class="info-item"><span>Mã vé</span><strong>{{ $ticket->MaVeDienTu ?? '—' }}</strong></div>
                    <div class="info-item"><span>Hạng vé</span><strong>{{ $ticket->TenHangVe ?? '—' }}</strong></div>
                    <div class="info-item"><span>Khu vực</span><strong>{{ $ticket->TenKhuVuc ?? '—' }}</strong></div>
                    <div class="info-item"><span>Ghế</span><strong>{{ ($ticket && ($ticket->HangGhe || $ticket->SoGhe)) ? 'Ghế '.trim(($ticket->HangGhe ?? '').($ticket->SoGhe ?? '')) : 'Chưa gán ghế' }}</strong></div>
                    <div class="info-item"><span>Thời gian</span><strong>{{ $ticket && $ticket->ThoiGianBatDau ? \Carbon\Carbon::parse($ticket->ThoiGianBatDau)->format('d/m/Y H:i') : '—' }}</strong></div>
                    <div class="info-item"><span>Địa điểm</span><strong>{{ $ticket->TenDiaDiem ?? '—' }} {{ $ticket && $ticket->ThanhPho ? '- '.$ticket->ThanhPho : '' }}</strong></div>
                </div>

                @if($gift->LoiChuc)
                    <div class="info-item" style="margin-top:16px;">
                        <span>Lời nhắn</span>
                        <strong>{{ $gift->LoiChuc }}</strong>
                    </div>
                @endif

                @if($gift->TrangThai === 'DangChoNhan')
                    <form class="form-box" method="POST" action="{{ route('ticket-gifts.confirm', $gift->TokenNhanVe) }}">
                        @csrf
                        <label>Nhập đúng email người nhận để xác nhận</label>
                        <input type="email" name="EmailNguoiNhan" value="{{ old('EmailNguoiNhan', $gift->EmailNguoiNhan) }}" required>
                        @if($errors->any())
                            <div style="color:#b91c1c;margin-top:10px;line-height:1.6;">
                                @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
                            </div>
                        @endif
                        <button class="btn" type="submit">Xác nhận nhận vé</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
