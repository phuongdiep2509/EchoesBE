@extends('admin.layouts.app')

@section('title', 'Quản lý Merchandise')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Quản lý Merchandise</h2>
    <a href="{{ route('admin.merchandise.create') }}" class="btn btn-primary">+ Thêm sản phẩm</a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<table class="table table-bordered table-hover">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Tên sản phẩm</th>
            <th>Ảnh</th>
            <th>Giá</th>
            <th>Tồn kho</th>
            <th>Trạng thái</th>
            <th>Thao tác</th>
        </tr>
    </thead>
    <tbody>
        @forelse($products as $p)
        <tr>
            <td>{{ $p->MaMerch }}</td>
            <td>{{ $p->TenMerch }}</td>
            <td>
                @if($p->AnhSanPham)
                    <img src="{{ asset($p->AnhSanPham) }}" width="80" style="border-radius:4px">
                @else
                    <span class="text-muted">—</span>
                @endif
            </td>
            <td>{{ number_format($p->GiaBan, 0, ',', '.') }}₫</td>
            <td>
                <span class="{{ $p->SoLuongTon <= 5 ? 'text-danger fw-bold' : '' }}">
                    {{ $p->SoLuongTon }}
                </span>
            </td>
            <td>
                @php
                    $badges = ['DangBan'=>'success','NgungBan'=>'secondary'];
                    $labels = ['DangBan'=>'Đang bán','NgungBan'=>'Ngừng bán'];
                @endphp
                <span class="badge bg-{{ $badges[$p->TrangThai] ?? 'secondary' }}">
                    {{ $labels[$p->TrangThai] ?? $p->TrangThai }}
                </span>
            </td>
            <td>
                <a href="{{ route('admin.merchandise.edit', $p->MaMerch) }}" class="btn btn-sm btn-outline-primary">Sửa</a>
                <form action="{{ route('admin.merchandise.destroy', $p->MaMerch) }}" method="POST" class="d-inline"
                      onsubmit="return confirm('Xóa sản phẩm này?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger">Xóa</button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7" class="text-center text-muted py-4">Chưa có sản phẩm nào.</td>
        </tr>
        @endforelse
    </tbody>
</table>

@endsection
