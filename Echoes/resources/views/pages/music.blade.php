@extends('layouts.app')

@section('title', 'Nhạc Sống | Echoes')

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/music.css') }}">
@endsection

@section('content')

<div class="bg-wrapper">

    {{-- HERO --}}
    <section class="page-hero">
        <div class="section-content">

            <div class="breadcrumb-pill">
                <a href="{{ url('/') }}">TRANG CHỦ</a> / NHẠC SỐNG
            </div>

            <h1 class="page-title">NHẠC SỐNG</h1>

            <p class="page-desc">
                Hãy cùng hòa mình vào không gian nghệ thuật sống động để thưởng thức những thanh âm
                nguyên bản và giai điệu bất hủ, nơi tâm hồn bạn được vỗ về bởi các nghệ sĩ tài hoa.
            </p>

            <div class="soft-line"></div>

        </div>
    </section>

    {{-- DANH SÁCH SỰ KIỆN --}}
    <section class="container" style="padding-top:40px; padding-bottom:60px">

        @if($events->isEmpty())
            <div style="text-align:center; padding:60px 0; color:#999">
                <p>Hiện chưa có sự kiện nhạc sống nào đang mở bán.</p>
            </div>
        @else
            <div class="music-grid">
                @foreach($events as $e)
                    <x-music-card
                        :title="$e->title"
                        :location="$e->location ?? $e->city ?? 'Đang cập nhật'"
                        :price="'Xem chi tiết'"
                        :date="\Carbon\Carbon::parse($e->event_date)->format('d/m/Y - H:i')"
                        :image="$e->image ?? 'assets/images/music/default.png'"
                        :link="url('/music/' . $e->id)"
                    />
                @endforeach
            </div>
        @endif

    </section>

</div>

@endsection
