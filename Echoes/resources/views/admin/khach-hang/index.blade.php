@extends('admin.layouts.app')
@section('title', 'Quản lý khách hàng')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0 fw-bold"><i class="fas fa-users me-2 text-success"></i>Quản lý khách hàng</h4>
        <small class="text-muted">Tổng: {{ $danhSach->total() }} khách hàng</small>
    </div>
    <a href="{{ route('admin.khach-hang.create') }}" class="btn btn-success">
        <i class="fas fa-user-plus me-1"></i> Thêm khách hàng
    </a>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label small mb-1">Tìm kiếm</label>
                <input type="text" name="search" class="form-control form-control-sm"
                       placeholder="Tên, email, SĐT, tên đăng nhập..."
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label small mb-1">Giới tính</label>
                <select name="gioi_tinh" class="form-select form-select-sm">
                    <option value="">Tất cả</option>
                    <option value="Nam"  {{ request('gioi_tinh') === 'Nam'  ? 'selected' : '' }}>Nam</option>
                    <option value="Nu"   {{ request('gioi_tinh') === 'Nu'   ? 'selected' : '' }}>Nữ</option>
                    <option value="Khac" {{ request('gioi_tinh') === 'Khac' ? 'selected' : '' }}>Khác</option>
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
                <button type="submit" class="btn btn-sm btn-success w-100">
                    <i class="fas fa-search me-1"></i>Lọc
                </button>
            </div>
            @if(request()->hasAny(['search','gioi_tinh','trang_thai']))
            <div class="col-md-2">
                <a href="{{ route('admin.khach-hang.index') }}" class="btn btn-sm btn-outline-secondary w-100">
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
                    <th class="ps-3">Mã KH</th>
                    <th>Họ tên</th>
                    <th>Email</th>
                    <th>Số điện thoại</th>
                    <th>Ngày sinh</th>
                    <th>Giới tính</th>
                    <th>Trạng thái</th>
                    <th class="text-center">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($danhSach as $kh)
                <tr>
                    <td class="ps-3 text-muted small"><code>#{{ $kh->MaKhachHang }}</code></td>
                    <td>
                        <div class="fw-semibold">{{ $kh->taiKhoan?->HoTen ?? '—' }}</div>
                        <small class="text-muted">{{ $kh->taiKhoan?->TenDangNhap }}</small>
                    </td>
                    <td class="small text-muted">{{ $kh->taiKhoan?->Email ?? '—' }}</td>
                    <td class="small">{{ $kh->taiKhoan?->SoDienThoai ?? '—' }}</td>
                    <td class="small">{{ $kh->NgaySinh?->format('d/m/Y') ?? '—' }}</td>
                    <td class="small">
                        @if($kh->GioiTinh === 'Nam') <span class="badge bg-primary">Nam</span>
                        @elseif($kh->GioiTinh === 'Nu') <span class="badge bg-pink" style="background:#e91e8c;">Nữ</span>
                        @elseif($kh->GioiTinh) <span class="badge bg-secondary">{{ $kh->GioiTinh }}</span>
                        @else —
                        @endif
                    </td>
                    <td>
                        @if($kh->taiKhoan?->TrangThai === 'HoatDong')
                            <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Hoạt động</span>
                        @else
                            <span class="badge bg-danger"><i class="fas fa-ban me-1"></i>Đã khóa</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('admin.khach-hang.show', $kh->MaKhachHang) }}"
                               class="btn btn-outline-info" title="Xem">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.khach-hang.edit', $kh->MaKhachHang) }}"
                               class="btn btn-outline-warning" title="Sửa">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.khach-hang.toggle', $kh->MaKhachHang) }}" method="POST" class="d-inline">
                                @csrf @method('PATCH')
                                <button type="submit"
                                    class="btn btn-outline-{{ $kh->taiKhoan?->TrangThai === 'HoatDong' ? 'secondary' : 'success' }}"
                                    title="{{ $kh->taiKhoan?->TrangThai === 'HoatDong' ? 'Khóa' : 'Kích hoạt' }}"
                                    onclick="return confirm('{{ $kh->taiKhoan?->TrangThai === 'HoatDong' ? 'Khóa tài khoản khách hàng này?' : 'Kích hoạt tài khoản này?' }}')">
                                    <i class="fas fa-{{ $kh->taiKhoan?->TrangThai === 'HoatDong' ? 'lock' : 'unlock' }}"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-5">
                        <i class="fas fa-users fa-2x mb-2 d-block"></i>Không có khách hàng nào.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3 d-flex justify-content-between align-items-center">
    <small class="text-muted">
        Hiển thị {{ $danhSach->firstItem() }}–{{ $danhSach->lastItem() }} / {{ $danhSach->total() }}
    </small>
    {{ $danhSach->links() }}
</div>
@endsection
