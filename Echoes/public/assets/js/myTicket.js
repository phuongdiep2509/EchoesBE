/* ===== MY TICKET - SIMPLIFIED VERSION ===== */

let tickets = [];
const content = document.getElementById("ticket-content");

/* ===== DATA LOADING ===== */
function loadTicketsFromStorage() {
    try {
        const userBookings = JSON.parse(localStorage.getItem('userBookings') || '[]');
        const completedBookings = JSON.parse(localStorage.getItem('completedBookings') || '[]');
        
        console.log('userBookings:', userBookings);
        console.log('completedBookings:', completedBookings);
        
        // Handle newly completed ticket
        const completedTicket = sessionStorage.getItem('completedTicket');
        let newlyCompleted = null;
        if (completedTicket) {
            try {
                newlyCompleted = JSON.parse(completedTicket);
                sessionStorage.removeItem('completedTicket');
            } catch (e) {
                console.error('Error parsing completed ticket:', e);
            }
        }
        
        // Combine all bookings
        const allBookings = [...userBookings, ...completedBookings];
        
        if (newlyCompleted && !allBookings.find(b => b.id === newlyCompleted.id)) {
            allBookings.push(newlyCompleted);
            completedBookings.push(newlyCompleted);
            localStorage.setItem('completedBookings', JSON.stringify(completedBookings));
        }
        
        // If no bookings found, add demo data for testing
        if (allBookings.length === 0) {
            console.log('No bookings found, adding demo data...');
            const demoBookings = createDemoBookings();
            localStorage.setItem('completedBookings', JSON.stringify(demoBookings));
            allBookings.push(...demoBookings);
        }
        
        // Transform bookings to tickets
        tickets = allBookings.map(transformBookingToTicket);
        
        // Sort by booking time (newest first)
        tickets.sort((a, b) => {
            const timeA = a.paymentTime || a.bookingTime || '0';
            const timeB = b.paymentTime || b.bookingTime || '0';
            return new Date(timeB) - new Date(timeA);
        });
        
        console.log(`Loaded ${tickets.length} tickets`);
        // ✅ Đồng bộ danh sách vé cho ticketDetail đọc
        localStorage.setItem("ECHOES_TICKETS", JSON.stringify(tickets));

        
    } catch (error) {
        console.error('Error loading tickets:', error);
        tickets = [];
    }
}

function createDemoBookings() {
    return [
        {
            id: 'demo_' + Date.now() + '_1',
            eventName: 'ANH TRAI "SAY HI" 2025 CONCERT',
            eventDate: '27 tháng 12, 2025',
            eventTime: '20:00',
            venue: 'Khu đô thị Vạn Phúc, TP.HCM',
            ticketType: 'VIP',
            seatSection: 'VIP-A1',
            price: 2000000,
            quantity: 2,
            totalAmount: 4000000,
            timestamp: new Date().toISOString(),
            status: 'completed',
            isGift: false,
            bookingType: 'seat-booking',
            paymentTime: new Date().toLocaleString('vi-VN')
        },
        {
            id: 'demo_' + Date.now() + '_2',
            eventName: 'Đêm nhạc Acoustic',
            eventDate: '15/01/2025',
            eventTime: '19:30',
            venue: 'Nhà hát Hòa Bình, TP.HCM',
            ticketType: 'Standard',
            seatSection: null,
            price: 500000,
            quantity: 1,
            totalAmount: 500000,
            timestamp: new Date(Date.now() - 86400000).toISOString(), // 1 day ago
            status: 'gift',
            isGift: true,
            bookingType: 'regular',
            paymentTime: new Date(Date.now() - 86400000).toLocaleString('vi-VN')
        }
    ];
}

function transformBookingToTicket(booking) {
  const priceNumber = Number(
    booking.totalAmount ?? booking.totalPaid ?? booking.price ?? 0
  );

  const ticketType = booking.ticketType || 'Standard';
  const seatSection = booking.seatSection || null;

  return {
    id: booking.id || `ECHOES${Date.now()}`,
    name: booking.eventName || booking.title || 'Echoes Event',
    time: `${booking.eventTime || '20:00'} · ${booking.eventDate || booking.date || ''}`.trim(),
    location: booking.venue || booking.location || 'Venue TBA',
    price: priceNumber,

    ticketType,
    seatSection,
    // ✅ ticketDetail đang dùng ticket.seat -> tạo luôn cho nó
    seat: seatSection ? `${ticketType} (${seatSection})` : (booking.seat || 'Standing'),

    quantity: booking.quantity || 1,
    isGift: booking.isGift || false,
    bookingType: booking.seatSection ? 'seat-booking' : 'regular',
    paymentTime: booking.paymentTime || null,
    bookingTime: booking.timestamp ? new Date(booking.timestamp).toLocaleString('vi-VN') : null,

    // ✅ để nút "Gửi vé về Gmail" có email
    receiverName: booking.receiverName || booking.customerName || booking.fullName || "Khách hàng",
    receiverEmail: booking.receiverEmail || booking.customerEmail || booking.email || ""
  };
}


/* ===== RENDERING ===== */
function renderTickets() {
    content.innerHTML = "";

    if (tickets.length === 0) {
        renderEmpty();
        return;
    }

    tickets.forEach(ticket => {
        const link = document.createElement("a");
        link.href = `ticketDetail.html?id=${ticket.id}`;
        link.className = "ticket-link";
        link.innerHTML = createTicketHTML(ticket);
        content.appendChild(link);
    });
}

function createTicketHTML(ticket) {
    const seatInfo = ticket.seatSection ? 
        `<div class="ticket-meta">🎫 ${ticket.ticketType} (${ticket.seatSection})</div>` :
        `<div class="ticket-meta">🎫 ${ticket.ticketType}</div>`;
    
    const quantityInfo = ticket.quantity > 1 ? 
        `<div class="ticket-meta">👥 ${ticket.quantity} vé</div>` : '';
    
    const giftBadge = ticket.isGift ? 
        `<div class="gift-badge">🎁 Vé tặng</div>` : '';
    
    const bookingTimeInfo = ticket.paymentTime ? 
        `<div class="ticket-meta">💳 Thanh toán: ${ticket.paymentTime}</div>` :
        ticket.bookingTime ? 
        `<div class="ticket-meta">📅 Đặt vé: ${ticket.bookingTime}</div>` : '';

    return `
        <div class="ticket-card ${ticket.bookingType}">
            <div class="ticket-header">
                <h3>${ticket.name}</h3>
                ${giftBadge}
            </div>
            <div class="ticket-meta">📍 ${ticket.location}</div>
            <div class="ticket-meta">🕒 ${ticket.time}</div>
            ${seatInfo}
            ${quantityInfo}
            ${bookingTimeInfo}
            <div class="ticket-price">${ticket.price.toLocaleString()}đ</div>
        </div>
    `;
}

function renderEmpty() {
    content.innerHTML = `
        <div class="empty-state">
            <div class="empty-svg">
                <svg viewBox="0 0 200 200" width="200">
                    <circle cx="100" cy="100" r="95" fill="#e5c9a8"/>
                    <circle cx="130" cy="70" r="22" fill="#facc15"/>
                    <path d="M0 120 Q100 80 200 120 V200 H0Z" fill="#c2410c"/>
                    <circle cx="100" cy="110" r="20" fill="#1f3a2a"/>
                </svg>
            </div>
            <p class="empty-text">Bạn chưa có vé nào</p>
            <a href="concert.html" class="buy-btn">Khám phá sự kiện</a>
        </div>
    `;
}

/* ===== NOTIFICATIONS ===== */
function showNewTicketNotification() {
    const urlParams = new URLSearchParams(window.location.search);
    const fromPayment = urlParams.get('from') === 'payment';
    
    if (fromPayment && tickets.length > 0) {
        showTicketSuccessMessage(tickets[0]);
        window.history.replaceState({}, document.title, window.location.pathname);
    }
}

function showTicketSuccessMessage(ticket) {
    const successDiv = document.createElement('div');
    successDiv.className = 'alert alert-success ticket-success-alert';
    successDiv.innerHTML = `
        <div class="d-flex align-items-center">
            <i class="fas fa-check-circle me-3" style="font-size: 2rem; color: #28a745;"></i>
            <div>
                <h5 class="mb-1">🎉 Đặt vé thành công!</h5>
                <p class="mb-0">Vé <strong>${ticket.name}</strong> đã được thêm vào danh sách của bạn</p>
            </div>
        </div>
    `;
    
    const wrapper = document.querySelector('.my-ticket-wrapper');
    wrapper.insertBefore(successDiv, wrapper.firstChild);
    
    setTimeout(() => successDiv.remove(), 5000);
}

/* ===== INITIALIZATION ===== */
document.addEventListener('DOMContentLoaded', function() {
    loadTicketsFromStorage();
    renderTickets();
    showNewTicketNotification();
    
    // Listen for storage changes
    window.addEventListener('storage', function(e) {
        if (e.key === 'userBookings' || e.key === 'completedBookings') {
            loadTicketsFromStorage();
            renderTickets();
        }
    });
});

// Global refresh function
window.refreshMyTickets = function() {
    loadTicketsFromStorage();
    renderTickets();
};

// Function to clear demo data (for testing)
window.clearDemoData = function() {
    localStorage.removeItem('userBookings');
    localStorage.removeItem('completedBookings');
    sessionStorage.removeItem('completedTicket');
    console.log('Demo data cleared');
    loadTicketsFromStorage();
    renderTickets();
};

// Function to add demo data manually
window.addDemoData = function() {
    const demoBookings = createDemoBookings();
    localStorage.setItem('completedBookings', JSON.stringify(demoBookings));
    console.log('Demo data added');
    loadTicketsFromStorage();
    renderTickets();
};