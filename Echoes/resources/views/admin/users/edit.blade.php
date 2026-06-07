@extends('admin.layouts.app')

@section('title', 'Chỉnh sửa tài khoản')

@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> Quay lại
    </a>
    <h4 class="mb-0">Chỉnh sửa: {{ $user->name }}</h4>
</div>

<div class="card shadow-sm" style="max-width:600px;">
    <div class="card-body">
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        <form action="{{ route('admin.users.update', $user) }}" method="POST">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label">Tên đăng nhập</label>
                <input type="text" class="form-control" value="{{ $user->username }}" readonly disabled>
            </div>
            <div class="mb-3">
                <label class="form-label">Họ và tên <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('name') is-invalid @enderror"
                       name="name" value="{{ old('name', $user->name) }}" required>
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Email <span class="text-danger">*</span></label>
                <input type="email" class="form-control @error('email') is-invalid @enderror"
                       name="email" value="{{ old('email', $user->email) }}" required>
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Số điện thoại</label>
                <input type="text" class="form-control" name="phone" value="{{ old('phone', $user->phone) }}">
            </div>
            <div class="mb-4">
                <label class="form-label">Mật khẩu mới <small class="text-muted">(để trống nếu không đổi)</small></label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" name="password">
                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-1"></i> Lưu thay đổi
            </button>
        </form>
    </div>
</div>
@endsection
