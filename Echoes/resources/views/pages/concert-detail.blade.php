@extends('layouts.app')

@section('title', $event->title ?? 'Chi tiết concert')

@section('content')
<main class="booking-page">
    <nav class="booking-breadcrumb">
        <a href="{{ url('/') }}">Trang chủ</a>
        <span>/</span>
        <a href="{{ url('/concert') }}">Concert</a>
        <span>/</span>
        <strong>{{ $event->title }}</strong>
    </nav>

    <section class="booking-hero">
        <img src="{{ asset($event->image) }}" alt="{{ $event->title }}">
        <div class="booking-hero-content">
            <span class="booking-kicker">Concert âm nhạc</span>
            <h1>{{ $event->title }}</h1>
            <div class="booking-meta">
                <span>{{ $event->event_date }}</span>
                <span>{{ $event->location }}</span>
                <span>{{ $event->address }}</span>
            </div>
        </div>
    </section>

    <div class="booking-grid">
        <section class="booking-panel">
            <h2>Giới thiệu</h2>
            <p>{!! $event->description !!}</p>

            @if($event->highlights)
                <h3>Điểm nổi bật</h3>
                <ul>
                    @foreach(explode('|', $event->highlights) as $item)
                        <li>{{ $item }}</li>
                    @endforeach
                </ul>
            @endif
        </section>

        <aside class="booking-panel booking-side">
            <div class="booking-side-heading">
                <div>
                    <span class="booking-kicker">Đặt vé</span>
                    <h2>Chọn hạng vé</h2>
                </div>
            </div>

            @if(session('success'))
                <div class="booking-alert">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="booking-alert error">{{ session('error') }}</div>
            @endif

            @forelse($hangVe as $ticket)
                <form method="POST" action="{{ route('cart.add') }}" class="ticket-option">
                    @csrf
                    <input type="hidden" name="MaHangVe" value="{{ $ticket->id }}">
                    <div class="ticket-option-title">{{ $ticket->ticket_name }}</div>
                    <div class="ticket-option-note">{{ $ticket->zone }}</div>
                    <div class="ticket-option-price">{{ number_format($ticket->price, 0, ',', '.') }}đ</div>
                    <div class="ticket-option-note">Còn {{ max(0, $ticket->total - $ticket->sold) }} vé</div>

                    <div class="booking-form-row single">
                        <div>
                            <label>Số lượng</label>
                            <input class="booking-input" type="number" min="1" name="SoLuong" value="1">
                        </div>
                    </div>

                    <button class="booking-button ticket-submit" type="submit">Thêm vào giỏ</button>
                </form>
            @empty
                <div class="booking-empty">
                    Sự kiện chưa có hạng vé. Khi admin thêm hạng vé trong database, khu vực đặt vé sẽ tự hiển thị tại đây.
                </div>
            @endforelse
        </aside>
    </div>
</main>
@endsection
