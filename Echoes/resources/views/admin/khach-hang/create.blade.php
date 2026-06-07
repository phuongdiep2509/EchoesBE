@extends('admin.layouts.app')
@section('title', 'Thêm khách hàng')

@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin.khach-hang.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i>Quay lại
    </a>
    <h4 class="mb-0 fw-bold"><i class="fas fa-user-plus me-2 text-success"></i>Thêm khách hàng mới</h4>
</div>

<div class="card shadow-sm" style="max-width:700px;">
    <div class="card-body p-4">
        @if($errors->any())
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle me-2"></i><strong>Lỗi:</strong>
                <ul class="mb-0 mt-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        <form action="{{ route('admin.khach-hang.store') }}" method="POST">
            @csrf

            <h6 class="fw-bold text-uppercase text-muted mb-3 border-bottom pb-2">
                <i class="fas fa-lock me-2"></i>Thông tin đăng nhập
            </h6>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label">Tên đăng nhập <span class="text-danger">*</span></label>
                    <input type="text" name="TenDangNhap"
                           class="form-control @error('TenDangNhap') is-invalid @enderror"
                           value="{{ old('TenDangNhap') }}" placeholder="Chỉ chữ, số, dấu _" required>
                    @error('TenDangNhap')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Mật khẩu <span class="text-danger">*</span></label>
                    <input type="password" name="MatKhau"
                           class="form-control @error('MatKhau') is-invalid @enderror"
                           placeholder="Tối thiểu 8 ký tự" required>
                    @error('MatKhau')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <h6 class="fw-bold text-uppercase text-muted mb-3 border-bottom pb-2">
                <i class="fas fa-user me-2"></i>Thông tin cá nhân
            </h6>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label">Họ và tên <span class="text-danger">*</span></label>
                    <input type="text" name="HoTen"
                           class="form-control @error('HoTen') is-invalid @enderror"
                           value="{{ old('HoTen') }}" required>
                    @error('HoTen')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" name="Email"
                           class="form-control @error('Email') is-invalid @enderror"
                           value="{{ old('Email') }}" required>
                    @error('Email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Số điện thoại</label>
                    <input type="text" name="SoDienThoai" class="form-control"
                           value="{{ old('SoDienThoai') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Ngày sinh</label>
                    <input type="date" name="NgaySinh" class="form-control"
                           value="{{ old('NgaySinh') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Giới tính</label>
                    <select name="GioiTinh" class="form-select">
                        <option value="">-- Chọn --</option>
                        <option value="Nam"  {{ old('GioiTinh') === 'Nam'  ? 'selected' : '' }}>Nam</option>
                        <option value="Nu"   {{ old('GioiTinh') === 'Nu'   ? 'selected' : '' }}>Nữ</option>
                        <option value="Khac" {{ old('GioiTinh') === 'Khac' ? 'selected' : '' }}>Khác</option>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Địa chỉ</label>
                    <input type="text" name="DiaChi" class="form-control"
                           value="{{ old('DiaChi') }}" placeholder="Địa chỉ cụ thể">
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success px-4">
                    <i class="fas fa-save me-2"></i>Thêm khách hàng
                </button>
                <a href="{{ route('admin.khach-hang.index') }}" class="btn btn-outline-secondary px-4">Hủy</a>
            </div>
        </form>
    </div>
</div>
@endsection
