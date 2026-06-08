<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class AdminOrderController extends Controller
{
    public function index()
    {
        $orders = DB::table('don_hang as dh')
            ->leftJoin('khach_hang as kh', 'kh.MaKhachHang', '=', 'dh.MaKhachHang')
            ->leftJoin('tai_khoan as tk', 'tk.MaTaiKhoan', '=', 'kh.MaTaiKhoan')
            ->leftJoin('ve as v', 'v.MaDonHang', '=', 'dh.MaDonHang')
            ->groupBy('dh.MaDonHang', 'dh.MaKhachHang', 'dh.NgayDat', 'dh.TongTien', 'dh.TrangThai', 'tk.HoTen', 'tk.Email')
            ->orderByDesc('dh.NgayDat')
            ->selectRaw('dh.MaDonHang, dh.MaKhachHang, dh.NgayDat, dh.TongTien, dh.TrangThai, tk.HoTen, tk.Email, COUNT(v.MaVe) as SoLuongVe')
            ->get();

        return view('admin.orders.index', compact('orders'));
    }

}
