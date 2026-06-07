@extends('admin.layouts.app')

@section('title', 'Báo cáo vé')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><h2 class="mb-1">Báo cáo vé & vận hành</h2><p class="text-muted mb-0">Thống kê số vé bán ra, vé đã tặng, sự kiện và khách hàng.</p></div>
</div>

<div class="card table-card p-3 mb-4">
    <form class="row g-2" method="GET">
        <div class="col-md-3"><label class="form-label">Từ ngày</label><input type="date" name="from" class="form-control" value="{{ $summary['from'] }}"></div>
        <div class="col-md-3"><label class="form-label">Đến ngày</label><input type="date" name="to" class="form-control" value="{{ $summary['to'] }}"></div>
        <div class="col-md-3 d-flex align-items-end gap-2"><button class="btn btn-primary">Lọc báo cáo</button><a href="{{ route('admin.reports.tickets') }}" class="btn btn-outline-secondary">Reset</a></div>
    </form>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-2"><div class="card stat-card p-3"><span class="text-muted small">Vé đã bán</span><strong class="fs-4">{{ number_format($summary['soldTickets']) }}</strong></div></div>
    <div class="col-md-2"><div class="card stat-card p-3"><span class="text-muted small">Vé đã dùng</span><strong class="fs-4">{{ number_format($summary['usedTickets']) }}</strong></div></div>
    <div class="col-md-2"><div class="card stat-card p-3"><span class="text-muted small">Vé đã hủy</span><strong class="fs-4">{{ number_format($summary['cancelledTickets']) }}</strong></div></div>
    <div class="col-md-2"><div class="card stat-card p-3"><span class="text-muted small">Vé đã tặng</span><strong class="fs-4">{{ number_format($summary['giftedTickets']) }}</strong></div></div>
    <div class="col-md-2"><div class="card stat-card p-3"><span class="text-muted small">Sự kiện</span><strong class="fs-4">{{ number_format($summary['eventCount']) }}</strong></div></div>
    <div class="col-md-2"><div class="card stat-card p-3"><span class="text-muted small">Khách hàng</span><strong class="fs-4">{{ number_format($summary['customerCount']) }}</strong></div></div>
</div>

<div class="row g-4">
    <div class="col-lg-6"><div class="card table-card p-3"><h5>Vé bán theo sự kiện</h5><canvas id="ticketEventChart" height="170"></canvas></div></div>
    <div class="col-lg-6"><div class="card table-card p-3"><h5>Trạng thái vé</h5><canvas id="ticketStatusChart" height="170"></canvas></div></div>
</div>

<div class="row g-4 mt-1">
    <div class="col-lg-6">
        <div class="card table-card p-3"><h5>Chi tiết vé bán theo sự kiện</h5>
            <table class="table"><thead><tr><th>Sự kiện</th><th>Số vé bán</th></tr></thead><tbody>
            @forelse($ticketByEvent as $row)<tr><td>{{ $row->TenSuKien }}</td><td>{{ number_format($row->so_ve) }}</td></tr>@empty<tr><td colspan="2" class="text-center text-muted">Chưa có dữ liệu.</td></tr>@endforelse
            </tbody></table>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card table-card p-3"><h5>Vé tặng theo sự kiện</h5>
            <table class="table"><thead><tr><th>Sự kiện</th><th>Số vé tặng</th></tr></thead><tbody>
            @forelse($giftedByEvent as $row)<tr><td>{{ $row->TenSuKien }}</td><td>{{ number_format($row->so_ve_tang) }}</td></tr>@empty<tr><td colspan="2" class="text-center text-muted">Chưa có dữ liệu.</td></tr>@endforelse
            </tbody></table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
new Chart(document.getElementById('ticketEventChart'), {
    type: 'bar',
    data: { labels: @json($ticketByEvent->pluck('TenSuKien')), datasets: [{ label: 'Số vé bán', data: @json($ticketByEvent->pluck('so_ve')) }] },
    options: { responsive: true, scales: { y: { beginAtZero: true } } }
});
new Chart(document.getElementById('ticketStatusChart'), {
    type: 'doughnut',
    data: { labels: @json($ticketByStatus->pluck('TrangThai')), datasets: [{ data: @json($ticketByStatus->pluck('so_luong')) }] },
    options: { responsive: true }
});
</script>
@endsection
