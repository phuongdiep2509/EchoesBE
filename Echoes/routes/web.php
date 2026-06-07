<?php

use Illuminate\Support\Facades\Route;
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

// ─── Trang chủ ───────────────────────────────────────
Route::get('/', fn() => view('pages.home'))->name('home');

// ─── Public pages ────────────────────────────────────
Route::get('/about',       fn() => view('pages.about'))->name('about');
Route::get('/rules',       fn() => view('pages.rules'))->name('rules');
//Route::get('/my-ticket',   fn() => view('pages.my-ticket'))->name('my-ticket');

// ─── Merchandise ─────────────────────────────────────
Route::get('/merchandise',        [MerchandiseController::class, 'index'])->name('merchandise.index');
Route::get('/merchandise/{id}',   [MerchandiseController::class, 'show'])->where('id', '[0-9]+')->name('merchandise.show');

// ─── Tin tức ─────────────────────────────────────────
Route::get('/news',        [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{id}',   [NewsController::class, 'show'])->where('id', '[0-9]+')->name('news.show');

// ─── Nhạc sống ───────────────────────────────────────
Route::get('/music',       [MusicController::class, 'index'])->name('music.index');
Route::get('/music/{id}',  [MusicController::class, 'show'])->where('id', '[0-9]+')->name('music.show');

// ─── Concert ─────────────────────────────────────────
Route::get('/concert',      [ConcertController::class, 'publicIndex'])->name('concert.index');
Route::get('/concert/{id}', [ConcertController::class, 'show'])->where('id', '[0-9]+')->name('concert.show');

// ─── Payment ─────────────────────────────────────────
Route::get('/payment/{orderId}', [PaymentController::class, 'show'])
    ->where('orderId', '[0-9]+')
    ->name('payment.show');

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
// ─── Admin ───────────────────────────────────────────
Route::prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Concert CRUD
    Route::get('/concerts',           [ConcertController::class, 'index'])->name('concerts.index');
    Route::get('/concerts/create',    [ConcertController::class, 'create'])->name('concerts.create');
    Route::post('/concerts',          [ConcertController::class, 'store'])->name('concerts.store');
    Route::get('/concerts/{id}/edit', [ConcertController::class, 'edit'])->name('concerts.edit');
    Route::put('/concerts/{id}',      [ConcertController::class, 'update'])->name('concerts.update');
    Route::delete('/concerts/{id}',   [ConcertController::class, 'destroy'])->name('concerts.destroy');

    // Music CRUD
    Route::get('/music',           [MusicController::class, 'adminIndex'])->name('music.index');
    Route::get('/music/create',    [MusicController::class, 'adminCreate'])->name('music.create');
    Route::post('/music',          [MusicController::class, 'adminStore'])->name('music.store');
    Route::get('/music/{id}/edit', [MusicController::class, 'adminEdit'])->name('music.edit');
    Route::put('/music/{id}',      [MusicController::class, 'adminUpdate'])->name('music.update');
    Route::delete('/music/{id}',   [MusicController::class, 'adminDestroy'])->name('music.destroy');

    // News CRUD
    Route::get('/news',           [NewsController::class, 'adminIndex'])->name('news.index');
    Route::get('/news/create',    [NewsController::class, 'adminCreate'])->name('news.create');
    Route::post('/news',          [NewsController::class, 'adminStore'])->name('news.store');
    Route::get('/news/{id}/edit', [NewsController::class, 'adminEdit'])->name('news.edit');
    Route::put('/news/{id}',      [NewsController::class, 'adminUpdate'])->name('news.update');
    Route::delete('/news/{id}',   [NewsController::class, 'adminDestroy'])->name('news.destroy');

    // Merchandise CRUD
    Route::get('/merchandise',           [MerchandiseController::class, 'adminIndex'])->name('merchandise.index');
    Route::get('/merchandise/create',    [MerchandiseController::class, 'adminCreate'])->name('merchandise.create');
    Route::post('/merchandise',          [MerchandiseController::class, 'adminStore'])->name('merchandise.store');
    Route::get('/merchandise/{id}/edit', [MerchandiseController::class, 'adminEdit'])->name('merchandise.edit');
    Route::put('/merchandise/{id}',      [MerchandiseController::class, 'adminUpdate'])->name('merchandise.update');
    Route::delete('/merchandise/{id}',   [MerchandiseController::class, 'adminDestroy'])->name('merchandise.destroy');

        // Payment Management
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

    // Reports
    Route::get('/reports/revenue', [ReportController::class, 'revenue'])->name('reports.revenue');
    Route::get('/reports/revenue/export', [ReportController::class, 'exportRevenueCsv'])->name('reports.revenue.export');
    Route::get('/reports/tickets', [ReportController::class, 'tickets'])->name('reports.tickets');
});
