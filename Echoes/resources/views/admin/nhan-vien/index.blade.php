@extends('admin.layouts.app')
@section('title', 'Quản lý nhân viên')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0 fw-bold"><i class="fas fa-id-badge me-2 text-info"></i>Quản lý nhân viên</h4>
        <small class="text-muted">Tổng: {{ $danhSach->total() }} nhân viên</small>
    </div>
    <a href="{{ route('admin.nhan-vien.create') }}" class="btn btn-info text-white">
        <i class="fas fa-user-plus me-1"></i> Thêm nhân viên
    </a>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-5">
                <label class="form-label small mb-1">Tìm kiếm</label>
                <input type="text" name="search" class="form-control form-control-sm"
                       placeholder="Tên, email, chức vụ, tên đăng nhập..."
                       value="{{ request('search') }}">
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
                <button type="submit" class="btn btn-sm btn-info text-white w-100">
                    <i class="fas fa-search me-1"></i>Lọc
                </button>
            </div>
            @if(request()->hasAny(['search','trang_thai']))
            <div class="col-md-2">
                <a href="{{ route('admin.nhan-vien.index') }}" class="btn btn-sm btn-outline-secondary w-100">
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
                    <th class="ps-3">Mã NV</th>
                    <th>Họ tên</th>
                    <th>Email</th>
                    <th>Số điện thoại</th>
                    <th>Chức vụ</th>
                    <th>Ngày vào làm</th>
                    <th>Trạng thái</th>
                    <th class="text-center">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($danhSach as $nv)
                <tr>
                    <td class="ps-3"><code>#{{ $nv->MaNhanVien }}</code></td>
                    <td>
                        <div class="fw-semibold">{{ $nv->taiKhoan?->HoTen ?? '—' }}</div>
                        <small class="text-muted">{{ $nv->taiKhoan?->TenDangNhap }}</small>
                    </td>
                    <td class="small text-muted">{{ $nv->taiKhoan?->Email ?? '—' }}</td>
                    <td class="small">{{ $nv->taiKhoan?->SoDienThoai ?? '—' }}</td>
                    <td>
                        <span class="badge bg-info text-dark">{{ $nv->ChucVu }}</span>
                    </td>
                    <td class="small">{{ $nv->NgayVaoLam?->format('d/m/Y') ?? '—' }}</td>
                    <td>
                        @if($nv->taiKhoan?->TrangThai === 'HoatDong')
                            <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Hoạt động</span>
                        @else
                            <span class="badge bg-danger"><i class="fas fa-ban me-1"></i>Đã khóa</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('admin.nhan-vien.show', $nv->MaNhanVien) }}"
                               class="btn btn-outline-info" title="Xem">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.nhan-vien.edit', $nv->MaNhanVien) }}"
                               class="btn btn-outline-warning" title="Sửa">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.nhan-vien.toggle', $nv->MaNhanVien) }}" method="POST" class="d-inline">
                                @csrf @method('PATCH')
                                <button type="submit"
                                    class="btn btn-outline-{{ $nv->taiKhoan?->TrangThai === 'HoatDong' ? 'secondary' : 'success' }}"
                                    title="{{ $nv->taiKhoan?->TrangThai === 'HoatDong' ? 'Khóa' : 'Kích hoạt' }}"
                                    onclick="return confirm('{{ $nv->taiKhoan?->TrangThai === 'HoatDong' ? 'Khóa tài khoản nhân viên này?' : 'Kích hoạt tài khoản này?' }}')">
                                    <i class="fas fa-{{ $nv->taiKhoan?->TrangThai === 'HoatDong' ? 'lock' : 'unlock' }}"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-5">
                        <i class="fas fa-id-badge fa-2x mb-2 d-block"></i>Không có nhân viên nào.
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
