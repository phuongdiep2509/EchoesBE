<?php

namespace App\Http\Controllers;

use App\Models\KhachHang;
use App\Models\SuKien;
use App\Models\ThanhToan;
use App\Models\Ve;
use App\Models\VeTang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function revenue(Request $request)
    {
        [$from, $to] = $this->dateRange($request);

        $base = ThanhToan::where('TrangThai', 'ThanhCong')
            ->whereBetween(DB::raw('DATE(ThoiGianThanhToan)'), [$from, $to]);

        $summary = [
            'from' => $from,
            'to' => $to,
            'totalRevenue' => (clone $base)->sum('SoTien'),
            'transactionCount' => (clone $base)->count(),
            'averageOrderValue' => (clone $base)->count() > 0 ? (clone $base)->avg('SoTien') : 0,
        ];

        $dailyRevenue = (clone $base)
            ->selectRaw('DATE(ThoiGianThanhToan) as ngay, SUM(SoTien) as doanh_thu, COUNT(*) as so_giao_dich')
            ->groupByRaw('DATE(ThoiGianThanhToan)')
            ->orderBy('ngay')
            ->get();

        $paymentMethods = (clone $base)
            ->selectRaw('PhuongThucThanhToan, SUM(SoTien) as doanh_thu, COUNT(*) as so_giao_dich')
            ->groupBy('PhuongThucThanhToan')
            ->orderByDesc('doanh_thu')
            ->get();

        $eventRevenue = Ve::query()
            ->join('don_hang', 've.MaDonHang', '=', 'don_hang.MaDonHang')
            ->join('thanh_toan', 'don_hang.MaDonHang', '=', 'thanh_toan.MaDonHang')
            ->join('su_kien', 've.MaSuKien', '=', 'su_kien.MaSuKien')
            ->join('hang_ve', 've.MaHangVe', '=', 'hang_ve.MaHangVe')
            ->where('don_hang.TrangThai', 'DaThanhToan')
            ->where('thanh_toan.TrangThai', 'ThanhCong')
            ->where('ve.TrangThai', '!=', 'DaHuy')
            ->whereBetween(DB::raw('DATE(thanh_toan.ThoiGianThanhToan)'), [$from, $to])
            ->select('su_kien.MaSuKien', 'su_kien.TenSuKien', DB::raw('COUNT(ve.MaVe) as so_ve'), DB::raw('SUM(hang_ve.GiaVe) as doanh_thu_uoc_tinh'))
            ->groupBy('su_kien.MaSuKien', 'su_kien.TenSuKien')
            ->orderByDesc('doanh_thu_uoc_tinh')
            ->get();

        return view('admin.reports.revenue', compact('summary', 'dailyRevenue', 'paymentMethods', 'eventRevenue'));
    }

    public function tickets(Request $request)
    {
        [$from, $to] = $this->dateRange($request);

        $soldTicketBase = Ve::query()
            ->join('don_hang', 've.MaDonHang', '=', 'don_hang.MaDonHang')
            ->leftJoin('thanh_toan', 'don_hang.MaDonHang', '=', 'thanh_toan.MaDonHang')
            ->where('don_hang.TrangThai', 'DaThanhToan')
            ->where('thanh_toan.TrangThai', 'ThanhCong')
            ->whereBetween(DB::raw('DATE(thanh_toan.ThoiGianThanhToan)'), [$from, $to]);

        $summary = [
            'from' => $from,
            'to' => $to,
            'soldTickets' => (clone $soldTicketBase)->where('ve.TrangThai', '!=', 'DaHuy')->count(),
            'usedTickets' => (clone $soldTicketBase)->where('ve.TrangThai', 'DaSuDung')->count(),
            'cancelledTickets' => Ve::where('TrangThai', 'DaHuy')->count(),
            'giftedTickets' => VeTang::count(),
            'eventCount' => SuKien::count(),
            'customerCount' => KhachHang::count(),
        ];

        $ticketByStatus = Ve::query()
            ->select('TrangThai', DB::raw('COUNT(*) as so_luong'))
            ->groupBy('TrangThai')
            ->orderBy('TrangThai')
            ->get();

        $ticketByEvent = (clone $soldTicketBase)
            ->join('su_kien', 've.MaSuKien', '=', 'su_kien.MaSuKien')
            ->select('su_kien.MaSuKien', 'su_kien.TenSuKien', DB::raw('COUNT(ve.MaVe) as so_ve'))
            ->where('ve.TrangThai', '!=', 'DaHuy')
            ->groupBy('su_kien.MaSuKien', 'su_kien.TenSuKien')
            ->orderByDesc('so_ve')
            ->get();

        $giftedByEvent = VeTang::query()
            ->join('ve', 've_tang.MaVe', '=', 've.MaVe')
            ->join('su_kien', 've.MaSuKien', '=', 'su_kien.MaSuKien')
            ->select('su_kien.MaSuKien', 'su_kien.TenSuKien', DB::raw('COUNT(ve_tang.MaVeTang) as so_ve_tang'))
            ->groupBy('su_kien.MaSuKien', 'su_kien.TenSuKien')
            ->orderByDesc('so_ve_tang')
            ->get();

        return view('admin.reports.tickets', compact('summary', 'ticketByStatus', 'ticketByEvent', 'giftedByEvent'));
    }

    public function exportRevenueCsv(Request $request): StreamedResponse
    {
        [$from, $to] = $this->dateRange($request);

        $rows = ThanhToan::where('TrangThai', 'ThanhCong')
            ->whereBetween(DB::raw('DATE(ThoiGianThanhToan)'), [$from, $to])
            ->selectRaw('DATE(ThoiGianThanhToan) as ngay, PhuongThucThanhToan, SUM(SoTien) as doanh_thu, COUNT(*) as so_giao_dich')
            ->groupByRaw('DATE(ThoiGianThanhToan), PhuongThucThanhToan')
            ->orderBy('ngay')
            ->get();

        return response()->streamDownload(function () use ($rows) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Ngay', 'Phuong thuc', 'Doanh thu', 'So giao dich']);
            foreach ($rows as $row) {
                fputcsv($handle, [$row->ngay, $row->PhuongThucThanhToan, $row->doanh_thu, $row->so_giao_dich]);
            }
            fclose($handle);
        }, 'bao-cao-doanh-thu-echoes.csv');
    }

    private function dateRange(Request $request): array
    {
        $from = $request->input('from', now()->startOfMonth()->toDateString());
        $to = $request->input('to', now()->toDateString());
        return [$from, $to];
    }
}
