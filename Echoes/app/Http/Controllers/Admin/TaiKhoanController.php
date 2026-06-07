<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TaiKhoan;
use Illuminate\Http\Request;

class TaiKhoanController extends Controller
{
    // ─── Danh sách tất cả tài khoản ──────────────────
    public function index(Request $request)
    {
        $query = TaiKhoan::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('TenDangNhap', 'like', "%$search%")
                  ->orWhere('HoTen', 'like', "%$search%")
                  ->orWhere('Email', 'like', "%$search%")
                  ->orWhere('SoDienThoai', 'like', "%$search%");
            });
        }

        if ($vaiTro = $request->input('vai_tro')) {
            $query->where('VaiTro', $vaiTro);
        }

        if ($trangThai = $request->input('trang_thai')) {
            $query->where('TrangThai', $trangThai);
        }

        $danhSach = $query->with('nhanVien')->orderBy('MaTaiKhoan', 'desc')->paginate(15)->withQueryString();

        return view('admin.tai-khoan.index', compact('danhSach'));
    }

    // ─── Chi tiết tài khoản ───────────────────────────
    public function show(TaiKhoan $taiKhoan)
    {
        $taiKhoan->load(['khachHang', 'nhanVien']);
        return view('admin.tai-khoan.show', compact('taiKhoan'));
    }

    // ─── Khóa / Kích hoạt tài khoản ──────────────────
    public function toggleTrangThai(TaiKhoan $taiKhoan)
    {
        // Không cho khóa chính mình
        if ($taiKhoan->MaTaiKhoan === auth()->id()) {
            return back()->with('error', 'Không thể khóa tài khoản đang đăng nhập.');
        }

        $trangThaiMoi = $taiKhoan->TrangThai === 'HoatDong' ? 'NgungHoatDong' : 'HoatDong';
        $taiKhoan->update(['TrangThai' => $trangThaiMoi]);

        $msg = $trangThaiMoi === 'HoatDong' ? 'Tài khoản đã được kích hoạt.' : 'Tài khoản đã bị khóa.';
        return back()->with('success', $msg);
    }
}
