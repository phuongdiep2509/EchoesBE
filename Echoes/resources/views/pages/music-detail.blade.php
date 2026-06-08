@extends('layouts.app')

@section('title', ($event->title ?? $music->title ?? 'Chi tiết') . ' | Echoes')

@php
    // Normalize: controller passes $event, legacy view used $music
    if (!isset($music) && isset($event)) { $music = $event; }
    if (!isset($event) && isset($music)) { $event = $music; }
@endphp

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/css/eventDetail.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/eventDetailRe.css') }}">
<style>
    /* ── Breadcrumb bar ───────────────────────────── */
.detail-breadcrumb {
    background: var(--color-green, #46462a);
    color: #fff;
    padding: 12px 0;
    margin-top: 100px;   /* clear fixed header */
}
.detail-breadcrumb .inner {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
    font-size: 0.78rem;
    letter-spacing: 1px;
    text-transform: uppercase;
    display: flex;
    align-items: center;
    gap: 6px;
    flex-wrap: wrap;
}
.detail-breadcrumb a { color: rgba(255,255,255,.75); text-decoration: none; }
.detail-breadcrumb a:hover { color: #fff; }
.detail-breadcrumb .sep { color: rgba(255,255,255,.4); }
.detail-breadcrumb .current { color: #fff; font-weight: 600; }

/* ── Main wrapper ─────────────────────────────── */
.detail-wrap {
    max-width: 1200px;
    margin: 32px auto 60px;
    padding: 0 20px;
    display: grid;
    grid-template-columns: 1fr 340px;
    gap: 32px;
    align-items: start;
}
@media (max-width: 900px) {
    .detail-wrap { grid-template-columns: 1fr; }
}

/* ── Poster ───────────────────────────────────── */
.detail-poster {
    border-radius: 12px;
    overflow: hidden;
    margin-bottom: 20px;
    position: relative;
}
.detail-poster img {
    width: 100%;
    display: block;
    max-height: 480px;
    object-fit: cover;
}
.detail-poster .status-pill {
    position: absolute;
    top: 14px; right: 14px;
    background: var(--color-red, #74070d);
    color: #fff;
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
    padding: 5px 12px;
    border-radius: 999px;
}

/* ── Section header bar ───────────────────────── */
.section-bar {
    background: var(--color-green, #46462a);
    color: #fff;
    padding: 10px 16px;
    border-radius: 4px;
    font-size: 0.9rem;
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 8px;
}

/* ── Info card ────────────────────────────────── */
.detail-info-card {
    background: #fff;
    border-radius: 12px;
    border: 1px solid rgba(0,0,0,.07);
    padding: 24px;
    margin-bottom: 20px;
}
.detail-title {
    color: var(--color-red, #74070d);
    font-size: 1.9rem;
    font-weight: 900;
    margin-bottom: 20px;
    line-height: 1.15;
}
.detail-meta-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    margin-bottom: 0;
}
.detail-meta-item { display: flex; flex-direction: column; gap: 2px; }
.detail-meta-label {
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    color: #888;
}
.detail-meta-value {
    font-size: 0.95rem;
    font-weight: 600;
    color: #222;
    display: flex;
    align-items: center;
    gap: 6px;
}
.detail-meta-value .star { color: var(--color-red, #74070d); font-size: 0.8rem; }

/* ── Text sections ────────────────────────────── */
.detail-section {
    background: #fff;
    border-radius: 12px;
    border: 1px solid rgba(0,0,0,.07);
    padding: 20px 24px;
    margin-bottom: 20px;
}
.detail-section p {
    font-size: 0.95rem;
    color: #444;
    line-height: 1.75;
    margin-bottom: 14px;
}
.detail-section p:last-child { margin-bottom: 0; }
.highlights-list {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: 8px;
}
.highlights-list li {
    font-size: 0.9rem;
    color: #333;
    display: flex;
    gap: 10px;
    padding-bottom: 8px;
    border-bottom: 1px solid rgba(0,0,0,.05);
}
.highlights-list li:last-child { border-bottom: none; padding-bottom: 0; }

/* Terms section inside detail-section */
.terms-block { margin-top: 12px; }
.terms-block h4 {
    font-size: 0.82rem;
    font-weight: 700;
    text-transform: uppercase;
    color: var(--color-red, #74070d);
    margin-bottom: 6px;
}
.terms-block p, .terms-block ul {
    font-size: 0.83rem;
    color: #555;
    line-height: 1.6;
}
.terms-block ul { padding-left: 16px; margin: 4px 0; }

/* ── RIGHT: Booking card ──────────────────────── */
.booking-card-new {
    background: #fff;
    border-radius: 12px;
    border: 2px solid var(--color-green, #46462a);
    overflow: hidden;
    position: sticky;
    top: calc(100px + 16px);
}
.booking-card-header {
    background: var(--color-green, #46462a);
    color: #fff;
    padding: 14px 20px;
    font-size: 1rem;
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
    text-align: center;
}
.booking-card-body { padding: 20px; }

/* Price table */
.price-section-title {
    color: var(--color-red, #74070d);
    font-weight: 700;
    font-size: 0.85rem;
    text-align: center;
    margin-bottom: 10px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.price-table { width: 100%; border-collapse: collapse; margin-bottom: 6px; }
.price-table tr {
    cursor: pointer;
    transition: background .15s;
}
.price-table tr:hover { background: rgba(116,7,13,.04); }
.price-table tr.selected { background: rgba(116,7,13,.08); }
.price-table td {
    padding: 11px 12px;
    font-size: 0.9rem;
    border-bottom: 1px solid rgba(0,0,0,.05);
}
.price-table td:last-child {
    text-align: right;
    font-weight: 700;
    color: #a00a12;
    white-space: nowrap;
}
.price-table td:first-child { font-weight: 600; color: #222; }
.price-table tr:last-child td { border-bottom: none; }
.price-min {
    font-size: 0.8rem;
    color: #888;
    text-align: center;
    padding: 6px 0 12px;
    border-top: 1px solid rgba(0,0,0,.05);
}

/* Buy button */
.btn-buy-now {
    display: block;
    width: 100%;
    padding: 14px;
    background: linear-gradient(135deg, #a00a12, #74070d);
    color: #fff;
    font-size: 1rem;
    font-weight: 800;
    text-align: center;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    letter-spacing: 1px;
    text-transform: uppercase;
    text-decoration: none;
    box-shadow: 0 6px 20px rgba(116,7,13,.3);
    transition: opacity .2s, transform .2s;
    margin-bottom: 10px;
}
.btn-buy-now:hover { opacity: .9; transform: translateY(-2px); text-decoration: none; color: #fff; }

/* Notes */
.note-box {
    background: #fdf8f8;
    border-radius: 8px;
    padding: 14px;
    font-size: 0.8rem;
    color: #555;
    border: 1px solid rgba(116,7,13,.08);
    margin-top: 10px;
}
.note-box .note-title {
    font-weight: 700;
    color: var(--color-red, #74070d);
    margin-bottom: 6px;
    display: flex;
    align-items: center;
    gap: 5px;
}
.note-box ul { list-style: none; padding: 0; margin: 0; line-height: 1.9; }
</style>    
@endsection

@section('content')

{{-- ── Breadcrumb ──────────────────────────────── --}}
<div class="detail-breadcrumb">
    <div class="inner">
        <a href="{{ url('/') }}">TRANG CHỦ</a>
        <span class="sep">/</span>
        <a href="{{ url('/music') }}">NHẠC SỐNG</a>
        <span class="sep">/</span>
        <span class="current">{{ \Illuminate\Support\Str::limit($music->title ?? '', 60) }}</span>
    </div>
</div>

{{-- ── 2-column layout ─────────────────────────── --}}
<div class="detail-wrap">

    {{-- ═══════ LEFT ═══════ --}}
    <div>

        {{-- Poster --}}
        <div class="detail-poster">
            @if(!empty($music->image))
                <img src="{{ asset($music->image) }}" alt="{{ $music->title }}">
            @else
                <div style="width:100%;height:360px;background:var(--color-beige,#e1cfac);
                            display:flex;align-items:center;justify-content:center;font-size:3rem">
                    🎵
                </div>
            @endif
            @php
                $statusLabel = match($music->status ?? '') {
                    'SapDienRa' => 'SẮP DIỄN RA',
                    'DangMoBan' => 'ĐANG MỞ BÁN',
                    'DaKetThuc' => 'ĐÃ KẾT THÚC',
                    'DaHuy'     => 'ĐÃ HỦY',
                    default     => 'MỞ BÁN',
                };
            @endphp
            <span class="status-pill">{{ $statusLabel }}</span>
        </div>

        {{-- Thông tin sự kiện --}}
        <div class="detail-info-card">
            <div class="section-bar">✶ THÔNG TIN SỰ KIỆN</div>

            <h1 class="detail-title">{{ $music->title }}</h1>

            <div class="detail-meta-grid">
                <div class="detail-meta-item">
                    <span class="detail-meta-label">✶ THỜI GIAN</span>
                    <span class="detail-meta-value">
                        @if(!empty($music->event_date) && $music->event_date !== 'Đang cập nhật')
                            {{ \Carbon\Carbon::parse($music->event_date)->format('H:i, d/m/Y') }}
                        @else
                            Đang cập nhật
                        @endif
                    </span>
                </div>
                <div class="detail-meta-item">
                    <span class="detail-meta-label">✶ ĐỊA ĐIỂM</span>
                    <span class="detail-meta-value">
                        {{ $music->location ?? ($music->city ?? 'Đang cập nhật') }}
                    </span>
                </div>
                <div class="detail-meta-item">
                    <span class="detail-meta-label">✶ THỜI LƯỢNG</span>
                    <span class="detail-meta-value">
                        @if(!empty($music->event_date) && !empty($music->event_end)
                            && $music->event_date !== 'Đang cập nhật')
                            {{ \Carbon\Carbon::parse($music->event_date)->format('H:i') }}
                            – {{ \Carbon\Carbon::parse($music->event_end)->format('H:i') }}
                        @else
                            Đang cập nhật
                        @endif
                    </span>
                </div>
                <div class="detail-meta-item">
                    <span class="detail-meta-label">✶ THỂ LOẠI</span>
                    <span class="detail-meta-value">
                        {{ $music->event_type ?? 'music âm nhạc' }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Giới thiệu --}}
        @if(!empty($music->description))
        <div class="detail-section">
            <div class="section-bar">✶ GIỚI THIỆU</div>
            <p>{!! nl2br(e($music->description)) !!}</p>

            @if(!empty($music->highlights))
                <p style="font-weight:700;color:#222;margin-bottom:8px">ĐIỂM NỔI BẬT</p>
                <ul class="highlights-list">
                    @foreach(preg_split('/[\n|•]+/', $music->highlights, -1, PREG_SPLIT_NO_EMPTY) as $item)
                        @if(trim($item))
                            <li><span style="color:var(--color-red,#74070d)">✦</span> {{ trim($item) }}</li>
                        @endif
                    @endforeach
                </ul>
            @endif
        </div>
        @endif

        {{-- Điều kiện & điều khoản --}}
        @if(!empty($music->terms))
        <div class="detail-section">
            <div class="section-bar">🗂 ĐIỀU KIỆN & ĐIỀU KHOẢN</div>
            <div class="terms-block">
                {!! nl2br(e($music->terms)) !!}
            </div>
        </div>
        @endif

    </div>

    {{-- ═══════ RIGHT: Booking card ═══════ --}}
    <div>
    <div class="booking-card-new">

        <div class="booking-card-header">THÔNG TIN VÉ</div>

        <div class="booking-card-body">

            @if(isset($hangVe) && $hangVe->count() > 0)
                <p class="price-section-title">GIÁ VÉ</p>

                <table class="price-table" id="priceTable">
                    @foreach($hangVe as $hv)
                    <tr class="{{ $loop->first ? 'selected' : '' }}"
                        onclick="selectRow(this, {{ (int)$hv->price }}, '{{ addslashes($hv->ticket_name) }}')">
                        <td>{{ $hv->ticket_name }}</td>
                        <td>{{ number_format($hv->price, 0, ',', '.') }} ₫</td>
                    </tr>
                    @endforeach
                </table>

                <p class="price-min">
                    Giá vé từ {{ number_format($hangVe->min('price') ?? 0, 0, ',', '.') }} ₫
                </p>
            @else
                <p style="text-align:center;color:#aaa;padding:16px 0;font-size:0.875rem">
                    Thông tin vé đang được cập nhật
                </p>
            @endif

            {{-- CTA --}}
            @if(!empty($music->id))
                <a href="{{ route('booking.show', \Illuminate\Support\Str::slug($music->title)) }}?eventId={{ \Illuminate\Support\Str::slug($music->title) }}" class="btn-buy-now">
                    🎫 ĐẶT NGAY
                </a>
                <a href="{{ route('booking.show', \Illuminate\Support\Str::slug($music->title)) }}?eventId={{ \Illuminate\Support\Str::slug($music->title) }}&gift=1"
                   style="display:block;text-align:center;color:var(--color-green,#46462a);
                          font-size:0.875rem;font-weight:600;padding:8px;
                          border:2px solid var(--color-green,#46462a);border-radius:8px;
                          text-decoration:none;transition:all .2s"
                   onmouseover="this.style.background='var(--color-green,#46462a)';this.style.color='#fff'"
                   onmouseout="this.style.background='';this.style.color='var(--color-green,#46462a)'">
                    🎁 TẶNG VÉ
                </a>
            @else
                <button class="btn-buy-now" disabled style="opacity:.5;cursor:not-allowed">
                    🎫 SẮP MỞ BÁN
                </button>
            @endif

            <div class="note-box" style="margin-top:1.5rem;">
                <div class="note-title">💡 LƯU Ý QUAN TRỌNG:</div>
                <ul>
                    <li>Vé đã mua không được hoàn trả</li>
                    <li>Vui lòng đến trước 30 phút</li>
                    <li>Không mang đồ uống có cồn, rượu bia, các chất gây nghiện</li>
                    <li>Không dẫn theo thú cưng</li>
                </ul>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
function selectRow(tr, price, name) {
    document.querySelectorAll('#priceTable tr').forEach(r => r.classList.remove('selected'));
    tr.classList.add('selected');
}
</script>
@endsection
