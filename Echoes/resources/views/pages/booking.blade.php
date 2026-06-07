@extends('layouts.app')

@section('title', 'Đặt vé — ' . $concert->title . ' | Echoes')

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/booking.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/seat-booking.css') }}">
@endsection

@section('content')

<main class="container-fluid my-5">

    {{-- Breadcrumb --}}
    <nav class="breadcrumb-custom mb-4">
        <div class="container">
            <a href="{{ route('home') }}">TRANG CHỦ</a> /
            <a href="{{ route('concert.index') }}">CONCERT</a> /
            <a href="{{ route('concert.show', $concert->id) }}">{{ $concert->title }}</a> /
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

        <div class="row">

            {{-- ─── LEFT: Seat Map ──────────────────────────── --}}
            <div class="col-lg-8">
                <div class="seat-map-container">
                    <h4 class="text-center mb-4">CHỌN CHỖ NGỒI</h4>

                    {{-- Step 1: Seat selection --}}
                    <div id="seat" class="step active">

                        {{-- Stage --}}
                        <div class="seatBox">
                            <div class="stage mb-4">
                                <span class="stage-text">✦ SÂN KHẤU ✦</span>
                            </div>

                            @if($gheNgoi->count() > 0)
                                {{-- Group seats by zone then by row --}}
                                @php
                                    $zones = $gheNgoi->groupBy('zone');
                                @endphp

                                @foreach($zones as $zoneName => $seatsInZone)
                                    <div class="seat-section mb-4">
                                        <p class="text-center fw-bold mb-3"
                                           style="color:var(--color-red);letter-spacing:1px;text-transform:uppercase">
                                            {{ $zoneName }}
                                        </p>

                                        @php
                                            $rows = $seatsInZone->groupBy('row');
                                            // Find matching ticket type for this zone
                                            $ticket = $hangVe->firstWhere('zone', $zoneName);
                                            $ticketPrice = $ticket ? $ticket->price : 0;
                                            $ticketId    = $ticket ? $ticket->ticket_id : 0;
                                        @endphp

                                        @foreach($rows as $rowLabel => $rowSeats)
                                            <div class="row-container">
                                                <span style="width:24px;font-size:0.75rem;font-weight:700;color:#888;text-align:right;margin-right:8px">
                                                    {{ $rowLabel }}
                                                </span>
                                                <div class="seat-row">
                                                    @foreach($rowSeats as $seat)
                                                        @php
                                                            $isUnavailable = $seat->status === 'DaBan';
                                                            $isHeld        = $seat->status === 'DangGiu';
                                                            $seatClass = 'seat';
                                                            if ($isUnavailable || $isHeld) {
                                                                $seatClass .= ' unavailable';
                                                            } elseif (str_contains(strtolower($zoneName), 'vip')) {
                                                                $seatClass .= ' seat-vip';
                                                            } else {
                                                                $seatClass .= ' seat-standard';
                                                            }
                                                        @endphp
                                                        <div class="{{ $seatClass }}"
                                                             @if(!$isUnavailable && !$isHeld)
                                                                 onclick="toggleSeat(this, '{{ $seat->seat_id }}', '{{ $rowLabel }}{{ $seat->number }}', {{ $ticketPrice }}, '{{ addslashes($zoneName) }}', {{ $ticketId }})"
                                                                 title="{{ $rowLabel }}{{ $seat->number }} — {{ number_format($ticketPrice, 0, ',', '.') }}₫"
                                                             @else
                                                                 title="Ghế đã bán"
                                                                 style="cursor:not-allowed;opacity:0.4"
                                                             @endif>
                                                            {{ $seat->number }}
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach

                            @else
                                {{-- Fallback: show ticket types without seat map --}}
                                <div style="text-align:center;padding:30px;color:#888">
                                    <p style="font-size:1rem;margin-bottom:20px">Sự kiện này không có sơ đồ ghế ngồi.</p>
                                    <p>Vui lòng chọn loại vé bên phải để tiến hành đặt vé.</p>
                                </div>
                            @endif

                            {{-- Legend --}}
                            <div class="seat-legend">
                                <div class="legend-item">
                                    <div class="legend-seat legend-vip"></div>
                                    <span>VIP</span>
                                </div>
                                <div class="legend-item">
                                    <div class="legend-seat legend-standard"></div>
                                    <span>Thường</span>
                                </div>
                                <div class="legend-item">
                                    <div class="legend-seat legend-selected"></div>
                                    <span>Đã chọn</span>
                                </div>
                                <div class="legend-item">
                                    <div class="legend-seat legend-unavailable"></div>
                                    <span>Đã bán</span>
                                </div>
                            </div>
                        </div>

                    </div>{{-- /#seat --}}

                    {{-- Step 2: Payment form --}}
                    <div id="payment" class="step">
                        <form id="bookingForm" method="POST" action="{{ route('booking.store') }}">
                            @csrf
                            <input type="hidden" name="concert_id" value="{{ $concert->id }}">
                            <input type="hidden" name="is_gift" id="isGift" value="0">
                            <input type="hidden" name="selected_seats" id="selectedSeatsInput" value="">

                            <h5 class="fw-bold mb-4" style="color:var(--color-red)">THÔNG TIN NGƯỜI MUA</h5>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Họ tên <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="buyer_name" required
                                       placeholder="Nhập họ và tên">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" name="buyer_email" required
                                       placeholder="Vé sẽ được gửi qua email này">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Số điện thoại</label>
                                <input type="tel" class="form-control" name="buyer_phone"
                                       placeholder="0901234567">
                            </div>

                            {{-- Gift recipient --}}
                            <div id="giftSection" style="display:none">
                                <h5 class="fw-bold mb-3 mt-4" style="color:var(--color-red)">THÔNG TIN NGƯỜI NHẬN</h5>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Tên người nhận</label>
                                    <input type="text" class="form-control" name="recipient_name"
                                           placeholder="Tên người nhận vé">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Email người nhận</label>
                                    <input type="email" class="form-control" name="recipient_email"
                                           placeholder="Email người nhận vé">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Lời chúc</label>
                                    <textarea class="form-control" name="gift_message" rows="3"
                                              placeholder="Nhập lời chúc (tùy chọn)"></textarea>
                                </div>
                            </div>

                            <h5 class="fw-bold mb-3 mt-4" style="color:var(--color-red)">PHƯƠNG THỨC THANH TOÁN</h5>
                            <div class="row g-2 mb-4">
                                @php
                                    $methods = [
                                        ['value'=>'vnpay',  'label'=>'VNPay',  'icon'=>'💳'],
                                        ['value'=>'momo',   'label'=>'MoMo',   'icon'=>'📱'],
                                        ['value'=>'banking','label'=>'Chuyển khoản', 'icon'=>'🏦'],
                                    ];
                                @endphp
                                @foreach($methods as $m)
                                    <div class="col-4">
                                        <label style="display:block;border:2px solid #ddd;border-radius:10px;padding:12px;text-align:center;cursor:pointer;transition:all 0.2s"
                                               class="payment-method-label">
                                            <input type="radio" name="payment_method" value="{{ $m['value'] }}"
                                                   style="display:none" {{ $loop->first ? 'checked' : '' }}>
                                            <div style="font-size:1.5rem">{{ $m['icon'] }}</div>
                                            <div style="font-size:0.8rem;font-weight:600;margin-top:4px">{{ $m['label'] }}</div>
                                        </label>
                                    </div>
                                @endforeach
                            </div>

                        </form>
                    </div>{{-- /#payment --}}

                </div>
            </div>

            {{-- ─── RIGHT: Booking Summary ──────────────────── --}}
            <div class="col-lg-4">
                <div class="booking-summary">
                    <h4 class="fw-bold mb-3" style="color:var(--color-red);text-align:center">THÔNG TIN ĐẶT VÉ</h4>

                    {{-- Seat selection alert --}}
                    <div class="selected-seat-info mb-3">
                        <div class="alert alert-info" id="seatSelection">
                            <i class="fas fa-info-circle me-2"></i>Vui lòng chọn chỗ ngồi
                        </div>
                    </div>

                    {{-- Dynamic seat info --}}
                    <div class="seat-info-container mb-3" id="seatInfoContainer">
                        {{-- Filled by JS --}}
                    </div>

                    {{-- Total --}}
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold" style="font-size:1.1rem">Tổng tiền:</span>
                                <span id="totalPrice" class="fw-bold total-price-number" style="color:var(--color-red);font-size:1.3rem">0₫</span>
                            </div>
                        </div>
                    </div>

                    {{-- Action buttons --}}
                    <div class="booking-actions mt-3">
                        <button type="button"
                                class="btn btn-danger w-100 mb-3 button-next disabled"
                                id="proceedToPayment"
                                onclick="handleProceed(false)">
                            <i class="fas fa-credit-card me-2"></i>
                            <span id="btnProceedText">CHỌN GHẾ TRƯỚC</span>
                        </button>

                        <button type="button"
                                class="btn btn-outline-danger w-100 mb-3 button-gift disabled"
                                id="giftTicket"
                                onclick="handleProceed(true)">
                            <i class="fas fa-gift me-2"></i>TẶNG VÉ
                        </button>

                        <button type="button"
                                class="btn btn-outline-secondary w-100 button-prev"
                                id="btnBack"
                                onclick="goBack()"
                                style="display:none">
                            <i class="fas fa-arrow-left me-2"></i>QUAY LẠI
                        </button>

                        <a href="{{ route('concert.show', $concert->id) }}"
                           class="btn btn-outline-secondary w-100 mt-2"
                           id="btnCancel">
                            <i class="fas fa-times me-2"></i>HỦY
                        </a>
                    </div>

                    {{-- Notes --}}
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

                </div>
            </div>

        </div>{{-- /.row --}}
    </div>{{-- /.container --}}
</main>

@endsection

@section('scripts')
<script>
// ─── State ──────────────────────────────────────────
const selectedSeats = {}; // { seat_id: { label, price, zone, ticketId } }
let currentStep = 'seat'; // 'seat' | 'payment'

// ─── Seat toggle ────────────────────────────────────
function toggleSeat(el, seatId, label, price, zone, ticketId) {
    if (el.classList.contains('unavailable')) return;

    if (el.classList.contains('selected')) {
        el.classList.remove('selected');
        delete selectedSeats[seatId];
    } else {
        if (Object.keys(selectedSeats).length >= 10) {
            alert('Bạn chỉ được chọn tối đa 10 vé.');
            return;
        }
        el.classList.add('selected');
        selectedSeats[seatId] = { label, price, zone, ticketId };
    }
    updateSummary();
}

// ─── Update right panel ─────────────────────────────
function updateSummary() {
    const count = Object.keys(selectedSeats).length;
    const container = document.getElementById('seatInfoContainer');
    const alert     = document.getElementById('seatSelection');
    const total     = document.getElementById('totalPrice');
    const btnBook   = document.getElementById('proceedToPayment');
    const btnGift   = document.getElementById('giftTicket');

    if (count === 0) {
        alert.style.display = 'block';
        container.innerHTML = '';
        total.textContent   = '0₫';
        btnBook.classList.add('disabled');
        btnGift.classList.add('disabled');
        document.getElementById('btnProceedText').textContent = 'CHỌN GHẾ TRƯỚC';
        return;
    }

    alert.style.display = 'none';
    btnBook.classList.remove('disabled');
    btnGift.classList.remove('disabled');
    document.getElementById('btnProceedText').textContent = 'TIẾN HÀNH THANH TOÁN';

    let html = '';
    let grandTotal = 0;
    for (const [id, s] of Object.entries(selectedSeats)) {
        grandTotal += s.price;
        html += `
            <div class="seat-info">
                <div class="seat-selected">
                    <div class="seat-total">Ghế ${s.label}</div>
                    <div class="seat-number">${s.zone}</div>
                </div>
                <div class="seat-price">${s.price.toLocaleString('vi-VN')}₫</div>
            </div>`;
    }
    container.innerHTML = html;
    total.textContent   = grandTotal.toLocaleString('vi-VN') + '₫';
}

// ─── Proceed / back ─────────────────────────────────
function handleProceed(isGift) {
    if (Object.keys(selectedSeats).length === 0) {
        alert('Vui lòng chọn ít nhất 1 ghế.');
        return;
    }

    if (currentStep === 'seat') {
        // Switch to payment form
        document.getElementById('seat').classList.remove('active');
        document.getElementById('payment').classList.add('active');
        document.getElementById('isGift').value    = isGift ? '1' : '0';
        document.getElementById('giftSection').style.display = isGift ? 'block' : 'none';
        document.getElementById('selectedSeatsInput').value  = JSON.stringify(selectedSeats);
        document.getElementById('btnBack').style.display    = 'block';
        document.getElementById('btnCancel').style.display  = 'none';
        document.getElementById('btnProceedText').textContent = 'XÁC NHẬN ĐẶT VÉ';
        currentStep = 'payment';
    } else {
        // Submit
        document.getElementById('bookingForm').submit();
    }
}

function goBack() {
    document.getElementById('payment').classList.remove('active');
    document.getElementById('seat').classList.add('active');
    document.getElementById('btnBack').style.display   = 'none';
    document.getElementById('btnCancel').style.display = 'block';
    document.getElementById('btnProceedText').textContent = 'TIẾN HÀNH THANH TOÁN';
    currentStep = 'seat';
}

// ─── Payment method highlight ────────────────────────
document.querySelectorAll('.payment-method-label').forEach(label => {
    const radio = label.querySelector('input[type=radio]');
    if (radio?.checked) label.style.borderColor = 'var(--color-red)';
    label.addEventListener('click', () => {
        document.querySelectorAll('.payment-method-label').forEach(l => {
            l.style.borderColor = '#ddd';
            l.style.background  = 'transparent';
        });
        label.style.borderColor = 'var(--color-red)';
        label.style.background  = 'rgba(116,7,13,0.05)';
    });
});
</script>
@endsection
