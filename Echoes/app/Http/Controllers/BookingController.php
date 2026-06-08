<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Ticket;
use App\Models\TicketHold;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    private const HOLD_ACTIVE = 'DangGiuCho';
    private const HOLD_EXPIRED = 'DaHetHan';
    private const HOLD_CONVERTED = 'DaThanhToan';
    private const TICKET_ACTIVE = 'ChoSuDung';
    private const TICKET_CANCELLED = 'DaHuy';

    public function addToCart(Request $request): JsonResponse
    {
        $data = $request->validate([
            'MaKhachHang' => ['required', 'integer', 'min:1'],
            'MaHangVe' => ['required', 'integer', 'min:1'],
            'SoLuong' => ['nullable', 'integer', 'min:1'],
            'SoPhutGiuCho' => ['nullable', 'integer', 'min:1'],
        ]);

        $customerId = (int) $data['MaKhachHang'];
        $ticketClassId = (int) $data['MaHangVe'];
        $quantity = (int) ($data['SoLuong'] ?? 1);
        $holdMinutes = (int) ($data['SoPhutGiuCho'] ?? 15);

        $ticketClass = $this->findTicketClass($ticketClassId);
        if (!$ticketClass) {
            return $this->error('Hang ve khong ton tai.', 404);
        }

        $now = now();
        if ($now->lt($ticketClass->ThoiGianMoBan) || $now->gt($ticketClass->ThoiGianKetThucBan)) {
            return $this->error('Hang ve khong nam trong thoi gian mo ban.');
        }

        $this->expireHolds($customerId);
        $available = $this->availableTickets($ticketClassId);

        if ($available < $quantity) {
            return $this->error('So luong ve con lai khong du.', 409, [
                'SoLuongConLai' => max(0, $available),
            ]);
        }

        DB::transaction(function () use ($customerId, $ticketClassId, $quantity, $holdMinutes): void {
            $hold = $this->activeHold($customerId);
            $expiresAt = now()->addMinutes($holdMinutes);

            if (!$hold) {
                $hold = TicketHold::create([
                    'MaKhachHang' => $customerId,
                    'ThoiGianBatDau' => now(),
                    'ThoiGianHetHan' => $expiresAt,
                    'TrangThai' => self::HOLD_ACTIVE,
                ]);
            } else {
                $hold->update(['ThoiGianHetHan' => $expiresAt]);
            }

            $detail = DB::table('chi_tiet_giu_cho')
                ->where('MaGiuCho', $hold->MaGiuCho)
                ->where('MaHangVe', $ticketClassId)
                ->first();

            if ($detail) {
                DB::table('chi_tiet_giu_cho')
                    ->where('MaGiuCho', $hold->MaGiuCho)
                    ->where('MaHangVe', $ticketClassId)
                    ->update(['SoLuong' => $detail->SoLuong + $quantity]);
            } else {
                DB::table('chi_tiet_giu_cho')->insert([
                    'MaGiuCho' => $hold->MaGiuCho,
                    'MaHangVe' => $ticketClassId,
                    'SoLuong' => $quantity,
                ]);
            }
        });

        return response()->json([
            'message' => 'Da them ve vao gio hang.',
            'data' => $this->cartData($customerId),
        ]);
    }

    public function cart(int $customerId): JsonResponse
    {
        if ($customerId <= 0) {
            return $this->error('MaKhachHang khong hop le.');
        }

        return response()->json(['data' => $this->cartData($customerId)]);
    }

    public function createOrder(Request $request): JsonResponse
    {
        $data = $request->validate([
            'MaKhachHang' => ['required', 'integer', 'min:1'],
        ]);

        $customerId = (int) $data['MaKhachHang'];
        $cart = $this->cartData($customerId);

        if (!$cart || empty($cart['ChiTiet'])) {
            return $this->error('Gio hang rong hoac da het han.', 404);
        }

        $orderId = DB::transaction(function () use ($customerId, $cart): int {
            $order = Order::create([
                'MaKhachHang' => $customerId,
                'NgayDat' => now(),
                'TongTien' => $cart['TongTien'],
                'TrangThai' => Order::STATUS_PENDING,
            ]);

            foreach ($cart['ChiTiet'] as $item) {
                $ticketClassId = (int) $item->MaHangVe;
                $quantity = (int) $item->SoLuong;
                $ticketClass = $this->findTicketClass($ticketClassId);
                $availableForThisCart = $this->availableTickets($ticketClassId) + $quantity;

                if (!$ticketClass || $availableForThisCart < $quantity) {
                    throw new \RuntimeException('So luong ve con lai khong du de tao don.');
                }

                for ($i = 1; $i <= $quantity; $i++) {
                    $ticketCode = $this->ticketCode($order->MaDonHang, $ticketClassId, $i);
                    Ticket::create([
                        'MaDonHang' => $order->MaDonHang,
                        'MaHangVe' => $ticketClassId,
                        'MaGhe' => null,
                        'MaSuKien' => $ticketClass->MaSuKien,
                        'MaQR' => hash('sha256', $ticketCode),
                        'MaVeDienTu' => $ticketCode,
                        'TrangThai' => self::TICKET_ACTIVE,
                        'ThoiGianCheckIn' => null,
                    ]);
                }

                DB::table('hang_ve')
                    ->where('MaHangVe', $ticketClassId)
                    ->increment('SoLuongDaBan', $quantity);
            }

            TicketHold::where('MaGiuCho', $cart['MaGiuCho'])->update([
                'TrangThai' => self::HOLD_CONVERTED,
            ]);

            return (int) $order->MaDonHang;
        });

        return response()->json([
            'message' => 'Da tao don dat ve.',
            'data' => $this->orderDetail($orderId),
        ], 201);
    }

    public function cancelOrder(Request $request, int $orderId): JsonResponse
    {
        $customerId = $request->filled('MaKhachHang') ? (int) $request->input('MaKhachHang') : null;
        $order = $this->findOrder($orderId, $customerId);

        if (!$order) {
            return $this->error('Don hang khong ton tai.', 404);
        }

        if ($order->TrangThai === Order::STATUS_CANCELLED) {
            return response()->json([
                'message' => 'Don hang da bi huy truoc do.',
                'data' => $this->orderDetail($orderId),
            ]);
        }

        if ($order->TrangThai === Order::STATUS_PAID) {
            return $this->error('Don hang da thanh toan, khong the huy truc tiep.', 409);
        }

        return $this->cancelOrderInternal($orderId);
    }

    public function history(int $customerId): JsonResponse
    {
        if ($customerId <= 0) {
            return $this->error('MaKhachHang khong hop le.');
        }

        $orders = DB::table('don_hang as dh')
            ->leftJoin('ve as v', 'v.MaDonHang', '=', 'dh.MaDonHang')
            ->where('dh.MaKhachHang', $customerId)
            ->groupBy('dh.MaDonHang', 'dh.MaKhachHang', 'dh.NgayDat', 'dh.TongTien', 'dh.TrangThai')
            ->orderByDesc('dh.NgayDat')
            ->selectRaw('dh.MaDonHang, dh.MaKhachHang, dh.NgayDat, dh.TongTien, dh.TrangThai, COUNT(v.MaVe) as SoLuongVe')
            ->get();

        return response()->json(['data' => $orders]);
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
            ->select('ct.MaHangVe', 'ct.SoLuong', 'hv.TenHangVe', 'hv.GiaVe', 'kv.MaSuKien', 'sk.TenSuKien')
            ->get()
            ->map(function ($item) {
                $item->ThanhTien = (float) $item->GiaVe * (int) $item->SoLuong;
                return $item;
            });

        return [
            'MaGiuCho' => $hold->MaGiuCho,
            'MaKhachHang' => $hold->MaKhachHang,
            'ThoiGianBatDau' => $hold->ThoiGianBatDau,
            'ThoiGianHetHan' => $hold->ThoiGianHetHan,
            'TrangThai' => $hold->TrangThai,
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
        if (!$ticketClass) {
            return 0;
        }

        $held = DB::table('giu_cho_ve as gc')
            ->join('chi_tiet_giu_cho as ct', 'ct.MaGiuCho', '=', 'gc.MaGiuCho')
            ->where('ct.MaHangVe', $ticketClassId)
            ->where('gc.TrangThai', self::HOLD_ACTIVE)
            ->where('gc.ThoiGianHetHan', '>=', now())
            ->sum('ct.SoLuong');

        return (int) $ticketClass->SoLuongMoBan - (int) $ticketClass->SoLuongDaBan - (int) $held;
    }

    private function findOrder(int $orderId, ?int $customerId = null): ?Order
    {
        return Order::query()
            ->where('MaDonHang', $orderId)
            ->when($customerId !== null, fn ($query) => $query->where('MaKhachHang', $customerId))
            ->first();
    }

    private function orderDetail(int $orderId): ?array
    {
        $order = $this->findOrder($orderId);
        if (!$order) {
            return null;
        }

        $data = $order->toArray();
        $data['Ve'] = DB::table('ve as v')
            ->join('hang_ve as hv', 'hv.MaHangVe', '=', 'v.MaHangVe')
            ->join('su_kien as sk', 'sk.MaSuKien', '=', 'v.MaSuKien')
            ->where('v.MaDonHang', $orderId)
            ->orderBy('v.MaVe')
            ->select('v.MaVe', 'v.MaHangVe', 'v.MaSuKien', 'v.MaVeDienTu', 'v.MaQR', 'v.TrangThai', 'hv.TenHangVe', 'hv.GiaVe', 'sk.TenSuKien')
            ->get();

        return $data;
    }

    private function cancelOrderInternal(int $orderId, bool $allowPaid = false): JsonResponse
    {
        $order = $this->findOrder($orderId);
        if (!$order) {
            return $this->error('Don hang khong ton tai.', 404);
        }

        if (!$allowPaid && $order->TrangThai === Order::STATUS_PAID) {
            return $this->error('Don hang da thanh toan, khong the huy truc tiep.', 409);
        }

        DB::transaction(function () use ($orderId, $order): void {
            $soldByTicketClass = DB::table('ve')
                ->where('MaDonHang', $orderId)
                ->where('TrangThai', '!=', self::TICKET_CANCELLED)
                ->groupBy('MaHangVe')
                ->selectRaw('MaHangVe, COUNT(*) as SoLuong')
                ->get();

            foreach ($soldByTicketClass as $row) {
                DB::table('hang_ve')
                    ->where('MaHangVe', $row->MaHangVe)
                    ->update([
                        'SoLuongDaBan' => DB::raw('GREATEST(SoLuongDaBan - ' . (int) $row->SoLuong . ', 0)'),
                    ]);
            }

            Ticket::where('MaDonHang', $orderId)->update(['TrangThai' => self::TICKET_CANCELLED]);
            $order->update(['TrangThai' => Order::STATUS_CANCELLED]);
        });

        return response()->json([
            'message' => 'Da huy don hang.',
            'data' => $this->orderDetail($orderId),
        ]);
    }

    private function ticketCode(int $orderId, int $ticketClassId, int $index): string
    {
        return sprintf('VE-%d-%d-%s-%02d', $orderId, $ticketClassId, strtoupper(Str::random(8)), $index);
    }

    private function error(string $message, int $status = 400, array $errors = []): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'errors' => $errors,
        ], $status);
    }
}