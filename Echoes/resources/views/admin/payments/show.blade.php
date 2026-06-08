@extends('admin.layouts.app')

@section('title', 'Chi tiáº¿t giao dá»‹ch')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">Chi tiáº¿t giao dá»‹ch #{{ $payment->MaThanhToan }}</h2>
        <p class="text-muted mb-0">MÃ£ giao dá»‹ch: {{ $payment->MaGiaoDich ?? 'ChÆ°a cÃ³' }}</p>
    </div>
    <a href="{{ route('admin.payments.index') }}" class="btn btn-outline-secondary">Quay láº¡i</a>
</div>

<div class="row g-4">
    <div class="col-lg-5">
        <div class="card table-card p-4">
            <h5>ThÃ´ng tin thanh toÃ¡n</h5>
            <dl class="row mt-3">
                <dt class="col-5">ÄÆ¡n hÃ ng</dt><dd class="col-7">#{{ $payment->MaDonHang }}</dd>
                <dt class="col-5">Sá»‘ tiá»n</dt><dd class="col-7 fw-bold">{{ number_format($payment->SoTien, 0, ',', '.') }} Ä‘</dd>
                <dt class="col-5">PhÆ°Æ¡ng thá»©c</dt><dd class="col-7">{{ $payment->PhuongThucThanhToan }}</dd>
                <dt class="col-5">Tráº¡ng thÃ¡i</dt><dd class="col-7"><span class="badge bg-{{ $payment->TrangThai === 'ThanhCong' ? 'success' : ($payment->TrangThai === 'ThatBai' ? 'danger' : 'warning') }}">{{ $payment->TrangThai }}</span></dd>
                <dt class="col-5">Thá»i gian</dt><dd class="col-7">{{ $payment->ThoiGianThanhToan ? $payment->ThoiGianThanhToan->format('d/m/Y H:i') : 'â€”' }}</dd>
            </dl>
            <div class="alert alert-info mb-0">
                Trạng thái thanh toán chỉ được cập nhật tự động từ luồng thanh toán của hệ thống. Admin không xác nhận hoặc đánh dấu thất bại thủ công tại đây.
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card table-card p-4">
            <h5>KhÃ¡ch hÃ ng & vÃ© trong Ä‘Æ¡n</h5>
            @php($customer = optional(optional($payment->donHang)->khachHang)->taiKhoan)
            <p class="mb-1"><strong>{{ $customer->HoTen ?? 'ChÆ°a rÃµ' }}</strong></p>
            <p class="text-muted">{{ $customer->Email ?? '' }}</p>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead><tr><th>MÃ£ vÃ©</th><th>Sá»± kiá»‡n</th><th>Háº¡ng vÃ©</th><th>Tráº¡ng thÃ¡i</th></tr></thead>
                    <tbody>
                    @forelse(optional($payment->donHang)->ve ?? [] as $ticket)
                        <tr>
                            <td>{{ $ticket->MaVeDienTu }}</td>
                            <td>{{ optional($ticket->suKien)->TenSuKien }}</td>
                            <td>{{ optional($ticket->hangVe)->TenHangVe }}</td>
                            <td>{{ $ticket->TrangThai }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted">ÄÆ¡n hÃ ng chÆ°a cÃ³ vÃ©. Pháº§n Ä‘áº·t vÃ© cáº§n sinh vÃ© sau khi táº¡o Ä‘Æ¡n.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection