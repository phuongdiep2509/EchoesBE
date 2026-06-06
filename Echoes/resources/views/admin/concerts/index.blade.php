@extends('admin.layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Quản lý Concert</h2>
    <a href="{{ route('admin.concerts.create') }}" class="btn btn-primary">+ Thêm Concert</a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table table-bordered table-hover">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Tên sự kiện</th>
            <th>Ảnh</th>
            <th>Thời gian bắt đầu</th>
            <th>Trạng thái</th>
            <th>Thao tác</th>
        </tr>
    </thead>
    <tbody>
        @forelse($concerts as $c)
        <tr>
            <td>{{ $c->MaSuKien }}</td>
            <td>{{ $c->TenSuKien }}</td>
            <td>
                @if($c->AnhBia)
                    <img src="{{ asset($c->AnhBia) }}" width="80" style="border-radius:4px">
                @else
                    <span class="text-muted">—</span>
                @endif
            </td>
            <td>{{ $c->ThoiGianBatDau }}</td>
            <td>
                @php
                    $badges = [
                        'SapDienRa'  => 'warning',
                        'DangMoBan'  => 'success',
                        'DaKetThuc'  => 'secondary',
                        'DaHuy'      => 'danger',
                    ];
                    $labels = [
                        'SapDienRa'  => 'Sắp diễn ra',
                        'DangMoBan'  => 'Đang mở bán',
                        'DaKetThuc'  => 'Đã kết thúc',
                        'DaHuy'      => 'Đã hủy',
                    ];
                @endphp
                <span class="badge bg-{{ $badges[$c->TrangThai] ?? 'secondary' }}">
                    {{ $labels[$c->TrangThai] ?? $c->TrangThai }}
                </span>
            </td>
            <td>
                <a href="{{ route('admin.concerts.edit', $c->MaSuKien) }}" class="btn btn-sm btn-outline-primary">Sửa</a>

                <form action="{{ route('admin.concerts.destroy', $c->MaSuKien) }}" method="POST" class="d-inline"
                      onsubmit="return confirm('Xóa sự kiện này?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger">Xóa</button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" class="text-center text-muted">Chưa có sự kiện nào.</td>
        </tr>
        @endforelse
    </tbody>
</table>

@endsection
