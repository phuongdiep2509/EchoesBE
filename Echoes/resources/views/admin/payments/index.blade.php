@extends('admin.layouts.app')

@section('title', 'Quản lý giao dịch thanh toán')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">Giao dịch thanh toán</h2>
        <p class="text-muted mb-0">Theo dõi giao dịch, trạng thái thanh toán và đối soát đơn hàng.</p>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3"><div class="card stat-card p-3"><span class="text-muted small">Tổng giao dịch</span><strong class="fs-4">{{ number_format($summary['total']) }}</strong></div></div>
    <div class="col-md-3"><div class="card stat-card p-3"><span class="text-muted small">Thành công</span><strong class="fs-4 text-success">{{ number_format($summary['success']) }}</strong></div></div>
    <div class="col-md-3"><div class="card stat-card p-3"><span class="text-muted small">Chờ thanh toán</span><strong class="fs-4 text-warning">{{ number_format($summary['pending']) }}</strong></div></div>
    <div class="col-md-3"><div class="card stat-card p-3"><span class="text-muted small">Doanh thu</span><strong class="fs-4">{{ number_format($summary['revenue'], 0, ',', '.') }} đ</strong></div></div>
</div>

<div class="card table-card p-3 mb-4">
    <form class="row g-2" method="GET">
        <div class="col-md-3"><input name="keyword" value="{{ request('keyword') }}" class="form-control" placeholder="Mã GD, mã đơn, email..."></div>
        <div class="col-md-2">
            <select name="status" class="form-select">
                <option value="">Tất cả trạng thái</option>
                <option value="ChoThanhToan" @selected(request('status')==='ChoThanhToan')>Chờ thanh toán</option>
                <option value="ThanhCong" @selected(request('status')==='ThanhCong')>Thành công</option>
                <option value="ThatBai" @selected(request('status')==='ThatBai')>Thất bại</option>
            </select>
        </div>
        <div class="col-md-2"><input type="date" name="from" value="{{ request('from') }}" class="form-control"></div>
        <div class="col-md-2"><input type="date" name="to" value="{{ request('to') }}" class="form-control"></div>
        <div class="col-md-3 d-flex gap-2">
            <button class="btn btn-primary"><i class="fas fa-filter me-1"></i> Lọc</button>
            <a href="{{ route('admin.payments.index') }}" class="btn btn-outline-secondary">Reset</a>
        </div>
    </form>
</div>

<div class="card table-card p-3">
    <div class="table-responsive">
        <table class="table align-middle">
            <thead><tr><th>Mã TT</th><th>Mã GD</th><th>Đơn</th><th>Khách hàng</th><th>Số tiền</th><th>Phương thức</th><th>Trạng thái</th><th>Thời gian</th><th></th></tr></thead>
            <tbody>
            @forelse($payments as $p)
                <tr>
                    <td>#{{ $p->MaThanhToan }}</td>
                    <td>{{ $p->MaGiaoDich ?? '—' }}</td>
                    <td>#{{ $p->MaDonHang }}<br><small class="text-muted">{{ $p->TrangThaiDonHang }}</small></td>
                    <td>{{ $p->HoTen ?? 'Chưa rõ' }}<br><small class="text-muted">{{ $p->Email }}</small></td>
                    <td>{{ number_format($p->SoTien, 0, ',', '.') }} đ</td>
                    <td>{{ $p->PhuongThucThanhToan }}</td>
                    <td><span class="badge bg-{{ $p->TrangThai === 'ThanhCong' ? 'success' : ($p->TrangThai === 'ThatBai' ? 'danger' : 'warning') }}">{{ $p->TrangThai }}</span></td>
                    <td>{{ $p->ThoiGianThanhToan ? \Carbon\Carbon::parse($p->ThoiGianThanhToan)->format('d/m/Y H:i') : '—' }}</td>
                    <td><a class="btn btn-sm btn-outline-primary" href="{{ route('admin.payments.show', $p->MaThanhToan) }}">Chi tiết</a></td>
                </tr>
            @empty
                <tr><td colspan="9" class="text-center text-muted">Không có giao dịch phù hợp.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    {{ $payments->links() }}
</div>
@endsection
