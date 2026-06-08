@extends('admin.layouts.app')
@section('title', 'Thêm danh mục sự kiện')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0 fw-bold"><i class="fas fa-plus-circle me-2 text-primary"></i>Thêm danh mục sự kiện</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.loai-su-kien.index') }}" class="text-decoration-none">Danh mục sự kiện</a></li>
                <li class="breadcrumb-item active" aria-current="page">Thêm mới</li>
            </ol>
        </nav>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <form action="{{ route('admin.loai-su-kien.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="TenLoai" class="form-label fw-bold">Tên danh mục <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('TenLoai') is-invalid @enderror" id="TenLoai" name="TenLoai" value="{{ old('TenLoai') }}" placeholder="Nhập tên danh mục (vd: Liveshow, Nhạc kịch...)" required>
                @error('TenLoai')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-end mt-4">
                <a href="{{ route('admin.loai-su-kien.index') }}" class="btn btn-outline-secondary me-2">
                    <i class="fas fa-times me-1"></i>Hủy
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i>Lưu danh mục
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
