@extends('admin.layouts.app')
@section('title', 'Chỉnh sửa nhân viên')

@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin.nhan-vien.show', $nhanVien->MaNhanVien) }}" class="btn btn-sm btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i>Quay lại
    </a>
    <h4 class="mb-0 fw-bold">
        <i class="fas fa-edit me-2 text-warning"></i>
        Chỉnh sửa: {{ $nhanVien->taiKhoan?->HoTen }}
    </h4>
</div>

<div class="card shadow-sm" style="max-width:700px;">
    <div class="card-body p-4">
        @if($errors->any())
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle me-2"></i><strong>Vui lòng kiểm tra lại:</strong>
                <ul class="mb-0 mt-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        <form action="{{ route('admin.nhan-vien.update', $nhanVien->MaNhanVien) }}" method="POST">
            @csrf @method('PUT')

            {{-- Thông tin đăng nhập --}}
            <h6 class="fw-bold text-uppercase text-muted mb-3 border-bottom pb-2">
                <i class="fas fa-lock me-2"></i>Thông tin đăng nhập
            </h6>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label">Tên đăng nhập</label>
                    <input type="text" class="form-control bg-light"
                           value="{{ $nhanVien->taiKhoan?->TenDangNhap }}" disabled>
                    <small class="text-muted">Không thể thay đổi</small>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Mật khẩu mới</label>
                    <input type="password" name="MatKhau" class="form-control"
                           placeholder="Để trống nếu không đổi">
                    <small class="text-muted">Tối thiểu 8 ký tự</small>
                </div>
            </div>

            {{-- Thông tin cá nhân --}}
            <h6 class="fw-bold text-uppercase text-muted mb-3 border-bottom pb-2">
                <i class="fas fa-user me-2"></i>Thông tin cá nhân
            </h6>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label">Họ và tên <span class="text-danger">*</span></label>
                    <input type="text" name="HoTen"
                           class="form-control @error('HoTen') is-invalid @enderror"
                           value="{{ old('HoTen', $nhanVien->taiKhoan?->HoTen) }}" required>
                    @error('HoTen')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" name="Email"
                           class="form-control @error('Email') is-invalid @enderror"
                           value="{{ old('Email', $nhanVien->taiKhoan?->Email) }}" required>
                    @error('Email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Số điện thoại</label>
                    <input type="text" name="SoDienThoai" class="form-control"
                           value="{{ old('SoDienThoai', $nhanVien->taiKhoan?->SoDienThoai) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Ngày sinh</label>
                    <input type="date" name="NgaySinh" class="form-control"
                           value="{{ old('NgaySinh', $nhanVien->NgaySinh?->format('Y-m-d')) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Giới tính</label>
                    <select name="GioiTinh" class="form-select">
                        <option value="">-- Chọn --</option>
                        <option value="Nam"  {{ old('GioiTinh', $nhanVien->GioiTinh) === 'Nam'  ? 'selected' : '' }}>Nam</option>
                        <option value="Nu"   {{ old('GioiTinh', $nhanVien->GioiTinh) === 'Nu'   ? 'selected' : '' }}>Nữ</option>
                        <option value="Khac" {{ old('GioiTinh', $nhanVien->GioiTinh) === 'Khac' ? 'selected' : '' }}>Khác</option>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Địa chỉ</label>
                    <input type="text" name="DiaChi" class="form-control"
                           value="{{ old('DiaChi', $nhanVien->DiaChi) }}">
                </div>
            </div>

            {{-- Thông tin nhân viên --}}
            <h6 class="fw-bold text-uppercase text-muted mb-3 border-bottom pb-2">
                <i class="fas fa-id-badge me-2"></i>Thông tin nhân viên
            </h6>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label">Chức vụ <span class="text-danger">*</span></label>
                    <input type="text" name="ChucVu"
                           class="form-control @error('ChucVu') is-invalid @enderror"
                           value="{{ old('ChucVu', $nhanVien->ChucVu) }}"
                           placeholder="VD: Nhân viên bán vé" required>
                    @error('ChucVu')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Ngày vào làm</label>
                    <input type="date" name="NgayVaoLam" class="form-control"
                           value="{{ old('NgayVaoLam', $nhanVien->NgayVaoLam?->format('Y-m-d')) }}">
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="fas fa-save me-2"></i>Lưu thay đổi
                </button>
                <a href="{{ route('admin.nhan-vien.show', $nhanVien->MaNhanVien) }}"
                   class="btn btn-outline-secondary px-4">Hủy</a>
            </div>
        </form>
    </div>
</div>
@endsection
