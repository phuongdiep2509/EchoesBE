<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Ticket;
use App\Models\TicketHold;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BookingPageController extends Controller
{
    private const HOLD_ACTIVE = 'DangGiuCho';
    private const HOLD_EXPIRED = 'DaHetHan';
    private const HOLD_CONVERTED = 'DaThanhToan';
    private const TICKET_ACTIVE = 'ChoSuDung';
    private const TICKET_CANCELLED = 'DaHuy';

    public function cart(Request $request)
    {
        $customerId = $this->customerId($request);

        return view('pages.cart', [
            'customerId' => $customerId,
            'cart' => $this->cartData($customerId),
        ]);
    }

    public function myTickets(Request $request)
    {
        $customerId = $this->customerId($request);
        $orders = DB::table('don_hang as dh')
            ->leftJoin('ve as v', 'v.MaDonHang', '=', 'dh.MaDonHang')
            ->where('dh.MaKhachHang', $customerId)
            ->groupBy('dh.MaDonHang', 'dh.MaKhachHang', 'dh.NgayDat', 'dh.TongTien', 'dh.TrangThai')
            ->orderByDesc('dh.NgayDat')
            ->selectRaw('dh.MaDonHang, dh.MaKhachHang, dh.NgayDat, dh.TongTien, dh.TrangThai, COUNT(v.MaVe) as SoLuongVe')
            ->get();

        return view('pages.my-ticket', compact('customerId', 'orders'));
    }

    public function addToCart(Request $request)
    {
        $data = $request->validate([
            'MaKhachHang' => ['required', 'integer', 'min:1'],
            'MaHangVe' => ['required', 'integer', 'min:1'],
            'SoLuong' => ['required', 'integer', 'min:1'],
        ]);

        $customerId = (int) $data['MaKhachHang'];
        session(['MaKhachHang' => $customerId]);

        $ticketClass = $this->findTicketClass((int) $data['MaHangVe']);
        if (!$ticketClass) {
            return back()->with('error', 'Hạng vé không tồn tại.');
        }

        $available = $this->availableTickets((int) $data['MaHangVe']);
        if ($available < (int) $data['SoLuong']) {
            return back()->with('error', 'Số lượng vé còn lại không đủ.');
        }

        DB::transaction(function () use ($data, $customerId): void {
            $this->expireHolds($customerId);
            $hold = $this->activeHold($customerId);

            if (!$hold) {
                $hold = TicketHold::create([
                    'MaKhachHang' => $customerId,
                    'ThoiGianBatDau' => now(),
                    'ThoiGianHetHan' => now()->addMinutes(15),
                    'TrangThai' => self::HOLD_ACTIVE,
                ]);
            }

            $detail = DB::table('chi_tiet_giu_cho')
                ->where('MaGiuCho', $hold->MaGiuCho)
                ->where('MaHangVe', $data['MaHangVe'])
                ->first();

            if ($detail) {
                DB::table('chi_tiet_giu_cho')
                    ->where('MaGiuCho', $hold->MaGiuCho)
                    ->where('MaHangVe', $data['MaHangVe'])
                    ->update(['SoLuong' => $detail->SoLuong + (int) $data['SoLuong']]);
            } else {
                DB::table('chi_tiet_giu_cho')->insert([
                    'MaGiuCho' => $hold->MaGiuCho,
                    'MaHangVe' => $data['MaHangVe'],
                    'SoLuong' => $data['SoLuong'],
                ]);
            }
        });

        return redirect()->route('cart')->with('success', 'Đã thêm vé vào giỏ hàng.');
    }

    public function createOrder(Request $request)
    {
        $customerId = $this->customerId($request);
        $cart = $this->cartData($customerId);

        if (!$cart || $cart['ChiTiet']->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Giỏ hàng rỗng hoặc đã hết hạn.');
        }

        DB::transaction(function () use ($customerId, $cart): void {
            $order = Order::create([
                'MaKhachHang' => $customerId,
                'NgayDat' => now(),
                'TongTien' => $cart['TongTien'],
                'TrangThai' => Order::STATUS_PENDING,
            ]);

            foreach ($cart['ChiTiet'] as $item) {
                $ticketClass = $this->findTicketClass((int) $item->MaHangVe);
                for ($i = 1; $i <= (int) $item->SoLuong; $i++) {
                    $code = sprintf('VE-%d-%d-%s-%02d', $order->MaDonHang, $item->MaHangVe, strtoupper(Str::random(8)), $i);
                    Ticket::create([
                        'MaDonHang' => $order->MaDonHang,
                        'MaHangVe' => $item->MaHangVe,
                        'MaGhe' => null,
                        'MaSuKien' => $ticketClass->MaSuKien,
                        'MaQR' => hash('sha256', $code),
                        'MaVeDienTu' => $code,
                        'TrangThai' => self::TICKET_ACTIVE,
                        'ThoiGianCheckIn' => null,
                    ]);
                }

                DB::table('hang_ve')->where('MaHangVe', $item->MaHangVe)->increment('SoLuongDaBan', (int) $item->SoLuong);
            }

            TicketHold::where('MaGiuCho', $cart['MaGiuCho'])->update(['TrangThai' => self::HOLD_CONVERTED]);
        });

        return redirect()->route('my-ticket')->with('success', 'Đã tạo đơn đặt vé.');
    }

    public function cancelOrder(Request $request, int $orderId)
    {
        $customerId = $this->customerId($request);
        $order = Order::where('MaDonHang', $orderId)->where('MaKhachHang', $customerId)->first();

        if (!$order || $order->TrangThai === Order::STATUS_PAID) {
            return back()->with('error', 'Không thể hủy đơn này.');
        }

        DB::transaction(function () use ($order, $orderId): void {
            $items = DB::table('ve')
                ->where('MaDonHang', $orderId)
                ->where('TrangThai', '!=', self::TICKET_CANCELLED)
                ->groupBy('MaHangVe')
                ->selectRaw('MaHangVe, COUNT(*) as SoLuong')
                ->get();

            foreach ($items as $item) {
                DB::table('hang_ve')
                    ->where('MaHangVe', $item->MaHangVe)
                    ->update(['SoLuongDaBan' => DB::raw('GREATEST(SoLuongDaBan - ' . (int) $item->SoLuong . ', 0)')]);
            }

            Ticket::where('MaDonHang', $orderId)->update(['TrangThai' => self::TICKET_CANCELLED]);
            $order->update(['TrangThai' => Order::STATUS_CANCELLED]);
        });

        return back()->with('success', 'Đã hủy đơn hàng.');
    }

    private function customerId(Request $request): int
    {
        $customerId = (int) $request->input('MaKhachHang', session('MaKhachHang', 1));
        session(['MaKhachHang' => $customerId]);

        return $customerId;
    }

    private function cartData(int $customerId): ?array
    {
        $this->expireHolds($customerId);
        $hold = $this->activeHold($customerId);

        if (!$hold) {
            return null;
        }

        $items = DB::table('chi_tiet_giu_cho as ct')
            ->join('hang_ve as hv', 'hv.MaHangVe', '=', 'ct.MaHangVe')
            ->join('khu_vuc_su_kien as kv', 'kv.MaKhuVuc', '=', 'hv.MaKhuVuc')
            ->join('su_kien as sk', 'sk.MaSuKien', '=', 'kv.MaSuKien')
            ->where('ct.MaGiuCho', $hold->MaGiuCho)
            ->select('ct.MaHangVe', 'ct.SoLuong', 'hv.TenHangVe', 'hv.GiaVe', 'sk.TenSuKien')
            ->get()
            ->map(function ($item) {
                $item->ThanhTien = (float) $item->GiaVe * (int) $item->SoLuong;
                return $item;
            });

        return [
            'MaGiuCho' => $hold->MaGiuCho,
            'ThoiGianHetHan' => $hold->ThoiGianHetHan,
            'TongTien' => $items->sum('ThanhTien'),
            'ChiTiet' => $items,
        ];
    }

    private function activeHold(int $customerId): ?TicketHold
    {
        return TicketHold::where('MaKhachHang', $customerId)
            ->where('TrangThai', self::HOLD_ACTIVE)
            ->where('ThoiGianHetHan', '>=', now())
            ->latest('MaGiuCho')
            ->first();
    }

    private function expireHolds(int $customerId): void
    {
        TicketHold::where('MaKhachHang', $customerId)
            ->where('TrangThai', self::HOLD_ACTIVE)
            ->where('ThoiGianHetHan', '<', now())
            ->update(['TrangThai' => self::HOLD_EXPIRED]);
    }

    private function findTicketClass(int $ticketClassId): ?object
    {
        return DB::table('hang_ve as hv')
            ->join('khu_vuc_su_kien as kv', 'kv.MaKhuVuc', '=', 'hv.MaKhuVuc')
            ->where('hv.MaHangVe', $ticketClassId)
            ->select('hv.*', 'kv.MaSuKien')
            ->first();
    }

    private function availableTickets(int $ticketClassId): int
    {
        $ticketClass = $this->findTicketClass($ticketClassId);
        $held = DB::table('giu_cho_ve as gc')
            ->join('chi_tiet_giu_cho as ct', 'ct.MaGiuCho', '=', 'gc.MaGiuCho')
            ->where('ct.MaHangVe', $ticketClassId)
            ->where('gc.TrangThai', self::HOLD_ACTIVE)
            ->where('gc.ThoiGianHetHan', '>=', now())
            ->sum('ct.SoLuong');

        return (int) $ticketClass->SoLuongMoBan - (int) $ticketClass->SoLuongDaBan - (int) $held;
    }
}
