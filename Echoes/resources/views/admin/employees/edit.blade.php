@extends('admin.layouts.app')

@section('title', 'Chỉnh sửa nhân viên')

@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin.employees.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> Quay lại
    </a>
    <h4 class="mb-0">Chỉnh sửa: {{ $user->name }}</h4>
</div>

<div class="card shadow-sm" style="max-width:700px;">
    <div class="card-body">
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        <form action="{{ route('admin.employees.update', $user) }}" method="POST">
            @csrf @method('PUT')

            <h6 class="fw-bold text-muted mb-3 border-bottom pb-2">Thông tin tài khoản</h6>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tên đăng nhập</label>
                    <input type="text" class="form-control" value="{{ $user->username }}" readonly disabled>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Họ và tên <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                           name="name" value="{{ old('name', $user->name) }}" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                           name="email" value="{{ old('email', $user->email) }}" required>
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Số điện thoại</label>
                    <input type="text" class="form-control" name="phone" value="{{ old('phone', $user->phone) }}">
                </div>
                <div class="col-md-12 mb-4">
                    <label class="form-label">Mật khẩu mới <small class="text-muted">(để trống nếu không đổi)</small></label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" name="password">
                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <h6 class="fw-bold text-muted mb-3 border-bottom pb-2">Thông tin nhân viên</h6>
            <div class="row">
                @if($user->employee)
                <div class="col-md-6 mb-3">
                    <label class="form-label">Mã nhân viên</label>
                    <input type="text" class="form-control" value="{{ $user->employee->employee_code }}" readonly disabled>
                </div>
                @endif
                <div class="col-md-6 mb-3">
                    <label class="form-label">Phòng ban</label>
                    <input type="text" class="form-control" name="department" value="{{ old('department', $user->employee?->department) }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Chức vụ</label>
                    <input type="text" class="form-control" name="position" value="{{ old('position', $user->employee?->position) }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Ngày vào làm</label>
                    <input type="date" class="form-control" name="hire_date" value="{{ old('hire_date', $user->employee?->hire_date?->format('Y-m-d')) }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Lương (VNĐ)</label>
                    <input type="number" class="form-control" name="salary" value="{{ old('salary', $user->employee?->salary) }}" min="0" step="100000">
                </div>
                <div class="col-md-12 mb-4">
                    <label class="form-label">Ghi chú</label>
                    <textarea class="form-control" name="notes" rows="3">{{ old('notes', $user->employee?->notes) }}</textarea>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-1"></i> Lưu thay đổi
            </button>
        </form>
    </div>
</div>
@endsection
