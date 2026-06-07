<?php

namespace App\Http\Controllers;

use App\Models\KhachHang;
use App\Models\SuKien;
use App\Models\ThanhToan;
use App\Models\Ve;
use App\Models\VeTang;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();

        $stats = [
            'totalRevenue' => ThanhToan::where('TrangThai', 'ThanhCong')->sum('SoTien'),
            'todayRevenue' => ThanhToan::where('TrangThai', 'ThanhCong')->whereDate('ThoiGianThanhToan', $today)->sum('SoTien'),
            'successPayments' => ThanhToan::where('TrangThai', 'ThanhCong')->count(),
            'pendingPayments' => ThanhToan::where('TrangThai', 'ChoThanhToan')->count(),
            'failedPayments' => ThanhToan::where('TrangThai', 'ThatBai')->count(),
            'ticketsSold' => Ve::join('don_hang', 've.MaDonHang', '=', 'don_hang.MaDonHang')
                ->where('don_hang.TrangThai', 'DaThanhToan')
                ->where('ve.TrangThai', '!=', 'DaHuy')
                ->count(),
            'giftedTickets' => VeTang::count(),
            'events' => SuKien::count(),
            'customers' => KhachHang::count(),
        ];

        $revenueByDay = ThanhToan::selectRaw('DATE(ThoiGianThanhToan) as ngay, SUM(SoTien) as doanh_thu')
            ->where('TrangThai', 'ThanhCong')
            ->whereNotNull('ThoiGianThanhToan')
            ->whereDate('ThoiGianThanhToan', '>=', now()->subDays(6)->toDateString())
            ->groupByRaw('DATE(ThoiGianThanhToan)')
            ->orderBy('ngay')
            ->get();

        $topEvents = Ve::query()
            ->join('don_hang', 've.MaDonHang', '=', 'don_hang.MaDonHang')
            ->join('su_kien', 've.MaSuKien', '=', 'su_kien.MaSuKien')
            ->where('don_hang.TrangThai', 'DaThanhToan')
            ->where('ve.TrangThai', '!=', 'DaHuy')
            ->select('su_kien.MaSuKien', 'su_kien.TenSuKien', DB::raw('COUNT(ve.MaVe) as so_ve'))
            ->groupBy('su_kien.MaSuKien', 'su_kien.TenSuKien')
            ->orderByDesc('so_ve')
            ->limit(5)
            ->get();

        $recentPayments = ThanhToan::query()
            ->leftJoin('don_hang', 'thanh_toan.MaDonHang', '=', 'don_hang.MaDonHang')
            ->leftJoin('khach_hang', 'don_hang.MaKhachHang', '=', 'khach_hang.MaKhachHang')
            ->leftJoin('tai_khoan', 'khach_hang.MaTaiKhoan', '=', 'tai_khoan.MaTaiKhoan')
            ->select('thanh_toan.*', 'don_hang.TrangThai as TrangThaiDonHang', 'tai_khoan.HoTen', 'tai_khoan.Email')
            ->orderByDesc('thanh_toan.MaThanhToan')
            ->limit(8)
            ->get();

        return view('admin.dashboard', compact('stats', 'revenueByDay', 'topEvents', 'recentPayments'));
    }
}
