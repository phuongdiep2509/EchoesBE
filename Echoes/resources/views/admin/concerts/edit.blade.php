@extends('admin.layouts.app')

@section('content')

<h2>Chỉnh sửa: {{ $concert->TenSuKien }}</h2>

<form method="POST" action="{{ route('admin.concerts.update', $concert->MaSuKien) }}" class="mt-3" style="max-width:700px">
    @csrf
    @method('PUT')

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="row g-3">
        <div class="col-12">
            <label class="form-label">Tên sự kiện <span class="text-danger">*</span></label>
            <input type="text" name="TenSuKien" class="form-control"
                value="{{ old('TenSuKien', $concert->TenSuKien) }}" required>
        </div>

        <div class="col-md-6">
            <label class="form-label">Thời gian bắt đầu <span class="text-danger">*</span></label>
            <input type="datetime-local" name="ThoiGianBatDau" class="form-control"
                value="{{ old('ThoiGianBatDau', date('Y-m-d\TH:i', strtotime($concert->ThoiGianBatDau))) }}" required>
        </div>

        <div class="col-md-6">
            <label class="form-label">Thời gian kết thúc <span class="text-danger">*</span></label>
            <input type="datetime-local" name="ThoiGianKetThuc" class="form-control"
                value="{{ old('ThoiGianKetThuc', date('Y-m-d\TH:i', strtotime($concert->ThoiGianKetThuc))) }}" required>
        </div>

        <div class="col-md-4">
            <label class="form-label">Mã ban tổ chức</label>
            <input type="number" name="MaBTC" class="form-control" value="{{ old('MaBTC', $concert->MaBTC) }}" required>
        </div>

        <div class="col-md-4">
            <label class="form-label">Mã địa điểm</label>
            <input type="number" name="MaDiaDiem" class="form-control" value="{{ old('MaDiaDiem', $concert->MaDiaDiem) }}" required>
        </div>

        <div class="col-md-4">
            <label class="form-label">Mã loại sự kiện</label>
            <input type="number" name="MaLoaiSuKien" class="form-control" value="{{ old('MaLoaiSuKien', $concert->MaLoaiSuKien) }}" required>
        </div>

        <div class="col-12">
            <label class="form-label">Ảnh bìa (đường dẫn)</label>
            <input type="text" name="AnhBia" class="form-control" value="{{ old('AnhBia', $concert->AnhBia) }}">
            @if($concert->AnhBia)
                <img src="{{ $concert->AnhBiaUrl }}" class="mt-2" style="height:80px;border-radius:4px">
            @endif
        </div>

        <div class="col-12">
            <label class="form-label">Mô tả</label>
            <textarea name="MoTa" class="form-control" rows="3">{{ old('MoTa', $concert->MoTa) }}</textarea>
        </div>

        <div class="col-12">
            <label class="form-label">Điểm nổi bật</label>
            <textarea name="DiemNoiBat" class="form-control" rows="2">{{ old('DiemNoiBat', $concert->DiemNoiBat) }}</textarea>
        </div>

        <div class="col-12">
            <label class="form-label">Điều kiện và điều khoản</label>
            <textarea name="DieuKienVaDieuKhoan" class="form-control" rows="2">{{ old('DieuKienVaDieuKhoan', $concert->DieuKienVaDieuKhoan) }}</textarea>
        </div>

        <div class="col-md-6">
            <label class="form-label">Trạng thái <span class="text-danger">*</span></label>
            <select name="TrangThai" class="form-select" required>
                @foreach(['SapDienRa' => 'Sắp diễn ra', 'DangMoBan' => 'Đang mở bán', 'DaKetThuc' => 'Đã kết thúc', 'DaHuy' => 'Đã hủy'] as $val => $label)
                    <option value="{{ $val }}" {{ old('TrangThai', $concert->TrangThai) == $val ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="mt-4">
        <button class="btn btn-primary">Cập nhật</button>
        <a href="{{ route('admin.concerts.index') }}" class="btn btn-secondary ms-2">Hủy</a>
    </div>
</form>

@endsection
