<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return view('profile.show', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'HoTen'       => 'required|string|max:255',
            'SoDienThoai' => 'nullable|string|max:15|regex:/^[0-9+\-\s()]+$/',
            'NgaySinh'    => 'nullable|date|before:today',
            'GioiTinh'    => 'nullable|in:Nam,Nu,Khac',
        ], [
            'HoTen.required'       => 'Vui lòng nhập họ tên.',
            'SoDienThoai.regex'    => 'Số điện thoại không hợp lệ.',
            'NgaySinh.before'      => 'Ngày sinh phải trước ngày hôm nay.',
        ]);

        $user->update([
            'HoTen'       => trim($request->HoTen),
            'SoDienThoai' => $request->SoDienThoai,
        ]);

        // Cập nhật thêm vào bảng khach_hang nếu có
        if ($user->isKhachHang() && $user->khachHang) {
            $user->khachHang->update([
                'NgaySinh' => $request->NgaySinh ?: null,
                'GioiTinh' => $request->GioiTinh ?: null,
            ]);
        }

        return redirect()->back()->with('profile_success', 'Cập nhật hồ sơ thành công.');
    }

    public function changePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => 'required|string',
            'MatKhau'          => 'required|string|min:8|max:32|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*_]).+$/',
        ], [
            'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại.',
            'MatKhau.required'          => 'Vui lòng nhập mật khẩu mới.',
            'MatKhau.confirmed'         => 'Xác nhận mật khẩu không khớp.',
            'MatKhau.regex'             => 'Mật khẩu phải có chữ hoa, thường, số và ký tự đặc biệt.',
        ]);

        if (!Hash::check($request->current_password, $user->MatKhau)) {
            return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng.']);
        }

        $user->update(['MatKhau' => Hash::make($request->MatKhau)]);

        return redirect()->back()->with('profile_success', 'Đổi mật khẩu thành công.');
    }
}
