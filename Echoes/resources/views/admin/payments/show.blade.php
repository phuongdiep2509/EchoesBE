@extends('admin.layouts.app')

@section('title', 'Chi tiết giao dịch')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">Chi tiết giao dịch #{{ $payment->MaThanhToan }}</h2>
        <p class="text-muted mb-0">Mã giao dịch: {{ $payment->MaGiaoDich ?? 'Chưa có' }}</p>
    </div>
    <a href="{{ route('admin.payments.index') }}" class="btn btn-outline-secondary">Quay lại</a>
</div>

<div class="row g-4">
    <div class="col-lg-5">
        <div class="card table-card p-4">
            <h5>Thông tin thanh toán</h5>
            <dl class="row mt-3">
                <dt class="col-5">Đơn hàng</dt><dd class="col-7">#{{ $payment->MaDonHang }}</dd>
                <dt class="col-5">Số tiền</dt><dd class="col-7 fw-bold">{{ number_format($payment->SoTien, 0, ',', '.') }} đ</dd>
                <dt class="col-5">Phương thức</dt><dd class="col-7">{{ $payment->PhuongThucThanhToan }}</dd>
                <dt class="col-5">Trạng thái</dt><dd class="col-7"><span class="badge bg-{{ $payment->TrangThai === 'ThanhCong' ? 'success' : ($payment->TrangThai === 'ThatBai' ? 'danger' : 'warning') }}">{{ $payment->TrangThai }}</span></dd>
                <dt class="col-5">Thời gian</dt><dd class="col-7">{{ $payment->ThoiGianThanhToan ? $payment->ThoiGianThanhToan->format('d/m/Y H:i') : '—' }}</dd>
            </dl>
            <div class="d-flex gap-2 mt-3">
                @if($payment->TrangThai !== 'ThanhCong')
                    <form method="POST" action="{{ route('admin.payments.markSuccess', $payment->MaThanhToan) }}">@csrf<button class="btn btn-success">Xác nhận thành công</button></form>
                @endif
                @if($payment->TrangThai !== 'ThatBai')
                    <form method="POST" action="{{ route('admin.payments.markFailed', $payment->MaThanhToan) }}">@csrf<button class="btn btn-outline-danger">Đánh dấu thất bại</button></form>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card table-card p-4">
            <h5>Khách hàng & vé trong đơn</h5>
            @php($customer = optional(optional($payment->donHang)->khachHang)->taiKhoan)
            <p class="mb-1"><strong>{{ $customer->HoTen ?? 'Chưa rõ' }}</strong></p>
            <p class="text-muted">{{ $customer->Email ?? '' }}</p>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead><tr><th>Mã vé</th><th>Sự kiện</th><th>Hạng vé</th><th>Trạng thái</th></tr></thead>
                    <tbody>
                    @forelse(optional($payment->donHang)->ve ?? [] as $ticket)
                        <tr>
                            <td>{{ $ticket->MaVeDienTu }}</td>
                            <td>{{ optional($ticket->suKien)->TenSuKien }}</td>
                            <td>{{ optional($ticket->hangVe)->TenHangVe }}</td>
                            <td>{{ $ticket->TrangThai }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted">Đơn hàng chưa có vé. Phần đặt vé cần sinh vé sau khi tạo đơn.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
