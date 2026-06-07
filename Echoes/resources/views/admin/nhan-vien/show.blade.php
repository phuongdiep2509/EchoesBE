@extends('admin.layouts.app')
@section('title', 'Chi tiết nhân viên')

@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin.nhan-vien.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i>Quay lại
    </a>
    <h4 class="mb-0 fw-bold"><i class="fas fa-id-badge me-2 text-info"></i>Chi tiết nhân viên</h4>
</div>

<div class="row g-4" style="max-width:900px;">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header fw-bold" style="background:#1a1a2e; color:#fff;">
                <i class="fas fa-lock me-2"></i>Tài khoản
            </div>
            <div class="card-body">
                <table class="table table-borderless small mb-0">
                    <tr><th width="130" class="text-muted">Tên đăng nhập</th><td><strong>{{ $nhanVien->taiKhoan?->TenDangNhap }}</strong></td></tr>
                    <tr><th class="text-muted">Họ tên</th><td>{{ $nhanVien->taiKhoan?->HoTen }}</td></tr>
                    <tr><th class="text-muted">Email</th><td>{{ $nhanVien->taiKhoan?->Email }}</td></tr>
                    <tr><th class="text-muted">SĐT</th><td>{{ $nhanVien->taiKhoan?->SoDienThoai ?? '—' }}</td></tr>
                    <tr>
                        <th class="text-muted">Trạng thái</th>
                        <td>
                            @if($nhanVien->taiKhoan?->TrangThai === 'HoatDong')
                                <span class="badge bg-success">Hoạt động</span>
                            @else
                                <span class="badge bg-danger">Đã khóa</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header fw-bold bg-info text-dark">
                <i class="fas fa-id-badge me-2"></i>Thông tin nhân viên
            </div>
            <div class="card-body">
                <table class="table table-borderless small mb-0">
                    <tr><th width="130" class="text-muted">Mã NV</th><td><code>#{{ $nhanVien->MaNhanVien }}</code></td></tr>
                    <tr><th class="text-muted">Chức vụ</th><td><span class="badge bg-info text-dark">{{ $nhanVien->ChucVu }}</span></td></tr>
                    <tr><th class="text-muted">Ngày sinh</th><td>{{ $nhanVien->NgaySinh?->format('d/m/Y') ?? '—' }}</td></tr>
                    <tr>
                        <th class="text-muted">Giới tính</th>
                        <td>
                            @if($nhanVien->GioiTinh === 'Nam') <span class="badge bg-primary">Nam</span>
                            @elseif($nhanVien->GioiTinh === 'Nu') <span class="badge" style="background:#e91e8c;">Nữ</span>
                            @elseif($nhanVien->GioiTinh) {{ $nhanVien->GioiTinh }}
                            @else —
                            @endif
                        </td>
                    </tr>
                    <tr><th class="text-muted">Địa chỉ</th><td>{{ $nhanVien->DiaChi ?? '—' }}</td></tr>
                    <tr><th class="text-muted">Ngày vào làm</th><td>{{ $nhanVien->NgayVaoLam?->format('d/m/Y') ?? '—' }}</td></tr>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="d-flex gap-2 mt-4">
    <a href="{{ route('admin.nhan-vien.edit', $nhanVien->MaNhanVien) }}" class="btn btn-warning">
        <i class="fas fa-edit me-2"></i>Chỉnh sửa
    </a>
    <form action="{{ route('admin.nhan-vien.toggle', $nhanVien->MaNhanVien) }}" method="POST">
        @csrf @method('PATCH')
        <button type="submit"
            class="btn btn-{{ $nhanVien->taiKhoan?->TrangThai === 'HoatDong' ? 'outline-danger' : 'outline-success' }}"
            onclick="return confirm('{{ $nhanVien->taiKhoan?->TrangThai === 'HoatDong' ? 'Khóa tài khoản nhân viên này?' : 'Kích hoạt tài khoản này?' }}')">
            <i class="fas fa-{{ $nhanVien->taiKhoan?->TrangThai === 'HoatDong' ? 'lock' : 'unlock' }} me-2"></i>
            {{ $nhanVien->taiKhoan?->TrangThai === 'HoatDong' ? 'Khóa tài khoản' : 'Kích hoạt' }}
        </button>
    </form>
</div>
@endsection
