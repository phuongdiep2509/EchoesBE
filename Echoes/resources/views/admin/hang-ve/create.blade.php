@extends('admin.layouts.app')
@section('title', 'Thêm hạng vé')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0 fw-bold"><i class="fas fa-plus-circle me-2 text-primary"></i>Thêm hạng vé</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.hang-ve.index') }}" class="text-decoration-none">Hạng vé</a></li>
                <li class="breadcrumb-item active" aria-current="page">Thêm mới</li>
            </ol>
        </nav>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <form action="{{ route('admin.hang-ve.store') }}" method="POST">
            @csrf
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="MaSuKien" class="form-label fw-bold">Thuộc Sự kiện <span class="text-danger">*</span></label>
                    <select class="form-select @error('MaSuKien') is-invalid @enderror" id="MaSuKien" name="MaSuKien" required>
                        <option value="">-- Chọn sự kiện --</option>
                        @foreach($concerts as $concert)
                            <option value="{{ $concert->MaSuKien }}" {{ old('MaSuKien') == $concert->MaSuKien ? 'selected' : '' }}>
                                {{ $concert->TenSuKien }}
                            </option>
                        @endforeach
                    </select>
                    @error('MaSuKien')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="KhuVuc" class="form-label fw-bold">Khu vực <span class="text-danger">*</span></label>
                    <input type="text" list="khuVucList" class="form-control @error('KhuVuc') is-invalid @enderror" id="KhuVuc" name="KhuVuc" value="{{ old('KhuVuc') }}" placeholder="VD: VIP, ZONE A, Tầng 1..." required>
                    <datalist id="khuVucList">
                        @foreach($khuVucs as $kv)
                            <option value="{{ $kv }}">
                        @endforeach
                    </datalist>
                    @error('KhuVuc')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="TenHangVe" class="form-label fw-bold">Tên hạng vé <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('TenHangVe') is-invalid @enderror" id="TenHangVe" name="TenHangVe" value="{{ old('TenHangVe') }}" placeholder="VD: Vé VIP, Vé Thường..." required>
                    @error('TenHangVe')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="GiaVe" class="form-label fw-bold">Giá vé (VNĐ) <span class="text-danger">*</span></label>
                    <input type="number" class="form-control @error('GiaVe') is-invalid @enderror" id="GiaVe" name="GiaVe" value="{{ old('GiaVe') }}" min="0" required>
                    @error('GiaVe')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="SoLuongMoBan" class="form-label fw-bold">Số lượng mở bán <span class="text-danger">*</span></label>
                    <input type="number" class="form-control @error('SoLuongMoBan') is-invalid @enderror" id="SoLuongMoBan" name="SoLuongMoBan" value="{{ old('SoLuongMoBan') }}" min="1" required>
                    @error('SoLuongMoBan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="ThoiGianMoBan" class="form-label fw-bold">Thời gian mở bán</label>
                    <input type="datetime-local" class="form-control @error('ThoiGianMoBan') is-invalid @enderror" id="ThoiGianMoBan" name="ThoiGianMoBan" value="{{ old('ThoiGianMoBan') }}">
                    @error('ThoiGianMoBan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="ThoiGianKetThucBan" class="form-label fw-bold">Thời gian kết thúc bán</label>
                    <input type="datetime-local" class="form-control @error('ThoiGianKetThucBan') is-invalid @enderror" id="ThoiGianKetThucBan" name="ThoiGianKetThucBan" value="{{ old('ThoiGianKetThucBan') }}">
                    @error('ThoiGianKetThucBan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="QuyenLoi" class="form-label fw-bold">Quyền lợi</label>
                <textarea class="form-control @error('QuyenLoi') is-invalid @enderror" id="QuyenLoi" name="QuyenLoi" rows="3" placeholder="Các quyền lợi đi kèm vé này...">{{ old('QuyenLoi') }}</textarea>
                @error('QuyenLoi')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-end mt-4">
                <a href="{{ route('admin.hang-ve.index') }}" class="btn btn-outline-secondary me-2">
                    <i class="fas fa-times me-1"></i>Hủy
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i>Lưu hạng vé
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
