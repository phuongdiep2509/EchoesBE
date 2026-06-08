<?php

namespace App\Http\Controllers;

use App\Models\ThanhToan;
use Illuminate\Http\Request;

class AdminPaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = ThanhToan::query()
            ->leftJoin('don_hang', 'thanh_toan.MaDonHang', '=', 'don_hang.MaDonHang')
            ->leftJoin('khach_hang', 'don_hang.MaKhachHang', '=', 'khach_hang.MaKhachHang')
            ->leftJoin('tai_khoan', 'khach_hang.MaTaiKhoan', '=', 'tai_khoan.MaTaiKhoan')
            ->select(
                'thanh_toan.*',
                'don_hang.TrangThai as TrangThaiDonHang',
                'don_hang.NgayDat',
                'tai_khoan.HoTen',
                'tai_khoan.Email'
            )
            ->orderByDesc('thanh_toan.MaThanhToan');

        if ($request->filled('status')) {
            $query->where('thanh_toan.TrangThai', $request->status);
        }

        if ($request->filled('from')) {
            $query->whereDate('thanh_toan.ThoiGianThanhToan', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('thanh_toan.ThoiGianThanhToan', '<=', $request->to);
        }

        if ($request->filled('keyword')) {
            $keyword = '%' . $request->keyword . '%';
            $query->where(function ($q) use ($keyword) {
                $q->where('thanh_toan.MaGiaoDich', 'like', $keyword)
                  ->orWhere('tai_khoan.HoTen', 'like', $keyword)
                  ->orWhere('tai_khoan.Email', 'like', $keyword)
                  ->orWhere('don_hang.MaDonHang', 'like', $keyword);
            });
        }

        $payments = $query->paginate(15)->withQueryString();

        $summary = [
            'total' => ThanhToan::count(),
            'success' => ThanhToan::where('TrangThai', 'ThanhCong')->count(),
            'pending' => ThanhToan::where('TrangThai', 'ChoThanhToan')->count(),
            'failed' => ThanhToan::where('TrangThai', 'ThatBai')->count(),
            'revenue' => ThanhToan::where('TrangThai', 'ThanhCong')->sum('SoTien'),
        ];

        return view('admin.payments.index', compact('payments', 'summary'));
    }

    public function show(int $id)
    {
        $payment = ThanhToan::with(['donHang.khachHang.taiKhoan', 'donHang.ve.suKien', 'donHang.ve.hangVe'])
            ->findOrFail($id);

        return view('admin.payments.show', compact('payment'));
    }
}