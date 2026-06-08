<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MyTicketController extends Controller
{
    /**
     * Lấy MaTaiKhoan hiện tại.
     * Hỗ trợ cả session thật và account_id query để test.
     */
    private function currentAccountId(Request $request): ?int
    {
        $authUser = auth()->user();

        $id = session('MaTaiKhoan')
            ?? session('ma_tai_khoan')
            ?? data_get(session('user'), 'MaTaiKhoan')
            ?? data_get(session('user'), 'id')
            ?? data_get($authUser, 'MaTaiKhoan')
            ?? data_get($authUser, 'id')
            ?? $request->input('account_id')
            ?? $request->query('account_id');

        return $id ? (int) $id : null;
    }

    /**
     * Lấy MaKhachHang từ MaTaiKhoan.
     */
    private function currentCustomerId(Request $request): ?int
    {
        $accountId = $this->currentAccountId($request);

        if (!$accountId) {
            return null;
        }

        $customerId = DB::table('khach_hang')
            ->where('MaTaiKhoan', $accountId)
            ->value('MaKhachHang');

        return $customerId ? (int) $customerId : null;
    }

    /**
     * Trả về cột nếu tồn tại, nếu không thì trả NULL để tránh lỗi Unknown column.
     */
    private function optionalSelect(string $table, string $alias, string $column, ?string $as = null)
    {
        $outputName = $as ?? $column;

        if (Schema::hasColumn($table, $column)) {
            return "{$alias}.{$column} as {$outputName}";
        }

        return DB::raw("NULL as {$outputName}");
    }

    /**
     * Trang Vé của tôi.
     */
    public function index(Request $request)
    {
        $accountId = $this->currentAccountId($request);

        if (!$accountId) {
            return redirect('/login')
                ->with('error', 'Vui lòng đăng nhập để xem vé của tôi.');
        }

        $customer = DB::table('khach_hang as kh')
            ->join('tai_khoan as tk', 'tk.MaTaiKhoan', '=', 'kh.MaTaiKhoan')
            ->where('kh.MaTaiKhoan', $accountId)
            ->select([
                'kh.MaKhachHang',
                'kh.MaTaiKhoan',
                'tk.TenDangNhap',
                'tk.Email',
                $this->optionalSelect('tai_khoan', 'tk', 'HoTen', 'HoTen'),
                $this->optionalSelect('tai_khoan', 'tk', 'SoDienThoai', 'SoDienThoai'),
            ])
            ->first();

        if (!$customer) {
            return view('pages.my-ticket', [
                'accountId' => $accountId,
                'customer' => null,
                'tickets' => collect(),
                'ticketItems' => collect(),
                'orders' => collect(),
                'giftHistory' => collect(),
                'gifts' => collect(),
                'needLogin' => false,
            ])->with('warning', 'Tài khoản hiện tại chưa có thông tin khách hàng.');
        }

        /*
         * Lấy bản ghi tặng vé mới nhất của từng vé.
         * Tránh việc 1 vé có nhiều lịch sử tặng làm bị nhân dòng trong danh sách vé.
         */
        $latestGiftSub = DB::table('ve_tang')
            ->select('MaVe', DB::raw('MAX(MaVeTang) as LatestMaVeTang'))
            ->groupBy('MaVe');

        /*
         * Danh sách vé đã thanh toán của khách hàng.
         */
        $tickets = DB::table('ve as v')
            ->join('don_hang as dh', 'dh.MaDonHang', '=', 'v.MaDonHang')
            ->leftJoin('thanh_toan as tt', function ($join) {
                $join->on('tt.MaDonHang', '=', 'dh.MaDonHang')
                    ->where('tt.TrangThai', '=', 'ThanhCong');
            })
            ->leftJoin('su_kien as sk', 'sk.MaSuKien', '=', 'v.MaSuKien')
            ->leftJoin('hang_ve as hv', 'hv.MaHangVe', '=', 'v.MaHangVe')
            ->leftJoin('khu_vuc_su_kien as kv', 'kv.MaKhuVuc', '=', 'hv.MaKhuVuc')
            ->leftJoin('ghe_ngoi as g', 'g.MaGhe', '=', 'v.MaGhe')
            ->leftJoinSub($latestGiftSub, 'last_vt', function ($join) {
                $join->on('last_vt.MaVe', '=', 'v.MaVe');
            })
            ->leftJoin('ve_tang as vt', 'vt.MaVeTang', '=', 'last_vt.LatestMaVeTang')
            ->where('dh.MaKhachHang', $customer->MaKhachHang)
            ->where('dh.TrangThai', 'DaThanhToan')
            ->select([
                'v.MaVe',
                'v.MaDonHang',
                'v.MaHangVe',
                'v.MaGhe',
                'v.MaSuKien',
                'v.MaQR',
                'v.MaVeDienTu',
                'v.TrangThai as TrangThaiVe',

                'dh.TrangThai as TrangThaiDonHang',
                'dh.NgayDat',
                'dh.TongTien',

                'sk.MaSuKien',
                'sk.TenSuKien',
                'sk.AnhBia',
                'sk.ThoiGianBatDau',
                'sk.ThoiGianKetThuc',
                'sk.TrangThai as TrangThaiSuKien',

                'hv.TenHangVe',
                'hv.GiaVe',

                'kv.TenKhuVuc',

                'g.HangGhe',
                'g.SoGhe',

                $this->optionalSelect('ve_tang', 'vt', 'MaVeTang', 'MaVeTang'),
                $this->optionalSelect('ve_tang', 'vt', 'EmailNguoiNhan', 'EmailNguoiNhan'),
                $this->optionalSelect('ve_tang', 'vt', 'TrangThai', 'TrangThaiTangVe'),
                $this->optionalSelect('ve_tang', 'vt', 'ThoiGianTang', 'ThoiGianTang'),
                $this->optionalSelect('ve_tang', 'vt', 'ThoiGianNhan', 'ThoiGianNhan'),
            ])
            ->orderByDesc('dh.NgayDat')
            ->orderByDesc('v.MaVe')
            ->get();

        /*
         * Danh sách đơn hàng của khách hàng.
         * Biến này dùng cho phần @forelse($orders as $order) trong my-ticket.blade.php.
         */
        $orders = DB::table('don_hang as dh')
            ->leftJoin('ve as v', 'v.MaDonHang', '=', 'dh.MaDonHang')
            ->where('dh.MaKhachHang', $customer->MaKhachHang)
            ->select([
                'dh.MaDonHang',
                'dh.MaKhachHang',
                'dh.NgayDat',
                'dh.TongTien',
                'dh.TrangThai',
                DB::raw('COUNT(v.MaVe) as SoLuongVe'),
            ])
            ->groupBy(
                'dh.MaDonHang',
                'dh.MaKhachHang',
                'dh.NgayDat',
                'dh.TongTien',
                'dh.TrangThai'
            )
            ->orderByDesc('dh.NgayDat')
            ->get();

        /*
         * Lịch sử tặng vé.
         */
        $giftHistory = $this->giftHistoryQueryByCustomer($customer->MaKhachHang)->get();

        return view('pages.my-ticket', [
            'accountId' => $accountId,
            'customer' => $customer,
            'tickets' => $tickets,
            'ticketItems' => $tickets,
            'orders' => $orders,
            'giftHistory' => $giftHistory,
            'gifts' => $giftHistory,
            'needLogin' => false,
        ]);
    }

    /**
     * Chi tiết vé.
     */
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
            ->when($customerId, function ($query) use ($customerId) {
                $query->where('dh.MaKhachHang', $customerId);
            })
            ->select([
                'v.MaVe',
                'v.MaDonHang',
                'v.MaHangVe',
                'v.MaGhe',
                'v.MaSuKien',
                'v.MaQR',
                'v.MaVeDienTu',
                'v.TrangThai as TrangThaiVe',
                'v.ThoiGianCheckIn',

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

        return view('pages.ticket-detail', [
            'ticket' => $ticket,
            'gift' => $gift,
            'accountId' => $accountId,
        ]);
    }

    /**
     * Trang lịch sử tặng vé.
     */
    public function giftHistory(Request $request)
    {
        $accountId = $this->currentAccountId($request);
        $customerId = $this->currentCustomerId($request);

        if (!$accountId || !$customerId) {
            return view('pages.gift-history', [
                'giftHistory' => collect(),
                'gifts' => collect(),
                'accountId' => $accountId,
                'needLogin' => true,
            ]);
        }

        $giftHistory = $this->giftHistoryQueryByCustomer($customerId)->get();

        return view('pages.gift-history', [
            'giftHistory' => $giftHistory,
            'gifts' => $giftHistory,
            'accountId' => $accountId,
            'needLogin' => false,
        ]);
    }

    /**
     * Query lịch sử tặng vé theo khách hàng sở hữu đơn hàng.
     */
    private function giftHistoryQueryByCustomer(int $customerId)
    {
        return DB::table('ve_tang as vt')
            ->join('ve as v', 'vt.MaVe', '=', 'v.MaVe')
            ->join('don_hang as dh', 'dh.MaDonHang', '=', 'v.MaDonHang')
            ->join('su_kien as sk', 'v.MaSuKien', '=', 'sk.MaSuKien')
            ->leftJoin('hang_ve as hv', 'v.MaHangVe', '=', 'hv.MaHangVe')
            ->leftJoin('khu_vuc_su_kien as kv', 'hv.MaKhuVuc', '=', 'kv.MaKhuVuc')
            ->leftJoin('ghe_ngoi as g', 'g.MaGhe', '=', 'v.MaGhe')
            ->where('dh.MaKhachHang', $customerId)
            ->select([
                'vt.MaVeTang',
                'vt.MaVe',

                $this->optionalSelect('ve_tang', 'vt', 'TenNguoiNhan', 'TenNguoiNhan'),
                $this->optionalSelect('ve_tang', 'vt', 'EmailNguoiNhan', 'EmailNguoiNhan'),
                $this->optionalSelect('ve_tang', 'vt', 'SdtNguoiNhan', 'SdtNguoiNhan'),
                $this->optionalSelect('ve_tang', 'vt', 'LoaiThiep', 'LoaiThiep'),
                $this->optionalSelect('ve_tang', 'vt', 'LoiChuc', 'LoiChuc'),
                $this->optionalSelect('ve_tang', 'vt', 'TrangThai', 'TrangThai'),
                $this->optionalSelect('ve_tang', 'vt', 'ThoiGianTang', 'ThoiGianTang'),
                $this->optionalSelect('ve_tang', 'vt', 'ThoiGianNhan', 'ThoiGianNhan'),

                'sk.TenSuKien',
                'sk.AnhBia',

                'hv.TenHangVe',
                'hv.GiaVe',

                'kv.TenKhuVuc',

                'g.HangGhe',
                'g.SoGhe',

                'v.MaVeDienTu',
                'v.TrangThai as TrangThaiVe',
            ])
            ->orderByDesc('vt.MaVeTang');
    }
}