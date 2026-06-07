<?php

namespace App\Http\Controllers;

use App\Models\TaiKhoan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    // Gửi email reset
    public function sendResetEmail(Request $request)
    {
        $request->validate([
            'forgot_email' => 'required|email',
        ], [
            'forgot_email.required' => 'Vui lòng nhập email.',
            'forgot_email.email'    => 'Email không hợp lệ.',
        ]);

        $taiKhoan = TaiKhoan::where('Email', $request->forgot_email)->first();

        // Luôn trả về success để tránh lộ thông tin tài khoản
        if ($taiKhoan && $taiKhoan->TrangThai === 'HoatDong') {
            $token  = Str::random(64);
            $expiry = Carbon::now()->addMinutes(30);

            $taiKhoan->update([
                'ResetToken'       => $token,
                'ResetTokenExpiry' => $expiry,
            ]);

            $resetUrl = route('password.reset.form', ['token' => $token]);

            try {
                Mail::send('emails.reset-password', [
                    'taiKhoan' => $taiKhoan,
                    'resetUrl' => $resetUrl,
                    'expiry'   => 30,
                ], function ($mail) use ($taiKhoan) {
                    $mail->to($taiKhoan->Email)
                         ->subject('Đặt lại mật khẩu — Echoes');
                });
            } catch (\Exception $e) {
                // Ghi log lỗi nhưng không lộ ra ngoài
                \Log::error('Gửi email reset thất bại: ' . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể gửi email. Vui lòng kiểm tra cấu hình mail hoặc thử lại sau.'
                ], 500);
            }
        }

        return response()->json(['success' => true]);
    }

    // Hiện form đặt lại mật khẩu
    public function showResetForm(string $token)
    {
        $taiKhoan = TaiKhoan::where('ResetToken', $token)
                             ->where('ResetTokenExpiry', '>', now())
                             ->first();

        if (!$taiKhoan) {
            return redirect()->route('home')
                ->with('open_auth', 'login')
                ->with('error', 'Liên kết đặt lại mật khẩu không hợp lệ hoặc đã hết hạn.');
        }

        return view('auth.reset-password', compact('token'));
    }

    // Xử lý đặt lại mật khẩu
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'       => 'required',
            'MatKhau'     => 'required|string|min:8|max:32|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*_]).+$/',
        ], [
            'MatKhau.required'  => 'Vui lòng nhập mật khẩu mới.',
            'MatKhau.confirmed' => 'Xác nhận mật khẩu không khớp.',
            'MatKhau.regex'     => 'Mật khẩu phải có chữ hoa, thường, số và ký tự đặc biệt.',
        ]);

        $taiKhoan = TaiKhoan::where('ResetToken', $request->token)
                             ->where('ResetTokenExpiry', '>', now())
                             ->first();

        if (!$taiKhoan) {
            return back()->withErrors(['token' => 'Token không hợp lệ hoặc đã hết hạn.']);
        }

        $taiKhoan->update([
            'MatKhau'          => Hash::make($request->MatKhau),
            'ResetToken'       => null,
            'ResetTokenExpiry' => null,
        ]);

        return redirect()->route('home')
            ->with('open_auth', 'login')
            ->with('success', 'Đặt lại mật khẩu thành công! Vui lòng đăng nhập.');
    }
}
