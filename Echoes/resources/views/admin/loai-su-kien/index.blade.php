@extends('admin.layouts.app')
@section('title', 'Quản lý danh mục sự kiện')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0 fw-bold"><i class="fas fa-list me-2 text-primary"></i>Quản lý danh mục sự kiện</h4>
        <small class="text-muted">Tổng: {{ count($categories) }} danh mục</small>
    </div>
    <div>
        <a href="{{ route('admin.loai-su-kien.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Thêm mới
        </a>
    </div>
</div>

{{-- Bộ lọc --}}
<div class="card shadow-sm mb-4">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('admin.loai-su-kien.index') }}" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label small mb-1">Tìm kiếm</label>
                <input type="text" name="search" class="form-control form-control-sm"
                       placeholder="Nhập tên danh mục..."
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-sm btn-primary w-100">
                    <i class="fas fa-search me-1"></i>Lọc
                </button>
            </div>
            @if(request('search'))
            <div class="col-md-2">
                <a href="{{ route('admin.loai-su-kien.index') }}" class="btn btn-sm btn-outline-secondary w-100">
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
                    <th class="ps-3" style="width: 15%">Mã danh mục</th>
                    <th>Tên danh mục</th>
                    <th class="text-center" style="width: 20%">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                <tr>
                    <td class="ps-3 text-muted small">#{{ $category->MaLoaiSuKien }}</td>
                    <td><strong>{{ $category->TenLoai }}</strong></td>
                    <td class="text-center">
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('admin.loai-su-kien.edit', $category->MaLoaiSuKien) }}"
                               class="btn btn-outline-warning" title="Chỉnh sửa">
                                <i class="fas fa-edit"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="text-center text-muted py-5">
                        <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                        Không tìm thấy danh mục nào.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
