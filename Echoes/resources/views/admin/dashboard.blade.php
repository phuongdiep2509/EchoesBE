@extends('admin.layouts.app')

@section('content')

<h2>Dashboard Echoes</h2>

<div class="row mt-4">

    <div class="col-md-4">
        <div class="card p-3">
            <h4>Concert</h4>
            <p>Quản lý sự kiện concert</p>
        </div>
    @endforeach
</div>

    <div class="col-md-4">
        <div class="card p-3">
            <h4>Music</h4>
            <p>Quản lý nhạc sống</p>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card p-3">
            <h4>News</h4>
            <p>Quản lý tin tức</p>
        </div>
    </div>

</div>

<div class="card table-card p-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Giao dịch gần đây</h5>
        <a href="{{ route('admin.payments.index') }}" class="btn btn-sm btn-outline-primary">Xem tất cả</a>
    </div>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
            <tr>
                <th>Mã GD</th><th>Đơn hàng</th><th>Khách hàng</th><th>Số tiền</th><th>Phương thức</th><th>Trạng thái</th><th>Thời gian</th>
            </tr>
            </thead>
            <tbody>
            @forelse($recentPayments as $p)
                <tr>
                    <td>{{ $p->MaGiaoDich ?? '—' }}</td>
                    <td>#{{ $p->MaDonHang }}</td>
                    <td>{{ $p->HoTen ?? 'Chưa rõ' }}<br><small class="text-muted">{{ $p->Email }}</small></td>
                    <td>{{ number_format($p->SoTien, 0, ',', '.') }} đ</td>
                    <td>{{ $p->PhuongThucThanhToan }}</td>
                    <td>
                        <span class="badge bg-{{ $p->TrangThai === 'ThanhCong' ? 'success' : ($p->TrangThai === 'ThatBai' ? 'danger' : 'warning') }}">
                            {{ $p->TrangThai }}
                        </span>
                    </td>
                    <td>{{ $p->ThoiGianThanhToan ? \Carbon\Carbon::parse($p->ThoiGianThanhToan)->format('d/m/Y H:i') : '—' }}</td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center text-muted">Chưa có giao dịch.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script>
const revenueLabels = @json($revenueByDay->pluck('ngay'));
const revenueData = @json($revenueByDay->pluck('doanh_thu'));
new Chart(document.getElementById('revenueChart'), {
    type: 'line',
    data: { labels: revenueLabels, datasets: [{ label: 'Doanh thu', data: revenueData, tension: .35 }] },
    options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
});
</script>
@endsection
