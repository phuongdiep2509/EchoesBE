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

use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AdminPaymentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;

use App\Http\Controllers\MyTicketController;
use App\Http\Controllers\TicketGiftController;
use App\Http\Controllers\BookingPageController;
use App\Http\Controllers\AdminOrderController;

use App\Http\Controllers\Admin\TaiKhoanController;
use App\Http\Controllers\Admin\KhachHangController;
use App\Http\Controllers\Admin\NhanVienController;

/*
|--------------------------------------------------------------------------
| Public Pages
|--------------------------------------------------------------------------
*/

Route::get('/', fn () => view('pages.home'))->name('home');

Route::get('/about', fn () => view('pages.about'))->name('about');
Route::get('/rules', fn () => view('pages.rules'))->name('rules');

/*
|--------------------------------------------------------------------------
| Auth
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/auth', [AuthController::class, 'showAuthPage'])->name('auth.page');

    Route::get('/auth/login', fn () => redirect()->route('auth.page'))->name('auth.login');
    Route::get('/login', fn () => redirect()->route('auth.page'))->name('login');
    Route::get('/register', fn () => redirect()->route('auth.page', ['tab' => 'register']))->name('register.page');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.post')
        ->middleware('throttle:10,1');

    Route::post('/register', [AuthController::class, 'register'])
        ->name('register')
        ->middleware('throttle:5,1');

    // Route phụ để tránh lỗi nếu Blade đang gọi route('register.post')
    Route::post('/register/post', [AuthController::class, 'register'])
        ->name('register.post')
        ->middleware('throttle:5,1');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth.custom');

/*
|--------------------------------------------------------------------------
| Google OAuth
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('auth.google');
    Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('auth.google.callback');
});

/*
|--------------------------------------------------------------------------
| Password Reset
|--------------------------------------------------------------------------
*/

Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetEmail'])
    ->name('password.forgot')
    ->middleware('throttle:5,1');

Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])
    ->name('password.reset.form');

Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])
    ->name('password.reset');

/*
|--------------------------------------------------------------------------
| Profile
|--------------------------------------------------------------------------
*/

Route::middleware('auth.custom')->prefix('profile')->name('profile.')->group(function () {
    Route::get('/', [ProfileController::class, 'show'])->name('show');
    Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
    Route::put('/update', [ProfileController::class, 'update'])->name('update');
    Route::put('/password', [ProfileController::class, 'changePassword'])->name('password');
});

/*
|--------------------------------------------------------------------------
| Public Concert / Music / News / Merchandise
|--------------------------------------------------------------------------
*/

Route::get('/concert', [ConcertController::class, 'publicIndex'])->name('concert.index');

Route::get('/concert/{id}', [ConcertController::class, 'show'])
    ->where('id', '[0-9]+')
    ->name('concert.show');

Route::get('/music', [MusicController::class, 'index'])->name('music.index');

Route::get('/music/{id}', [MusicController::class, 'show'])
    ->where('id', '[0-9]+')
    ->name('music.show');

Route::get('/news', [NewsController::class, 'index'])->name('news.index');

Route::get('/news/{id}', [NewsController::class, 'show'])
    ->where('id', '[0-9]+')
    ->name('news.show');

Route::get('/merchandise', [MerchandiseController::class, 'index'])->name('merchandise.index');

Route::get('/merchandise/{id}', [MerchandiseController::class, 'show'])
    ->where('id', '[0-9]+')
    ->name('merchandise.show');

/*
|--------------------------------------------------------------------------
| Booking / Cart / Order
|--------------------------------------------------------------------------
*/

Route::get('/cart', [BookingPageController::class, 'cart'])->name('cart');

Route::post('/cart/tickets', [BookingPageController::class, 'addToCart'])
    ->name('cart.add');

Route::post('/orders', [BookingPageController::class, 'createOrder'])
    ->name('orders.create');

Route::post('/orders/{orderId}/cancel', [BookingPageController::class, 'cancelOrder'])
    ->where('orderId', '[0-9]+')
    ->name('orders.cancel');

/*
|--------------------------------------------------------------------------
| Payment QR Internal
|--------------------------------------------------------------------------
*/

Route::get('/payment/{orderId}', [PaymentController::class, 'show'])
    ->where('orderId', '[0-9]+')
    ->name('payment.show');

Route::post('/payment/{orderId}/qr/create', [PaymentController::class, 'createQrPayment'])
    ->where('orderId', '[0-9]+')
    ->name('payment.qr.create');

Route::post('/payment/{orderId}/qr/confirm', [PaymentController::class, 'confirmQrPayment'])
    ->where('orderId', '[0-9]+')
    ->name('payment.qr.confirm');

Route::post('/payment/{orderId}/qr/expire', [PaymentController::class, 'expireQrPayment'])
    ->where('orderId', '[0-9]+')
    ->name('payment.qr.expire');

/*
|--------------------------------------------------------------------------
| Ticket Gift
|--------------------------------------------------------------------------
*/

Route::get('/my-ticket', [MyTicketController::class, 'index'])
    ->name('my-ticket')
    ->middleware('auth.custom');

Route::get('/my-ticket/{ticketId}', [MyTicketController::class, 'show'])
    ->where('ticketId', '[0-9]+')
    ->name('my-ticket.show')
    ->middleware('auth.custom');

Route::get('/gift-history', [MyTicketController::class, 'giftHistory'])
    ->name('ticket-gifts.history')
    ->middleware('auth.custom');

Route::post('/tickets/{ticketId}/gift', [TicketGiftController::class, 'store'])
    ->where('ticketId', '[0-9]+')
    ->name('tickets.gift.store')
    ->middleware('auth.custom');

Route::post('/ticket-gifts/{giftId}/cancel', [TicketGiftController::class, 'cancel'])
    ->where('giftId', '[0-9]+')
    ->name('ticket-gifts.cancel')
    ->middleware('auth.custom');

Route::get('/receive-ticket/{token}', [TicketGiftController::class, 'receive'])
    ->name('ticket-gifts.receive');

Route::post('/receive-ticket/{token}/confirm', [TicketGiftController::class, 'confirm'])
    ->name('ticket-gifts.confirm');

/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->middleware('staff')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Order Management
    |--------------------------------------------------------------------------
    */

    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');

    Route::patch('/orders/{orderId}/status', [AdminOrderController::class, 'updateStatus'])
        ->where('orderId', '[0-9]+')
        ->name('orders.status');

    /*
    |--------------------------------------------------------------------------
    | Account Management
    |--------------------------------------------------------------------------
    */

    Route::middleware('admin')->group(function () {

        Route::prefix('tai-khoan')->name('tai-khoan.')->group(function () {
            Route::get('/', [TaiKhoanController::class, 'index'])->name('index');

            Route::get('/{taiKhoan}', [TaiKhoanController::class, 'show'])
                ->where('taiKhoan', '[0-9]+')
                ->name('show');

            Route::patch('/{taiKhoan}/toggle', [TaiKhoanController::class, 'toggleTrangThai'])
                ->where('taiKhoan', '[0-9]+')
                ->name('toggle');
        });

        Route::prefix('nhan-vien')->name('nhan-vien.')->group(function () {
            Route::get('/', [NhanVienController::class, 'index'])->name('index');
            Route::get('/them', [NhanVienController::class, 'create'])->name('create');
            Route::post('/', [NhanVienController::class, 'store'])->name('store');

            Route::get('/{nhanVien}', [NhanVienController::class, 'show'])
                ->where('nhanVien', '[0-9]+')
                ->name('show');

            Route::get('/{nhanVien}/sua', [NhanVienController::class, 'edit'])
                ->where('nhanVien', '[0-9]+')
                ->name('edit');

            Route::put('/{nhanVien}', [NhanVienController::class, 'update'])
                ->where('nhanVien', '[0-9]+')
                ->name('update');

            Route::patch('/{nhanVien}/toggle', [NhanVienController::class, 'toggleTrangThai'])
                ->where('nhanVien', '[0-9]+')
                ->name('toggle');
        });
    });

    /*
    |--------------------------------------------------------------------------
    | Customer Management
    |--------------------------------------------------------------------------
    */

    Route::prefix('khach-hang')->name('khach-hang.')->group(function () {
        Route::get('/', [KhachHangController::class, 'index'])->name('index');

        Route::get('/{khachHang}', [KhachHangController::class, 'show'])
            ->where('khachHang', '[0-9]+')
            ->name('show');

        Route::patch('/{khachHang}/toggle', [KhachHangController::class, 'toggleTrangThai'])
            ->where('khachHang', '[0-9]+')
            ->name('toggle');
    });

    /*
    |--------------------------------------------------------------------------
    | Concert Management
    |--------------------------------------------------------------------------
    */

    Route::get('/concerts', [ConcertController::class, 'index'])->name('concerts.index');
    Route::get('/concerts/create', [ConcertController::class, 'create'])->name('concerts.create');
    Route::post('/concerts', [ConcertController::class, 'store'])->name('concerts.store');

    Route::get('/concerts/{id}/edit', [ConcertController::class, 'edit'])
        ->where('id', '[0-9]+')
        ->name('concerts.edit');

    Route::put('/concerts/{id}', [ConcertController::class, 'update'])
        ->where('id', '[0-9]+')
        ->name('concerts.update');

    Route::delete('/concerts/{id}', [ConcertController::class, 'destroy'])
        ->where('id', '[0-9]+')
        ->name('concerts.destroy');

    /*
    |--------------------------------------------------------------------------
    | Music Management
    |--------------------------------------------------------------------------
    */

    Route::get('/music', [MusicController::class, 'adminIndex'])->name('music.index');
    Route::get('/music/create', [MusicController::class, 'adminCreate'])->name('music.create');
    Route::post('/music', [MusicController::class, 'adminStore'])->name('music.store');

    Route::get('/music/{id}/edit', [MusicController::class, 'adminEdit'])
        ->where('id', '[0-9]+')
        ->name('music.edit');

    Route::put('/music/{id}', [MusicController::class, 'adminUpdate'])
        ->where('id', '[0-9]+')
        ->name('music.update');

    Route::delete('/music/{id}', [MusicController::class, 'adminDestroy'])
        ->where('id', '[0-9]+')
        ->name('music.destroy');

    /*
    |--------------------------------------------------------------------------
    | News Management
    |--------------------------------------------------------------------------
    */

    Route::get('/news', [NewsController::class, 'adminIndex'])->name('news.index');
    Route::get('/news/create', [NewsController::class, 'adminCreate'])->name('news.create');
    Route::post('/news', [NewsController::class, 'adminStore'])->name('news.store');

    Route::get('/news/{id}/edit', [NewsController::class, 'adminEdit'])
        ->where('id', '[0-9]+')
        ->name('news.edit');

    Route::put('/news/{id}', [NewsController::class, 'adminUpdate'])
        ->where('id', '[0-9]+')
        ->name('news.update');

    Route::delete('/news/{id}', [NewsController::class, 'adminDestroy'])
        ->where('id', '[0-9]+')
        ->name('news.destroy');

    /*
    |--------------------------------------------------------------------------
    | Merchandise Management
    |--------------------------------------------------------------------------
    */

    Route::get('/merchandise', [MerchandiseController::class, 'adminIndex'])->name('merchandise.index');
    Route::get('/merchandise/create', [MerchandiseController::class, 'adminCreate'])->name('merchandise.create');
    Route::post('/merchandise', [MerchandiseController::class, 'adminStore'])->name('merchandise.store');

    Route::get('/merchandise/{id}/edit', [MerchandiseController::class, 'adminEdit'])
        ->where('id', '[0-9]+')
        ->name('merchandise.edit');

    Route::put('/merchandise/{id}', [MerchandiseController::class, 'adminUpdate'])
        ->where('id', '[0-9]+')
        ->name('merchandise.update');

    Route::delete('/merchandise/{id}', [MerchandiseController::class, 'adminDestroy'])
        ->where('id', '[0-9]+')
        ->name('merchandise.destroy');

    /*
    |--------------------------------------------------------------------------
    | Payment Management
    |--------------------------------------------------------------------------
    */

    Route::get('/payments', [AdminPaymentController::class, 'index'])->name('payments.index');

    Route::get('/payments/{id}', [AdminPaymentController::class, 'show'])
        ->where('id', '[0-9]+')
        ->name('payments.show');

    Route::post('/payments/{id}/mark-success', [AdminPaymentController::class, 'markSuccess'])
        ->where('id', '[0-9]+')
        ->name('payments.markSuccess');

    Route::post('/payments/{id}/mark-failed', [AdminPaymentController::class, 'markFailed'])
        ->where('id', '[0-9]+')
        ->name('payments.markFailed');

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