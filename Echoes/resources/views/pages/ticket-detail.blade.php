@extends('layouts.app')

@section('title', 'Chi tiết vé | Echoes')

@section('styles')
<style>
    .detail-page { background:#f5f1ea; min-height:100vh; padding:48px 20px 80px; }
    .detail-wrap { width:min(1080px,100%); margin:0 auto; }
    .detail-card { background:#fff; border-radius:28px; overflow:hidden; box-shadow:0 18px 50px rgba(17,24,39,.08); border:1px solid rgba(17,24,39,.08); }
    .detail-grid { display:grid; grid-template-columns:380px 1fr; }
    .detail-img { width:100%; height:100%; min-height:420px; object-fit:cover; background:#e6ded1; }
    .detail-body { padding:34px; }
    .detail-body h1 { margin:0 0 14px; font-size:34px; line-height:1.18; color:#111827; }
    .meta-grid { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:14px; margin:22px 0; }
    .meta-item { background:#f7f3ec; border-radius:16px; padding:14px 15px; }
    .meta-item span { display:block; color:#777; font-size:12px; text-transform:uppercase; letter-spacing:.8px; margin-bottom:6px; }
    .meta-item strong { display:block; font-size:15px; color:#111827; line-height:1.4; }
    .qr-box { background:#111827; color:#fff; border-radius:18px; padding:18px; margin-top:20px; word-break:break-word; }
    .badge { display:inline-flex; border-radius:999px; padding:7px 11px; font-size:12px; line-height:1; border:1px solid transparent; margin-right:6px; }
    .badge.ok { background:#e7f8ed; color:#166534; border-color:#bcebc9; }
    .badge.wait { background:#fff8e6; color:#8a5b00; border-color:#ffe0a0; }
    .badge.fail { background:#fff0f0; color:#b91c1c; border-color:#ffcaca; }
    .btn-back { display:inline-flex; margin-top:22px; text-decoration:none; background:#74070d; color:#fff; border-radius:14px; padding:12px 16px; }
    @media(max-width:840px){ .detail-grid{grid-template-columns:1fr}.detail-img{min-height:260px}.meta-grid{grid-template-columns:1fr}.detail-body h1{font-size:28px} }
</style>
@endsection

@section('content')
@php
    $image = !empty($ticket->AnhBia)
        ? (\Illuminate\Support\Str::startsWith($ticket->AnhBia, ['http://','https://']) ? $ticket->AnhBia : asset($ticket->AnhBia))
        : asset('assets/images/index/logo (no back).png');
@endphp
<section class="detail-page">
    <div class="detail-wrap">
        <div class="detail-card">
            <div class="detail-grid">
                <img class="detail-img" src="{{ $image }}" alt="{{ $ticket->TenSuKien }}">
                <div class="detail-body">
                    <h1>{{ $ticket->TenSuKien }}</h1>

                    @if($ticket->TrangThai === 'ChoSuDung') <span class="badge ok">Vé chờ sử dụng</span>
                    @elseif($ticket->TrangThai === 'DaSuDung') <span class="badge fail">Vé đã sử dụng</span>
                    @else <span class="badge fail">Vé đã hủy</span>
                    @endif

                    @if($gift)
                        @if($gift->TrangThai === 'DangChoNhan') <span class="badge wait">Tặng vé - chờ nhận</span>
                        @elseif($gift->TrangThai === 'DaNhan') <span class="badge ok">Tặng vé - đã nhận</span>
                        @elseif($gift->TrangThai === 'DaHuy') <span class="badge fail">Lượt tặng đã hủy</span>
                        @endif
                    @endif

                    <div class="meta-grid">
                        <div class="meta-item"><span>Mã vé điện tử</span><strong>{{ $ticket->MaVeDienTu }}</strong></div>
                        <div class="meta-item"><span>Hạng vé</span><strong>{{ $ticket->TenHangVe }}</strong></div>
                        <div class="meta-item"><span>Khu vực</span><strong>{{ $ticket->TenKhuVuc ?? '—' }}</strong></div>
                        <div class="meta-item"><span>Ghế</span><strong>{{ ($ticket->HangGhe || $ticket->SoGhe) ? 'Ghế '.trim(($ticket->HangGhe ?? '').($ticket->SoGhe ?? '')) : 'Chưa gán ghế' }}</strong></div>
                        <div class="meta-item"><span>Thời gian</span><strong>{{ \Carbon\Carbon::parse($ticket->ThoiGianBatDau)->format('d/m/Y H:i') }}</strong></div>
                        <div class="meta-item"><span>Địa điểm</span><strong>{{ $ticket->TenDiaDiem ?? '—' }} {{ $ticket->ThanhPho ? '- '.$ticket->ThanhPho : '' }}</strong></div>
                    </div>

                    <div class="qr-box">
                        <strong>Mã QR / chuỗi kiểm soát vé</strong><br>
                        {{ $ticket->MaQR }}
                    </div>

                    @if($gift)
                        <div class="meta-item" style="margin-top:18px;">
                            <span>Thông tin tặng vé gần nhất</span>
                            <strong>{{ $gift->TenNguoiNhan }} - {{ $gift->EmailNguoiNhan }}</strong>
                            @if($gift->LoiChuc)
                                <div style="margin-top:8px;color:#4b5563;line-height:1.6;">{{ $gift->LoiChuc }}</div>
                            @endif
                        </div>
                    @endif

                    <a class="btn-back" href="{{ route('my-ticket.index', ['account_id' => $accountId]) }}">Quay lại Vé của tôi</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
