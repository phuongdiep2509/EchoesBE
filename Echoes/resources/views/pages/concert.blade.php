@extends('layouts.app')

@section('title', 'Concert Âm Nhạc | Echoes')

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/concert.css') }}">
    <style>
        .pagination-wrap { display:flex; justify-content:center; align-items:center; gap:6px; margin:40px 0 20px; flex-wrap:wrap; }
        .page-btn { display:inline-flex; align-items:center; justify-content:center; min-width:38px; height:38px; padding:0 10px; border-radius:10px; background:#fff; border:1px solid #ddd; color:#111827; font-size:14px; font-weight:600; text-decoration:none; cursor:pointer; transition:all .2s; }
        .page-btn:hover { background:#74070d; color:#fff; border-color:#74070d; }
        .page-btn.active { background:#74070d; color:#fff; border-color:#74070d; }
        .page-btn.disabled { opacity:.4; cursor:not-allowed; pointer-events:none; }
    </style>
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
    @php $trending = $concerts->getCollection()->take(3); @endphp
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

        {{-- Pagination --}}
        @if($concerts->hasPages())
            <div class="pagination-wrap">
                @if($concerts->onFirstPage())
                    <span class="page-btn disabled">&#8249;</span>
                @else
                    <a class="page-btn" href="{{ $concerts->previousPageUrl() }}">&#8249;</a>
                @endif

                @foreach($concerts->getUrlRange(1, $concerts->lastPage()) as $page => $url)
                    @if($page == $concerts->currentPage())
                        <span class="page-btn active">{{ $page }}</span>
                    @else
                        <a class="page-btn" href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach

                @if($concerts->hasMorePages())
                    <a class="page-btn" href="{{ $concerts->nextPageUrl() }}">&#8250;</a>
                @else
                    <span class="page-btn disabled">&#8250;</span>
                @endif
            </div>
        @endif
    @endif

</div>

@endsection
