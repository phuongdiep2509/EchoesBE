@extends('admin.layouts.app')
@section('title', 'Chi tiết khách hàng')

@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin.khach-hang.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i>Quay lại
    </a>
    <h4 class="mb-0 fw-bold"><i class="fas fa-user me-2 text-success"></i>Chi tiết khách hàng</h4>
</div>

<div class="row g-4" style="max-width:900px;">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header fw-bold" style="background:#1a1a2e; color:#fff;">
                <i class="fas fa-lock me-2"></i>Tài khoản
            </div>
            <div class="card-body">
                <table class="table table-borderless small mb-0">
                    <tr><th width="130" class="text-muted">Tên đăng nhập</th><td><strong>{{ $khachHang->taiKhoan?->TenDangNhap }}</strong></td></tr>
                    <tr><th class="text-muted">Họ tên</th><td>{{ $khachHang->taiKhoan?->HoTen }}</td></tr>
                    <tr><th class="text-muted">Email</th><td>{{ $khachHang->taiKhoan?->Email }}</td></tr>
                    <tr><th class="text-muted">SĐT</th><td>{{ $khachHang->taiKhoan?->SoDienThoai ?? '—' }}</td></tr>
                    <tr>
                        <th class="text-muted">Trạng thái</th>
                        <td>
                            @if($khachHang->taiKhoan?->TrangThai === 'HoatDong')
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
            <div class="card-header fw-bold bg-success text-white">
                <i class="fas fa-users me-2"></i>Thông tin khách hàng
            </div>
            <div class="card-body">
                <table class="table table-borderless small mb-0">
                    <tr><th width="130" class="text-muted">Mã KH</th><td><code>#{{ $khachHang->MaKhachHang }}</code></td></tr>
                    <tr><th class="text-muted">Ngày sinh</th><td>{{ $khachHang->NgaySinh?->format('d/m/Y') ?? '—' }}</td></tr>
                    <tr>
                        <th class="text-muted">Giới tính</th>
                        <td>
                            @if($khachHang->GioiTinh === 'Nam') <span class="badge bg-primary">Nam</span>
                            @elseif($khachHang->GioiTinh === 'Nu') <span class="badge" style="background:#e91e8c;">Nữ</span>
                            @elseif($khachHang->GioiTinh) {{ $khachHang->GioiTinh }}
                            @else —
                            @endif
                        </td>
                    </tr>
                    <tr><th class="text-muted">Địa chỉ</th><td>{{ $khachHang->DiaChi ?? '—' }}</td></tr>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="d-flex gap-2 mt-4">
    <a href="{{ route('admin.khach-hang.edit', $khachHang->MaKhachHang) }}" class="btn btn-warning">
        <i class="fas fa-edit me-2"></i>Chỉnh sửa
    </a>
    <form action="{{ route('admin.khach-hang.toggle', $khachHang->MaKhachHang) }}" method="POST">
        @csrf @method('PATCH')
        <button type="submit"
            class="btn btn-{{ $khachHang->taiKhoan?->TrangThai === 'HoatDong' ? 'outline-danger' : 'outline-success' }}"
            onclick="return confirm('{{ $khachHang->taiKhoan?->TrangThai === 'HoatDong' ? 'Khóa tài khoản này?' : 'Kích hoạt tài khoản này?' }}')">
            <i class="fas fa-{{ $khachHang->taiKhoan?->TrangThai === 'HoatDong' ? 'lock' : 'unlock' }} me-2"></i>
            {{ $khachHang->taiKhoan?->TrangThai === 'HoatDong' ? 'Khóa tài khoản' : 'Kích hoạt' }}
        </button>
    </form>
</div>
@endsection
