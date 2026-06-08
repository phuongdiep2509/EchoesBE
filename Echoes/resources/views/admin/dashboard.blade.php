@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')

{{-- Page header --}}
<div class="admin-page-header">
    <h2><i class="fas fa-th-large me-2"></i>Dashboard</h2>
    <span style="font-size:0.8rem;opacity:0.7">{{ now()->format('d/m/Y') }}</span>
</div>

{{-- Stat cards --}}
<div class="row g-4 mb-4">

    <div class="col-sm-6 col-xl-3">
        <div class="echoes-stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-number">{{ number_format($stats['totalRevenue'] ?? 0, 0, ',', '.') }}₫</div>
                    <div class="stat-label">Tổng doanh thu</div>
                </div>
                <i class="fas fa-coins stat-icon"></i>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="echoes-stat-card red">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-number red">{{ number_format($stats['ticketsSold'] ?? 0) }}</div>
                    <div class="stat-label">Vé đã bán</div>
                </div>
                <i class="fas fa-ticket-alt stat-icon"></i>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="echoes-stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-number">{{ number_format($stats['customers'] ?? 0) }}</div>
                    <div class="stat-label">Khách hàng</div>
                </div>
                <i class="fas fa-users stat-icon"></i>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="echoes-stat-card beige">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-number">{{ number_format($stats['events'] ?? 0) }}</div>
                    <div class="stat-label">Sự kiện</div>
                </div>
                <i class="fas fa-calendar-alt stat-icon"></i>
            </div>
        </div>
    </div>

</div>

{{-- Revenue chart + top events --}}
<div class="row g-4 mb-4">

    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">Doanh thu 7 ngày qua</div>
            <div class="card-body">
                <canvas id="revenueChart" height="100"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header">Top sự kiện bán vé</div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @forelse($topEvents ?? [] as $ev)
                        <li class="list-group-item d-flex justify-content-between align-items-center"
                            style="font-family:var(--font);font-size:0.875rem">
                            <span style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:200px">
                                {{ $ev->TenSuKien }}
                            </span>
                            <span class="badge"
                                  style="background:var(--echoes-red,#74070d);color:white;border-radius:999px">
                                {{ $ev->so_ve }} vé
                            </span>
                        </li>
                    @empty
                        <li class="list-group-item text-muted text-center" style="font-family:var(--font)">
                            Chưa có dữ liệu
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

</div>

{{-- Payment stats row --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div style="background:#d4edda;border-radius:10px;padding:16px 20px;border-left:4px solid #155724">
            <div style="font-size:1.5rem;font-weight:800;color:#155724">{{ $stats['successPayments'] ?? 0 }}</div>
            <div style="font-size:0.78rem;color:#155724;font-weight:600;text-transform:uppercase;letter-spacing:.8px">
                Giao dịch thành công
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div style="background:#fff3cd;border-radius:10px;padding:16px 20px;border-left:4px solid #856404">
            <div style="font-size:1.5rem;font-weight:800;color:#856404">{{ $stats['pendingPayments'] ?? 0 }}</div>
            <div style="font-size:0.78rem;color:#856404;font-weight:600;text-transform:uppercase;letter-spacing:.8px">
                Chờ thanh toán
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div style="background:#f8d7da;border-radius:10px;padding:16px 20px;border-left:4px solid #721c24">
            <div style="font-size:1.5rem;font-weight:800;color:#721c24">{{ $stats['failedPayments'] ?? 0 }}</div>
            <div style="font-size:0.78rem;color:#721c24;font-weight:600;text-transform:uppercase;letter-spacing:.8px">
                Giao dịch thất bại
            </div>
        </div>
    </div>
</div>

{{-- Recent payments table --}}
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>Giao dịch gần đây</span>
        <a href="{{ route('admin.payments.index') }}" class="btn btn-sm btn-outline-primary">Xem tất cả</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Mã GD</th>
                        <th>Đơn hàng</th>
                        <th>Khách hàng</th>
                        <th>Số tiền</th>
                        <th>Phương thức</th>
                        <th>Trạng thái</th>
                        <th>Thời gian</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentPayments as $p)
                        <tr>
                            <td style="font-family:monospace;font-size:0.8rem">
                                {{ $p->MaGiaoDich ? \Illuminate\Support\Str::limit($p->MaGiaoDich, 12) : '—' }}
                            </td>
                            <td>#{{ $p->MaDonHang }}</td>
                            <td>
                                {{ $p->HoTen ?? 'Chưa rõ' }}
                                <br><small class="text-muted">{{ $p->Email ?? '' }}</small>
                            </td>
                            <td style="font-weight:600;color:var(--echoes-red,#74070d)">
                                {{ number_format($p->SoTien, 0, ',', '.') }}₫
                            </td>
                            <td>{{ $p->PhuongThucThanhToan }}</td>
                            <td>
                                @php
                                    $bg = match($p->TrangThai) {
                                        'ThanhCong'    => 'success',
                                        'ThatBai'      => 'danger',
                                        'ChoThanhToan' => 'warning',
                                        default        => 'secondary',
                                    };
                                    $label = match($p->TrangThai) {
                                        'ThanhCong'    => 'Thành công',
                                        'ThatBai'      => 'Thất bại',
                                        'ChoThanhToan' => 'Chờ TT',
                                        default        => $p->TrangThai,
                                    };
                                @endphp
                                <span class="badge bg-{{ $bg }}">{{ $label }}</span>
                            </td>
                            <td style="font-size:0.8rem;color:#888">
                                {{ $p->ThoiGianThanhToan
                                    ? \Carbon\Carbon::parse($p->ThoiGianThanhToan)->format('d/m/Y H:i')
                                    : '—' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Chưa có giao dịch nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const revenueLabels = @json(($revenueByDay ?? collect())->pluck('ngay'));
const revenueData   = @json(($revenueByDay ?? collect())->pluck('doanh_thu'));

new Chart(document.getElementById('revenueChart'), {
    type: 'line',
    data: {
        labels: revenueLabels,
        datasets: [{
            label: 'Doanh thu (₫)',
            data: revenueData,
            tension: 0.35,
            borderColor: '#46462a',
            backgroundColor: 'rgba(70,70,42,.08)',
            fill: true,
            pointBackgroundColor: '#74070d',
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { callback: v => v.toLocaleString('vi-VN') + '₫' }
            }
        }
    }
});
</script>
@endsection
