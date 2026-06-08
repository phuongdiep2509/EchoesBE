@extends('layouts.app')

@section('title', 'Giỏ hàng | Echoes')

@section('content')

{{-- Breadcrumb --}}
<div style="margin-top:100px;background:var(--color-green,#46462a)">
    <div style="max-width:1200px;margin:0 auto;padding:12px 20px;
                font-size:0.78rem;letter-spacing:1px;text-transform:uppercase;
                display:flex;align-items:center;gap:8px">
        <a href="{{ url('/') }}" style="color:rgba(255,255,255,.7);text-decoration:none">TRANG CHỦ</a>
        <span style="color:rgba(255,255,255,.3)">/</span>
        <span style="color:white;font-weight:600">GIỎ HÀNG</span>
    </div>
</div>

<div style="max-width:1100px;margin:40px auto 80px;padding:0 20px">

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
                Vé được giữ trong <strong style="color:var(--color-red,#74070d)">15 phút</strong>.
                Vui lòng thanh toán trước khi hết thời gian.
            </p>

            @if(!$cart || $cart['ChiTiet']->isEmpty())
                <div style="background:#fff;border-radius:16px;padding:60px 40px;text-align:center;
                            border:2px dashed var(--color-beige,#e1cfac)">
                    <div style="font-size:3rem;margin-bottom:16px">🎫</div>
                    <h3 style="color:var(--color-green,#46462a);margin-bottom:10px">Giỏ hàng trống</h3>
                    <p style="color:#999;margin-bottom:24px">
                        Bạn chưa chọn vé nào. Hãy khám phá các sự kiện và thêm vào giỏ hàng.
                    </p>
                    <a href="{{ url('/concert') }}"
                       style="display:inline-block;background:var(--color-green,#46462a);color:white;
                              padding:12px 28px;border-radius:8px;font-weight:700;text-decoration:none">
                        Xem sự kiện Concert
                    </a>
                    &nbsp;
                    <a href="{{ url('/music') }}"
                       style="display:inline-block;background:var(--color-red,#74070d);color:white;
                              padding:12px 28px;border-radius:8px;font-weight:700;text-decoration:none;margin-top:8px">
                        Xem Nhạc sống
                    </a>
                </div>
            @else
                {{-- Countdown timer --}}
                @php
                    $expire = \Carbon\Carbon::parse($cart['ThoiGianHetHan']);
                    $secondsLeft = max(0, now()->diffInSeconds($expire, false));
                @endphp
                <div style="background:rgba(116,7,13,.06);border:1px solid rgba(116,7,13,.15);
                            border-radius:10px;padding:12px 18px;margin-bottom:20px;
                            display:flex;align-items:center;gap:10px">
                    <span style="font-size:1.2rem">⏰</span>
                    <span style="font-size:0.875rem;color:#555">
                        Giỏ hàng hết hạn lúc
                        <strong style="color:var(--color-red,#74070d)">{{ $expire->format('H:i:s') }}</strong>
                        — còn lại:
                        <strong id="countdown" style="color:var(--color-red,#74070d)">{{ gmdate('i:s', $secondsLeft) }}</strong>
                    </span>
                </div>

                {{-- Items --}}
                <div style="background:#fff;border-radius:16px;overflow:hidden;
                            border:1px solid rgba(0,0,0,.07)">
                    <table style="width:100%;border-collapse:collapse">
                        <thead>
                            <tr style="background:var(--color-green,#46462a)">
                                <th style="padding:14px 20px;text-align:left;color:white;
                                           font-size:0.78rem;letter-spacing:0.8px;text-transform:uppercase">
                                    Sự kiện
                                </th>
                                <th style="padding:14px 16px;text-align:left;color:white;
                                           font-size:0.78rem;letter-spacing:0.8px;text-transform:uppercase">
                                    Hạng vé
                                </th>
                                <th style="padding:14px 16px;text-align:center;color:white;
                                           font-size:0.78rem;letter-spacing:0.8px;text-transform:uppercase">
                                    SL
                                </th>
                                <th style="padding:14px 16px;text-align:right;color:white;
                                           font-size:0.78rem;letter-spacing:0.8px;text-transform:uppercase">
                                    Đơn giá
                                </th>
                                <th style="padding:14px 20px;text-align:right;color:white;
                                           font-size:0.78rem;letter-spacing:0.8px;text-transform:uppercase">
                                    Thành tiền
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cart['ChiTiet'] as $item)
                            <tr style="border-bottom:1px solid rgba(0,0,0,.05)">
                                <td style="padding:16px 20px;font-weight:600;color:#222;font-size:0.9rem">
                                    {{ $item->TenSuKien }}
                                </td>
                                <td style="padding:16px;color:#555;font-size:0.875rem">
                                    {{ $item->TenHangVe }}
                                </td>
                                <td style="padding:16px;text-align:center;font-weight:600">
                                    {{ $item->SoLuong }}
                                </td>
                                <td style="padding:16px;text-align:right;color:#555;font-size:0.875rem">
                                    {{ number_format($item->GiaVe, 0, ',', '.') }}₫
                                </td>
                                <td style="padding:16px 20px;text-align:right;
                                           font-weight:700;color:var(--color-red,#74070d)">
                                    {{ number_format($item->ThanhTien, 0, ',', '.') }}₫
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        {{-- ─── RIGHT: Summary ─── --}}
        <div style="position:sticky;top:calc(100px + 20px)">
            <div style="background:#fff;border-radius:16px;overflow:hidden;
                        border:2px solid var(--color-green,#46462a)">

                <div style="background:var(--color-green,#46462a);padding:16px 24px;
                            color:white;font-weight:700;font-size:1rem;
                            letter-spacing:0.5px;text-transform:uppercase">
                    THÔNG TIN ĐƠN HÀNG
                </div>

                <div style="padding:24px">
                    <div style="display:flex;justify-content:space-between;
                                margin-bottom:12px;font-size:0.9rem;color:#555">
                        <span>Số loại vé</span>
                        <span>{{ $cart ? $cart['ChiTiet']->count() : 0 }} loại</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;
                                margin-bottom:20px;font-size:0.9rem;color:#555">
                        <span>Tổng số vé</span>
                        <span>{{ $cart ? $cart['ChiTiet']->sum('SoLuong') : 0 }} vé</span>
                    </div>

                    <div style="border-top:2px solid var(--color-beige,#e1cfac);
                                padding-top:16px;margin-bottom:20px;
                                display:flex;justify-content:space-between;align-items:center">
                        <span style="font-weight:700;font-size:1rem;color:var(--color-green,#46462a)">
                            TỔNG TIỀN
                        </span>
                        <span style="font-size:1.4rem;font-weight:900;color:var(--color-red,#74070d)">
                            {{ $cart ? number_format($cart['TongTien'], 0, ',', '.') . '₫' : '0₫' }}
                        </span>
                    </div>

                    @if($cart && !$cart['ChiTiet']->isEmpty())
                        <form method="POST" action="{{ route('orders.create') }}">
                            @csrf
                            <button type="submit"
                                    style="width:100%;padding:15px;
                                           background:linear-gradient(135deg,#a00a12,#74070d);
                                           color:white;font-size:1rem;font-weight:800;
                                           border:none;border-radius:8px;cursor:pointer;
                                           letter-spacing:1px;text-transform:uppercase;
                                           box-shadow:0 6px 20px rgba(116,7,13,.3);
                                           transition:opacity .2s,transform .2s"
                                    onmouseover="this.style.opacity='.9';this.style.transform='translateY(-2px)'"
                                    onmouseout="this.style.opacity='1';this.style.transform=''">
                                🎫 ĐẶT VÉ NGAY
                            </button>
                        </form>
                    @endif

                    <div style="margin-top:16px">
                        <a href="{{ url('/concert') }}"
                           style="display:block;text-align:center;color:var(--color-green,#46462a);
                                  font-size:0.875rem;font-weight:600;padding:10px;
                                  border:2px solid var(--color-green,#46462a);border-radius:8px;
                                  text-decoration:none;transition:all .2s"
                           onmouseover="this.style.background='var(--color-green,#46462a)';this.style.color='white'"
                           onmouseout="this.style.background='';this.style.color='var(--color-green,#46462a)'">
                            Tiếp tục mua vé
                        </a>
                    </div>

                    <div style="margin-top:16px;padding:12px;background:rgba(116,7,13,.04);
                                border-radius:8px;font-size:0.78rem;color:#666;line-height:1.8">
                        <p style="font-weight:700;color:var(--color-red,#74070d);margin-bottom:4px">
                            ⚠️ Lưu ý:
                        </p>
                        <ul style="list-style:none;padding:0;margin:0">
                            <li>• Vé được giữ 15 phút</li>
                            <li>• Vé đã mua không hoàn trả</li>
                            <li>• Kiểm tra kỹ trước khi đặt</li>
                        </ul>
                    </div>
                </div>

            </div>

            <div style="margin-top:16px;text-align:center">
                <a href="{{ route('my-ticket') }}"
                   style="color:var(--color-green,#46462a);font-size:0.875rem;
                          font-weight:600;text-decoration:none">
                    📋 Xem lịch sử đặt vé →
                </a>
            </div>
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
