@extends('admin.layouts.app')

@section('content')

<h2>Thêm Concert</h2>

<form method="POST" action="{{ route('admin.concerts.store') }}" class="mt-3" style="max-width:700px">
    @csrf

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="row g-3">
        <div class="col-12">
            <label class="form-label">Tên sự kiện <span class="text-danger">*</span></label>
            <input type="text" name="TenSuKien" class="form-control" value="{{ old('TenSuKien') }}" required>
        </div>

        <div class="col-md-6">
            <label class="form-label">Thời gian bắt đầu <span class="text-danger">*</span></label>
            <input type="datetime-local" name="ThoiGianBatDau" class="form-control" value="{{ old('ThoiGianBatDau') }}" required>
        </div>

        <div class="col-md-6">
            <label class="form-label">Thời gian kết thúc <span class="text-danger">*</span></label>
            <input type="datetime-local" name="ThoiGianKetThuc" class="form-control" value="{{ old('ThoiGianKetThuc') }}" required>
        </div>

        <div class="col-md-4">
            <label class="form-label">Mã ban tổ chức (MaBTC) <span class="text-danger">*</span></label>
            <input type="number" name="MaBTC" class="form-control" value="{{ old('MaBTC', 1) }}" required>
        </div>

        <div class="col-md-4">
            <label class="form-label">Mã địa điểm (MaDiaDiem) <span class="text-danger">*</span></label>
            <input type="number" name="MaDiaDiem" class="form-control" value="{{ old('MaDiaDiem', 1) }}" required>
        </div>

        <div class="col-md-4">
            <label class="form-label">Mã loại sự kiện (MaLoaiSuKien) <span class="text-danger">*</span></label>
            <input type="number" name="MaLoaiSuKien" class="form-control" value="{{ old('MaLoaiSuKien', 1) }}" required>
        </div>

        <div class="col-12">
            <label class="form-label">Ảnh bìa (đường dẫn)</label>
            <input type="text" name="AnhBia" class="form-control" value="{{ old('AnhBia') }}" placeholder="assets/images/concert/...">
        </div>

        <div class="col-12">
            <label class="form-label">Mô tả</label>
            <textarea name="MoTa" class="form-control" rows="3">{{ old('MoTa') }}</textarea>
        </div>

        <div class="col-12">
            <label class="form-label">Điểm nổi bật</label>
            <textarea name="DiemNoiBat" class="form-control" rows="2">{{ old('DiemNoiBat') }}</textarea>
        </div>

        <div class="col-12">
            <label class="form-label">Điều kiện và điều khoản</label>
            <textarea name="DieuKienVaDieuKhoan" class="form-control" rows="2">{{ old('DieuKienVaDieuKhoan') }}</textarea>
        </div>

        <div class="col-md-6">
            <label class="form-label">Trạng thái <span class="text-danger">*</span></label>
            <select name="TrangThai" class="form-select" required>
                <option value="SapDienRa"  {{ old('TrangThai') == 'SapDienRa'  ? 'selected' : '' }}>Sắp diễn ra</option>
                <option value="DangMoBan"  {{ old('TrangThai') == 'DangMoBan'  ? 'selected' : '' }}>Đang mở bán</option>
                <option value="DaKetThuc"  {{ old('TrangThai') == 'DaKetThuc'  ? 'selected' : '' }}>Đã kết thúc</option>
                <option value="DaHuy"      {{ old('TrangThai') == 'DaHuy'      ? 'selected' : '' }}>Đã hủy</option>
            </select>
        </div>
    </div>

    <div class="mt-4">
        <button class="btn btn-success">Lưu sự kiện</button>
        <a href="{{ route('admin.concerts.index') }}" class="btn btn-secondary ms-2">Hủy</a>
    </div>
</form>

@endsection
