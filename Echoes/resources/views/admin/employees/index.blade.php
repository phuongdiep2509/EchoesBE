@extends('admin.layouts.app')

@section('title', 'Quản lý nhân viên')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="fas fa-id-badge me-2"></i>Quản lý nhân viên</h4>
    <a href="{{ route('admin.employees.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus me-1"></i> Thêm nhân viên
    </a>
</div>

<form method="GET" class="row g-2 mb-4">
    <div class="col-md-6">
        <input type="text" name="search" class="form-control" placeholder="Tìm theo tên, email, mã NV, phòng ban..."
               value="{{ request('search') }}">
    </div>
    <div class="col-md-2">
        <button class="btn btn-outline-secondary w-100" type="submit">
            <i class="fas fa-search"></i> Lọc
        </button>
    </div>
    @if(request('search'))
    <div class="col-md-2">
        <a href="{{ route('admin.employees.index') }}" class="btn btn-outline-danger w-100">Xóa lọc</a>
    </div>
    @endif
</form>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Mã NV</th>
                    <th>Họ tên</th>
                    <th>Email</th>
                    <th>Phòng ban</th>
                    <th>Chức vụ</th>
                    <th>Trạng thái</th>
                    <th class="text-center">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($employees as $emp)
                <tr>
                    <td><code>{{ $emp->employee?->employee_code ?? '—' }}</code></td>
                    <td>{{ $emp->name }}</td>
                    <td>{{ $emp->email }}</td>
                    <td>{{ $emp->employee?->department ?? '—' }}</td>
                    <td>{{ $emp->employee?->position ?? '—' }}</td>
                    <td>
                        @if($emp->is_active)
                            <span class="badge bg-success">Hoạt động</span>
                        @else
                            <span class="badge bg-danger">Đã khoá</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('admin.employees.show', $emp) }}" class="btn btn-outline-info" title="Xem">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.employees.edit', $emp) }}" class="btn btn-outline-warning" title="Sửa">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.employees.toggle', $emp) }}" method="POST" class="d-inline">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-outline-{{ $emp->is_active ? 'secondary' : 'success' }}"
                                        title="{{ $emp->is_active ? 'Khoá' : 'Mở khoá' }}"
                                        onclick="return confirm('{{ $emp->is_active ? 'Khoá nhân viên này?' : 'Mở khoá nhân viên này?' }}')">
                                    <i class="fas fa-{{ $emp->is_active ? 'lock' : 'unlock' }}"></i>
                                </button>
                            </form>
                            <form action="{{ route('admin.employees.destroy', $emp) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger" title="Xóa"
                                        onclick="return confirm('Xóa nhân viên {{ $emp->name }}?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">Không có nhân viên nào.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $employees->links() }}</div>
@endsection
