@extends('admin.layouts.app')

@section('title', 'Quản lý Sự kiện Âm nhạc')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Quản lý Nhạc Sống</h2>
    <a href="{{ route('admin.music.create') }}" class="btn btn-primary">+ Thêm sự kiện</a>
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
            <th>Tên sự kiện</th>
            <th>Ảnh</th>
            <th>Thời gian bắt đầu</th>
            <th>Địa điểm</th>
            <th>Trạng thái</th>
            <th>Thao tác</th>
        </tr>
    </thead>
    <tbody>
        @forelse($events as $e)
        <tr>
            <td>{{ $e->MaSuKien }}</td>
            <td>{{ $e->TenSuKien }}</td>
            <td>
                @if($e->AnhBia)
                    <img src="{{ asset($e->AnhBia) }}" width="80" style="border-radius:4px">
                @else
                    <span class="text-muted">—</span>
                @endif
            </td>
            <td>{{ $e->ThoiGianBatDau }}</td>
            <td>{{ optional($e->diaDiem)->TenDiaDiem ?? '—' }}</td>
            <td>
                @php
                    $badges = ['SapDienRa'=>'warning','DangMoBan'=>'success','DaKetThuc'=>'secondary','DaHuy'=>'danger'];
                    $labels = ['SapDienRa'=>'Sắp diễn ra','DangMoBan'=>'Đang mở bán','DaKetThuc'=>'Đã kết thúc','DaHuy'=>'Đã hủy'];
                @endphp
                <span class="badge bg-{{ $badges[$e->TrangThai] ?? 'secondary' }}">
                    {{ $labels[$e->TrangThai] ?? $e->TrangThai }}
                </span>
            </td>
            <td>
                <a href="{{ route('admin.music.edit', $e->MaSuKien) }}" class="btn btn-sm btn-outline-primary">Sửa</a>
                <form action="{{ route('admin.music.destroy', $e->MaSuKien) }}" method="POST" class="d-inline"
                      onsubmit="return confirm('Xóa sự kiện này?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger">Xóa</button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7" class="text-center text-muted py-4">Chưa có sự kiện nào.</td>
        </tr>
        @endforelse
    </tbody>
</table>

@endsection
