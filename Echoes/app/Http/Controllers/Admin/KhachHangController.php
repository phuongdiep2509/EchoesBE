<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KhachHang;
use Illuminate\Http\Request;

class KhachHangController extends Controller
{
    public function index(Request $request)
    {
        $query = KhachHang::with('taiKhoan');

        if ($search = $request->input('search')) {
            $query->whereHas('taiKhoan', function ($q) use ($search) {
                $q->where('HoTen', 'like', "%$search%")
                  ->orWhere('Email', 'like', "%$search%")
                  ->orWhere('TenDangNhap', 'like', "%$search%")
                  ->orWhere('SoDienThoai', 'like', "%$search%");
            });
        }

        if ($trangThai = $request->input('trang_thai')) {
            $query->whereHas('taiKhoan', fn($q) => $q->where('TrangThai', $trangThai));
        }

        if ($gioiTinh = $request->input('gioi_tinh')) {
            $query->where('GioiTinh', $gioiTinh);
        }

        $danhSach = $query->orderBy('MaKhachHang', 'desc')->paginate(15)->withQueryString();

        return view('admin.khach-hang.index', compact('danhSach'));
    }

    public function show(KhachHang $khachHang)
    {
        $khachHang->load('taiKhoan');
        return view('admin.khach-hang.show', compact('khachHang'));
    }

    public function toggleTrangThai(KhachHang $khachHang)
    {
        $tk = $khachHang->taiKhoan;
        $moiTrangThai = $tk->TrangThai === 'HoatDong' ? 'NgungHoatDong' : 'HoatDong';
        $tk->update(['TrangThai' => $moiTrangThai]);

        $msg = $moiTrangThai === 'HoatDong' ? 'Tài khoản đã được kích hoạt.' : 'Tài khoản đã bị khóa.';
        return back()->with('success', $msg);
    }
}
