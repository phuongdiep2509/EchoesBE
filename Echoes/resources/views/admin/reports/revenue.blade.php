@extends('admin.layouts.app')

@section('title', 'Báo cáo doanh thu')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><h2 class="mb-1">Báo cáo doanh thu</h2><p class="text-muted mb-0">Thống kê doanh thu theo thời gian, phương thức thanh toán và sự kiện.</p></div>
    <a href="{{ route('admin.reports.revenue.export', request()->query()) }}" class="btn btn-outline-success"><i class="fas fa-file-csv me-1"></i> Xuất CSV</a>
</div>

<div class="card table-card p-3 mb-4">
    <form class="row g-2" method="GET">
        <div class="col-md-3"><label class="form-label">Từ ngày</label><input type="date" name="from" class="form-control" value="{{ $summary['from'] }}"></div>
        <div class="col-md-3"><label class="form-label">Đến ngày</label><input type="date" name="to" class="form-control" value="{{ $summary['to'] }}"></div>
        <div class="col-md-3 d-flex align-items-end gap-2"><button class="btn btn-primary">Lọc báo cáo</button><a href="{{ route('admin.reports.revenue') }}" class="btn btn-outline-secondary">Reset</a></div>
    </form>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4"><div class="card stat-card p-3"><span class="text-muted small">Tổng doanh thu</span><strong class="fs-3">{{ number_format($summary['totalRevenue'], 0, ',', '.') }} đ</strong></div></div>
    <div class="col-md-4"><div class="card stat-card p-3"><span class="text-muted small">Số giao dịch thành công</span><strong class="fs-3">{{ number_format($summary['transactionCount']) }}</strong></div></div>
    <div class="col-md-4"><div class="card stat-card p-3"><span class="text-muted small">Giá trị trung bình</span><strong class="fs-3">{{ number_format($summary['averageOrderValue'], 0, ',', '.') }} đ</strong></div></div>
</div>

<div class="row g-4">
    <div class="col-lg-8"><div class="card table-card p-3"><h5>Doanh thu theo ngày</h5><canvas id="dailyRevenueChart" height="130"></canvas></div></div>
    <div class="col-lg-4"><div class="card table-card p-3"><h5>Theo phương thức</h5><canvas id="methodChart" height="180"></canvas></div></div>
</div>

<div class="card table-card p-3 mt-4">
    <h5>Doanh thu ước tính theo sự kiện</h5>
    <p class="text-muted small">Doanh thu theo sự kiện được ước tính bằng số vé đã thanh toán nhân với giá hạng vé, tránh nhân trùng tổng tiền đơn hàng khi một đơn có nhiều vé.</p>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead><tr><th>Sự kiện</th><th>Số vé</th><th>Doanh thu ước tính</th></tr></thead>
            <tbody>
            @forelse($eventRevenue as $row)
                <tr><td>{{ $row->TenSuKien }}</td><td>{{ number_format($row->so_ve) }}</td><td>{{ number_format($row->doanh_thu_uoc_tinh, 0, ',', '.') }} đ</td></tr>
            @empty
                <tr><td colspan="3" class="text-center text-muted">Chưa có dữ liệu.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script>
new Chart(document.getElementById('dailyRevenueChart'), {
    type: 'bar',
    data: { labels: @json($dailyRevenue->pluck('ngay')), datasets: [{ label: 'Doanh thu', data: @json($dailyRevenue->pluck('doanh_thu')) }] },
    options: { responsive: true, scales: { y: { beginAtZero: true } } }
});
new Chart(document.getElementById('methodChart'), {
    type: 'doughnut',
    data: { labels: @json($paymentMethods->pluck('PhuongThucThanhToan')), datasets: [{ data: @json($paymentMethods->pluck('doanh_thu')) }] },
    options: { responsive: true }
});
</script>
@endsection
