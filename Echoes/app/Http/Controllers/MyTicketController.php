<?php

namespace App\Http\Controllers;

use App\Models\KhachHang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MyTicketController extends Controller
{
    private function currentAccountId(Request $request): ?int
    {
        $id = session('MaTaiKhoan')
            ?? session('ma_tai_khoan')
            ?? data_get(session('user'), 'MaTaiKhoan')
            ?? data_get(session('user'), 'id')
            ?? $request->input('account_id')
            ?? $request->query('account_id')
            ?? optional(auth()->user())->MaTaiKhoan
            ?? optional(auth()->user())->id;

        return $id ? (int) $id : null;
    }

    private function currentCustomerId(Request $request): ?int
    {
        $accountId = $this->currentAccountId($request);

        if (!$accountId) {
            return null;
        }

        return KhachHang::where('MaTaiKhoan', $accountId)->value('MaKhachHang');
    }

    public function index(Request $request)
    {
        $accountId = $this->currentAccountId($request);
        $customerId = $this->currentCustomerId($request);

        if (!$accountId || !$customerId) {
            return view('pages.my-ticket', [
                'tickets' => collect(),
                'giftHistory' => collect(),
                'accountId' => $accountId,
                'customerId' => $customerId,
                'needLogin' => true,
            ]);
        }

        $latestGiftSub = DB::table('ve_tang')
            ->select('MaVe', DB::raw('MAX(MaVeTang) as LatestGiftId'))
            ->groupBy('MaVe');

        $tickets = DB::table('ve as v')
            ->join('don_hang as dh', 'v.MaDonHang', '=', 'dh.MaDonHang')
            ->join('su_kien as sk', 'v.MaSuKien', '=', 'sk.MaSuKien')
            ->join('hang_ve as hv', 'v.MaHangVe', '=', 'hv.MaHangVe')
            ->leftJoin('khu_vuc_su_kien as kv', 'hv.MaKhuVuc', '=', 'kv.MaKhuVuc')
            ->leftJoin('ghe_ngoi as gn', 'v.MaGhe', '=', 'gn.MaGhe')
            ->leftJoinSub($latestGiftSub, 'last_gift', function ($join) {
                $join->on('v.MaVe', '=', 'last_gift.MaVe');
            })
            ->leftJoin('ve_tang as vt', 'last_gift.LatestGiftId', '=', 'vt.MaVeTang')
            ->where('dh.MaKhachHang', $customerId)
            ->where('dh.TrangThai', 'DaThanhToan')
            ->select([
                'v.MaVe',
                'v.MaVeDienTu',
                'v.MaQR',
                'v.TrangThai as TrangThaiVe',
                'v.ThoiGianCheckIn',
                'dh.MaDonHang',
                'dh.NgayDat',
                'sk.MaSuKien',
                'sk.TenSuKien',
                'sk.AnhBia',
                'sk.ThoiGianBatDau',
                'sk.ThoiGianKetThuc',
                'sk.TrangThai as TrangThaiSuKien',
                'hv.TenHangVe',
                'hv.GiaVe',
                'kv.TenKhuVuc',
                'gn.HangGhe',
                'gn.SoGhe',
                'vt.MaVeTang',
                'vt.TenNguoiNhan',
                'vt.EmailNguoiNhan',
                'vt.TrangThai as TrangThaiTang',
                'vt.ThoiGianTang',
                'vt.ThoiGianNhan',
            ])
            ->orderByDesc('dh.NgayDat')
            ->orderByDesc('v.MaVe')
            ->get();

        $giftHistory = $this->giftHistoryQuery($accountId)->get();

        return view('pages.my-ticket', [
            'tickets' => $tickets,
            'giftHistory' => $giftHistory,
            'accountId' => $accountId,
            'customerId' => $customerId,
            'needLogin' => false,
        ]);
    }

    public function show(Request $request, int $ticketId)
    {
        $accountId = $this->currentAccountId($request);
        $customerId = $this->currentCustomerId($request);

        $ticket = DB::table('ve as v')
            ->join('don_hang as dh', 'v.MaDonHang', '=', 'dh.MaDonHang')
            ->join('su_kien as sk', 'v.MaSuKien', '=', 'sk.MaSuKien')
            ->join('hang_ve as hv', 'v.MaHangVe', '=', 'hv.MaHangVe')
            ->leftJoin('khu_vuc_su_kien as kv', 'hv.MaKhuVuc', '=', 'kv.MaKhuVuc')
            ->leftJoin('dia_diem_to_chuc as dd', 'sk.MaDiaDiem', '=', 'dd.MaDiaDiem')
            ->leftJoin('ghe_ngoi as gn', 'v.MaGhe', '=', 'gn.MaGhe')
            ->where('v.MaVe', $ticketId)
            ->when($customerId, fn ($query) => $query->where('dh.MaKhachHang', $customerId))
            ->select([
                'v.*',
                'dh.MaKhachHang',
                'dh.NgayDat',
                'dh.TongTien',
                'dh.TrangThai as TrangThaiDonHang',
                'sk.TenSuKien',
                'sk.AnhBia',
                'sk.ThoiGianBatDau',
                'sk.ThoiGianKetThuc',
                'sk.TrangThai as TrangThaiSuKien',
                'sk.DieuKienVaDieuKhoan',
                'dd.TenDiaDiem',
                'dd.DiaChiChiTiet',
                'dd.ThanhPho',
                'hv.TenHangVe',
                'hv.GiaVe',
                'kv.TenKhuVuc',
                'gn.HangGhe',
                'gn.SoGhe',
            ])
            ->first();

        abort_if(!$ticket, 404);

        $gift = DB::table('ve_tang')
            ->where('MaVe', $ticketId)
            ->orderByDesc('MaVeTang')
            ->first();

        return view('pages.ticket-detail', compact('ticket', 'gift', 'accountId'));
    }

    public function giftHistory(Request $request)
    {
        $accountId = $this->currentAccountId($request);

        if (!$accountId) {
            return view('pages.gift-history', [
                'giftHistory' => collect(),
                'accountId' => null,
                'needLogin' => true,
            ]);
        }

        return view('pages.gift-history', [
            'giftHistory' => $this->giftHistoryQuery($accountId)->get(),
            'accountId' => $accountId,
            'needLogin' => false,
        ]);
    }

    private function giftHistoryQuery(int $accountId)
    {
        return DB::table('ve_tang as vt')
            ->join('ve as v', 'vt.MaVe', '=', 'v.MaVe')
            ->join('su_kien as sk', 'v.MaSuKien', '=', 'sk.MaSuKien')
            ->leftJoin('hang_ve as hv', 'v.MaHangVe', '=', 'hv.MaHangVe')
            ->leftJoin('khu_vuc_su_kien as kv', 'hv.MaKhuVuc', '=', 'kv.MaKhuVuc')
            ->where('vt.MaTaiKhoanNguoiTang', $accountId)
            ->select([
                'vt.MaVeTang',
                'vt.MaVe',
                'vt.TenNguoiNhan',
                'vt.EmailNguoiNhan',
                'vt.SdtNguoiNhan',
                'vt.LoaiThiep',
                'vt.LoiChuc',
                'vt.TrangThai',
                'vt.ThoiGianTang',
                'vt.ThoiGianNhan',
                'sk.TenSuKien',
                'sk.AnhBia',
                'hv.TenHangVe',
                'kv.TenKhuVuc',
                'v.MaVeDienTu',
            ])
            ->orderByDesc('vt.MaVeTang');
    }
}
