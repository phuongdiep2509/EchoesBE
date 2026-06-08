@extends('layouts.app')

@section('title', 'Concert Âm Nhạc | Echoes')

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/concert.css') }}">
@endsection

@section('content')

<div class="bg-wrapper">

    {{-- ── HERO ──────────────────────────────────────── --}}
    <section class="page-hero">
        <div class="section-content">

            <div class="breadcrumb-pill">
                <a href="{{ url('/') }}" style="color:white;text-decoration:none">TRANG CHỦ</a>
                &nbsp;/&nbsp; CONCERT
            </div>

            <h1 class="page-title">CONCERT</h1>

            <p class="page-desc">
                Hãy cùng hòa mình vào không khí rực lửa của đêm concert để thưởng thức
                những màn trình diễn bùng nổ và kết nối trực tiếp với nguồn năng lượng vô tận
                từ nghệ sĩ yêu thích. Những vị trí đẹp nhất đang dần lấp đầy – hãy đặt vé
                ngay hôm nay để cùng hàng ngàn khán giả tạo nên một đêm cháy hết mình!
            </p>

            <div class="soft-line"></div>

        </div>
    </section>

    {{-- ── ĐANG THỊNH HÀNH ───────────────────────────── --}}
    @php $trending = $concerts->take(3); @endphp
    @if($trending->count() > 0)
    <section class="section-1">
        <div class="section-content">

            <h3 class="sub-title">ĐANG THỊNH HÀNH</h3>

            <div class="trending-list">
                @foreach($trending as $c)
                    @php
                        $isPast = $c->event_end
                            ? \Carbon\Carbon::parse($c->event_end)->isPast()
                            : ($c->event_date && \Carbon\Carbon::parse($c->event_date)->isPast());
                    @endphp
                    <a href="{{ url('/concert/' . $c->id) }}"
                       class="trending-item {{ $isPast ? 'expired-event' : '' }}"
                       style="text-decoration:none;color:inherit">

                        <div class="trending-thumb">
                            <img src="{{ asset($c->image ?? 'assets/images/concert/default.png') }}"
                                 alt="{{ $c->title }}">
                        </div>

                        <div class="trending-info">
                            <h4 style="color:var(--color-green)">{{ $c->title }}</h4>
                            <p class="price" style="color:var(--color-red);font-weight:700">
                                Xem chi tiết
                            </p>
                            <p class="date">
                                <img src="{{ asset('assets/images/index/calendar-icon.png') }}"
                                     style="height:12px;margin-right:4px" alt="">
                                @if($c->event_date)
                                    {{ \Carbon\Carbon::parse($c->event_date)->format('H:i, d/m/Y') }}
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

    <h3 class="sub-title">TẤT CẢ SỰ KIỆN CONCERT</h3>

    @if($concerts->isEmpty())
        <div style="text-align:center;padding:60px 0;color:#999">
            <p>Hiện chưa có sự kiện nào đang mở bán.</p>
        </div>
    @else
        <div class="event-list">
            @foreach($concerts as $c)
                @php
                    $isPast = $c->event_end
                        ? \Carbon\Carbon::parse($c->event_end)->isPast()
                        : ($c->event_date && \Carbon\Carbon::parse($c->event_date)->isPast());
                    $isSoldOut = ($c->status ?? '') === 'DaKetThuc' || ($c->status ?? '') === 'DaHuy';
                @endphp
                <div class="event-wrapper {{ $isPast || $isSoldOut ? 'expired-event' : '' }}">

                    <div class="event-thumb">
                        <img src="{{ asset($c->image ?? 'assets/images/concert/default.png') }}"
                             alt="{{ $c->title }}"
                             style="width:100%;height:100%;object-fit:cover;border-radius:14px">
                    </div>

                    <div class="event-content" style="padding-top:10px">
                        <h4>{{ $c->title }}</h4>
                        <p>Xem chi tiết</p>
                        <span>
                            <img src="{{ asset('assets/images/index/calendar-icon.png') }}"
                                 style="height:10px;margin-right:3px" alt="">
                            @if($c->event_date)
                                {{ \Carbon\Carbon::parse($c->event_date)->format('d/m/Y - H:i') }}
                            @else
                                Đang cập nhật
                            @endif
                        </span>

                        @if($isPast || $isSoldOut)
                            <button class="btn expired" disabled>ĐÃ HẾT THỜI GIAN</button>
                        @else
                            <a href="{{ url('/concert/' . $c->id) }}" style="text-decoration:none">
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
