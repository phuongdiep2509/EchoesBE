@extends('admin.layouts.app')

@section('title', 'Dashboard quản trị')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">Dashboard Echoes</h2>
        <p class="text-muted mb-0">Tổng quan doanh thu, giao dịch, vé bán ra, vé tặng, sự kiện và khách hàng.</p>
    </div>
    <a href="{{ route('admin.reports.revenue') }}" class="btn btn-primary"><i class="fas fa-chart-line me-1"></i> Xem báo cáo</a>
</div>

<div class="row g-3 mb-4">
    @php
        $cards = [
            ['label' => 'Tổng doanh thu', 'value' => number_format($stats['totalRevenue'], 0, ',', '.') . ' đ', 'icon' => 'fa-sack-dollar'],
            ['label' => 'Doanh thu hôm nay', 'value' => number_format($stats['todayRevenue'], 0, ',', '.') . ' đ', 'icon' => 'fa-calendar-day'],
            ['label' => 'Vé đã bán', 'value' => number_format($stats['ticketsSold']), 'icon' => 'fa-ticket-alt'],
            ['label' => 'Vé đã được tặng', 'value' => number_format($stats['giftedTickets']), 'icon' => 'fa-gift'],
            ['label' => 'Sự kiện', 'value' => number_format($stats['events']), 'icon' => 'fa-calendar-alt'],
            ['label' => 'Khách hàng', 'value' => number_format($stats['customers']), 'icon' => 'fa-users'],
            ['label' => 'Giao dịch chờ', 'value' => number_format($stats['pendingPayments']), 'icon' => 'fa-hourglass-half'],
            ['label' => 'Giao dịch thất bại', 'value' => number_format($stats['failedPayments']), 'icon' => 'fa-triangle-exclamation'],
        ];
    @endphp
    @foreach($cards as $card)
        <div class="col-md-3">
            <div class="card stat-card p-3 h-100">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-muted small">{{ $card['label'] }}</div>
                        <div class="fs-4 fw-bold mt-1">{{ $card['value'] }}</div>
                    </div>
                    <div class="stat-icon"><i class="fas {{ $card['icon'] }}"></i></div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="card table-card p-3 h-100">
            <h5 class="mb-3">Doanh thu 7 ngày gần nhất</h5>
            <canvas id="revenueChart" height="110"></canvas>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card table-card p-3 h-100">
            <h5 class="mb-3">Top sự kiện bán vé</h5>
            @forelse($topEvents as $event)
                <div class="d-flex justify-content-between border-bottom py-2">
                    <span class="text-truncate pe-2">{{ $event->TenSuKien }}</span>
                    <strong>{{ $event->so_ve }} vé</strong>
                </div>
            @empty
                <p class="text-muted mb-0">Chưa có dữ liệu vé bán.</p>
            @endforelse
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
