@extends('layouts.app')

@section('title', $concert->title . ' | Echoes')

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/eventDetail.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/eventDetailRe.css') }}">
@endsection

@section('content')

<div class="page-container">
<div class="container">

    {{-- Breadcrumb --}}
    <nav style="padding: 20px 0; font-size: 0.875rem; color: #888">
        <a href="{{ url('/') }}" style="color: var(--color-green)">TRANG CHỦ</a>
        <span style="margin: 0 8px">/</span>
        <a href="{{ url('/concert') }}" style="color: var(--color-green)">CONCERT</a>
        <span style="margin: 0 8px">/</span>
        <span style="color: var(--color-red)">{{ $concert->title }}</span>
    </nav>

    <div class="event-detail-container">
    <div class="event-grid">

        {{-- ─── LEFT COLUMN ─────────────────────────────── --}}
        <div class="event-left">

            {{-- Poster --}}
            <div class="event-poster">
                @if($concert->image)
                    <img src="{{ asset($concert->image) }}" alt="{{ $concert->title }}">
                @else
                    <div style="width:100%;height:400px;background:var(--color-beige,#e1cfac);display:flex;align-items:center;justify-content:center;border-radius:15px;font-size:3rem">🎵</div>
                @endif

                @php
                    $statusLabels = [
                        'SapDienRa' => 'Sắp diễn ra',
                        'DangMoBan' => 'Đang mở bán',
                        'DaKetThuc' => 'Đã kết thúc',
                        'DaHuy'     => 'Đã hủy',
                    ];
                @endphp
                <div class="poster-badge">
                    {{ $statusLabels[$concert->status] ?? 'Đang cập nhật' }}
                </div>
            </div>

            {{-- Info Card --}}
            <div class="event-info-card">
                <h3 style="color: var(--color-red,#74070d); font-size: 1rem; margin-bottom: 12px; letter-spacing: 2px">
                    ✶ THÔNG TIN SỰ KIỆN
                </h3>
                <h1 class="event-title">{{ $concert->title }}</h1>

                <div class="event-meta">
                    <div class="meta-item">
                        <div class="meta-icon">🕐</div>
                        <div class="meta-content">
                            <span class="meta-label">✶ THỜI GIAN</span>
                            <span class="meta-value">
                                @if($concert->event_date)
                                    {{ \Carbon\Carbon::parse($concert->event_date)->format('d/m/Y - H:i') }}
                                @else
                                    Đang cập nhật
                                @endif
                            </span>
                        </div>
                    </div>

                    <div class="meta-item">
                        <div class="meta-icon">📍</div>
                        <div class="meta-content">
                            <span class="meta-label">✶ ĐỊA ĐIỂM</span>
                            <span class="meta-value">
                                {{ $concert->location ?? ($concert->city ?? 'Đang cập nhật') }}
                                @if($concert->address)
                                    <br><small style="color:#888;font-size:0.85rem">{{ $concert->address }}</small>
                                @endif
                            </span>
                        </div>
                    </div>

                    <div class="meta-item">
                        <div class="meta-icon">⏱</div>
                        <div class="meta-content">
                            <span class="meta-label">✶ THỜI LƯỢNG</span>
                            <span class="meta-value">
                                @if($concert->event_date && $concert->event_end)
                                    {{ \Carbon\Carbon::parse($concert->event_date)->format('H:i') }}
                                    – {{ \Carbon\Carbon::parse($concert->event_end)->format('H:i') }}
                                @else
                                    Đang cập nhật
                                @endif
                            </span>
                        </div>
                    </div>

                    <div class="meta-item">
                        <div class="meta-icon">🎵</div>
                        <div class="meta-content">
                            <span class="meta-label">✶ THỂ LOẠI</span>
                            <span class="meta-value">{{ $concert->event_type ?? 'Concert âm nhạc' }}</span>
                        </div>
                    </div>
                </div>

                {{-- Description --}}
                @if($concert->description)
                <div class="event-description">
                    <h2>✶ GIỚI THIỆU</h2>
                    <p>{!! nl2br(e($concert->description)) !!}</p>
                </div>
                @endif

                {{-- Highlights --}}
                @if($concert->highlights)
                <div class="event-highlights">
                    <h2>ĐIỂM NỔI BẬT</h2>
                    <ul>
                        @foreach(preg_split('/[\n|•]+/', $concert->highlights, -1, PREG_SPLIT_NO_EMPTY) as $item)
                            @if(trim($item))
                                <li>{{ trim($item) }}</li>
                            @endif
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>

        </div>

        {{-- ─── RIGHT COLUMN — BOOKING ──────────────────── --}}
        <div class="event-right">
        <div class="booking-card sticky">

            <h3 class="booking-title">THÔNG TIN VÉ</h3>

            {{-- Ticket types from DB --}}
            @if(isset($hangVe) && $hangVe->count() > 0)
                <div class="ticket-options" id="ticketOptions">
                    @foreach($hangVe as $hv)
                        @php
                            $remaining = $hv->total - $hv->sold;
                            $isSoldOut = $remaining <= 0;
                            $isLimited = $remaining > 0 && $remaining <= 20;
                        @endphp
                        <div class="ticket-option {{ $loop->first ? 'selected' : '' }}"
                             onclick="selectTicket(this, {{ $hv->price }}, '{{ addslashes($hv->ticket_name) }}')"
                             data-price="{{ $hv->price }}"
                             data-name="{{ $hv->ticket_name }}">

                            <div class="ticket-info">
                                <div class="ticket-header">
                                    <h4>{{ $hv->ticket_name }}</h4>
                                    @if($isSoldOut)
                                        <span class="ticket-badge badge-sold">Hết vé</span>
                                    @elseif($isLimited)
                                        <span class="ticket-badge badge-limited">Sắp hết ({{ $remaining }})</span>
                                    @else
                                        <span class="ticket-badge badge-available">Còn vé</span>
                                    @endif
                                </div>

                                @if($hv->zone)
                                    <p class="ticket-desc">Khu vực: {{ $hv->zone }}</p>
                                @endif

                                @if($hv->benefits)
                                    <div class="ticket-features">
                                        @foreach(preg_split('/[,|•]+/', $hv->benefits, -1, PREG_SPLIT_NO_EMPTY) as $benefit)
                                            @if(trim($benefit))
                                                <span>✓ {{ trim($benefit) }}</span>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <div class="ticket-price-section">
                                <div class="ticket-price">
                                    {{ number_format($hv->price, 0, ',', '.') }}₫
                                </div>
                                <button class="btn-select" {{ $isSoldOut ? 'disabled' : '' }}>
                                    {{ $isSoldOut ? 'Hết vé' : 'Chọn' }}
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                {{-- Fallback khi chưa có dữ liệu vé --}}
                <div style="padding: 20px; text-align: center; color: #888; border: 2px dashed #ddd; border-radius: 12px; margin-bottom: 20px">
                    <p>Thông tin vé đang được cập nhật</p>
                </div>
            @endif

            {{-- Booking Summary --}}
            <div class="booking-summary">
                <div class="summary-item">
                    <span>Loại vé</span>
                    <span id="selectedTicketName">
                        {{ isset($hangVe) && $hangVe->count() > 0 ? $hangVe->first()->ticket_name : '—' }}
                    </span>
                </div>
                <div class="summary-item">
                    <span>Số lượng</span>
                    <div class="quantity-control">
                        <button class="qty-btn" onclick="changeQty(-1)">-</button>
                        <input type="number" id="ticketQuantity" value="1" min="1" max="10" readonly>
                        <button class="qty-btn" onclick="changeQty(1)">+</button>
                    </div>
                </div>
                <div class="summary-item summary-total">
                    <span>Tổng cộng</span>
                    <span id="totalPrice">
                        {{ isset($hangVe) && $hangVe->count() > 0 ? number_format($hangVe->first()->price, 0, ',', '.') . '₫' : '0₫' }}
                    </span>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="booking-actions">
                <a href="{{ url('/booking/' . $concert->id) }}"
                   class="btn-book"
                   id="btnBook"
                   style="text-decoration:none;text-align:center;display:block">
                    🎫 ĐẶT NGAY
                </a>
                <a href="{{ url('/booking/' . $concert->id . '?gift=1') }}"
                   class="btn-gift"
                   style="text-decoration:none;text-align:center;display:block">
                    🎁 TẶNG VÉ
                </a>
            </div>

            {{-- Notes --}}
            <div style="background: rgba(116,7,13,0.05); padding: 16px; border-radius: 12px; border: 1px solid rgba(116,7,13,0.15)">
                <p style="font-weight: 700; color: var(--color-red,#74070d); margin-bottom: 8px; font-size: 0.875rem">
                    💡 LƯU Ý QUAN TRỌNG:
                </p>
                <ul style="list-style: none; padding: 0; margin: 0; font-size: 0.8125rem; color: #555; line-height: 1.8">
                    <li>• Vé đã mua không được hoàn trả</li>
                    <li>• Vui lòng đến trước 30 phút</li>
                    <li>• Không mang đồ uống có cồn, rượu bia</li>
                    <li>• Không dẫn theo thú cưng</li>
                </ul>
            </div>

        </div>
        </div>

    </div>{{-- /.event-grid --}}
    </div>{{-- /.event-detail-container --}}

</div>{{-- /.container --}}
</div>{{-- /.page-container --}}

{{-- ─── RELATED EVENTS ──────────────────────────────────── --}}
@if($related->count() > 0)
<section style="padding: 60px 0; background: var(--color-yellow,#f0efeb)">
    <div class="container">

        <h2 style="color: var(--color-red,#74070d); text-align: center; font-size: 2rem; margin-bottom: 40px">
            🎵 BẠN CÓ THỂ THÍCH
        </h2>

        <div class="music-grid">
            @foreach($related as $r)
                <x-concert-card
                    :title="$r->title"
                    :location="$r->location ?? $r->city ?? 'Đang cập nhật'"
                    :price="'Xem chi tiết'"
                    :date="\Carbon\Carbon::parse($r->event_date)->format('d/m/Y - H:i')"
                    :image="$r->image ?? 'assets/images/concert/default.png'"
                    :link="url('/concert/' . $r->id)"
                />
            @endforeach 
        </div>

    </div>
</section>
@endif

@endsection

@section('scripts')
<script>
let selectedPrice = {{ isset($hangVe) && $hangVe->count() > 0 ? $hangVe->first()->price : 0 }};
let qty = 1;

function selectTicket(el, price, name) {
    document.querySelectorAll('.ticket-option').forEach(o => o.classList.remove('selected'));
    el.classList.add('selected');
    selectedPrice = price;
    document.getElementById('selectedTicketName').textContent = name;
    updateTotal();
}

function changeQty(delta) {
    qty = Math.max(1, Math.min(10, qty + delta));
    document.getElementById('ticketQuantity').value = qty;
    updateTotal();
}

function updateTotal() {
    const total = selectedPrice * qty;
    document.getElementById('totalPrice').textContent =
        total.toLocaleString('vi-VN') + '₫';
}
</script>
@endsection
