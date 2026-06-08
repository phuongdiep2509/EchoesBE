@extends('layouts.app')

@section('title', 'Vé của tôi | Echoes')

@section('content')

{{-- Breadcrumb --}}
<div style="margin-top:100px;background:var(--color-green,#46462a)">
    <div style="max-width:1200px;margin:0 auto;padding:12px 20px;
                font-size:0.78rem;letter-spacing:1px;text-transform:uppercase;
                display:flex;align-items:center;gap:8px">
        <a href="{{ url('/') }}" style="color:rgba(255,255,255,.7);text-decoration:none">TRANG CHỦ</a>
        <span style="color:rgba(255,255,255,.3)">/</span>
        <span style="color:white;font-weight:600">VÉ CỦA TÔI</span>
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

    {{-- Header --}}
    <div style="display:flex;justify-content:space-between;align-items:center;
                flex-wrap:wrap;gap:12px;margin-bottom:28px">
        <div>
            <h1 style="font-size:1.75rem;font-weight:900;color:var(--color-green,#46462a);margin-bottom:4px">
                Vé của tôi
            </h1>
            <p style="color:#888;font-size:0.875rem">Lịch sử đặt vé của bạn</p>
        </div>
        <a href="{{ route('cart') }}"
           style="display:inline-flex;align-items:center;gap:8px;
                  background:var(--color-green,#46462a);color:white;
                  padding:10px 20px;border-radius:8px;font-weight:700;
                  font-size:0.875rem;text-decoration:none">
            🛒 Giỏ hàng
        </a>
    </div>

    @if($orders->isEmpty())
        <div style="background:#fff;border-radius:16px;padding:60px 40px;text-align:center;
                    border:2px dashed var(--color-beige,#e1cfac)">
            <div style="font-size:3.5rem;margin-bottom:16px">🎟️</div>
            <h3 style="color:var(--color-green,#46462a);margin-bottom:10px">Chưa có đơn đặt vé</h3>
            <p style="color:#999;margin-bottom:24px;max-width:360px;margin-left:auto;margin-right:auto">
                Bạn chưa có đơn đặt vé nào. Hãy khám phá các sự kiện và đặt vé ngay!
            </p>
            <a href="{{ url('/concert') }}"
               style="display:inline-block;background:var(--color-green,#46462a);color:white;
                      padding:12px 28px;border-radius:8px;font-weight:700;text-decoration:none">
                Xem sự kiện ngay
            </a>
        </div>
    @else
        <div style="background:#fff;border-radius:16px;overflow:hidden;
                    border:1px solid rgba(0,0,0,.07)">
            <table style="width:100%;border-collapse:collapse">
                <thead>
                    <tr style="background:var(--color-green,#46462a)">
                        <th style="padding:14px 20px;text-align:left;color:white;
                                   font-size:0.78rem;letter-spacing:.8px;text-transform:uppercase">
                            Mã đơn
                        </th>
                        <th style="padding:14px 16px;text-align:left;color:white;
                                   font-size:0.78rem;letter-spacing:.8px;text-transform:uppercase">
                            Ngày đặt
                        </th>
                        <th style="padding:14px 16px;text-align:center;color:white;
                                   font-size:0.78rem;letter-spacing:.8px;text-transform:uppercase">
                            Số vé
                        </th>
                        <th style="padding:14px 16px;text-align:right;color:white;
                                   font-size:0.78rem;letter-spacing:.8px;text-transform:uppercase">
                            Tổng tiền
                        </th>
                        <th style="padding:14px 16px;text-align:center;color:white;
                                   font-size:0.78rem;letter-spacing:.8px;text-transform:uppercase">
                            Trạng thái
                        </th>
                        <th style="padding:14px 20px;text-align:center;color:white;
                                   font-size:0.78rem;letter-spacing:.8px;text-transform:uppercase">
                            Thao tác
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    @php
                        $statusMap = [
                            'ChoThanhToan' => ['label' => 'Chờ thanh toán', 'bg' => '#fff3cd', 'color' => '#856404'],
                            'DaThanhToan'  => ['label' => 'Đã thanh toán',  'bg' => '#d4edda', 'color' => '#155724'],
                            'DaHuy'        => ['label' => 'Đã hủy',          'bg' => '#f8d7da', 'color' => '#721c24'],
                        ];
                        $st = $statusMap[$order->TrangThai] ?? ['label' => $order->TrangThai, 'bg' => '#f0efeb', 'color' => '#555'];
                    @endphp
                    <tr style="border-bottom:1px solid rgba(0,0,0,.05);
                               transition:background .15s"
                        onmouseover="this.style.background='rgba(70,70,42,.03)'"
                        onmouseout="this.style.background=''">
                        <td style="padding:16px 20px;font-weight:700;color:var(--color-green,#46462a)">
                            #{{ $order->MaDonHang }}
                        </td>
                        <td style="padding:16px;color:#555;font-size:0.875rem">
                            {{ \Carbon\Carbon::parse($order->NgayDat)->format('d/m/Y H:i') }}
                        </td>
                        <td style="padding:16px;text-align:center;font-weight:600">
                            {{ $order->SoLuongVe ?? 0 }}
                        </td>
                        <td style="padding:16px;text-align:right;
                                   font-weight:700;color:var(--color-red,#74070d)">
                            {{ number_format($order->TongTien, 0, ',', '.') }}₫
                        </td>
                        <td style="padding:16px;text-align:center">
                            <span style="background:{{ $st['bg'] }};color:{{ $st['color'] }};
                                         font-size:0.75rem;font-weight:700;padding:5px 12px;
                                         border-radius:999px;letter-spacing:.5px">
                                {{ $st['label'] }}
                            </span>
                        </td>
                        <td style="padding:16px 20px;text-align:center">
                            @if($order->TrangThai === 'ChoThanhToan')
                                <form method="POST"
                                      action="{{ route('orders.cancel', $order->MaDonHang) }}"
                                      onsubmit="return confirm('Hủy đơn #{{ $order->MaDonHang }}?')">
                                    @csrf
                                    <button type="submit"
                                            style="background:none;border:2px solid var(--color-red,#74070d);
                                                   color:var(--color-red,#74070d);padding:6px 14px;
                                                   border-radius:6px;font-size:0.78rem;font-weight:700;
                                                   cursor:pointer;transition:all .15s"
                                            onmouseover="this.style.background='var(--color-red,#74070d)';this.style.color='white'"
                                            onmouseout="this.style.background='none';this.style.color='var(--color-red,#74070d)'">
                                        Hủy đơn
                                    </button>
                                </form>
                            @else
                                <span style="font-size:0.8rem;color:#bbb">—</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="margin-top:20px;text-align:center">
            <a href="{{ url('/concert') }}"
               style="color:var(--color-green,#46462a);font-size:0.875rem;font-weight:600;text-decoration:none">
                🎫 Đặt thêm vé →
            </a>
        </div>
    @endif

</div>

@endsection
