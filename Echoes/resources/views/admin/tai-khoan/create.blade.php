@extends('admin.layouts.app')
@section('title', 'Thêm tài khoản')

@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin.tai-khoan.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i>Quay lại
    </a>
    <h4 class="mb-0 fw-bold"><i class="fas fa-user-plus me-2 text-primary"></i>Thêm tài khoản mới</h4>
</div>

<div class="card shadow-sm" style="max-width:750px;">
    <div class="card-body p-4">
        @if($errors->any())
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle me-2"></i>
                <strong>Vui lòng kiểm tra lại:</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.tai-khoan.store') }}" method="POST" id="createForm">
            @csrf

            {{-- Thông tin tài khoản --}}
            <h6 class="fw-bold text-uppercase text-muted mb-3 border-bottom pb-2">
                <i class="fas fa-lock me-2"></i>Thông tin tài khoản
            </h6>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label">Tên đăng nhập <span class="text-danger">*</span></label>
                    <input type="text" name="TenDangNhap"
                           class="form-control @error('TenDangNhap') is-invalid @enderror"
                           value="{{ old('TenDangNhap') }}"
                           placeholder="Chỉ chữ cái, số, dấu _" required>
                    @error('TenDangNhap')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Vai trò <span class="text-danger">*</span></label>
                    <select name="VaiTro" id="VaiTro"
                            class="form-select @error('VaiTro') is-invalid @enderror" required>
                        <option value="">-- Chọn vai trò --</option>
                        <option value="Admin"     {{ old('VaiTro') === 'Admin'     ? 'selected' : '' }}>Admin</option>
                        <option value="NhanVien"  {{ old('VaiTro') === 'NhanVien'  ? 'selected' : '' }}>Nhân viên</option>
                        <option value="KhachHang" {{ old('VaiTro') === 'KhachHang' ? 'selected' : '' }}>Khách hàng</option>
                    </select>
                    @error('VaiTro')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Mật khẩu <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="password" name="MatKhau" id="MatKhauInput"
                               class="form-control @error('MatKhau') is-invalid @enderror"
                               placeholder="Tối thiểu 8 ký tự" required>
                        <button type="button" class="btn btn-outline-secondary" id="toggleMatKhau"
                                title="Hiện/ẩn mật khẩu" tabindex="-1">
                            <i class="fas fa-eye" id="toggleMatKhauIcon"></i>
                        </button>
                    </div>
                    @error('MatKhau')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
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
                           value="{{ old('SoDienThoai') }}" placeholder="0901234567">
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
                           value="{{ old('DiaChi') }}" placeholder="Số nhà, đường, phường/xã, tỉnh/thành">
                </div>
            </div>

            {{-- Thông tin nhân viên (hiện khi chọn NhanVien) --}}
            <div id="nhanVienFields" style="display:none;">
                <h6 class="fw-bold text-uppercase text-muted mb-3 border-bottom pb-2">
                    <i class="fas fa-id-badge me-2"></i>Thông tin nhân viên
                </h6>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Chức vụ <span class="text-danger">*</span></label>
                        <input type="text" name="ChucVu"
                               class="form-control @error('ChucVu') is-invalid @enderror"
                               value="{{ old('ChucVu') }}" placeholder="VD: Nhân viên bán vé">
                        @error('ChucVu')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Ngày vào làm</label>
                        <input type="date" name="NgayVaoLam" class="form-control"
                               value="{{ old('NgayVaoLam') }}">
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="fas fa-save me-2"></i>Tạo tài khoản
                </button>
                <a href="{{ route('admin.tai-khoan.index') }}" class="btn btn-outline-secondary px-4">Hủy</a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
const vaiTroSelect = document.getElementById('VaiTro');
const nhanVienFields = document.getElementById('nhanVienFields');

function toggleNhanVienFields() {
    const show = vaiTroSelect.value === 'NhanVien';
    nhanVienFields.style.display = show ? 'block' : 'none';
    nhanVienFields.querySelector('[name="ChucVu"]').required = show;
}

vaiTroSelect.addEventListener('change', toggleNhanVienFields);
toggleNhanVienFields();

// Toggle hiện/ẩn mật khẩu
document.getElementById('toggleMatKhau').addEventListener('click', function () {
    const input = document.getElementById('MatKhauInput');
    const icon  = document.getElementById('toggleMatKhauIcon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
});
</script>
@endsection
