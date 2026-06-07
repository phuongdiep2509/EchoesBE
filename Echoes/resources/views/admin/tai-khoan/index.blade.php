@extends('admin.layouts.app')
@section('title', 'Quản lý tài khoản')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0 fw-bold"><i class="fas fa-key me-2 text-primary"></i>Quản lý tài khoản</h4>
        <small class="text-muted">Tổng: {{ $danhSach->total() }} tài khoản</small>
    </div>
</div>

{{-- Bộ lọc --}}
<div class="card shadow-sm mb-4">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label small mb-1">Tìm kiếm</label>
                <input type="text" name="search" class="form-control form-control-sm"
                       placeholder="Tên, email, số điện thoại..."
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label small mb-1">Vai trò</label>
                <select name="vai_tro" class="form-select form-select-sm">
                    <option value="">Tất cả</option>
                    <option value="Admin"     {{ request('vai_tro') === 'Admin'     ? 'selected' : '' }}>Admin</option>
                    <option value="NhanVien"  {{ request('vai_tro') === 'NhanVien'  ? 'selected' : '' }}>Nhân viên</option>
                    <option value="KhachHang" {{ request('vai_tro') === 'KhachHang' ? 'selected' : '' }}>Khách hàng</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small mb-1">Trạng thái</label>
                <select name="trang_thai" class="form-select form-select-sm">
                    <option value="">Tất cả</option>
                    <option value="HoatDong"      {{ request('trang_thai') === 'HoatDong'      ? 'selected' : '' }}>Hoạt động</option>
                    <option value="NgungHoatDong" {{ request('trang_thai') === 'NgungHoatDong' ? 'selected' : '' }}>Đã khóa</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-sm btn-primary w-100">
                    <i class="fas fa-search me-1"></i>Lọc
                </button>
            </div>
            @if(request()->hasAny(['search','vai_tro','trang_thai']))
            <div class="col-md-2">
                <a href="{{ route('admin.tai-khoan.index') }}" class="btn btn-sm btn-outline-secondary w-100">
                    <i class="fas fa-times me-1"></i>Xóa lọc
                </a>
            </div>
            @endif
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead style="background:#1a1a2e; color:#fff;">
                <tr>
                    <th class="ps-3">Mã TK</th>
                    <th>Tên đăng nhập</th>
                    <th>Họ tên</th>
                    <th>Email</th>
                    <th>SĐT</th>
                    <th>Vai trò</th>
                    <th>Trạng thái</th>
                    <th class="text-center">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($danhSach as $tk)
                <tr>
                    <td class="ps-3 text-muted small">#{{ $tk->MaTaiKhoan }}</td>
                    <td><strong>{{ $tk->TenDangNhap }}</strong></td>
                    <td>{{ $tk->HoTen }}</td>
                    <td class="small text-muted">{{ $tk->Email }}</td>
                    <td class="small">{{ $tk->SoDienThoai ?? '—' }}</td>
                    <td>
                        @if($tk->VaiTro === 'Admin')
                            <span class="badge bg-warning text-dark"><i class="fas fa-crown me-1"></i>Admin</span>
                        @elseif($tk->VaiTro === 'NhanVien')
                            <span class="badge bg-info text-dark"><i class="fas fa-id-badge me-1"></i>Nhân viên</span>
                        @else
                            <span class="badge bg-secondary"><i class="fas fa-user me-1"></i>Khách hàng</span>
                        @endif
                    </td>
                    <td>
                        @if($tk->TrangThai === 'HoatDong')
                            <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Hoạt động</span>
                        @else
                            <span class="badge bg-danger"><i class="fas fa-ban me-1"></i>Đã khóa</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('admin.tai-khoan.show', $tk->MaTaiKhoan) }}"
                               class="btn btn-outline-info" title="Xem chi tiết">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if($tk->VaiTro === 'NhanVien' && $tk->nhanVien)
                            <a href="{{ route('admin.nhan-vien.edit', $tk->nhanVien->MaNhanVien) }}"
                               class="btn btn-outline-warning" title="Chỉnh sửa nhân viên">
                                <i class="fas fa-edit"></i>
                            </a>
                            @endif
                            <form action="{{ route('admin.tai-khoan.toggle', $tk->MaTaiKhoan) }}" method="POST" class="d-inline">
                                @csrf @method('PATCH')
                                <button type="submit"
                                    class="btn btn-outline-{{ $tk->TrangThai === 'HoatDong' ? 'secondary' : 'success' }}"
                                    title="{{ $tk->TrangThai === 'HoatDong' ? 'Khóa tài khoản' : 'Kích hoạt' }}"
                                    onclick="return confirm('{{ $tk->TrangThai === 'HoatDong' ? 'Khóa tài khoản này?' : 'Kích hoạt tài khoản này?' }}')">
                                    <i class="fas fa-{{ $tk->TrangThai === 'HoatDong' ? 'lock' : 'unlock' }}"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-5">
                        <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                        Không tìm thấy tài khoản nào.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3 d-flex justify-content-between align-items-center">
    <small class="text-muted">
        Hiển thị {{ $danhSach->firstItem() }}–{{ $danhSach->lastItem() }}
        / {{ $danhSach->total() }} tài khoản
    </small>
    {{ $danhSach->links() }}
</div>
@endsection
