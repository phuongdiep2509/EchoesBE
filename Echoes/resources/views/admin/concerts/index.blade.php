@extends('admin.layouts.app')
@section('title', 'Quản lý sự kiện (Concert)')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0 fw-bold"><i class="fas fa-calendar-alt me-2 text-primary"></i>Quản lý sự kiện (Concert)</h4>
        <small class="text-muted">Tổng: {{ count($concerts) }} sự kiện</small>
    </div>
    <div>
        <a href="{{ route('admin.concerts.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Thêm mới
        </a>
    </div>
</div>

{{-- Bộ lọc --}}
<div class="card shadow-sm mb-4">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('admin.concerts.index') }}" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label small mb-1">Tìm kiếm sự kiện</label>
                <input type="text" name="search" class="form-control form-control-sm"
                       placeholder="Nhập tên sự kiện..."
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-sm btn-primary w-100">
                    <i class="fas fa-search me-1"></i>Lọc
                </button>
            </div>
            @if(request('search'))
            <div class="col-md-2">
                <a href="{{ route('admin.concerts.index') }}" class="btn btn-sm btn-outline-secondary w-100">
                    <i class="fas fa-times me-1"></i>Xóa lọc
                </a>
            </div>
            @endif
        </form>
    </div>
</div>

{{-- Hiển thị thông báo --}}
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead style="background:#1a1a2e; color:#fff;">
                <tr>
                    <th class="ps-3">Mã SK</th>
                    <th>Tên sự kiện</th>
                    <th>Thời gian bắt đầu</th>
                    <th>Thời gian kết thúc</th>
                    <th>Trạng thái</th>
                    <th class="text-center">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($concerts as $concert)
                <tr>
                    <td class="ps-3 text-muted small">#{{ $concert->MaSuKien }}</td>
                    <td>
                        <div class="d-flex align-items-center">
                            @if($concert->AnhBia)
                                <img src="{{ asset($concert->AnhBia) }}" alt="{{ $concert->TenSuKien }}" class="rounded me-2" style="width: 40px; height: 40px; object-fit: cover;">
                            @else
                                <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center text-muted" style="width: 40px; height: 40px;">
                                    <i class="fas fa-image"></i>
                                </div>
                            @endif
                            <strong>{{ $concert->TenSuKien }}</strong>
                        </div>
                    </td>
                    <td class="small">{{ \Carbon\Carbon::parse($concert->ThoiGianBatDau)->format('d/m/Y H:i') }}</td>
                    <td class="small">{{ \Carbon\Carbon::parse($concert->ThoiGianKetThuc)->format('d/m/Y H:i') }}</td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-sm p-0 border-0 bg-transparent text-decoration-none dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                @if($concert->TrangThaiHienTai === 'SapDienRa')
                                    <span class="badge bg-info text-dark"><i class="fas fa-calendar-plus me-1"></i>Sắp diễn ra</span>
                                @elseif($concert->TrangThaiHienTai === 'DangMoBan')
                                    <span class="badge bg-success"><i class="fas fa-ticket-alt me-1"></i>Đang mở bán</span>
                                @elseif($concert->TrangThaiHienTai === 'DangDienRa')
                                    <span class="badge bg-primary"><i class="fas fa-play-circle me-1"></i>Đang diễn ra</span>
                                @elseif($concert->TrangThaiHienTai === 'DaKetThuc')
                                    <span class="badge bg-secondary"><i class="fas fa-calendar-check me-1"></i>Đã kết thúc</span>
                                @elseif($concert->TrangThaiHienTai === 'DaHuy')
                                    <span class="badge bg-danger"><i class="fas fa-ban me-1"></i>Đã hủy</span>
                                @else
                                    <span class="badge bg-secondary">{{ $concert->TrangThaiHienTai }}</span>
                                @endif
                            </button>
                            <ul class="dropdown-menu shadow-sm" style="font-size: 0.875rem;">
                                <li>
                                    <form action="{{ route('admin.concerts.updateStatus', $concert->MaSuKien) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="TrangThai" value="SapDienRa">
                                        <button type="submit" class="dropdown-item"><i class="fas fa-calendar-plus me-2 text-info"></i>Sắp diễn ra</button>
                                    </form>
                                </li>
                                <li>
                                    <form action="{{ route('admin.concerts.updateStatus', $concert->MaSuKien) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="TrangThai" value="DangMoBan">
                                        <button type="submit" class="dropdown-item"><i class="fas fa-ticket-alt me-2 text-success"></i>Đang mở bán</button>
                                    </form>
                                </li>
                                <li>
                                    <form action="{{ route('admin.concerts.updateStatus', $concert->MaSuKien) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="TrangThai" value="DaKetThuc">
                                        <button type="submit" class="dropdown-item"><i class="fas fa-calendar-check me-2 text-secondary"></i>Đã kết thúc</button>
                                    </form>
                                </li>
                                <li>
                                    <form action="{{ route('admin.concerts.updateStatus', $concert->MaSuKien) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="TrangThai" value="DaHuy">
                                        <button type="submit" class="dropdown-item text-danger"><i class="fas fa-ban me-2"></i>Đã hủy</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </td>
                    <td class="text-center">
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('admin.concerts.show', $concert->MaSuKien) }}"
                               class="btn btn-outline-info" title="Xem chi tiết">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.concerts.edit', $concert->MaSuKien) }}"
                               class="btn btn-outline-warning" title="Chỉnh sửa">
                                <i class="fas fa-edit"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-5">
                        <i class="fas fa-calendar-alt fa-2x mb-2 d-block"></i>
                        Không tìm thấy sự kiện nào.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
