@extends('layouts.app')

@section('title', 'Lịch sử tặng vé | Echoes')

@section('styles')
<style>
    .history-page { background:#f5f1ea; min-height:100vh; padding:48px 20px 80px; }
    .history-wrap { width:min(1160px,100%); margin:0 auto; }
    .hero { background:#111827; color:#fff; border-radius:28px; padding:34px 38px; margin-bottom:24px; }
    .hero h1 { margin:0 0 8px; font-size:34px; }
    .hero p { margin:0; color:#d1d5db; line-height:1.6; }
    .card { background:#fff; border-radius:24px; overflow:hidden; box-shadow:0 18px 50px rgba(17,24,39,.08); border:1px solid rgba(17,24,39,.08); }
    table { width:100%; border-collapse:collapse; }
    th,td { padding:16px; border-bottom:1px solid #eee; text-align:left; vertical-align:top; }
    th { background:#111827; color:#fff; }
    .badge { border-radius:999px; padding:7px 11px; font-size:12px; border:1px solid transparent; display:inline-flex; }
    .ok { background:#e7f8ed; color:#166534; border-color:#bcebc9; }
    .wait { background:#fff8e6; color:#8a5b00; border-color:#ffe0a0; }
    .fail { background:#fff0f0; color:#b91c1c; border-color:#ffcaca; }
    .btn { border:0; border-radius:12px; padding:10px 13px; background:#f1ede5; cursor:pointer; color:#111827; }
    .empty { background:#fff; padding:34px; text-align:center; border-radius:24px; color:#4b5563; }
</style>
@endsection

@section('content')
<section class="history-page">
    <div class="history-wrap">
        <div class="hero">
            <h1>Lịch sử tặng vé</h1>
            <p>Theo dõi các lượt tặng vé, trạng thái người nhận và thời gian xác nhận.</p>
        </div>

        @if(session('success')) <div class="empty" style="margin-bottom:16px;color:#166534;">{{ session('success') }}</div> @endif
        @if(session('error')) <div class="empty" style="margin-bottom:16px;color:#b91c1c;">{{ session('error') }}</div> @endif

        @if(!empty($needLogin))
            <div class="empty">Bạn cần đăng nhập để xem lịch sử tặng vé.</div>
        @elseif($giftHistory->isEmpty())
            <div class="empty">Bạn chưa có lượt tặng vé nào.</div>
        @else
            <div class="card" style="overflow-x:auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Mã vé</th>
                            <th>Sự kiện</th>
                            <th>Người nhận</th>
                            <th>Trạng thái</th>
                            <th>Thời gian</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($giftHistory as $gift)
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
                                    @if($gift->ThoiGianTang) Tặng: {{ \Carbon\Carbon::parse($gift->ThoiGianTang)->format('d/m/Y H:i') }}<br>@endif
                                    @if($gift->ThoiGianNhan) Nhận: {{ \Carbon\Carbon::parse($gift->ThoiGianNhan)->format('d/m/Y H:i') }}@endif
                                </td>
                                <td>
                                    @if($gift->TrangThai === 'DangChoNhan')
                                        <form method="POST" action="{{ route('ticket-gifts.cancel', ['giftId' => $gift->MaVeTang, 'account_id' => $accountId]) }}" onsubmit="return confirm('Hủy lượt tặng vé này?')">
                                            @csrf
                                            <input type="hidden" name="account_id" value="{{ $accountId }}">
                                            <button class="btn" type="submit">Hủy tặng</button>
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
