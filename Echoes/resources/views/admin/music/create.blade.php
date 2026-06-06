@extends('admin.layouts.app')

@section('title', 'Thêm Sự kiện Âm nhạc')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Thêm Sự kiện Âm nhạc</h2>
    <a href="{{ route('admin.music.index') }}" class="btn btn-outline-secondary">← Quay lại</a>
</div>

<form method="POST" action="{{ route('admin.music.store') }}" style="max-width:700px">
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
            <label class="form-label">Ban tổ chức <span class="text-danger">*</span></label>
            <select name="MaBTC" class="form-select" required>
                @forelse($banToChuc as $btc)
                    <option value="{{ $btc->MaBTC }}" {{ old('MaBTC') == $btc->MaBTC ? 'selected' : '' }}>
                        {{ $btc->TenToChuc }}
                    </option>
                @empty
                    <option value="1">Mặc định</option>
                @endforelse
            </select>
        </div>

        <div class="col-md-4">
            <label class="form-label">Địa điểm <span class="text-danger">*</span></label>
            <select name="MaDiaDiem" class="form-select" required>
                @forelse($diaDiems as $dd)
                    <option value="{{ $dd->MaDiaDiem }}" {{ old('MaDiaDiem') == $dd->MaDiaDiem ? 'selected' : '' }}>
                        {{ $dd->TenDiaDiem }}
                    </option>
                @empty
                    <option value="1">Mặc định</option>
                @endforelse
            </select>
        </div>

        <div class="col-md-4">
            <label class="form-label">Loại sự kiện <span class="text-danger">*</span></label>
            <select name="MaLoaiSuKien" class="form-select" required>
                @forelse($loaiSuKiens as $lsk)
                    <option value="{{ $lsk->MaLoaiSuKien }}" {{ old('MaLoaiSuKien') == $lsk->MaLoaiSuKien ? 'selected' : '' }}>
                        {{ $lsk->TenLoai }}
                    </option>
                @empty
                    <option value="1">Mặc định</option>
                @endforelse
            </select>
        </div>

        <div class="col-12">
            <label class="form-label">Ảnh bìa (đường dẫn)</label>
            <input type="text" name="AnhBia" class="form-control" value="{{ old('AnhBia') }}" placeholder="assets/images/music/...">
        </div>

        <div class="col-12">
            <label class="form-label">Mô tả</label>
            <textarea name="MoTa" class="form-control" rows="3">{{ old('MoTa') }}</textarea>
        </div>

        <div class="col-12">
            <label class="form-label">Điểm nổi bật</label>
            <textarea name="DiemNoiBat" class="form-control" rows="2">{{ old('DiemNoiBat') }}</textarea>
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
        <a href="{{ route('admin.music.index') }}" class="btn btn-secondary ms-2">Hủy</a>
    </div>
</form>

@endsection
