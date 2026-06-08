@extends('layouts.app')

@section('title', 'Đặt vé — ' . $concert->title . ' | Echoes')

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/booking.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/seat-booking.css') }}">
@endsection

@section('content')

<main class="container-fluid booking-page my-5">

    {{-- Breadcrumb --}}
    <nav class="breadcrumb-custom mb-4">
        <div class="container">
            <a href="{{ route('home') }}">TRANG CHỦ</a> /
            <a href="{{ route($eventIndexRoute) }}">CONCERT</a> /
            <a href="{{ route($eventShowRoute, $concert->id) }}">{{ $concert->title }}</a> /
            <span>CHỌN CHỖ NGỒI</span>
        </div>
    </nav>

    <div class="container">

        {{-- Event Info Header --}}
        <div class="event-header mb-4">
            <div class="row align-items-center">

                {{-- Poster --}}
                <div class="col-md-2">
                    @if($concert->image)
                        <img src="{{ asset($concert->image) }}"
                             alt="{{ $concert->title }}"
                             class="img-fluid rounded music-image"
                             style="width:100%;height:160px;object-fit:cover">
                    @else
                        <div style="width:100%;height:160px;background:var(--color-beige);border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:2.5rem">🎵</div>
                    @endif
                </div>

                {{-- Info --}}
                <div class="col-md-10">
                    <h2 class="fw-bold music-title mb-2">{{ $concert->title }}</h2>

                    <p class="mb-1">
                        <i class="fas fa-calendar-alt me-2" style="color:var(--color-red)"></i>
                        <span class="showtime-day">
                            {{ $concert->event_date
                                ? \Carbon\Carbon::parse($concert->event_date)->format('l, d/m/Y')
                                : 'Đang cập nhật' }}
                        </span>
                    </p>

                    <p class="mb-1">
                        <i class="fas fa-map-marker-alt me-2" style="color:var(--color-red)"></i>
                        <span class="cinema-name">
                            {{ $concert->location ?? ($concert->city ?? 'Đang cập nhật') }}
                        </span>
                    </p>

                    <p class="mb-0">
                        <i class="fas fa-clock me-2" style="color:var(--color-red)"></i>
                        <span class="showtime-number">
                            Buổi diễn:
                            {{ $concert->event_date
                                ? \Carbon\Carbon::parse($concert->event_date)->format('H:i')
                                : '---' }}
                        </span>
                    </p>

                    <div class="mt-2">
                        @php
                            $statusLabels = [
                                'SapDienRa' => ['label' => 'SẮP DIỄN RA', 'color' => '#f59e0b'],
                                'DangMoBan' => ['label' => 'CÒN VÉ',      'color' => 'var(--color-green)'],
                                'DaKetThuc' => ['label' => 'ĐÃ KẾT THÚC', 'color' => '#6b7280'],
                                'DaHuy'     => ['label' => 'ĐÃ HỦY',      'color' => 'var(--color-red)'],
                            ];
                            $st = $statusLabels[$concert->status] ?? ['label' => 'ĐANG CẬP NHẬT', 'color' => '#6b7280'];
                        @endphp
                        <span class="music-note"
                              style="background-color: {{ $st['color'] }}; color: white; padding: 8px 16px; border-radius: 5px; font-weight: 700; font-size: 0.8rem">
                            {{ $st['label'] }}
                        </span>
                    </div>
                </div>

            </div>
        </div>

        <div class="row booking-grid">

            {{-- ─── LEFT: Event Image ──────────────────────────── --}}
            <div class="col-lg-8">
                <div class="seat-map-container booking-panel shadow-sm border-0">
                    <h4 class="text-center mb-4">HÌNH ẢNH SỰ KIỆN</h4>
                    <div class="image-map-container text-center">
                        @php
                            $seatmapImage = null;
                            if($concert->AnhSeatMap) {
                                $seatmapImage = 'assets/images/seatmap/' . $concert->AnhSeatMap;
                            } elseif($concert->image) {
                                $seatmapImage = $concert->image;
                            }
                        @endphp
                        @if($seatmapImage)
                            <img src="{{ asset($seatmapImage) }}"
                                 alt="{{ $concert->title }}"
                                 class="venue-layout-image">
                        @else
                            <div class="venue-layout-image"
                                 style="display:flex;align-items:center;justify-content:center;height:420px;background:#f3f3f3;color:#666;border-radius:15px;font-size:2rem">
                                🎵 Ảnh sự kiện đang được cập nhật
                            </div>
                        @endif
                    </div>

                    <div class="mt-4 p-4 bg-light rounded-4 text-center">
                        <p class="mb-2 fw-bold" style="color:#74070d">{{ $concert->title }}</p>
                        <p class="mb-1"><strong>Thời gian:</strong>
                            {{ $concert->event_date ? \Carbon\Carbon::parse($concert->event_date)->format('H:i, d/m/Y') : 'Đang cập nhật' }}</p>
                        <p class="mb-1"><strong>Địa điểm:</strong> {{ $concert->location ?? ($concert->city ?? 'Đang cập nhật') }}</p>
                        <p class="mb-0 text-muted">Chọn số lượng vé bên phải rồi bấm "XÁC NHẬN ĐẶT VÉ" để thanh toán.</p>
                    </div>
                </div>
            </div>

            {{-- ─── RIGHT: Booking Summary ──────────────────── --}}
            <div class="col-lg-4">
                <div class="booking-summary booking-side booking-panel shadow-sm border-0">
                    <form id="bookingForm" method="POST" action="{{ route('booking.add', $concert->id) }}">
                        @csrf
                        <input type="hidden" name="concert_id" value="{{ $concert->id }}">
                        <input type="hidden" name="is_gift" id="isGift" value="0">
                        <input type="hidden" name="selected_tickets" id="selectedTicketsInput" value="">

                        <h4 class="fw-bold mb-3" style="color:var(--color-red);text-align:center">THÔNG TIN ĐẶT VÉ</h4>

                        <div class="price-block mb-4">
                            <p class="price-section-title">CHỌN LOẠI VÉ</p>
                            <div class="ticket-type-list">
                                @foreach($hangVe as $ticket)
                                    <div class="ticket-type-item">
                                        <div>
                                            <div class="ticket-type-name">{{ $ticket->ticket_name }}</div>
                                            <div class="ticket-type-zone">{{ $ticket->zone }}</div>
                                        </div>
                                        <div class="ticket-type-action">
                                            <div class="ticket-type-price">{{ number_format($ticket->price, 0, ',', '.') }} ₫</div>
                                            <div class="ticket-qty-controls">
                                                <button type="button" class="qty-btn" onclick="changeQuantity({{ (int)$ticket->ticket_id }}, -1)">-</button>
                                                <span id="qty-{{ (int)$ticket->ticket_id }}">0</span>
                                                <button type="button" class="qty-btn" onclick="changeQuantity({{ (int)$ticket->ticket_id }}, 1)">+</button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <p class="price-min mt-3">Giá vé từ {{ number_format($hangVe->min('price') ?? 0, 0, ',', '.') }} ₫</p>
                        </div>

                        <div class="cart-block mb-4">
                            <div class="cart-title">Giỏ hàng</div>
                            <div id="cartEmpty" class="cart-empty">Chưa có vé nào được chọn</div>
                            <div id="cartItems" class="cart-items"></div>
                        </div>

                        <div class="card total-card mb-4">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <span class="fw-bold">Tổng tiền</span>
                                <span id="totalPrice" class="fw-bold total-price-number" style="color:var(--color-red);font-size:1.3rem">0₫</span>
                            </div>
                        </div>

                        <div class="booking-actions mb-4">
                            <button type="button"
                                    class="btn btn-danger w-100 mb-3 button-next disabled"
                                    id="proceedToPayment"
                                    onclick="handleProceed(false)">
                                <i class="fas fa-credit-card me-2"></i>
                                <span id="btnProceedText">XÁC NHẬN ĐẶT VÉ</span>
                            </button>

                            <button type="button"
                                    class="btn btn-outline-danger w-100 mb-3 button-gift disabled"
                                    id="giftTicket"
                                    onclick="handleProceed(true)">
                                <i class="fas fa-gift me-2"></i>TẶNG VÉ
                            </button>

                            <a href="{{ route($eventShowRoute, $concert->id) }}"
                               class="btn btn-outline-secondary w-100"
                               id="btnCancel">
                                <i class="fas fa-times me-2"></i>HỦY
                            </a>
                        </div>

                        <div class="booking-notes mt-4">
                            <div class="alert alert-warning" style="font-size:0.875rem">
                                <h6 class="fw-bold mb-2">⚠️ Lưu ý:</h6>
                                <ul class="mb-0 small" style="padding-left:16px">
                                    <li>Mỗi tài khoản tối đa 10 vé</li>
                                    <li>Vé đã mua không đổi trả</li>
                                    <li>Kiểm tra thông tin trước khi thanh toán</li>
                                    <li>Vui lòng đến trước 30 phút</li>
                                </ul>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>{{-- /.row --}}
    </div>{{-- /.container --}}
</main>

@endsection

@section('scripts')
<script>
// ─── State ──────────────────────────────────────────
@php
    $ticketOptions = [];
    foreach ($hangVe as $ticket) {
        $ticketOptions[] = [
            'ticket_id' => (int) $ticket->ticket_id,
            'ticket_name' => $ticket->ticket_name,
            'zone' => $ticket->zone,
            'price' => (int) $ticket->price,
        ];
    }
@endphp
const ticketOptions = {!! json_encode($ticketOptions, JSON_UNESCAPED_UNICODE) !!};

const selectedTickets = {};
if (Array.isArray(ticketOptions)) {
    ticketOptions.forEach(ticket => {
        selectedTickets[ticket.ticket_id] = {
            ...ticket,
            quantity: 0,
        };
    });
}

function changeQuantity(ticketId, delta) {
    const ticket = selectedTickets[ticketId];
    if (!ticket) return;

    const nextQty = Math.max(0, ticket.quantity + delta);
    if (nextQty > 10) {
        alert('Bạn chỉ được chọn tối đa 10 vé cho mỗi loại.');
        return;
    }

    ticket.quantity = nextQty;
    document.getElementById(`qty-${ticketId}`).textContent = ticket.quantity;
    updateSummary();
}

function updateSummary() {
    const cartItems = document.getElementById('cartItems');
    const cartEmpty = document.getElementById('cartEmpty');
    const totalEl = document.getElementById('totalPrice');
    const btnBook = document.getElementById('proceedToPayment');
    const btnGift = document.getElementById('giftTicket');

    const selected = Object.values(selectedTickets).filter(item => item.quantity > 0);
    if (selected.length === 0) {
        cartEmpty.style.display = 'block';
        cartItems.innerHTML = '';
        totalEl.textContent = '0₫';
        btnBook.classList.add('disabled');
        btnGift.classList.add('disabled');
        document.getElementById('btnProceedText').textContent = 'XÁC NHẬN ĐẶT VÉ';
        document.getElementById('selectedTicketsInput').value = '';
        return;
    }

    cartEmpty.style.display = 'none';
    btnBook.classList.remove('disabled');
    btnGift.classList.remove('disabled');

    let grandTotal = 0;
    cartItems.innerHTML = selected.map(item => {
        const subtotal = item.price * item.quantity;
        grandTotal += subtotal;
        return `
            <div class="cart-item">
                <div class="cart-item-info">
                    <div class="ticket-type-name">${item.ticket_name}</div>
                    <div class="ticket-type-zone">${item.zone} · x${item.quantity}</div>
                </div>
                <div class="cart-item-price">${subtotal.toLocaleString('vi-VN')}₫</div>
            </div>`;
    }).join('');

    totalEl.textContent = grandTotal.toLocaleString('vi-VN') + '₫';
    document.getElementById('selectedTicketsInput').value = JSON.stringify(selected.map(item => ({
        ticket_id: item.ticket_id,
        quantity: item.quantity,
    })));
}

function handleProceed(isGift) {
    const selected = Object.values(selectedTickets).filter(item => item.quantity > 0);
    if (selected.length === 0) {
        alert('Vui lòng chọn ít nhất 1 vé.');
        return;
    }

    document.getElementById('isGift').value = isGift ? '1' : '0';
    document.getElementById('bookingForm').submit();
}
</script>
@endsection
