@extends('admin.layouts.app')

@section('title', 'Quản lý tài khoản khách hàng')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="fas fa-users me-2"></i>Quản lý tài khoản khách hàng</h4>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus me-1"></i> Thêm tài khoản
    </a>
</div>

{{-- Search / Filter --}}
<form method="GET" class="row g-2 mb-4">
    <div class="col-md-5">
        <input type="text" name="search" class="form-control" placeholder="Tìm theo tên, email, username..."
               value="{{ request('search') }}">
    </div>
    <div class="col-md-3">
        <select name="status" class="form-select">
            <option value="">Tất cả trạng thái</option>
            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Đang hoạt động</option>
            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Đã khoá</option>
        </select>
    </div>
    <div class="col-md-2">
        <button class="btn btn-outline-secondary w-100" type="submit">
            <i class="fas fa-search"></i> Lọc
        </button>
    </div>
    @if(request('search') || request('status') !== null && request('status') !== '')
    <div class="col-md-2">
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-danger w-100">Xóa lọc</a>
    </div>
    @endif
</form>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Tên đăng nhập</th>
                    <th>Họ tên</th>
                    <th>Email</th>
                    <th>Số điện thoại</th>
                    <th>Trạng thái</th>
                    <th>Ngày tạo</th>
                    <th class="text-center">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->username ?? '—' }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->phone ?? '—' }}</td>
                    <td>
                        @if($user->is_active)
                            <span class="badge bg-success">Hoạt động</span>
                        @else
                            <span class="badge bg-danger">Đã khoá</span>
                        @endif
                    </td>
                    <td>{{ $user->created_at->format('d/m/Y') }}</td>
                    <td class="text-center">
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('admin.users.show', $user) }}" class="btn btn-outline-info" title="Xem">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-outline-warning" title="Sửa">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.users.toggle', $user) }}" method="POST" class="d-inline">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-outline-{{ $user->is_active ? 'secondary' : 'success' }}"
                                        title="{{ $user->is_active ? 'Khoá' : 'Mở khoá' }}"
                                        onclick="return confirm('{{ $user->is_active ? 'Khoá tài khoản này?' : 'Mở khoá tài khoản này?' }}')">
                                    <i class="fas fa-{{ $user->is_active ? 'lock' : 'unlock' }}"></i>
                                </button>
                            </form>
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger" title="Xóa"
                                        onclick="return confirm('Xóa tài khoản {{ $user->name }}? Hành động này không thể hoàn tác.')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">Không có tài khoản nào.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">
    {{ $users->links() }}
</div>
@endsection
