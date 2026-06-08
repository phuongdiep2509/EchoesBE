@extends('admin.layouts.app')
@section('title', 'Chi tiết hạng vé')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0 fw-bold"><i class="fas fa-info-circle me-2 text-primary"></i>Chi tiết hạng vé</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.hang-ve.index') }}" class="text-decoration-none">Hạng vé</a></li>
                <li class="breadcrumb-item active" aria-current="page">Chi tiết (#{{ $ticket->MaHangVe }})</li>
            </ol>
        </nav>
    </div>
    <div>
        <a href="{{ route('admin.hang-ve.edit', $ticket->MaHangVe) }}" class="btn btn-warning">
            <i class="fas fa-edit me-2"></i>Chỉnh sửa
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold"><i class="fas fa-ticket-alt me-2 text-info"></i>Thông tin vé</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tbody>
                        <tr>
                            <td class="text-muted fw-bold" style="width: 35%;">Mã vé:</td>
                            <td class="fw-bold">#{{ $ticket->MaHangVe }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-bold">Tên hạng vé:</td>
                            <td><span class="badge bg-primary fs-6">{{ $ticket->TenHangVe }}</span></td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-bold">Khu vực:</td>
                            <td>{{ $ticket->khuVuc->TenKhuVuc ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-bold">Sự kiện:</td>
                            <td><strong>{{ $ticket->khuVuc->concert->TenSuKien ?? 'N/A' }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-bold">Giá vé:</td>
                            <td class="text-danger fw-bold fs-5">{{ number_format($ticket->GiaVe, 0, ',', '.') }} đ</td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-bold">Trạng thái:</td>
                            <td>
                                @if($ticket->TrangThaiHienTai === 'SapMoBan')
                                    <span class="badge bg-info text-dark">Sắp mở bán</span>
                                @elseif($ticket->TrangThaiHienTai === 'DangMoBan')
                                    <span class="badge bg-success">Đang mở bán</span>
                                @elseif($ticket->TrangThaiHienTai === 'HetVe')
                                    <span class="badge bg-danger">Hết vé</span>
                                @elseif($ticket->TrangThaiHienTai === 'DaKetThuc')
                                    <span class="badge bg-secondary">Đã kết thúc</span>
                                @elseif($ticket->TrangThaiHienTai === 'TamDung')
                                    <span class="badge bg-warning text-dark">Tạm dừng</span>
                                @elseif($ticket->TrangThaiHienTai === 'DaHuy')
                                    <span class="badge bg-dark">Đã hủy</span>
                                @else
                                    <span class="badge bg-secondary">{{ $ticket->TrangThaiHienTai }}</span>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold"><i class="fas fa-chart-pie me-2 text-info"></i>Thống kê bán hàng</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tbody>
                        <tr>
                            <td class="text-muted fw-bold" style="width: 35%;">Tổng số vé:</td>
                            <td class="fw-bold fs-5">{{ $ticket->SoLuongMoBan }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-bold">Đã bán:</td>
                            <td class="text-success fw-bold fs-5">{{ $ticket->SoLuongDaBan }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-bold">Còn lại:</td>
                            <td class="text-warning fw-bold fs-5">{{ max(0, $ticket->SoLuongMoBan - $ticket->SoLuongDaBan) }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-bold">Tiến độ:</td>
                            <td>
                                @php
                                    $percent = $ticket->SoLuongMoBan > 0 ? min(100, round(($ticket->SoLuongDaBan / $ticket->SoLuongMoBan) * 100)) : 0;
                                @endphp
                                <div class="progress mt-2" style="height: 20px;">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $percent }}%;" aria-valuenow="{{ $percent }}" aria-valuemin="0" aria-valuemax="100">{{ $percent }}%</div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-bold mt-3">Thời gian bán:</td>
                            <td>
                                <div><i class="far fa-calendar-check me-1 text-success"></i>Từ: {{ $ticket->ThoiGianMoBan ? \Carbon\Carbon::parse($ticket->ThoiGianMoBan)->format('d/m/Y H:i') : 'N/A' }}</div>
                                <div class="mt-1"><i class="far fa-calendar-times me-1 text-danger"></i>Đến: {{ $ticket->ThoiGianKetThucBan ? \Carbon\Carbon::parse($ticket->ThoiGianKetThucBan)->format('d/m/Y H:i') : 'N/A' }}</div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @if($ticket->QuyenLoi)
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold"><i class="fas fa-gift me-2 text-info"></i>Quyền lợi đi kèm</h5>
            </div>
            <div class="card-body bg-light rounded m-3">
                {!! nl2br(e($ticket->QuyenLoi)) !!}
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
