@extends('admin.layouts.app')
@section('title', 'Cập nhật hạng vé')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0 fw-bold"><i class="fas fa-edit me-2 text-primary"></i>Cập nhật hạng vé</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.hang-ve.index') }}" class="text-decoration-none">Hạng vé</a></li>
                <li class="breadcrumb-item active" aria-current="page">Chỉnh sửa (#{{ $ticket->MaHangVe }})</li>
            </ol>
        </nav>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <form action="{{ route('admin.hang-ve.update', $ticket->MaHangVe) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold text-muted">Thuộc Sự kiện</label>
                    <input type="text" class="form-control" value="{{ $ticket->khuVuc->concert->TenSuKien ?? '' }}" disabled>
                    <small class="text-muted">Không thể thay đổi sự kiện sau khi đã tạo.</small>
                </div>
                <div class="col-md-6">
                    <label for="KhuVuc" class="form-label fw-bold">Khu vực <span class="text-danger">*</span></label>
                    <input type="text" list="khuVucList" class="form-control @error('KhuVuc') is-invalid @enderror" id="KhuVuc" name="KhuVuc" value="{{ old('KhuVuc', $ticket->khuVuc->TenKhuVuc ?? '') }}" required>
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
                    <input type="text" class="form-control @error('TenHangVe') is-invalid @enderror" id="TenHangVe" name="TenHangVe" value="{{ old('TenHangVe', $ticket->TenHangVe) }}" required>
                    @error('TenHangVe')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="GiaVe" class="form-label fw-bold">Giá vé (VNĐ) <span class="text-danger">*</span></label>
                    <input type="number" class="form-control @error('GiaVe') is-invalid @enderror" id="GiaVe" name="GiaVe" value="{{ old('GiaVe', $ticket->GiaVe) }}" min="0" required>
                    @error('GiaVe')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label for="SoLuongMoBan" class="form-label fw-bold">Số lượng mở bán <span class="text-danger">*</span></label>
                    <input type="number" class="form-control @error('SoLuongMoBan') is-invalid @enderror" id="SoLuongMoBan" name="SoLuongMoBan" value="{{ old('SoLuongMoBan', $ticket->SoLuongMoBan) }}" min="1" required>
                    @error('SoLuongMoBan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label for="SoLuongDaBan" class="form-label fw-bold">Số lượng đã bán <span class="text-danger">*</span></label>
                    <input type="number" class="form-control @error('SoLuongDaBan') is-invalid @enderror" id="SoLuongDaBan" name="SoLuongDaBan" value="{{ old('SoLuongDaBan', $ticket->SoLuongDaBan) }}" min="0" required>
                    @error('SoLuongDaBan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex justify-content-end mt-4">
                <a href="{{ route('admin.hang-ve.index') }}" class="btn btn-outline-secondary me-2">
                    <i class="fas fa-times me-1"></i>Hủy
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i>Cập nhật
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
