@extends('admin.layouts.app')
@section('title', 'Chi tiết tài khoản')

@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin.tai-khoan.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i>Quay lại
    </a>
    <h4 class="mb-0 fw-bold"><i class="fas fa-user me-2 text-primary"></i>Chi tiết tài khoản</h4>
</div>

<div class="row g-4" style="max-width:900px;">
    {{-- Card tài khoản --}}
    <div class="col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-header fw-bold bg-dark text-white">
                <i class="fas fa-lock me-2"></i>Thông tin tài khoản
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0 small">
                    <tr>
                        <th width="140" class="text-muted">Mã TK</th>
                        <td><code>#{{ $taiKhoan->MaTaiKhoan }}</code></td>
                    </tr>
                    <tr>
                        <th class="text-muted">Tên đăng nhập</th>
                        <td><strong>{{ $taiKhoan->TenDangNhap }}</strong></td>
                    </tr>
                    <tr>
                        <th class="text-muted">Họ tên</th>
                        <td>{{ $taiKhoan->HoTen }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Email</th>
                        <td>{{ $taiKhoan->Email }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Số điện thoại</th>
                        <td>{{ $taiKhoan->SoDienThoai ?? '—' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Vai trò</th>
                        <td>
                            @if($taiKhoan->VaiTro === 'Admin')
                                <span class="badge bg-warning text-dark"><i class="fas fa-crown me-1"></i>Admin</span>
                            @elseif($taiKhoan->VaiTro === 'NhanVien')
                                <span class="badge bg-info text-dark"><i class="fas fa-id-badge me-1"></i>Nhân viên</span>
                            @else
                                <span class="badge bg-secondary"><i class="fas fa-user me-1"></i>Khách hàng</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="text-muted">Trạng thái</th>
                        <td>
                            @if($taiKhoan->TrangThai === 'HoatDong')
                                <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Hoạt động</span>
                            @else
                                <span class="badge bg-danger"><i class="fas fa-ban me-1"></i>Đã khóa</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="text-muted">Ngày tạo</th>
                        <td>{{ $taiKhoan->created_at?->format('d/m/Y H:i') ?? '—' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    {{-- Card chi tiết theo vai trò --}}
    <div class="col-md-6">
        @if($taiKhoan->VaiTro === 'KhachHang' && $taiKhoan->khachHang)
        <div class="card shadow-sm h-100">
            <div class="card-header fw-bold bg-secondary text-white">
                <i class="fas fa-users me-2"></i>Thông tin khách hàng
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0 small">
                    <tr><th width="130" class="text-muted">Mã KH</th><td><code>#{{ $taiKhoan->khachHang->MaKhachHang }}</code></td></tr>
                    <tr><th class="text-muted">Ngày sinh</th><td>{{ $taiKhoan->khachHang->NgaySinh?->format('d/m/Y') ?? '—' }}</td></tr>
                    <tr><th class="text-muted">Giới tính</th><td>{{ $taiKhoan->khachHang->GioiTinh ?? '—' }}</td></tr>
                    <tr><th class="text-muted">Địa chỉ</th><td>{{ $taiKhoan->khachHang->DiaChi ?? '—' }}</td></tr>
                </table>
            </div>
        </div>
        @elseif($taiKhoan->VaiTro === 'NhanVien' && $taiKhoan->nhanVien)
        <div class="card shadow-sm h-100">
            <div class="card-header fw-bold bg-info text-dark">
                <i class="fas fa-id-badge me-2"></i>Thông tin nhân viên
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0 small">
                    <tr><th width="130" class="text-muted">Mã NV</th><td><code>#{{ $taiKhoan->nhanVien->MaNhanVien }}</code></td></tr>
                    <tr><th class="text-muted">Chức vụ</th><td>{{ $taiKhoan->nhanVien->ChucVu }}</td></tr>
                    <tr><th class="text-muted">Ngày sinh</th><td>{{ $taiKhoan->nhanVien->NgaySinh?->format('d/m/Y') ?? '—' }}</td></tr>
                    <tr><th class="text-muted">Giới tính</th><td>{{ $taiKhoan->nhanVien->GioiTinh ?? '—' }}</td></tr>
                    <tr><th class="text-muted">Địa chỉ</th><td>{{ $taiKhoan->nhanVien->DiaChi ?? '—' }}</td></tr>
                    <tr><th class="text-muted">Ngày vào làm</th><td>{{ $taiKhoan->nhanVien->NgayVaoLam?->format('d/m/Y') ?? '—' }}</td></tr>
                </table>
            </div>
        </div>
        @else
        <div class="card shadow-sm h-100">
            <div class="card-body d-flex align-items-center justify-content-center text-muted">
                <div class="text-center">
                    <i class="fas fa-info-circle fa-2x mb-2"></i>
                    <p class="mb-0">Tài khoản Admin<br>không có thông tin chi tiết</p>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<div class="d-flex gap-2 mt-4">
    @if($taiKhoan->VaiTro === 'NhanVien' && $taiKhoan->nhanVien)
    <a href="{{ route('admin.nhan-vien.edit', $taiKhoan->nhanVien->MaNhanVien) }}"
       class="btn btn-warning">
        <i class="fas fa-edit me-2"></i>Chỉnh sửa nhân viên
    </a>
    @endif
    <form action="{{ route('admin.tai-khoan.toggle', $taiKhoan->MaTaiKhoan) }}" method="POST">
        @csrf @method('PATCH')
        <button type="submit"
            class="btn btn-{{ $taiKhoan->TrangThai === 'HoatDong' ? 'outline-danger' : 'outline-success' }}"
            onclick="return confirm('{{ $taiKhoan->TrangThai === 'HoatDong' ? 'Khóa tài khoản này?' : 'Kích hoạt tài khoản này?' }}')">
            <i class="fas fa-{{ $taiKhoan->TrangThai === 'HoatDong' ? 'lock' : 'unlock' }} me-2"></i>
            {{ $taiKhoan->TrangThai === 'HoatDong' ? 'Khóa tài khoản' : 'Kích hoạt' }}
        </button>
    </form>
</div>
@endsection
