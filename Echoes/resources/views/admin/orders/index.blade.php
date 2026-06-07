@extends('admin.layouts.app')

@section('title', 'Quản lý đơn hàng')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <strong>Đơn đặt vé</strong>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Mã đơn</th>
                        <th>Khách hàng</th>
                        <th>Ngày đặt</th>
                        <th>Số vé</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>Cập nhật</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td>#{{ $order->MaDonHang }}</td>
                        <td>
                            {{ $order->HoTen ?? ('KH #' . $order->MaKhachHang) }}<br>
                            <small class="text-muted">{{ $order->Email }}</small>
                        </td>
                        <td>{{ $order->NgayDat }}</td>
                        <td>{{ $order->SoLuongVe }}</td>
                        <td>{{ number_format($order->TongTien, 0, ',', '.') }}đ</td>
                        <td>{{ $order->TrangThai }}</td>
                        <td>
                            <form class="d-flex gap-2" method="POST" action="{{ route('admin.orders.status', $order->MaDonHang) }}">
                                @csrf
                                @method('PATCH')
                                <select class="form-select form-select-sm" name="TrangThai">
                                    <option value="ChoThanhToan" @selected($order->TrangThai === 'ChoThanhToan')>Chờ thanh toán</option>
                                    <option value="DaThanhToan" @selected($order->TrangThai === 'DaThanhToan')>Đã thanh toán</option>
                                    <option value="DaHuy" @selected($order->TrangThai === 'DaHuy')>Đã hủy</option>
                                </select>
                                <button class="btn btn-sm btn-primary">Lưu</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted">Chưa có đơn đặt vé.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
