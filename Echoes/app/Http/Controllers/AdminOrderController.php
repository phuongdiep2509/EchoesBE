<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Ticket;
use Illuminate\Http\Request;
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

    public function updateStatus(Request $request, int $orderId)
    {
        $data = $request->validate([
            'TrangThai' => ['required', 'in:ChoThanhToan,DaThanhToan,DaHuy'],
        ]);

        $order = Order::findOrFail($orderId);

        DB::transaction(function () use ($order, $data): void {
            if ($data['TrangThai'] === Order::STATUS_CANCELLED && $order->TrangThai !== Order::STATUS_CANCELLED) {
                $items = DB::table('ve')
                    ->where('MaDonHang', $order->MaDonHang)
                    ->where('TrangThai', '!=', 'DaHuy')
                    ->groupBy('MaHangVe')
                    ->selectRaw('MaHangVe, COUNT(*) as SoLuong')
                    ->get();

                foreach ($items as $item) {
                    DB::table('hang_ve')
                        ->where('MaHangVe', $item->MaHangVe)
                        ->update(['SoLuongDaBan' => DB::raw('GREATEST(SoLuongDaBan - ' . (int) $item->SoLuong . ', 0)')]);
                }

                Ticket::where('MaDonHang', $order->MaDonHang)->update(['TrangThai' => 'DaHuy']);
            }

            $order->update(['TrangThai' => $data['TrangThai']]);
        });

        return back()->with('success', 'Đã cập nhật trạng thái đơn hàng.');
    }
}
