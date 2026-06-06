@extends('admin.layouts.app')

@section('title', 'Thêm Bài viết')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Thêm Bài viết</h2>
    <a href="{{ route('admin.news.index') }}" class="btn btn-outline-secondary">← Quay lại</a>
</div>

<form method="POST" action="{{ route('admin.news.store') }}" style="max-width:700px">
    @csrf

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="row g-3">
        <div class="col-12">
            <label class="form-label">Tiêu đề <span class="text-danger">*</span></label>
            <input type="text" name="TieuDe" class="form-control" value="{{ old('TieuDe') }}" required>
        </div>

        <div class="col-12">
            <label class="form-label">Nội dung <span class="text-danger">*</span></label>
            <textarea name="NoiDung" class="form-control" rows="10" required>{{ old('NoiDung') }}</textarea>
        </div>

        <div class="col-12">
            <label class="form-label">Ảnh đại diện (đường dẫn)</label>
            <input type="text" name="AnhDaiDien" class="form-control" value="{{ old('AnhDaiDien') }}" placeholder="assets/images/news/...">
        </div>

        <div class="col-md-6">
            <label class="form-label">Danh mục <span class="text-danger">*</span></label>
            <select name="MaDanhMuc" class="form-select" required>
                @forelse($categories as $cat)
                    <option value="{{ $cat->MaDanhMuc }}" {{ old('MaDanhMuc') == $cat->MaDanhMuc ? 'selected' : '' }}>
                        {{ $cat->TenDanhMuc }}
                    </option>
                @empty
                    <option value="1">Mặc định</option>
                @endforelse
            </select>
        </div>

        <div class="col-md-6">
            <label class="form-label">ID nhân viên <span class="text-danger">*</span></label>
            <input type="number" name="MaNhanVien" class="form-control" value="{{ old('MaNhanVien', 1) }}" min="1" required>
            <div class="form-text text-muted">ID của nhân viên đăng bài</div>
        </div>

        <div class="col-12">
            <label class="form-label">Sự kiện liên quan</label>
            <select name="MaSuKienLienQuan" class="form-select">
                <option value="">— Không liên kết —</option>
                @foreach($events as $ev)
                    <option value="{{ $ev->MaSuKien }}" {{ old('MaSuKienLienQuan') == $ev->MaSuKien ? 'selected' : '' }}>
                        {{ $ev->TenSuKien }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="mt-4">
        <button class="btn btn-success">Lưu bài viết</button>
        <a href="{{ route('admin.news.index') }}" class="btn btn-secondary ms-2">Hủy</a>
    </div>
</form>

@endsection
