<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ConcertController;
use App\Http\Controllers\MusicController;
use App\Http\Controllers\MerchandiseController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\BookingPageController;
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\Admin\TaiKhoanController;
use App\Http\Controllers\Admin\KhachHangController;
use App\Http\Controllers\Admin\NhanVienController;
use App\Http\Controllers\Admin\LoaiSuKienController;
use App\Http\Controllers\Admin\TicketClassController;

Route::get('/', fn() => view('pages.home'))->name('home');

// Public pages
Route::get('/about', fn() => view('pages.about'))->name('about');
Route::get('/rules', fn() => view('pages.rules'))->name('rules');
Route::get('/auth/login', fn() => redirect()->route('auth.page'))->name('auth.login');

// Booking
// News
Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{id}', [NewsController::class, 'show'])->name('news.show');

Route::get('/booking/{event}', [BookingPageController::class, 'show'])->name('booking.show');

/*
|--------------------------------------------------------------------------
| Booking / Cart / Order
|--------------------------------------------------------------------------
*/

Route::get('/cart', [BookingPageController::class, 'cart'])->name('cart');

Route::post('/cart/tickets', [BookingPageController::class, 'addToCart'])
    ->name('cart.add');

Route::post('/booking/{event}', [BookingPageController::class, 'store'])
    ->name('booking.add');

Route::post('/cart/merchandise/{id}', [MerchandiseController::class, 'addToCart'])
    ->where('id', '[0-9]+')
    ->name('cart.merchandise.add');

Route::delete('/cart/merchandise/{id}', [MerchandiseController::class, 'removeFromCart'])
    ->where('id', '[0-9]+')
    ->name('cart.merchandise.remove');

Route::delete('/cart/tickets/{ticketClassId}', [BookingPageController::class, 'removeTicketFromCart'])
    ->where('ticketClassId', '[0-9]+')
    ->name('cart.ticket.remove');

Route::post('/orders', [BookingPageController::class, 'createOrder'])
    ->name('orders.create');

Route::post('/orders/{orderId}/cancel', [BookingPageController::class, 'cancelOrder'])
    ->where('orderId', '[0-9]+')
    ->name('orders.cancel');

Route::post('/payment/{orderId}/create-pending', [PaymentController::class, 'createPending'])
    ->where('orderId', '[0-9]+')
    ->name('payment.createPending');

Route::post('/payment/{orderId}/mock-success', [PaymentController::class, 'mockSuccess'])
    ->where('orderId', '[0-9]+')
    ->name('payment.mockSuccess');

Route::post('/payment/{orderId}/mock-fail', [PaymentController::class, 'mockFail'])
    ->where('orderId', '[0-9]+')
    ->name('payment.mockFail');
Route::post('/payment/{orderId}/qr/create', [PaymentController::class, 'createQrPayment'])
    ->where('orderId', '[0-9]+')
    ->name('payment.qr.create');

Route::post('/payment/{orderId}/qr/confirm', [PaymentController::class, 'confirmQrPayment'])
    ->where('orderId', '[0-9]+')
    ->name('payment.qr.confirm');

Route::post('/payment/{orderId}/qr/expire', [PaymentController::class, 'expireQrPayment'])
    ->where('orderId', '[0-9]+')
    ->name('payment.qr.expire');

// ─── Ticket Gift / My Ticket ─────────────────────────
Route::get('/my-ticket', [MyTicketController::class, 'index'])
    ->name('my-ticket');

Route::get('/my-ticket/{ticketId}', [MyTicketController::class, 'show'])
    ->where('ticketId', '[0-9]+')
    ->name('my-ticket.show');

Route::get('/gift-history', [TicketGiftController::class, 'history'])
    ->name('ticket-gifts.history');

Route::post('/tickets/{ticketId}/gift', [TicketGiftController::class, 'store'])
    ->where('ticketId', '[0-9]+')
    ->name('tickets.gift.store');

Route::post('/ticket-gifts/{giftId}/cancel', [TicketGiftController::class, 'cancel'])
    ->where('giftId', '[0-9]+')
    ->name('ticket-gifts.cancel');

Route::get('/receive-ticket/{token}', [TicketGiftController::class, 'receive'])
    ->name('ticket-gifts.receive');

Route::post('/receive-ticket/{token}/confirm', [TicketGiftController::class, 'confirm'])
    ->name('ticket-gifts.confirm');

// Music
Route::get('/music', [MusicController::class, 'index'])->name('music.index');
Route::get('/music/{id}', [MusicController::class, 'show'])->name('music.show');

// Concert
Route::get('/concert', [ConcertController::class, 'publicIndex'])->name('concert.index');
Route::get('/concert/{id}', [ConcertController::class, 'show'])->name('concert.show');

// Auth
Route::middleware('guest')->group(function () {
    Route::get('/auth', [AuthController::class, 'showAuthPage'])->name('auth.page');
    Route::get('/login', fn() => redirect()->route('auth.page'))->name('login');
    Route::get('/register', fn() => redirect()->route('auth.page', ['tab' => 'register']))->name('register.page');

    Route::post('/login', [AuthController::class, 'login'])->name('login.post')->middleware('throttle:10,1');
    Route::post('/register', [AuthController::class, 'register'])->name('register')->middleware('throttle:5,1');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth.custom');

// Google OAuth
Route::middleware('guest')->group(function () {
    Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('auth.google');
    Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('auth.google.callback');
});

// Password reset
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetEmail'])
    ->name('password.forgot')
    ->middleware('throttle:5,1');
Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset.form');
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.reset');

// Profile
Route::middleware('auth.custom')->prefix('profile')->name('profile.')->group(function () {
    Route::get('/', [ProfileController::class, 'show'])->name('show');
    Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
    Route::put('/update', [ProfileController::class, 'update'])->name('update');
    Route::put('/password', [ProfileController::class, 'changePassword'])->name('password');
});

// Admin
Route::prefix('admin')->name('admin.')->middleware('staff')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::patch('/orders/{orderId}/status', [AdminOrderController::class, 'updateStatus'])
        ->where('orderId', '[0-9]+')
        ->name('orders.status');

    Route::middleware('admin')->group(function () {

        // Quản lý Tài khoản — chỉ xem và khóa
        Route::prefix('tai-khoan')->name('tai-khoan.')->group(function () {
            Route::get('/',                    [TaiKhoanController::class, 'index'])->name('index');
            Route::get('/{taiKhoan}',          [TaiKhoanController::class, 'show'])->name('show');
            Route::patch('/{taiKhoan}/toggle', [TaiKhoanController::class, 'toggleTrangThai'])->name('toggle');
        });

        Route::prefix('nhan-vien')->name('nhan-vien.')->group(function () {
            Route::get('/', [NhanVienController::class, 'index'])->name('index');
            Route::get('/them', [NhanVienController::class, 'create'])->name('create');
            Route::post('/', [NhanVienController::class, 'store'])->name('store');
            Route::get('/{nhanVien}', [NhanVienController::class, 'show'])->name('show');
            Route::get('/{nhanVien}/sua', [NhanVienController::class, 'edit'])->name('edit');
            Route::put('/{nhanVien}', [NhanVienController::class, 'update'])->name('update');
            Route::patch('/{nhanVien}/toggle', [NhanVienController::class, 'toggleTrangThai'])->name('toggle');
        });
    });

    // ── Admin + Nhân viên ─────────────────────────────

    // Quản lý Khách hàng — chỉ xem và khóa
    Route::prefix('khach-hang')->name('khach-hang.')->group(function () {
        Route::get('/',                    [KhachHangController::class, 'index'])->name('index');
        Route::get('/{khachHang}',         [KhachHangController::class, 'show'])->name('show');
        Route::patch('/{khachHang}/toggle',[KhachHangController::class, 'toggleTrangThai'])->name('toggle');
    });

    Route::resource('loai-su-kien', LoaiSuKienController::class);
    Route::patch('/hang-ve/{hang_ve}/status', [TicketClassController::class, 'updateStatus'])->name('hang-ve.updateStatus');
    Route::resource('hang-ve', TicketClassController::class);

    Route::get('/concerts', [ConcertController::class, 'index'])->name('concerts.index');
    Route::get('/concerts/create', [ConcertController::class, 'create'])->name('concerts.create');
    Route::post('/concerts', [ConcertController::class, 'store'])->name('concerts.store');
    Route::get('/concerts/{id}/edit', [ConcertController::class, 'edit'])->name('concerts.edit');
    Route::get('/concerts/{id}/show', [ConcertController::class, 'adminShow'])->name('concerts.show');
    Route::put('/concerts/{id}', [ConcertController::class, 'update'])->name('concerts.update');
    Route::patch('/concerts/{id}/status', [ConcertController::class, 'updateStatus'])->name('concerts.updateStatus');
    Route::patch('/concerts/{id}/cancel', [ConcertController::class, 'cancel'])->name('concerts.cancel');
    Route::delete('/concerts/{id}', [ConcertController::class, 'destroy'])->name('concerts.destroy');

    Route::get('/music', [MusicController::class, 'adminIndex'])->name('music.index');
    Route::get('/music/create', [MusicController::class, 'adminCreate'])->name('music.create');
    Route::post('/music', [MusicController::class, 'adminStore'])->name('music.store');
    Route::get('/music/{id}/edit', [MusicController::class, 'adminEdit'])->name('music.edit');
    Route::put('/music/{id}', [MusicController::class, 'adminUpdate'])->name('music.update');
    Route::delete('/music/{id}', [MusicController::class, 'adminDestroy'])->name('music.destroy');

    Route::get('/news', [NewsController::class, 'adminIndex'])->name('news.index');
    Route::get('/news/create', [NewsController::class, 'adminCreate'])->name('news.create');
    Route::post('/news', [NewsController::class, 'adminStore'])->name('news.store');
    Route::get('/news/{id}/edit', [NewsController::class, 'adminEdit'])->name('news.edit');
    Route::put('/news/{id}', [NewsController::class, 'adminUpdate'])->name('news.update');
    Route::delete('/news/{id}', [NewsController::class, 'adminDestroy'])->name('news.destroy');

    Route::get('/merchandise', [MerchandiseController::class, 'adminIndex'])->name('merchandise.index');
    Route::get('/merchandise/create', [MerchandiseController::class, 'adminCreate'])->name('merchandise.create');
    Route::post('/merchandise', [MerchandiseController::class, 'adminStore'])->name('merchandise.store');
    Route::get('/merchandise/{id}/edit', [MerchandiseController::class, 'adminEdit'])->name('merchandise.edit');
    Route::put('/merchandise/{id}',      [MerchandiseController::class, 'adminUpdate'])->name('merchandise.update');
    Route::delete('/merchandise/{id}',   [MerchandiseController::class, 'adminDestroy'])->name('merchandise.destroy');
    Route::patch('/merchandise/{id}/toggle', [MerchandiseController::class, 'adminToggleStatus'])->name('merchandise.toggle');

    /*
    |--------------------------------------------------------------------------
    | Payment Management
    |--------------------------------------------------------------------------
    */

    Route::get('/payments', [AdminPaymentController::class, 'index'])->name('payments.index');

    Route::get('/payments/{id}', [AdminPaymentController::class, 'show'])
        ->where('id', '[0-9]+')
        ->name('payments.show');

    /*
    |--------------------------------------------------------------------------
    | Reports
    |--------------------------------------------------------------------------
    */

    Route::get('/reports/revenue', [ReportController::class, 'revenue'])->name('reports.revenue');

    Route::get('/reports/revenue/export', [ReportController::class, 'exportRevenueCsv'])
        ->name('reports.revenue.export');

    Route::get('/reports/tickets', [ReportController::class, 'tickets'])->name('reports.tickets');
});
