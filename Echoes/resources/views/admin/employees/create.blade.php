@extends('admin.layouts.app')

@section('title', 'Thêm nhân viên')

@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin.employees.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> Quay lại
    </a>
    <h4 class="mb-0">Thêm nhân viên mới</h4>
</div>

<div class="card shadow-sm" style="max-width:700px;">
    <div class="card-body">
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        <form action="{{ route('admin.employees.store') }}" method="POST">
            @csrf

            <h6 class="fw-bold text-muted mb-3 border-bottom pb-2">Thông tin tài khoản</h6>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tên đăng nhập <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('username') is-invalid @enderror"
                           name="username" value="{{ old('username') }}" required>
                    @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Họ và tên <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                           name="name" value="{{ old('name') }}" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                           name="email" value="{{ old('email') }}" required>
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Số điện thoại</label>
                    <input type="text" class="form-control" name="phone" value="{{ old('phone') }}">
                </div>
                <div class="col-md-12 mb-4">
                    <label class="form-label">Mật khẩu <span class="text-danger">*</span></label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                           name="password" required>
                    <small class="text-muted">8–32 ký tự, bao gồm hoa, thường, số, ký tự đặc biệt.</small>
                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <h6 class="fw-bold text-muted mb-3 border-bottom pb-2">Thông tin nhân viên</h6>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Phòng ban</label>
                    <input type="text" class="form-control" name="department" value="{{ old('department') }}" placeholder="Ví dụ: Kinh doanh">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Chức vụ</label>
                    <input type="text" class="form-control" name="position" value="{{ old('position') }}" placeholder="Ví dụ: Nhân viên bán hàng">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Ngày vào làm</label>
                    <input type="date" class="form-control" name="hire_date" value="{{ old('hire_date') }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Lương (VNĐ)</label>
                    <input type="number" class="form-control" name="salary" value="{{ old('salary') }}" min="0" step="100000">
                </div>
                <div class="col-md-12 mb-4">
                    <label class="form-label">Ghi chú</label>
                    <textarea class="form-control" name="notes" rows="3">{{ old('notes') }}</textarea>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-1"></i> Tạo nhân viên
            </button>
        </form>
    </div>
</div>
@endsection
