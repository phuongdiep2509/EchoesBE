<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConcertController;
use App\Http\Controllers\MusicController;
use App\Http\Controllers\MerchandiseController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\BookingPageController;
use App\Http\Controllers\AdminOrderController;

// ─── Trang chủ ───────────────────────────────────────
Route::get('/', fn() => view('pages.home'))->name('home');

// ─── Public pages ────────────────────────────────────
Route::get('/about',       fn() => view('pages.about'))->name('about');
Route::get('/rules',       fn() => view('pages.rules'))->name('rules');
Route::get('/auth/login',  fn() => view('auth.login'))->name('auth.login');
Route::get('/my-ticket',   [BookingPageController::class, 'myTickets'])->name('my-ticket');
Route::get('/cart',        [BookingPageController::class, 'cart'])->name('cart');
Route::post('/cart/tickets', [BookingPageController::class, 'addToCart'])->name('cart.add');
Route::post('/orders',     [BookingPageController::class, 'createOrder'])->name('orders.create');
Route::post('/orders/{orderId}/cancel', [BookingPageController::class, 'cancelOrder'])->where('orderId', '[0-9]+')->name('orders.cancel');

// ─── Merchandise ─────────────────────────────────────
Route::get('/merchandise',        [MerchandiseController::class, 'index'])->name('merchandise.index');
Route::get('/merchandise/{id}',   [MerchandiseController::class, 'show'])->where('id', '[0-9]+')->name('merchandise.show');

// ─── Tin tức ─────────────────────────────────────────
Route::get('/news',        [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{id}',   [NewsController::class, 'show'])->name('news.show');

// ─── Nhạc sống ───────────────────────────────────────
Route::get('/music',       [MusicController::class, 'index'])->name('music.index');
Route::get('/music/{id}',  [MusicController::class, 'show'])->name('music.show');

// ─── Concert ─────────────────────────────────────────
Route::get('/concert',      [ConcertController::class, 'publicIndex'])->name('concert.index');
Route::get('/concert/{id}', [ConcertController::class, 'show'])->name('concert.show');

// ─── Admin ───────────────────────────────────────────
Route::prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/', fn() => view('admin.dashboard'))->name('dashboard');

    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::patch('/orders/{orderId}/status', [AdminOrderController::class, 'updateStatus'])->where('orderId', '[0-9]+')->name('orders.status');

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
});
