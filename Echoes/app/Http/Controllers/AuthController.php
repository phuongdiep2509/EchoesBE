<?php

namespace App\Http\Controllers;

use App\Models\TaiKhoan;
use App\Models\KhachHang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // ─── Hiển thị trang đăng nhập / đăng ký ─────────
    public function showAuthPage(string $tab = 'login')
    {
        // Nếu đã đăng nhập rồi thì redirect luôn
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }
        // Không còn trang riêng — redirect về home, popup sẽ tự mở
        return redirect()->route('home')->with('open_auth', $tab);
    }

    // ─── Đăng nhập ───────────────────────────────────
    public function login(Request $request)
    {
        $request->validate([
            'login'    => 'required|string',
            'password' => 'required|string',
        ], [
            'login.required'    => 'Vui lòng nhập tên đăng nhập hoặc email.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
        ]);

        $login = trim($request->input('login'));

        // Tìm tài khoản theo TenDangNhap hoặc Email
        $taiKhoan = TaiKhoan::where('TenDangNhap', $login)
                             ->orWhere('Email', $login)
                             ->first();

        if (!$taiKhoan) {
            return back()
                ->withErrors(['login' => 'Tên đăng nhập hoặc mật khẩu không đúng.'])
                ->withInput(['login' => $login]);
        }

        if ($taiKhoan->TrangThai === 'NgungHoatDong') {
            return back()
                ->withErrors(['login' => 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên.'])
                ->withInput(['login' => $login]);
        }

        // Dùng Auth::attempt với trường password map sang MatKhau
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'Email' : 'TenDangNhap';

        if (!Auth::attempt([$field => $login, 'password' => $request->password], $request->boolean('remember'))) {
            return back()
                ->withErrors(['login' => 'Tên đăng nhập hoặc mật khẩu không đúng.'])
                ->withInput(['login' => $login]);
        }

        $request->session()->regenerate();

        return $this->redirectByRole(Auth::user());
    }

    // ─── Đăng ký (Khách hàng) ────────────────────────
    public function register(Request $request)
    {
        $request->validate([
            'TenDangNhap'          => 'required|string|max:50|unique:TAI_KHOAN,TenDangNhap|regex:/^[a-zA-Z0-9_]+$/',
            'HoTen'                => 'required|string|max:255',
            'Email'                => 'required|email|max:255|unique:TAI_KHOAN,Email',
            'MatKhau'              => 'required|string|min:8|max:32|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*_]).+$/',
            'SoDienThoai'          => 'nullable|string|max:15',
        ], [
            'TenDangNhap.required'  => 'Vui lòng nhập tên đăng nhập.',
            'TenDangNhap.unique'    => 'Tên đăng nhập đã được sử dụng.',
            'TenDangNhap.regex'     => 'Tên đăng nhập chỉ chứa chữ cái, số và dấu gạch dưới.',
            'HoTen.required'        => 'Vui lòng nhập họ tên.',
            'Email.required'        => 'Vui lòng nhập email.',
            'Email.unique'          => 'Email đã được sử dụng.',
            'MatKhau.required'      => 'Vui lòng nhập mật khẩu.',
            'MatKhau.confirmed'     => 'Xác nhận mật khẩu không khớp.',
            'MatKhau.regex'         => 'Mật khẩu chưa đạt yêu cầu.',
        ]);

        $taiKhoan = TaiKhoan::create([
            'TenDangNhap' => trim($request->TenDangNhap),
            'MatKhau'     => Hash::make($request->MatKhau),
            'HoTen'       => trim($request->HoTen),
            'Email'       => strtolower(trim($request->Email)),
            'SoDienThoai' => $request->SoDienThoai,
            'VaiTro'      => 'KhachHang',
            'TrangThai'   => 'HoatDong',
        ]);

        // Tạo bản ghi KHACH_HANG tương ứng
        KhachHang::create([
            'MaTaiKhoan' => $taiKhoan->MaTaiKhoan,
        ]);

        Auth::login($taiKhoan);
        $request->session()->regenerate();

        return $this->redirectByRole($taiKhoan);
    }

    // ─── Đăng xuất ───────────────────────────────────
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Đăng xuất thành công.');
    }

    // ─── Redirect theo vai trò ────────────────────────
    private function redirectByRole(TaiKhoan $taiKhoan)
    {
        if ($taiKhoan->isAdmin() || $taiKhoan->isNhanVien()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('home');
    }
}
