@extends('admin.layouts.app')

@section('title', 'Quản lý Tin tức')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Quản lý Tin tức</h2>
    <a href="{{ route('admin.news.create') }}" class="btn btn-primary">+ Thêm bài viết</a>
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
            <th>Tiêu đề</th>
            <th>Ảnh</th>
            <th>Danh mục</th>
            <th>Ngày đăng</th>
            <th>Thao tác</th>
        </tr>
    </thead>
    <tbody>
        @forelse($articles as $a)
        <tr>
            <td>{{ $a->id }}</td>
            <td style="max-width:300px">{{ $a->title }}</td>
            <td>
                @if($a->image)
                    <img src="{{ asset($a->image) }}" width="80" style="border-radius:4px">
                @else
                    <span class="text-muted">—</span>
                @endif
            </td>
            <td>{{ $a->category ?? '—' }}</td>
            <td>{{ $a->published_at }}</td>
            <td>
                <a href="{{ route('admin.news.edit', $a->id) }}" class="btn btn-sm btn-outline-primary">Sửa</a>
                <form action="{{ route('admin.news.destroy', $a->id) }}" method="POST" class="d-inline"
                      onsubmit="return confirm('Xóa bài viết này?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger">Xóa</button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" class="text-center text-muted py-4">Chưa có bài viết nào.</td>
        </tr>
        @endforelse
    </tbody>
</table>

@endsection
