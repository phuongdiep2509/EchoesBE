@extends('admin.layouts.app')
@section('title', 'Chi tiết danh mục sự kiện')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0 fw-bold"><i class="fas fa-info-circle me-2 text-primary"></i>Chi tiết danh mục sự kiện</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.loai-su-kien.index') }}" class="text-decoration-none">Danh mục sự kiện</a></li>
                <li class="breadcrumb-item active" aria-current="page">Chi tiết (#{{ $category->MaLoaiSuKien }})</li>
            </ol>
        </nav>
    </div>
    <div>
        <a href="{{ route('admin.loai-su-kien.edit', $category->MaLoaiSuKien) }}" class="btn btn-warning">
            <i class="fas fa-edit me-2"></i>Chỉnh sửa
        </a>
    </div>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold"><i class="fas fa-tag me-2 text-info"></i>Thông tin danh mục</h5>
    </div>
    <div class="card-body p-4">
        <div class="row mb-3">
            <div class="col-md-3 text-muted fw-bold">Mã danh mục:</div>
            <div class="col-md-9">#{{ $category->MaLoaiSuKien }}</div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3 text-muted fw-bold">Tên danh mục:</div>
            <div class="col-md-9 fs-5 text-primary fw-bold">{{ $category->TenLoai }}</div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3 text-muted fw-bold">Tổng số sự kiện:</div>
            <div class="col-md-9"><span class="badge bg-secondary">{{ $category->concerts->count() }}</span></div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold"><i class="fas fa-calendar-alt me-2 text-success"></i>Các sự kiện liên quan</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead style="background:#f8f9fa;">
                    <tr>
                        <th class="ps-3">Sự kiện</th>
                        <th>Địa điểm</th>
                        <th>Thời gian</th>
                        <th>Trạng thái</th>
                        <th class="text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($category->concerts as $concert)
                    <tr>
                        <td class="ps-3">
                            <div class="d-flex align-items-center">
                                @if($concert->AnhBia)
                                    <img src="{{ asset('storage/' . $concert->AnhBia) }}" alt="{{ $concert->TenSuKien }}" class="rounded me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                @else
                                    <div class="rounded me-3 bg-secondary d-flex align-items-center justify-content-center text-white" style="width: 50px; height: 50px;">
                                        <i class="fas fa-image"></i>
                                    </div>
                                @endif
                                <div>
                                    <h6 class="mb-0 fw-bold">{{ $concert->TenSuKien }}</h6>
                                    <small class="text-muted">#{{ $concert->MaSuKien }}</small>
                                </div>
                            </div>
                        </td>
                        <td>{{ $concert->diaDiem->TenDiaDiem ?? 'N/A' }}</td>
                        <td class="small">
                            <div><i class="far fa-calendar-check me-1 text-success"></i>{{ \Carbon\Carbon::parse($concert->ThoiGianBatDau)->format('d/m/Y H:i') }}</div>
                            <div><i class="far fa-calendar-times me-1 text-danger"></i>{{ \Carbon\Carbon::parse($concert->ThoiGianKetThuc)->format('d/m/Y H:i') }}</div>
                        </td>
                        <td>
                            @if($concert->TrangThaiHienTai === 'SapDienRa')
                                <span class="badge bg-info text-dark">Sắp diễn ra</span>
                            @elseif($concert->TrangThaiHienTai === 'DangDienRa')
                                <span class="badge bg-primary">Đang diễn ra</span>
                            @elseif($concert->TrangThaiHienTai === 'DaKetThuc')
                                <span class="badge bg-secondary">Đã kết thúc</span>
                            @elseif($concert->TrangThaiHienTai === 'DaHuy')
                                <span class="badge bg-danger">Đã hủy</span>
                            @else
                                <span class="badge bg-secondary">{{ $concert->TrangThaiHienTai }}</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.concerts.show', $concert->MaSuKien) }}" class="btn btn-sm btn-outline-info" title="Xem chi tiết">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-5">
                            <i class="fas fa-calendar-times fa-2x mb-2 d-block"></i>
                            Không có sự kiện nào thuộc danh mục này.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
