@extends('admin.layouts.app')
@section('title', 'Chỉnh sửa tài khoản')

@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin.tai-khoan.show', $taiKhoan->MaTaiKhoan) }}" class="btn btn-sm btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i>Quay lại
    </a>
    <h4 class="mb-0 fw-bold"><i class="fas fa-edit me-2 text-warning"></i>Chỉnh sửa: {{ $taiKhoan->HoTen }}</h4>
</div>

<div class="card shadow-sm" style="max-width:750px;">
    <div class="card-body p-4">
        @if($errors->any())
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle me-2"></i><strong>Vui lòng kiểm tra lại:</strong>
                <ul class="mb-0 mt-2">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        <form action="{{ route('admin.tai-khoan.update', $taiKhoan->MaTaiKhoan) }}" method="POST">
            @csrf @method('PUT')

            <h6 class="fw-bold text-uppercase text-muted mb-3 border-bottom pb-2">
                <i class="fas fa-lock me-2"></i>Thông tin tài khoản
            </h6>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label">Tên đăng nhập</label>
                    <input type="text" class="form-control bg-light" value="{{ $taiKhoan->TenDangNhap }}" disabled>
                    <small class="text-muted">Không thể thay đổi</small>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Vai trò</label>
                    <input type="text" class="form-control bg-light" value="{{ $taiKhoan->VaiTro }}" disabled>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Mật khẩu mới</label>
                    <input type="password" name="MatKhau" class="form-control"
                           placeholder="Để trống nếu không đổi">
                    <small class="text-muted">Tối thiểu 8 ký tự</small>
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
                           value="{{ old('HoTen', $taiKhoan->HoTen) }}" required>
                    @error('HoTen')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" name="Email"
                           class="form-control @error('Email') is-invalid @enderror"
                           value="{{ old('Email', $taiKhoan->Email) }}" required>
                    @error('Email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Số điện thoại</label>
                    <input type="text" name="SoDienThoai" class="form-control"
                           value="{{ old('SoDienThoai', $taiKhoan->SoDienThoai) }}">
                </div>
                @php
                    $detail = $taiKhoan->VaiTro === 'KhachHang' ? $taiKhoan->khachHang : $taiKhoan->nhanVien;
                @endphp
                <div class="col-md-4">
                    <label class="form-label">Ngày sinh</label>
                    <input type="date" name="NgaySinh" class="form-control"
                           value="{{ old('NgaySinh', $detail?->NgaySinh?->format('Y-m-d')) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Giới tính</label>
                    <select name="GioiTinh" class="form-select">
                        <option value="">-- Chọn --</option>
                        @foreach(['Nam' => 'Nam', 'Nu' => 'Nữ', 'Khac' => 'Khác'] as $val => $label)
                        <option value="{{ $val }}" {{ old('GioiTinh', $detail?->GioiTinh) === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Địa chỉ</label>
                    <input type="text" name="DiaChi" class="form-control"
                           value="{{ old('DiaChi', $detail?->DiaChi) }}">
                </div>
            </div>

            @if($taiKhoan->VaiTro === 'NhanVien')
            <h6 class="fw-bold text-uppercase text-muted mb-3 border-bottom pb-2">
                <i class="fas fa-id-badge me-2"></i>Thông tin nhân viên
            </h6>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label">Chức vụ <span class="text-danger">*</span></label>
                    <input type="text" name="ChucVu"
                           class="form-control @error('ChucVu') is-invalid @enderror"
                           value="{{ old('ChucVu', $taiKhoan->nhanVien?->ChucVu) }}" required>
                    @error('ChucVu')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Ngày vào làm</label>
                    <input type="date" name="NgayVaoLam" class="form-control"
                           value="{{ old('NgayVaoLam', $taiKhoan->nhanVien?->NgayVaoLam?->format('Y-m-d')) }}">
                </div>
            </div>
            @endif

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="fas fa-save me-2"></i>Lưu thay đổi
                </button>
                <a href="{{ route('admin.tai-khoan.show', $taiKhoan->MaTaiKhoan) }}" class="btn btn-outline-secondary px-4">Hủy</a>
            </div>
        </form>
    </div>
</div>
@endsection
