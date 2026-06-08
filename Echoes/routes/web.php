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
use Illuminate\Support\Facades\DB;

Route::get('/', function () {
    $resolveImage = function (string $filename = '', string $folder = 'music'): string {
        if (empty($filename)) {
            return '';
        }
        if (str_contains($filename, '/')) {
            return $filename;
        }

        $folders = [$folder, $folder === 'music' ? 'concert' : 'music'];
        foreach ($folders as $f) {
            $base = "assets/images/{$f}/{$filename}";
            if (file_exists(public_path($base))) {
                return $base;
            }
            $name = pathinfo($filename, PATHINFO_FILENAME);
            foreach (['jpg', 'jpeg', 'png', 'webp', 'gif', 'avif'] as $ext) {
                $try = "assets/images/{$f}/{$name}.{$ext}";
                if (file_exists(public_path($try))) {
                    return $try;
                }
            }
        }

        return "assets/images/{$folder}/{$filename}";
    };

    $latestMusic = DB::table('su_kien as sk')
        ->leftJoin('dia_diem_to_chuc as dd', 'sk.MaDiaDiem', '=', 'dd.MaDiaDiem')
        ->leftJoin('khu_vuc_su_kien as kv', 'sk.MaSuKien', '=', 'kv.MaSuKien')
        ->leftJoin('hang_ve as hv', 'kv.MaKhuVuc', '=', 'hv.MaKhuVuc')
        ->where('sk.MaLoaiSuKien', '!=', 1)
        ->whereIn('sk.TrangThai', ['SapDienRa', 'DangMoBan'])
        ->select([
            'sk.MaSuKien       as id',
            'sk.TenSuKien      as title',
            'sk.AnhBia         as image',
            'sk.ThoiGianBatDau as event_date',
            'dd.TenDiaDiem     as location',
            'dd.ThanhPho       as city',
            DB::raw('MIN(hv.GiaVe) as min_price'),
        ])
        ->groupBy('sk.MaSuKien', 'sk.TenSuKien', 'sk.AnhBia', 'sk.ThoiGianBatDau', 'dd.TenDiaDiem', 'dd.ThanhPho')
        ->orderByDesc('sk.MaSuKien')
        ->take(4)
        ->get()
        ->map(function ($item) use ($resolveImage) {
            $item->image = $resolveImage($item->image ?? '', 'music');
            $item->location = $item->location ?: ($item->city ?: 'Đang cập nhật');
            $item->date = $item->event_date
                ? \Carbon\Carbon::parse($item->event_date)->format('d/m/Y H:i')
                : 'Đang cập nhật';
            $item->price = $item->min_price
                ? 'Từ ' . number_format($item->min_price, 0, ',', '.') . 'đ'
                : 'Giá vé đang cập nhật';
            $item->type = 'NHẠC SỐNG';
            $item->link = url('/music/' . $item->id);
            return $item;
        });

    $latestConcerts = DB::table('su_kien as sk')
        ->leftJoin('dia_diem_to_chuc as dd', 'sk.MaDiaDiem', '=', 'dd.MaDiaDiem')
        ->leftJoin('khu_vuc_su_kien as kv', 'sk.MaSuKien', '=', 'kv.MaSuKien')
        ->leftJoin('hang_ve as hv', 'kv.MaKhuVuc', '=', 'hv.MaKhuVuc')
        ->where('sk.MaLoaiSuKien', 1)
        ->whereIn('sk.TrangThai', ['SapDienRa', 'DangMoBan'])
        ->select([
            'sk.MaSuKien       as id',
            'sk.TenSuKien      as title',
            'sk.AnhBia         as image',
            'sk.ThoiGianBatDau as event_date',
            'dd.TenDiaDiem     as location',
            'dd.ThanhPho       as city',
            DB::raw('MIN(hv.GiaVe) as min_price'),
        ])
        ->groupBy('sk.MaSuKien', 'sk.TenSuKien', 'sk.AnhBia', 'sk.ThoiGianBatDau', 'dd.TenDiaDiem', 'dd.ThanhPho')
        ->orderByDesc('sk.MaSuKien')
        ->take(4)
        ->get()
        ->map(function ($item) use ($resolveImage) {
            $item->image = $resolveImage($item->image ?? '', 'concert');
            $item->location = $item->location ?: ($item->city ?: 'Đang cập nhật');
            $item->date = $item->event_date
                ? \Carbon\Carbon::parse($item->event_date)->format('d/m/Y H:i')
                : 'Đang cập nhật';
            $item->price = $item->min_price
                ? 'Từ ' . number_format($item->min_price, 0, ',', '.') . 'đ'
                : 'Giá vé đang cập nhật';
            $item->type = 'CONCERT';
            $item->link = url('/concert/' . $item->id);
            return $item;
        });

    $hotEvents = $latestConcerts
        ->merge($latestMusic)
        ->sortByDesc('id')
        ->take(3)
        ->values();

    return view('pages.home', compact('latestMusic', 'latestConcerts', 'hotEvents'));
})->name('home');

// ─── Public pages ────────────────────────────────────
Route::get('/about',       fn() => view('pages.about'))->name('about');
Route::get('/rules',       fn() => view('pages.rules'))->name('rules');
//Route::get('/my-ticket',   fn() => view('pages.my-ticket'))->name('my-ticket');
// Public pages
Route::get('/about', fn() => view('pages.about'))->name('about');
Route::get('/rules', fn() => view('pages.rules'))->name('rules');
Route::get('/auth/login', fn() => redirect()->route('auth.page'))->name('auth.login');

// Booking
Route::get('/booking/{id}', [ConcertController::class, 'booking'])->name('booking.show');
Route::get('/my-ticket', [BookingPageController::class, 'myTickets'])->name('my-ticket')->middleware('auth.custom');
Route::get('/cart', [BookingPageController::class, 'cart'])->name('cart');
Route::post('/cart/tickets', [BookingPageController::class, 'addToCart'])->name('cart.add');
Route::post('/orders', [BookingPageController::class, 'createOrder'])->name('orders.create');
Route::post('/orders/{orderId}/cancel', [BookingPageController::class, 'cancelOrder'])
    ->where('orderId', '[0-9]+')
    ->name('orders.cancel');

// Merchandise
Route::get('/merchandise', [MerchandiseController::class, 'index'])->name('merchandise.index');
Route::get('/merchandise/{id}', [MerchandiseController::class, 'show'])
    ->where('id', '[0-9]+')
    ->name('merchandise.show');

// News
Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{id}', [NewsController::class, 'show'])->name('news.show');

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

    Route::get('/concerts', [ConcertController::class, 'index'])->name('concerts.index');
    Route::get('/concerts/create', [ConcertController::class, 'create'])->name('concerts.create');
    Route::post('/concerts', [ConcertController::class, 'store'])->name('concerts.store');
    Route::get('/concerts/{id}/edit', [ConcertController::class, 'edit'])->name('concerts.edit');
    Route::put('/concerts/{id}', [ConcertController::class, 'update'])->name('concerts.update');
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
    Route::patch('/merchandise/{id}/status', [MerchandiseController::class, 'adminToggleStatus'])->name('merchandise.toggle');
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
    Route::put('/merchandise/{id}', [MerchandiseController::class, 'adminUpdate'])->name('merchandise.update');
    Route::delete('/merchandise/{id}', [MerchandiseController::class, 'adminDestroy'])->name('merchandise.destroy');
});