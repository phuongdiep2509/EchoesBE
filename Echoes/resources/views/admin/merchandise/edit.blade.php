@extends('admin.layouts.app')

@section('title', 'Chỉnh sửa Merchandise')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Chỉnh sửa: {{ $product->TenMerch }}</h2>
    <a href="{{ route('admin.merchandise.index') }}" class="btn btn-outline-secondary">← Quay lại</a>
</div>

<form method="POST" action="{{ route('admin.merchandise.update', $product->MaMerch) }}" style="max-width:700px" onsubmit="return confirmSaveProduct()">
    @csrf
    @method('PUT')

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="row g-3">
        <div class="col-12">
            <label class="form-label">Tên sản phẩm <span class="text-danger">*</span></label>
            <input type="text" name="TenMerch" class="form-control"
                value="{{ old('TenMerch', $product->TenMerch) }}" required>
        </div>

        <div class="col-12">
            <label class="form-label">Mô tả</label>
            <textarea name="MoTa" class="form-control" rows="3">{{ old('MoTa', $product->MoTa) }}</textarea>
        </div>

        <div class="col-md-6">
            <label class="form-label">Giá bán (₫) <span class="text-danger">*</span></label>
            <input type="number" name="GiaBan" class="form-control"
                value="{{ old('GiaBan', $product->GiaBan) }}" min="0" step="1000" required>
        </div>

        <div class="col-md-6">
            <label class="form-label">Số lượng tồn kho</label>
            <input type="number" name="SoLuongTon" class="form-control"
                value="{{ old('SoLuongTon', $product->SoLuongTon) }}" min="0">
        </div>

        <div class="col-12">
            <label class="form-label">Ảnh sản phẩm (đường dẫn) <span class="text-danger">*</span></label>
            <input type="text" name="AnhSanPham" class="form-control"
                value="{{ old('AnhSanPham', $product->AnhSanPham) }}" placeholder="assets/images/merch/..." required>
            @if($product->AnhSanPham)
                <img src="{{ asset($product->AnhSanPham) }}" class="mt-2" style="height:80px;border-radius:4px">
            @endif
        </div>

        <div class="col-md-6">
            <label class="form-label">Trạng thái</label>
            <select name="TrangThai" class="form-select">
                <option value="DangBan"  {{ old('TrangThai', $product->TrangThai) == 'DangBan'  ? 'selected' : '' }}>Đang bán</option>
                <option value="NgungBan" {{ old('TrangThai', $product->TrangThai) == 'NgungBan' ? 'selected' : '' }}>Ngừng bán</option>
            </select>
        </div>

        <div class="col-12">
            <label class="form-label">Chính sách đổi trả</label>
            <textarea name="ChinhSachDoiTra" class="form-control" rows="3">{{ old('ChinhSachDoiTra', $product->ChinhSachDoiTra) }}</textarea>
        </div>

        <div class="col-12">
            <label class="form-label">Hướng dẫn bảo quản</label>
            <textarea name="HuongDanBaoQuan" class="form-control" rows="3">{{ old('HuongDanBaoQuan', $product->HuongDanBaoQuan) }}</textarea>
        </div>
    </div>

    <div class="mt-4">
        <button class="btn btn-primary">Cập nhật</button>
        <a href="{{ route('admin.merchandise.index') }}" class="btn btn-secondary ms-2">Hủy</a>
    </div>
</form>

<script>
function confirmSaveProduct() {
    return confirm('Bạn có chắc muốn lưu thông tin sản phẩm này không?');
}
</script>

@endsection
