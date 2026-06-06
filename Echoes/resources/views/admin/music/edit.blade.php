@extends('admin.layouts.app')

@section('title', 'Chỉnh sửa Sự kiện Âm nhạc')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Chỉnh sửa: {{ $event->TenSuKien }}</h2>
    <a href="{{ route('admin.music.index') }}" class="btn btn-outline-secondary">← Quay lại</a>
</div>

<form method="POST" action="{{ route('admin.music.update', $event->MaSuKien) }}" style="max-width:700px">
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
                value="{{ old('TenSuKien', $event->TenSuKien) }}" required>
        </div>

        <div class="col-md-6">
            <label class="form-label">Thời gian bắt đầu <span class="text-danger">*</span></label>
            <input type="datetime-local" name="ThoiGianBatDau" class="form-control"
                value="{{ old('ThoiGianBatDau', date('Y-m-d\TH:i', strtotime($event->ThoiGianBatDau))) }}" required>
        </div>

        <div class="col-md-6">
            <label class="form-label">Thời gian kết thúc <span class="text-danger">*</span></label>
            <input type="datetime-local" name="ThoiGianKetThuc" class="form-control"
                value="{{ old('ThoiGianKetThuc', date('Y-m-d\TH:i', strtotime($event->ThoiGianKetThuc))) }}" required>
        </div>

        <div class="col-md-4">
            <label class="form-label">Ban tổ chức</label>
            <select name="MaBTC" class="form-select">
                @foreach($banToChuc as $btc)
                    <option value="{{ $btc->MaBTC }}" {{ old('MaBTC', $event->MaBTC) == $btc->MaBTC ? 'selected' : '' }}>
                        {{ $btc->TenToChuc }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-4">
            <label class="form-label">Địa điểm</label>
            <select name="MaDiaDiem" class="form-select">
                @foreach($diaDiems as $dd)
                    <option value="{{ $dd->MaDiaDiem }}" {{ old('MaDiaDiem', $event->MaDiaDiem) == $dd->MaDiaDiem ? 'selected' : '' }}>
                        {{ $dd->TenDiaDiem }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-4">
            <label class="form-label">Loại sự kiện</label>
            <select name="MaLoaiSuKien" class="form-select">
                @foreach($loaiSuKiens as $lsk)
                    <option value="{{ $lsk->MaLoaiSuKien }}" {{ old('MaLoaiSuKien', $event->MaLoaiSuKien) == $lsk->MaLoaiSuKien ? 'selected' : '' }}>
                        {{ $lsk->TenLoai }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-12">
            <label class="form-label">Ảnh bìa (đường dẫn)</label>
            <input type="text" name="AnhBia" class="form-control"
                value="{{ old('AnhBia', $event->AnhBia) }}" placeholder="assets/images/music/...">
            @if($event->AnhBia)
                <img src="{{ asset($event->AnhBia) }}" class="mt-2" style="height:80px;border-radius:4px">
            @endif
        </div>

        <div class="col-12">
            <label class="form-label">Mô tả</label>
            <textarea name="MoTa" class="form-control" rows="3">{{ old('MoTa', $event->MoTa) }}</textarea>
        </div>

        <div class="col-12">
            <label class="form-label">Điểm nổi bật</label>
            <textarea name="DiemNoiBat" class="form-control" rows="2">{{ old('DiemNoiBat', $event->DiemNoiBat) }}</textarea>
        </div>

        <div class="col-md-6">
            <label class="form-label">Trạng thái <span class="text-danger">*</span></label>
            <select name="TrangThai" class="form-select" required>
                @foreach(['SapDienRa'=>'Sắp diễn ra','DangMoBan'=>'Đang mở bán','DaKetThuc'=>'Đã kết thúc','DaHuy'=>'Đã hủy'] as $val => $label)
                    <option value="{{ $val }}" {{ old('TrangThai', $event->TrangThai) == $val ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="mt-4">
        <button class="btn btn-primary">Cập nhật</button>
        <a href="{{ route('admin.music.index') }}" class="btn btn-secondary ms-2">Hủy</a>
    </div>
</form>

@endsection
