@extends('layouts.app')

@section('title', 'Concert Âm Nhạc | Echoes')

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/concert.css') }}">
@endsection

@section('content')

<div class="bg-wrapper">

    <!-- HERO -->
    <section class="page-hero">
        <div class="section-content">
            <div class="breadcrumb-pill">
                <a href="{{ url('/') }}">TRANG CHỦ</a> / CONCERT
            </div>
            <h1 class="page-title">CONCERT ÂM NHẠC</h1>
            <p class="page-desc">
                Hãy cùng hòa mình vào không khí rực lửa của đêm concert.
            </p>
            <div class="soft-line"></div>
        </div>
    </section>

    <!-- CONCERT LIST -->
    <section class="container" style="padding-top:40px; padding-bottom:60px">

        @if($concerts->isEmpty())
            <div style="text-align:center; padding:60px 0; color:#999">
                <p>Hiện chưa có sự kiện nào đang mở bán.</p>
            </div>
        @else
            <div class="music-grid">
                @foreach($concerts as $c)
                    <x-concert-card
                        :title="$c->title"
                        :location="$c->location ?? $c->city ?? 'Đang cập nhật'"
                        :price="'Xem chi tiết'"
                        :date="\Carbon\Carbon::parse($c->event_date)->format('d/m/Y - H:i')"
                        :image="$c->image ?? 'assets/images/concert/default.png'"
                        :link="url('/concert/' . $c->id)"
                    />
                @endforeach
            </div>
        @endif

    </section>

</div>

@endsection
