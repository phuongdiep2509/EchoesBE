@extends('admin.layouts.app')

@section('title', 'Chi tiết tài khoản')

@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> Quay lại
    </a>
    <h4 class="mb-0"><i class="fas fa-user me-2"></i>Chi tiết tài khoản</h4>
</div>

<div class="card shadow-sm" style="max-width:600px;">
    <div class="card-body">
        <table class="table table-borderless mb-0">
            <tr><th style="width:160px;">ID</th><td>{{ $user->id }}</td></tr>
            <tr><th>Tên đăng nhập</th><td>{{ $user->username ?? '—' }}</td></tr>
            <tr><th>Họ và tên</th><td>{{ $user->name }}</td></tr>
            <tr><th>Email</th><td>{{ $user->email }}</td></tr>
            <tr><th>Số điện thoại</th><td>{{ $user->phone ?? '—' }}</td></tr>
            <tr><th>Ngày sinh</th><td>{{ $user->birthday?->format('d/m/Y') ?? '—' }}</td></tr>
            <tr><th>Vai trò</th><td><span class="badge bg-secondary">{{ ucfirst($user->role) }}</span></td></tr>
            <tr>
                <th>Trạng thái</th>
                <td>
                    @if($user->is_active)
                        <span class="badge bg-success">Hoạt động</span>
                    @else
                        <span class="badge bg-danger">Đã khoá</span>
                    @endif
                </td>
            </tr>
            <tr><th>Ngày tạo</th><td>{{ $user->created_at->format('d/m/Y H:i') }}</td></tr>
        </table>

        <div class="d-flex gap-2 mt-3">
            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning btn-sm">
                <i class="fas fa-edit"></i> Chỉnh sửa
            </a>
            <form action="{{ route('admin.users.toggle', $user) }}" method="POST">
                @csrf @method('PATCH')
                <button type="submit" class="btn btn-sm btn-outline-{{ $user->is_active ? 'secondary' : 'success' }}"
                        onclick="return confirm('{{ $user->is_active ? 'Khoá tài khoản?' : 'Mở khoá tài khoản?' }}')">
                    <i class="fas fa-{{ $user->is_active ? 'lock' : 'unlock' }}"></i>
                    {{ $user->is_active ? 'Khoá' : 'Mở khoá' }}
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
