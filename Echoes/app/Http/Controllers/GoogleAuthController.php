<?php

namespace App\Http\Controllers;

use App\Models\TaiKhoan;
use App\Models\KhachHang;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class GoogleAuthController extends Controller
{
    // Redirect sang Google
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    // Google callback
    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('home')
                ->with('open_auth', 'login')
                ->with('error', 'Đăng nhập Google thất bại. Vui lòng thử lại.');
        }

        // Tìm tài khoản theo GoogleId hoặc Email
        $taiKhoan = TaiKhoan::where('GoogleId', $googleUser->getId())
                             ->orWhere('Email', $googleUser->getEmail())
                             ->first();

        if ($taiKhoan) {
            // Đã có tài khoản — cập nhật GoogleId nếu chưa có
            if (!$taiKhoan->GoogleId) {
                $taiKhoan->update(['GoogleId' => $googleUser->getId()]);
            }

            if ($taiKhoan->TrangThai === 'NgungHoatDong') {
                return redirect()->route('home')
                    ->with('open_auth', 'login')
                    ->with('error', 'Tài khoản của bạn đã bị khóa.');
            }
        } else {
            // Tạo tài khoản mới từ Google
            $tenDangNhap = $this->generateUsername($googleUser->getName());

            $taiKhoan = TaiKhoan::create([
                'TenDangNhap' => $tenDangNhap,
                'MatKhau'     => bcrypt(Str::random(24)), // mật khẩu ngẫu nhiên
                'HoTen'       => $googleUser->getName(),
                'Email'       => $googleUser->getEmail(),
                'GoogleId'    => $googleUser->getId(),
                'VaiTro'      => 'KhachHang',
                'TrangThai'   => 'HoatDong',
            ]);

            KhachHang::create(['MaTaiKhoan' => $taiKhoan->MaTaiKhoan]);
        }

        Auth::login($taiKhoan, true);
        request()->session()->regenerate();

        // Redirect theo vai trò
        if ($taiKhoan->isAdmin() || $taiKhoan->isNhanVien()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('home')->with('success', 'Đăng nhập Google thành công!');
    }

    // Tạo username từ tên Google
    private function generateUsername(string $name): string
    {
        $base = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $name));
        $base = $base ?: 'user';
        $username = $base;
        $i = 1;

        while (TaiKhoan::where('TenDangNhap', $username)->exists()) {
            $username = $base . $i++;
        }

        return $username;
    }
}
