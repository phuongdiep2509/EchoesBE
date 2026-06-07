@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')

{{-- Page header --}}
<div class="admin-page-header">
    <h2><i class="fas fa-th-large me-2"></i>Dashboard</h2>
    <span style="font-size:0.8rem;opacity:0.7">
        {{ now()->format('l, d/m/Y') }}
    </span>
</div>

{{-- Stat cards --}}
<div class="row g-4 mb-5">

    <div class="col-sm-6 col-xl-3">
        <div class="echoes-stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-number">—</div>
                    <div class="stat-label">Sự kiện concert</div>
                </div>
                <i class="fas fa-calendar-alt stat-icon"></i>
            </div>
            <a href="{{ route('admin.concerts.index') }}"
               style="font-size:0.78rem;color:var(--echoes-green);margin-top:10px;display:inline-block">
                Quản lý →
            </a>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="echoes-stat-card red">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-number red">—</div>
                    <div class="stat-label">Nhạc sống</div>
                </div>
                <i class="fas fa-music stat-icon"></i>
            </div>
            <a href="{{ route('admin.music.index') }}"
               style="font-size:0.78rem;color:var(--echoes-red);margin-top:10px;display:inline-block">
                Quản lý →
            </a>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="echoes-stat-card beige">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-number">—</div>
                    <div class="stat-label">Merchandise</div>
                </div>
                <i class="fas fa-shopping-bag stat-icon"></i>
            </div>
            <a href="{{ route('admin.merchandise.index') }}"
               style="font-size:0.78rem;color:var(--echoes-green);margin-top:10px;display:inline-block">
                Quản lý →
            </a>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="echoes-stat-card olive">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-number">—</div>
                    <div class="stat-label">Khách hàng</div>
                </div>
                <i class="fas fa-users stat-icon"></i>
            </div>
            <a href="{{ route('admin.khach-hang.index') }}"
               style="font-size:0.78rem;color:var(--echoes-green);margin-top:10px;display:inline-block">
                Quản lý →
            </a>
        </div>
    </div>

</div>

{{-- Quick links --}}
<div class="row g-4">

    @php
        $links = [
            ['icon'=>'fa-calendar-alt','label'=>'Concert',     'route'=>'admin.concerts.index',    'create'=>'admin.concerts.create'],
            ['icon'=>'fa-music',       'label'=>'Nhạc sống',   'route'=>'admin.music.index',       'create'=>'admin.music.create'],
            ['icon'=>'fa-shopping-bag','label'=>'Merchandise', 'route'=>'admin.merchandise.index', 'create'=>'admin.merchandise.create'],
            ['icon'=>'fa-newspaper',   'label'=>'Tin tức',     'route'=>'admin.news.index',        'create'=>'admin.news.create'],
        ];
    @endphp

    @foreach($links as $l)
    <div class="col-sm-6 col-lg-3">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center gap-2">
                <i class="fas {{ $l['icon'] }}" style="color:var(--echoes-red)"></i>
                {{ $l['label'] }}
            </div>
            <div class="card-body d-flex flex-column gap-2">
                <a href="{{ route($l['route']) }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-list me-1"></i> Danh sách
                </a>
                <a href="{{ route($l['create']) }}" class="btn btn-outline-danger btn-sm">
                    <i class="fas fa-plus me-1"></i> Thêm mới
                </a>
            </div>
        </div>
    </div>
    @endforeach

</div>

@endsection
