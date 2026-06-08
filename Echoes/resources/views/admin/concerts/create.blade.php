@extends('admin.layouts.app')
@section('title', 'Thêm sự kiện')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0 fw-bold"><i class="fas fa-plus-circle me-2 text-primary"></i>Thêm sự kiện</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.concerts.index') }}" class="text-decoration-none">Sự kiện</a></li>
                <li class="breadcrumb-item active" aria-current="page">Thêm mới</li>
            </ol>
        </nav>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <form action="{{ route('admin.concerts.store') }}" method="POST">
            @csrf
            
            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="TenSuKien" class="form-label fw-bold">Tên sự kiện <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('TenSuKien') is-invalid @enderror" id="TenSuKien" name="TenSuKien" value="{{ old('TenSuKien') }}" required>
                    @error('TenSuKien') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="MaLoaiSuKien" class="form-label fw-bold">Danh mục <span class="text-danger">*</span></label>
                    <select class="form-select @error('MaLoaiSuKien') is-invalid @enderror" id="MaLoaiSuKien" name="MaLoaiSuKien" required>
                        <option value="">-- Chọn danh mục --</option>
                        @foreach($loaiSuKiens as $loai)
                            <option value="{{ $loai->MaLoaiSuKien }}" {{ old('MaLoaiSuKien') == $loai->MaLoaiSuKien ? 'selected' : '' }}>{{ $loai->TenLoai }}</option>
                        @endforeach
                    </select>
                    @error('MaLoaiSuKien') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label for="BanToChuc" class="form-label fw-bold">Ban tổ chức <span class="text-danger">*</span></label>
                    <input type="text" list="banToChucList" class="form-control @error('BanToChuc') is-invalid @enderror" id="BanToChuc" name="BanToChuc" value="{{ old('BanToChuc') }}" placeholder="Nhập hoặc chọn ban tổ chức..." autocomplete="off" required>
                    <datalist id="banToChucList">
                        @foreach($banToChucs as $btc)
                            <option value="{{ $btc->TenToChuc }}"></option>
                        @endforeach
                    </datalist>
                    @error('BanToChuc') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label for="DiaDiem" class="form-label fw-bold">Địa điểm <span class="text-danger">*</span></label>
                    <input type="text" list="diaDiemList" class="form-control @error('DiaDiem') is-invalid @enderror" id="DiaDiem" name="DiaDiem" value="{{ old('DiaDiem') }}" placeholder="Nhập hoặc chọn địa điểm..." autocomplete="off" required>
                    <datalist id="diaDiemList">
                        @foreach($diaDiems as $dd)
                            <option value="{{ $dd->TenDiaDiem }}"></option>
                        @endforeach
                    </datalist>
                    @error('DiaDiem') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="ThoiGianBatDau" class="form-label fw-bold">Thời gian bắt đầu <span class="text-danger">*</span></label>
                    <input type="datetime-local" class="form-control @error('ThoiGianBatDau') is-invalid @enderror" id="ThoiGianBatDau" name="ThoiGianBatDau" value="{{ old('ThoiGianBatDau') }}" required>
                    @error('ThoiGianBatDau') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label for="ThoiGianKetThuc" class="form-label fw-bold">Thời gian kết thúc <span class="text-danger">*</span></label>
                    <input type="datetime-local" class="form-control @error('ThoiGianKetThuc') is-invalid @enderror" id="ThoiGianKetThuc" name="ThoiGianKetThuc" value="{{ old('ThoiGianKetThuc') }}" required>
                    @error('ThoiGianKetThuc') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label for="TrangThai" class="form-label fw-bold">Trạng thái <span class="text-danger">*</span></label>
                    <select class="form-select @error('TrangThai') is-invalid @enderror" id="TrangThai" name="TrangThai" required>
                        <option value="SapDienRa" {{ old('TrangThai') == 'SapDienRa' ? 'selected' : '' }}>Sắp diễn ra</option>
                        <option value="DangMoBan" {{ old('TrangThai') == 'DangMoBan' ? 'selected' : '' }}>Đang mở bán</option>
                        <option value="DaKetThuc" {{ old('TrangThai') == 'DaKetThuc' ? 'selected' : '' }}>Đã kết thúc</option>
                        <option value="DaHuy" {{ old('TrangThai') == 'DaHuy' ? 'selected' : '' }}>Đã hủy</option>
                    </select>
                    @error('TrangThai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="AnhBia" class="form-label fw-bold">Đường dẫn ảnh bìa</label>
                <input type="text" class="form-control @error('AnhBia') is-invalid @enderror" id="AnhBia" name="AnhBia" value="{{ old('AnhBia') }}" placeholder="assets/images/concert/...">
                @error('AnhBia') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="MoTa" class="form-label fw-bold">Mô tả</label>
                <textarea class="form-control @error('MoTa') is-invalid @enderror" id="MoTa" name="MoTa" rows="3">{{ old('MoTa') }}</textarea>
                @error('MoTa') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="DiemNoiBat" class="form-label fw-bold">Điểm nổi bật</label>
                    <textarea class="form-control @error('DiemNoiBat') is-invalid @enderror" id="DiemNoiBat" name="DiemNoiBat" rows="3">{{ old('DiemNoiBat') }}</textarea>
                    @error('DiemNoiBat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label for="DieuKienVaDieuKhoan" class="form-label fw-bold">Điều kiện và điều khoản</label>
                    <textarea class="form-control @error('DieuKienVaDieuKhoan') is-invalid @enderror" id="DieuKienVaDieuKhoan" name="DieuKienVaDieuKhoan" rows="3">{{ old('DieuKienVaDieuKhoan') }}</textarea>
                    @error('DieuKienVaDieuKhoan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="d-flex justify-content-end mt-4">
                <a href="{{ route('admin.concerts.index') }}" class="btn btn-outline-secondary me-2">
                    <i class="fas fa-times me-1"></i>Hủy
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i>Lưu sự kiện
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
