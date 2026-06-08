@extends('admin.layouts.app')

@section('title', 'Quan ly don hang')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <strong>Don dat ve</strong>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Ma don</th>
                        <th>Khach hang</th>
                        <th>Ngay dat</th>
                        <th>So ve</th>
                        <th>Tong tien</th>
                        <th>Trang thai</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($orders as $order)
                    @php
                        $statusLabels = [
                            'ChoThanhToan' => 'Cho thanh toan',
                            'DaThanhToan' => 'Da thanh toan',
                            'DaHuy' => 'Da huy',
                        ];
                        $statusClasses = [
                            'ChoThanhToan' => 'warning',
                            'DaThanhToan' => 'success',
                            'DaHuy' => 'danger',
                        ];
                    @endphp
                    <tr>
                        <td>#{{ $order->MaDonHang }}</td>
                        <td>
                            {{ $order->HoTen ?? ('KH #' . $order->MaKhachHang) }}<br>
                            <small class="text-muted">{{ $order->Email }}</small>
                        </td>
                        <td>{{ $order->NgayDat }}</td>
                        <td>{{ $order->SoLuongVe }}</td>
                        <td>{{ number_format($order->TongTien, 0, ',', '.') }}d</td>
                        <td>
                            <span class="badge bg-{{ $statusClasses[$order->TrangThai] ?? 'secondary' }}">
                                {{ $statusLabels[$order->TrangThai] ?? $order->TrangThai }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted">Chua co don dat ve.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
