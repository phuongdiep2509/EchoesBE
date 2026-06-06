@extends('admin.layouts.app')

@section('title', 'Chi tiết nhân viên')

@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin.employees.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> Quay lại
    </a>
    <h4 class="mb-0">Chi tiết nhân viên: {{ $user->name }}</h4>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card shadow-sm mb-3">
            <div class="card-header fw-bold"><i class="fas fa-user me-2"></i>Thông tin tài khoản</div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr><th style="width:140px;">Tên đăng nhập</th><td>{{ $user->username ?? '—' }}</td></tr>
                    <tr><th>Họ và tên</th><td>{{ $user->name }}</td></tr>
                    <tr><th>Email</th><td>{{ $user->email }}</td></tr>
                    <tr><th>Điện thoại</th><td>{{ $user->phone ?? '—' }}</td></tr>
                    <tr>
                        <th>Trạng thái</th>
                        <td>
                            <span class="badge bg-{{ $user->is_active ? 'success' : 'danger' }}">
                                {{ $user->is_active ? 'Hoạt động' : 'Đã khoá' }}
                            </span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm mb-3">
            <div class="card-header fw-bold"><i class="fas fa-id-badge me-2"></i>Thông tin nhân viên</div>
            <div class="card-body">
                @if($user->employee)
                <table class="table table-borderless mb-0">
                    <tr><th style="width:140px;">Mã nhân viên</th><td><code>{{ $user->employee->employee_code }}</code></td></tr>
                    <tr><th>Phòng ban</th><td>{{ $user->employee->department ?? '—' }}</td></tr>
                    <tr><th>Chức vụ</th><td>{{ $user->employee->position ?? '—' }}</td></tr>
                    <tr><th>Ngày vào làm</th><td>{{ $user->employee->hire_date?->format('d/m/Y') ?? '—' }}</td></tr>
                    <tr><th>Lương</th><td>{{ $user->employee->salary ? number_format($user->employee->salary, 0, ',', '.') . ' đ' : '—' }}</td></tr>
                    <tr><th>Ghi chú</th><td>{{ $user->employee->notes ?? '—' }}</td></tr>
                </table>
                @else
                    <p class="text-muted mb-0">Chưa có thông tin nhân viên.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="d-flex gap-2">
    <a href="{{ route('admin.employees.edit', $user) }}" class="btn btn-warning btn-sm">
        <i class="fas fa-edit"></i> Chỉnh sửa
    </a>
    <form action="{{ route('admin.employees.toggle', $user) }}" method="POST">
        @csrf @method('PATCH')
        <button type="submit" class="btn btn-sm btn-outline-{{ $user->is_active ? 'secondary' : 'success' }}"
                onclick="return confirm('{{ $user->is_active ? 'Khoá nhân viên?' : 'Mở khoá nhân viên?' }}')">
            <i class="fas fa-{{ $user->is_active ? 'lock' : 'unlock' }}"></i>
            {{ $user->is_active ? 'Khoá tài khoản' : 'Mở khoá' }}
        </button>
    </form>
</div>
@endsection
