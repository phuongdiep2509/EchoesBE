<?php

namespace App\Http\Controllers;

use App\Mail\TicketGiftNotificationMail;
use App\Models\KhachHang;
use App\Models\Ve;
use App\Models\VeTang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Throwable;

class TicketGiftController extends Controller
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

    public function store(Request $request, int $ticketId)
    {
        $validated = $request->validate([
            'TenNguoiNhan' => ['required', 'string', 'max:255'],
            'EmailNguoiNhan' => ['required', 'email', 'max:255'],
            'SdtNguoiNhan' => ['nullable', 'string', 'max:15'],
            'LoaiThiep' => ['nullable', 'string', 'max:100'],
            'LoiChuc' => ['nullable', 'string', 'max:1000'],
        ], [
            'TenNguoiNhan.required' => 'Vui lòng nhập tên người nhận.',
            'EmailNguoiNhan.required' => 'Vui lòng nhập email người nhận.',
            'EmailNguoiNhan.email' => 'Email người nhận không hợp lệ.',
        ]);

        $accountId = $this->currentAccountId($request);
        $customerId = $this->currentCustomerId($request);

        if (!$accountId || !$customerId) {
            return back()->with('error', 'Bạn cần đăng nhập trước khi tặng vé.');
        }

        $gift = null;

        $result = DB::transaction(function () use ($ticketId, $validated, $accountId, $customerId, &$gift) {
            $ticket = DB::table('ve as v')
                ->join('don_hang as dh', 'v.MaDonHang', '=', 'dh.MaDonHang')
                ->join('su_kien as sk', 'v.MaSuKien', '=', 'sk.MaSuKien')
                ->leftJoin('khach_hang as kh', 'dh.MaKhachHang', '=', 'kh.MaKhachHang')
                ->leftJoin('tai_khoan as tk', 'kh.MaTaiKhoan', '=', 'tk.MaTaiKhoan')
                ->where('v.MaVe', $ticketId)
                ->lockForUpdate()
                ->select([
                    'v.MaVe',
                    'v.MaDonHang',
                    'v.MaVeDienTu',
                    'v.TrangThai as TrangThaiVe',
                    'dh.MaKhachHang',
                    'dh.TrangThai as TrangThaiDonHang',
                    'sk.TrangThai as TrangThaiSuKien',
                    'sk.ThoiGianKetThuc',
                    'tk.Email as EmailNguoiTang',
                ])
                ->first();

            if (!$ticket) {
                return ['ok' => false, 'message' => 'Không tìm thấy vé cần tặng.'];
            }

            if ((int) $ticket->MaKhachHang !== (int) $customerId) {
                return ['ok' => false, 'message' => 'Bạn không có quyền tặng vé này.'];
            }

            if ($ticket->TrangThaiDonHang !== 'DaThanhToan') {
                return ['ok' => false, 'message' => 'Chỉ có thể tặng vé thuộc đơn hàng đã thanh toán.'];
            }

            if ($ticket->TrangThaiVe !== 'ChoSuDung') {
                return ['ok' => false, 'message' => 'Không thể tặng vé đã sử dụng hoặc đã hủy.'];
            }

            if (in_array($ticket->TrangThaiSuKien, ['DaHuy', 'DaKetThuc'], true)) {
                return ['ok' => false, 'message' => 'Không thể tặng vé của sự kiện đã hủy hoặc đã kết thúc.'];
            }

            if ($ticket->ThoiGianKetThuc && now()->greaterThan($ticket->ThoiGianKetThuc)) {
                return ['ok' => false, 'message' => 'Sự kiện đã kết thúc nên không thể tặng vé.'];
            }

            if ($ticket->EmailNguoiTang && strtolower($ticket->EmailNguoiTang) === strtolower($validated['EmailNguoiNhan'])) {
                return ['ok' => false, 'message' => 'Không thể tặng vé cho chính email của bạn.'];
            }

            $activeGift = VeTang::where('MaVe', $ticketId)
                ->whereIn('TrangThai', ['DangChoNhan', 'DaNhan'])
                ->latest('MaVeTang')
                ->first();

            if ($activeGift) {
                return ['ok' => false, 'message' => 'Vé này đã được tặng hoặc đang chờ người nhận xác nhận.'];
            }

            $gift = VeTang::create([
                'MaVe' => $ticketId,
                'MaTaiKhoanNguoiTang' => $accountId,
                'TenNguoiNhan' => $validated['TenNguoiNhan'],
                'EmailNguoiNhan' => $validated['EmailNguoiNhan'],
                'SdtNguoiNhan' => $validated['SdtNguoiNhan'] ?? null,
                'LoaiThiep' => $validated['LoaiThiep'] ?? null,
                'LoiChuc' => $validated['LoiChuc'] ?? null,
                'TrangThai' => 'DangChoNhan',
                'TokenNhanVe' => Str::random(64),
                'ThoiGianTang' => now(),
                'ThoiGianNhan' => null,
            ]);

            return ['ok' => true, 'message' => 'Đã tạo yêu cầu tặng vé.'];
        });

        if (!$result['ok']) {
            return back()->with('error', $result['message']);
        }

        $mailStatus = $this->sendGiftEmail($gift);

        if ($mailStatus === 'sent') {
            return back()->with('success', 'Đã tặng vé và gửi email thông báo cho người nhận.');
        }

        return back()
            ->with('success', 'Đã lưu thông tin tặng vé.')
            ->with('warning', 'Email thông báo chưa gửi được. Vui lòng kiểm tra cấu hình MAIL trong file .env.');
    }

    public function receive(string $token)
    {
        $gift = VeTang::where('TokenNhanVe', $token)->firstOrFail();
        $ticket = $this->getPublicTicketInfo($gift->MaVe);

        return view('pages.receive-ticket', compact('gift', 'ticket'));
    }

    public function confirm(Request $request, string $token)
    {
        $validated = $request->validate([
            'EmailNguoiNhan' => ['required', 'email', 'max:255'],
        ], [
            'EmailNguoiNhan.required' => 'Vui lòng nhập email nhận vé.',
            'EmailNguoiNhan.email' => 'Email nhận vé không hợp lệ.',
        ]);

        $message = null;

        $gift = DB::transaction(function () use ($token, $validated, &$message) {
            $gift = VeTang::where('TokenNhanVe', $token)
                ->lockForUpdate()
                ->firstOrFail();

            if ($gift->TrangThai === 'DaNhan') {
                $message = 'Vé này đã được xác nhận nhận trước đó.';
                return $gift;
            }

            if ($gift->TrangThai !== 'DangChoNhan') {
                $message = 'Lượt tặng vé không còn hiệu lực.';
                return $gift;
            }

            if (strtolower($gift->EmailNguoiNhan) !== strtolower($validated['EmailNguoiNhan'])) {
                $message = 'Email xác nhận không khớp với email người nhận vé.';
                return $gift;
            }

            $ticket = Ve::findOrFail($gift->MaVe);

            if ($ticket->TrangThai !== 'ChoSuDung') {
                $message = 'Vé đã sử dụng hoặc đã bị hủy nên không thể nhận.';
                return $gift;
            }

            $gift->update([
                'TrangThai' => 'DaNhan',
                'ThoiGianNhan' => now(),
            ]);

            $message = 'Xác nhận nhận vé thành công.';
            return $gift->fresh();
        });

        $ticket = $this->getPublicTicketInfo($gift->MaVe);

        return view('pages.receive-ticket', compact('gift', 'ticket', 'message'));
    }

    public function cancel(Request $request, int $giftId)
    {
        $accountId = $this->currentAccountId($request);

        if (!$accountId) {
            return back()->with('error', 'Bạn cần đăng nhập để hủy lượt tặng vé.');
        }

        $gift = VeTang::where('MaVeTang', $giftId)
            ->where('MaTaiKhoanNguoiTang', $accountId)
            ->firstOrFail();

        if ($gift->TrangThai === 'DaNhan') {
            return back()->with('error', 'Người nhận đã xác nhận nhận vé nên không thể hủy lượt tặng.');
        }

        if ($gift->TrangThai === 'DaHuy') {
            return back()->with('error', 'Lượt tặng vé này đã bị hủy trước đó.');
        }

        $gift->update(['TrangThai' => 'DaHuy']);

        return back()->with('success', 'Đã hủy lượt tặng vé. Vé có thể được tặng lại nếu vẫn hợp lệ.');
    }
    public function history(Request $request)
{
    $accountId = $this->currentAccountId($request);

    if (!$accountId) {
        return view('pages.gift-history', [
            'giftHistory' => collect(),
            'accountId' => null,
            'needLogin' => true,
        ]);
    }

    $giftHistory = DB::table('ve_tang as vt')
        ->join('ve as v', 'vt.MaVe', '=', 'v.MaVe')
        ->join('su_kien as sk', 'v.MaSuKien', '=', 'sk.MaSuKien')
        ->leftJoin('hang_ve as hv', 'v.MaHangVe', '=', 'hv.MaHangVe')
        ->leftJoin('khu_vuc_su_kien as kv', 'hv.MaKhuVuc', '=', 'kv.MaKhuVuc')
        ->leftJoin('ghe_ngoi as gn', 'v.MaGhe', '=', 'gn.MaGhe')
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
            'vt.TokenNhanVe',
            'vt.ThoiGianTang',
            'vt.ThoiGianNhan',
            'v.MaVeDienTu',
            'v.TrangThai as TrangThaiVe',
            'sk.TenSuKien',
            'sk.ThoiGianBatDau',
            'sk.ThoiGianKetThuc',
            'hv.TenHangVe',
            'kv.TenKhuVuc',
            'gn.HangGhe',
            'gn.SoGhe',
        ])
        ->orderByDesc('vt.MaVeTang')
        ->get();

    return view('pages.gift-history', [
        'giftHistory' => $giftHistory,
        'accountId' => $accountId,
        'needLogin' => false,
    ]);
}

    private function sendGiftEmail(?VeTang $gift): string
    {
        if (!$gift) {
            return 'missing_gift';
        }

        try {
            Mail::to($gift->EmailNguoiNhan)->send(new TicketGiftNotificationMail($gift->MaVeTang));
            return 'sent';
        } catch (Throwable $exception) {
            Log::error('Không gửi được email tặng vé Echoes', [
                'MaVeTang' => $gift->MaVeTang,
                'email' => $gift->EmailNguoiNhan,
                'error' => $exception->getMessage(),
            ]);

            return 'failed';
        }
    }

    private function getPublicTicketInfo(int $ticketId)
    {
        return DB::table('ve as v')
            ->join('su_kien as sk', 'v.MaSuKien', '=', 'sk.MaSuKien')
            ->join('hang_ve as hv', 'v.MaHangVe', '=', 'hv.MaHangVe')
            ->leftJoin('khu_vuc_su_kien as kv', 'hv.MaKhuVuc', '=', 'kv.MaKhuVuc')
            ->leftJoin('dia_diem_to_chuc as dd', 'sk.MaDiaDiem', '=', 'dd.MaDiaDiem')
            ->leftJoin('ghe_ngoi as gn', 'v.MaGhe', '=', 'gn.MaGhe')
            ->where('v.MaVe', $ticketId)
            ->select([
                'v.MaVe',
                'v.MaVeDienTu',
                'v.MaQR',
                'v.TrangThai as TrangThaiVe',
                'sk.TenSuKien',
                'sk.AnhBia',
                'sk.ThoiGianBatDau',
                'sk.ThoiGianKetThuc',
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
    }
}
