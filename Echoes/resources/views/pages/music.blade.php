@extends('layouts.app')

@section('title', 'Nhạc Sống | Echoes')

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/music.css') }}">
@endsection

@section('content')

<div class="bg-wrapper">

    {{-- ── HERO ──────────────────────────────────────── --}}
    <section class="page-hero">
        <div class="section-content">

            <div class="breadcrumb-pill">
                <a href="{{ url('/') }}" style="color:white;text-decoration:none">TRANG CHỦ</a>
                &nbsp;/&nbsp; NHẠC SỐNG
            </div>

            <h1 class="page-title">NHẠC SỐNG</h1>

            <p class="page-desc">
                Hãy cùng hòa mình vào không gian nghệ thuật sống động để thưởng thức những thanh âm
                nguyên bản và giai điệu bất hủ, nơi tâm hồn bạn được vỗ về bởi các nghệ sĩ tài hoa.
                Những vị trí đẹp nhất đang chờ đón bạn – hãy đặt vé ngay hôm nay để cùng chúng tôi
                tận hưởng một đêm nhạc đầy mê hoặc và đáng nhớ!
            </p>

            <div class="soft-line"></div>

        </div>
    </section>

    {{-- ── ĐANG THỊNH HÀNH ───────────────────────────── --}}
    @php $trending = $events->take(3); @endphp
    @if($trending->count() > 0)
    <section class="section-1">
        <div class="section-content">

            <h3 class="sub-title">ĐANG THỊNH HÀNH</h3>

            <div class="trending-list">
                @foreach($trending as $e)
                    @php
                        $isPast = $e->event_date && \Carbon\Carbon::parse($e->event_date)->isPast();
                    @endphp
                    <a href="{{ url('/music/' . $e->id) }}"
                       class="trending-item {{ $isPast ? 'expired-event' : '' }}"
                       style="text-decoration:none;color:inherit">

                        <div class="trending-thumb">
                            <img src="{{ asset($e->image ?? 'assets/images/music/default.png') }}"
                                 alt="{{ $e->title }}">
                        </div>

                        <div class="trending-info">
                            <h4 style="color:var(--color-green)">{{ $e->title }}</h4>
                            <p class="price" style="color:var(--color-red);font-weight:700">
                                Xem chi tiết
                            </p>
                            <p class="date">
                                <img src="{{ asset('assets/images/index/calendar-icon.png') }}"
                                     style="height:12px;margin-right:4px" alt="">
                                @if($e->event_date)
                                    {{ \Carbon\Carbon::parse($e->event_date)->format('H:i, d/m/Y') }}
                                @else
                                    Đang cập nhật
                                @endif
                            </p>
                        </div>

                    </a>
                @endforeach
            </div>

        </div>
    </section>
    @endif

</div>{{-- /.bg-wrapper --}}

{{-- ── TẤT CẢ SỰ KIỆN ─────────────────────────────── --}}
<div class="title-indent">

    <h3 class="sub-title">TẤT CẢ SỰ KIỆN NHẠC SỐNG</h3>

    @if($events->isEmpty())
        <div style="text-align:center;padding:60px 0;color:#999">
            <p>Hiện chưa có sự kiện nhạc sống nào đang mở bán.</p>
        </div>
    @else
        <div class="event-list">
            @foreach($events as $e)
                @php
                    $isPast   = $e->event_date && \Carbon\Carbon::parse($e->event_date)->isPast();
                    $isSoldOut = ($e->status ?? '') === 'DaKetThuc' || ($e->status ?? '') === 'DaHuy';
                @endphp
                <div class="event-wrapper {{ $isPast || $isSoldOut ? 'expired-event' : '' }}">

                    <div class="event-thumb">
                        <img src="{{ asset($e->image ?? 'assets/images/music/default.png') }}"
                             alt="{{ $e->title }}"
                             style="width:100%;height:100%;object-fit:cover;border-radius:14px">
                    </div>

                    <div class="event-content" style="padding-top:10px">
                        <h4>{{ $e->title }}</h4>
                        <p>Xem chi tiết</p>
                        <span>
                            <img src="{{ asset('assets/images/index/calendar-icon.png') }}"
                                 style="height:10px;margin-right:3px" alt="">
                            @if($e->event_date)
                                {{ \Carbon\Carbon::parse($e->event_date)->format('d/m/Y - H:i') }}
                            @else
                                Đang cập nhật
                            @endif
                        </span>

                        @if($isPast || $isSoldOut)
                            <button class="btn expired" disabled>ĐÃ HẾT THỜI GIAN</button>
                        @else
                            <a href="{{ url('/music/' . $e->id) }}" style="text-decoration:none">
                                <button class="btn buy">MUA NGAY</button>
                            </a>
                        @endif
                    </div>

                </div>
            @endforeach
        </div>
    @endif

</div>

@endsection
