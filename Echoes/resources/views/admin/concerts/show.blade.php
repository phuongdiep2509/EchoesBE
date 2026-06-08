@extends('admin.layouts.app')
@section('title', 'Chi tiết Sự kiện')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0 fw-bold"><i class="fas fa-calendar-alt me-2 text-primary"></i>Chi tiết Sự kiện</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.concerts.index') }}" class="text-decoration-none">Sự kiện</a></li>
                <li class="breadcrumb-item active" aria-current="page">Chi tiết (#{{ $concert->MaSuKien }})</li>
            </ol>
        </nav>
    </div>
    <div>
        <a href="{{ route('admin.concerts.edit', $concert->MaSuKien) }}" class="btn btn-warning">
            <i class="fas fa-edit me-2"></i>Chỉnh sửa
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold"><i class="fas fa-image me-2 text-info"></i>Ảnh bìa</h5>
            </div>
            <div class="card-body text-center">
                @if($concert->AnhBia)
                    <img src="{{ asset('storage/' . $concert->AnhBia) }}" alt="Ảnh bìa" class="img-fluid rounded shadow-sm">
                @else
                    <div class="bg-light p-5 rounded text-muted">
                        <i class="fas fa-image fa-3x mb-2"></i>
                        <p class="mb-0">Chưa có ảnh bìa</p>
                    </div>
                @endif
                <div class="mt-4">
                    <h5 class="fw-bold text-primary">{{ $concert->TenSuKien }}</h5>
                    <div class="badge bg-secondary mb-2">{{ $loaiSuKien->TenLoai ?? 'N/A' }}</div>
                    <div>
                        @if($concert->TrangThaiHienTai === 'SapDienRa')
                            <span class="badge bg-info text-dark fs-6"><i class="fas fa-calendar-plus me-1"></i>Sắp diễn ra</span>
                        @elseif($concert->TrangThaiHienTai === 'DangMoBan')
                            <span class="badge bg-success fs-6"><i class="fas fa-ticket-alt me-1"></i>Đang mở bán</span>
                        @elseif($concert->TrangThaiHienTai === 'DangDienRa')
                            <span class="badge bg-primary fs-6"><i class="fas fa-play-circle me-1"></i>Đang diễn ra</span>
                        @elseif($concert->TrangThaiHienTai === 'DaKetThuc')
                            <span class="badge bg-secondary fs-6"><i class="fas fa-calendar-check me-1"></i>Đã kết thúc</span>
                        @elseif($concert->TrangThaiHienTai === 'DaHuy')
                            <span class="badge bg-danger fs-6"><i class="fas fa-ban me-1"></i>Đã hủy</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold"><i class="fas fa-info-circle me-2 text-info"></i>Thông tin chi tiết</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tbody>
                        <tr>
                            <td class="text-muted fw-bold" style="width: 30%;"><i class="fas fa-building me-2"></i>Ban tổ chức:</td>
                            <td class="fw-bold">{{ $banToChuc->TenToChuc ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-bold"><i class="fas fa-map-marker-alt me-2"></i>Địa điểm:</td>
                            <td>{{ $diaDiem->TenDiaDiem ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-bold"><i class="far fa-calendar-check me-2 text-success"></i>Bắt đầu:</td>
                            <td>{{ \Carbon\Carbon::parse($concert->ThoiGianBatDau)->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-bold"><i class="far fa-calendar-times me-2 text-danger"></i>Kết thúc:</td>
                            <td>{{ \Carbon\Carbon::parse($concert->ThoiGianKetThuc)->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-bold" colspan="2"><i class="fas fa-align-left me-2"></i>Mô tả:</td>
                        </tr>
                        <tr>
                            <td colspan="2" class="bg-light p-3 rounded">{!! nl2br(e($concert->MoTa)) !!}</td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-bold" colspan="2"><i class="fas fa-star me-2 text-warning"></i>Điểm nổi bật:</td>
                        </tr>
                        <tr>
                            <td colspan="2" class="bg-light p-3 rounded">{!! nl2br(e($concert->DiemNoiBat)) !!}</td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-bold" colspan="2"><i class="fas fa-file-contract me-2"></i>Điều kiện và Điều khoản:</td>
                        </tr>
                        <tr>
                            <td colspan="2" class="bg-light p-3 rounded">{!! nl2br(e($concert->DieuKienVaDieuKhoan)) !!}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
