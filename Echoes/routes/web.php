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
use App\Http\Controllers\Admin\TaiKhoanController;
use App\Http\Controllers\Admin\KhachHangController;
use App\Http\Controllers\Admin\NhanVienController;

// ─── Trang chủ ───────────────────────────────────────
Route::get('/', fn() => view('pages.home'))->name('home');

// ─── Public pages ─────────────────────────────────────
Route::get('/about', fn() => view('pages.about'))->name('about');
Route::get('/rules', fn() => view('pages.rules'))->name('rules');

// ─── Merchandise ──────────────────────────────────────
Route::get('/merchandise',      [MerchandiseController::class, 'index'])->name('merchandise.index');
Route::get('/merchandise/{id}', [MerchandiseController::class, 'show'])->where('id', '[0-9]+')->name('merchandise.show');

// ─── Tin tức ──────────────────────────────────────────
Route::get('/news',      [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{id}', [NewsController::class, 'show'])->where('id', '[0-9]+')->name('news.show');

// ─── Nhạc sống ────────────────────────────────────────
Route::get('/music',      [MusicController::class, 'index'])->name('music.index');
Route::get('/music/{id}', [MusicController::class, 'show'])->where('id', '[0-9]+')->name('music.show');

// ─── Concert ──────────────────────────────────────────
Route::get('/concert',      [ConcertController::class, 'publicIndex'])->name('concert.index');
Route::get('/concert/{id}', [ConcertController::class, 'show'])->where('id', '[0-9]+')->name('concert.show');

// ─── Auth ─────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/auth',     [AuthController::class, 'showAuthPage'])->name('auth.page');
    Route::get('/login',    fn() => redirect()->route('auth.page'))->name('login');
    Route::get('/register', fn() => redirect()->route('auth.page', ['tab' => 'register']))->name('register.page');

    Route::post('/login',    [AuthController::class, 'login'])->name('login.post')->middleware('throttle:10,1');
    Route::post('/register', [AuthController::class, 'register'])->name('register')->middleware('throttle:5,1');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth.custom');

// ─── Google OAuth ──────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/auth/google',          [GoogleAuthController::class, 'redirect'])->name('auth.google');
    Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('auth.google.callback');
});

// ─── Quên / Đặt lại mật khẩu ──────────────────────────
Route::post('/forgot-password',          [ForgotPasswordController::class, 'sendResetEmail'])->name('password.forgot')->middleware('throttle:5,1');
Route::get('/reset-password/{token}',    [ForgotPasswordController::class, 'showResetForm'])->name('password.reset.form');
Route::post('/reset-password',           [ForgotPasswordController::class, 'resetPassword'])->name('password.reset');

// ─── Hồ sơ cá nhân ────────────────────────────────────
Route::middleware('auth.custom')->prefix('profile')->name('profile.')->group(function () {
    Route::get('/',         [ProfileController::class, 'show'])->name('show');
    Route::get('/edit',     [ProfileController::class, 'edit'])->name('edit');
    Route::put('/update',   [ProfileController::class, 'update'])->name('update');
    Route::put('/password', [ProfileController::class, 'changePassword'])->name('password');
});

Route::get('/my-ticket', fn() => view('pages.my-ticket'))->name('my-ticket')->middleware('auth.custom');

// ─── Admin ────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware('staff')->group(function () {

    Route::get('/', fn() => view('admin.dashboard'))->name('dashboard');

    // ── Chỉ Admin ─────────────────────────────────────
    Route::middleware('admin')->group(function () {

        // Quản lý Tài khoản — chỉ xem và khóa
        Route::prefix('tai-khoan')->name('tai-khoan.')->group(function () {
            Route::get('/',                    [TaiKhoanController::class, 'index'])->name('index');
            Route::get('/{taiKhoan}',          [TaiKhoanController::class, 'show'])->name('show');
            Route::patch('/{taiKhoan}/toggle', [TaiKhoanController::class, 'toggleTrangThai'])->name('toggle');
        });

        // Quản lý Nhân viên
        Route::prefix('nhan-vien')->name('nhan-vien.')->group(function () {
            Route::get('/',                    [NhanVienController::class, 'index'])->name('index');
            Route::get('/them',                [NhanVienController::class, 'create'])->name('create');
            Route::post('/',                   [NhanVienController::class, 'store'])->name('store');
            Route::get('/{nhanVien}',          [NhanVienController::class, 'show'])->name('show');
            Route::get('/{nhanVien}/sua',      [NhanVienController::class, 'edit'])->name('edit');
            Route::put('/{nhanVien}',          [NhanVienController::class, 'update'])->name('update');
            Route::patch('/{nhanVien}/toggle', [NhanVienController::class, 'toggleTrangThai'])->name('toggle');
        });
    });

    // ── Admin + Nhân viên ─────────────────────────────

    // Quản lý Khách hàng (Admin + Nhân viên) — chỉ xem và khóa
    Route::prefix('khach-hang')->name('khach-hang.')->group(function () {
        Route::get('/',                    [KhachHangController::class, 'index'])->name('index');
        Route::get('/{khachHang}',         [KhachHangController::class, 'show'])->name('show');
        Route::patch('/{khachHang}/toggle',[KhachHangController::class, 'toggleTrangThai'])->name('toggle');
    });
    Route::get('/concerts',           [ConcertController::class, 'index'])->name('concerts.index');
    Route::get('/concerts/create',    [ConcertController::class, 'create'])->name('concerts.create');
    Route::post('/concerts',          [ConcertController::class, 'store'])->name('concerts.store');
    Route::get('/concerts/{id}/edit', [ConcertController::class, 'edit'])->name('concerts.edit');
    Route::put('/concerts/{id}',      [ConcertController::class, 'update'])->name('concerts.update');
    Route::delete('/concerts/{id}',   [ConcertController::class, 'destroy'])->name('concerts.destroy');

    Route::get('/music',           [MusicController::class, 'adminIndex'])->name('music.index');
    Route::get('/music/create',    [MusicController::class, 'adminCreate'])->name('music.create');
    Route::post('/music',          [MusicController::class, 'adminStore'])->name('music.store');
    Route::get('/music/{id}/edit', [MusicController::class, 'adminEdit'])->name('music.edit');
    Route::put('/music/{id}',      [MusicController::class, 'adminUpdate'])->name('music.update');
    Route::delete('/music/{id}',   [MusicController::class, 'adminDestroy'])->name('music.destroy');

    Route::get('/news',           [NewsController::class, 'adminIndex'])->name('news.index');
    Route::get('/news/create',    [NewsController::class, 'adminCreate'])->name('news.create');
    Route::post('/news',          [NewsController::class, 'adminStore'])->name('news.store');
    Route::get('/news/{id}/edit', [NewsController::class, 'adminEdit'])->name('news.edit');
    Route::put('/news/{id}',      [NewsController::class, 'adminUpdate'])->name('news.update');
    Route::delete('/news/{id}',   [NewsController::class, 'adminDestroy'])->name('news.destroy');

    Route::get('/merchandise',           [MerchandiseController::class, 'adminIndex'])->name('merchandise.index');
    Route::get('/merchandise/create',    [MerchandiseController::class, 'adminCreate'])->name('merchandise.create');
    Route::post('/merchandise',          [MerchandiseController::class, 'adminStore'])->name('merchandise.store');
    Route::get('/merchandise/{id}/edit', [MerchandiseController::class, 'adminEdit'])->name('merchandise.edit');
    Route::put('/merchandise/{id}',      [MerchandiseController::class, 'adminUpdate'])->name('merchandise.update');
    Route::delete('/merchandise/{id}',   [MerchandiseController::class, 'adminDestroy'])->name('merchandise.destroy');
});
