<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Ticket;
use App\Models\TicketHold;
use App\Models\VeTang;
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
            'merchandiseCart' => $this->merchandiseCartData($request),
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

    public function show(Request $request, $event)
    {
        $concert = $this->findEventByKey($event);

        if (!$concert) {
            abort(404);
        }

        $hangVe = $this->getTicketClasses($concert->id);
        $eventShowRoute = $this->resolveEventRouteName($concert->event_type);
        $eventIndexRoute = $this->resolveEventIndexRouteName($concert->event_type);

        return view('pages.booking', compact('concert', 'hangVe', 'eventShowRoute', 'eventIndexRoute'));
    }

    public function store(Request $request, $event)
    {
        $concert = $this->findEventByKey($event);

        if (!$concert) {
            abort(404);
        }

        $data = $request->validate([
            'selected_tickets' => ['required', 'string'],
        ]);

        $selectedTickets = json_decode($data['selected_tickets'], true);
        if (!is_array($selectedTickets) || empty($selectedTickets)) {
            return back()->with('error', 'Vui lòng chọn ít nhất 1 vé.');
        }

        $customerId = $this->customerId($request);
        $preparedTickets = [];

        foreach ($selectedTickets as $item) {
            $ticketId = (int) ($item['ticket_id'] ?? 0);
            $quantity = max(0, (int) ($item['quantity'] ?? 0));
            if ($ticketId <= 0 || $quantity <= 0) {
                continue;
            }

            $ticketClass = $this->findTicketClass($ticketId);
            if (!$ticketClass) {
                return back()->with('error', 'Hạng vé không tồn tại.');
            }

            $available = $this->availableTickets($ticketId);
            if ($available < $quantity) {
                return back()->with('error', 'Số lượng vé còn lại không đủ.');
            }

            $preparedTickets[] = ['ticket_id' => $ticketId, 'quantity' => $quantity];
        }

        if (empty($preparedTickets)) {
            return back()->with('error', 'Vui lòng chọn ít nhất 1 vé.');
        }

        $this->bulkAddTicketsToCart($customerId, $preparedTickets);

        // Lưu thông tin tặng vé vào session nếu người dùng chọn tặng vé
        if ($request->input('is_gift') === '1') {
            $request->session()->put('pending_gift', [
                'TenNguoiNhan'   => $request->input('gift_name', ''),
                'EmailNguoiNhan' => $request->input('gift_email', ''),
                'SdtNguoiNhan'   => $request->input('gift_phone', ''),
                'LoaiThiep'      => $request->input('gift_card_type', ''),
                'LoiChuc'        => $request->input('gift_message', ''),
            ]);
        } else {
            $request->session()->forget('pending_gift');
        }

        return redirect()->route('cart')->with('success', 'Đã thêm vé vào giỏ hàng.');
    }

    private function bulkAddTicketsToCart(int $customerId, array $tickets): void
    {
        $this->expireHolds($customerId);

        $hold = $this->activeHold($customerId);

        if (!$hold) {
            $hold = TicketHold::create([
                'MaKhachHang' => $customerId,
                'ThoiGianBatDau' => now(),
                'ThoiGianHetHan' => now()->addMinutes(10),
                'TrangThai' => self::HOLD_ACTIVE,
            ]);
        }

        DB::transaction(function () use ($hold, $tickets): void {
            foreach ($tickets as $ticket) {
                $detail = DB::table('chi_tiet_giu_cho')
                    ->where('MaGiuCho', $hold->MaGiuCho)
                    ->where('MaHangVe', $ticket['ticket_id'])
                    ->first();

                if ($detail) {
                    DB::table('chi_tiet_giu_cho')
                        ->where('MaGiuCho', $hold->MaGiuCho)
                        ->where('MaHangVe', $ticket['ticket_id'])
                        ->update(['SoLuong' => (int) $detail->SoLuong + (int) $ticket['quantity']]);
                } else {
                    DB::table('chi_tiet_giu_cho')->insert([
                        'MaGiuCho' => $hold->MaGiuCho,
                        'MaHangVe' => $ticket['ticket_id'],
                        'SoLuong' => (int) $ticket['quantity'],
                    ]);
                }
            }
        });
    }

    private function getTicketClasses(int $eventId)
    {
        return DB::table('hang_ve as hv')
            ->join('khu_vuc_su_kien as kv', 'kv.MaKhuVuc', '=', 'hv.MaKhuVuc')
            ->where('kv.MaSuKien', $eventId)
            ->select([
                'hv.MaHangVe as ticket_id',
                'hv.TenHangVe as ticket_name',
                'kv.TenKhuVuc as zone',
                'hv.GiaVe as price',
            ])
            ->get();
    }

    private function eventQuery()
    {
        return DB::table('su_kien as sk')
            ->leftJoin('dia_diem_to_chuc as dd', 'sk.MaDiaDiem', '=', 'dd.MaDiaDiem')
            ->leftJoin('loai_su_kien as ls', 'sk.MaLoaiSuKien', '=', 'ls.MaLoaiSuKien')
            ->select([
                'sk.MaSuKien as id',
                'sk.TenSuKien as title',
                'sk.AnhBia as image',
                'sk.AnhSeatMap',
                'sk.MoTa as description',
                'sk.DiemNoiBat as highlights',
                'sk.ThoiGianBatDau as event_date',
                'sk.ThoiGianKetThuc as event_end',
                'sk.TrangThai as status',
                'dd.TenDiaDiem as location',
                'dd.DiaChiChiTiet as address',
                'dd.ThanhPho as city',
                'ls.TenLoai as event_type',
            ]);
    }

    private function findEventByKey($key): ?object
    {
        if (ctype_digit((string) $key)) {
            return $this->eventQuery()->where('sk.MaSuKien', (int) $key)->first();
        }

        $needle = Str::slug($key);

        return $this->eventQuery()
            ->whereIn('sk.TrangThai', ['SapDienRa', 'DangMoBan'])
            ->get()
            ->first(function ($item) use ($needle) {
                return Str::contains(Str::slug($item->title), $needle)
                    || Str::contains($needle, Str::slug($item->title));
            });
    }

    private function resolveEventRouteName(?string $eventType): string
    {
        return Str::contains(Str::lower((string) $eventType), 'nhạc')
            ? 'music.show'
            : 'concert.show';
    }

    private function resolveEventIndexRouteName(?string $eventType): string
    {
        return Str::contains(Str::lower((string) $eventType), 'nhạc')
            ? 'music.index'
            : 'concert.index';
    }

    public function addToCart(Request $request)
    {
        $data = $request->validate([
            'MaHangVe' => ['required', 'integer', 'min:1'],
            'SoLuong' => ['required', 'integer', 'min:1'],
        ]);

        $customerId = $this->customerId($request);
        $ticketClass = $this->findTicketClass((int) $data['MaHangVe']);

        if (!$ticketClass) {
            return back()->with('error', 'Hang ve khong ton tai.');
        }

        $available = $this->availableTickets((int) $data['MaHangVe']);

        if ($available < (int) $data['SoLuong']) {
            return back()->with('error', 'So luong ve con lai khong du.');
        }

        DB::transaction(function () use ($data, $customerId): void {
            $this->expireHolds($customerId);
            $hold = $this->activeHold($customerId);

            if (!$hold) {
                $hold = TicketHold::create([
                    'MaKhachHang' => $customerId,
                    'ThoiGianBatDau' => now(),
                    'ThoiGianHetHan' => now()->addMinutes(10),
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
                    ->update(['SoLuong' => (int) $detail->SoLuong + (int) $data['SoLuong']]);
            } else {
                DB::table('chi_tiet_giu_cho')->insert([
                    'MaGiuCho' => $hold->MaGiuCho,
                    'MaHangVe' => $data['MaHangVe'],
                    'SoLuong' => (int) $data['SoLuong'],
                ]);
            }
        });

        return redirect()->route('cart')->with('success', 'Da them ve vao gio hang.');
    }

    public function createOrder(Request $request)
    {
        $customerId = $this->customerId($request);
        $cart = $this->cartData($customerId);
        $merchandiseCart = $this->merchandiseCartData($request);
        $hasTicketItems = $cart && $cart['ChiTiet']->isNotEmpty();
        $hasMerchandiseItems = $merchandiseCart['ChiTiet']->isNotEmpty();

        if (!$hasTicketItems && !$hasMerchandiseItems) {
            return redirect()->route('cart')->with('error', 'Gio hang rong hoac da het han.');
        }

        $pendingGift = $request->session()->get('pending_gift');
        $accountId = auth()->check() ? (auth()->user()->MaTaiKhoan ?? null) : null;

        $orderId = DB::transaction(function () use ($customerId, $cart, $merchandiseCart, $hasTicketItems, $hasMerchandiseItems, $pendingGift, $accountId): int {
            $order = Order::create([
                'MaKhachHang' => $customerId,
                'NgayDat' => now(),
                'TongTien' => (float) ($cart['TongTien'] ?? 0) + (float) $merchandiseCart['TongTien'],
                'TrangThai' => Order::STATUS_PENDING,
            ]);

            $createdTicketIds = [];

            if ($hasTicketItems) {
                foreach ($cart['ChiTiet'] as $item) {
                    $ticketClass = $this->findTicketClass((int) $item->MaHangVe);

                    if (!$ticketClass) {
                        throw new \RuntimeException('Khong tim thay hang ve khi tao don hang.');
                    }

                    for ($i = 1; $i <= (int) $item->SoLuong; $i++) {
                        $code = sprintf('VE-%d-%d-%s-%02d', $order->MaDonHang, $item->MaHangVe, strtoupper(Str::random(8)), $i);
                        $ticket = Ticket::create([
                            'MaDonHang' => $order->MaDonHang,
                            'MaHangVe' => $item->MaHangVe,
                            'MaGhe' => null,
                            'MaSuKien' => $ticketClass->MaSuKien,
                            'MaQR' => hash('sha256', $code),
                            'MaVeDienTu' => $code,
                            'TrangThai' => self::TICKET_ACTIVE,
                            'ThoiGianCheckIn' => null,
                        ]);
                        $createdTicketIds[] = $ticket->MaVe;
                    }

                    DB::table('hang_ve')
                        ->where('MaHangVe', $item->MaHangVe)
                        ->increment('SoLuongDaBan', (int) $item->SoLuong);
                }

                // Nếu có thông tin tặng vé, tạo VeTang cho từng vé vừa tạo
                if ($pendingGift && !empty($pendingGift['EmailNguoiNhan']) && $accountId && !empty($createdTicketIds)) {
                    foreach ($createdTicketIds as $veId) {
                        VeTang::create([
                            'MaVe'                 => $veId,
                            'MaTaiKhoanNguoiTang'  => $accountId,
                            'TenNguoiNhan'         => $pendingGift['TenNguoiNhan'],
                            'EmailNguoiNhan'       => $pendingGift['EmailNguoiNhan'],
                            'SdtNguoiNhan'         => $pendingGift['SdtNguoiNhan'] ?: null,
                            'LoaiThiep'            => $pendingGift['LoaiThiep'] ?: null,
                            'LoiChuc'              => $pendingGift['LoiChuc'] ?: null,
                            'TrangThai'            => 'DangChoNhan',
                            'TokenNhanVe'          => Str::random(64),
                            'ThoiGianTang'         => now(),
                            'ThoiGianNhan'         => null,
                        ]);
                    }
                }

                // KHÔNG set HOLD_CONVERTED ở đây — hold chỉ được đóng sau khi thanh toán thành công.
            }

            if ($hasMerchandiseItems) {
                foreach ($merchandiseCart['ChiTiet'] as $item) {
                    DB::table('ct_don_hang_merchandise')->insert([
                        'MaDonHang' => $order->MaDonHang,
                        'MaMerch' => $item->MaMerch,
                        'SoLuong' => $item->SoLuong,
                        'DonGia' => $item->GiaBan,
                    ]);
                }
            }

            return (int) $order->MaDonHang;
        });

        // Xóa pending_gift khỏi session sau khi đã xử lý
        $request->session()->forget('pending_gift');

        return redirect()->route('payment.show', $orderId)
            ->with('success', 'Da tao don hang. Vui long hoan tat thanh toan.');
    }

    public function removeTicketFromCart(Request $request, int $ticketClassId)
    {
        $customerId = $this->customerId($request);
        $hold = $this->activeHold($customerId);

        if (!$hold) {
            return redirect()->route('cart')->with('error', 'Không có giỏ hàng đang hoạt động.');
        }

        DB::table('chi_tiet_giu_cho')
            ->where('MaGiuCho', $hold->MaGiuCho)
            ->where('MaHangVe', $ticketClassId)
            ->delete();

        // Nếu giỏ hàng vé trống sau khi xóa, hủy luôn hold
        $remaining = DB::table('chi_tiet_giu_cho')
            ->where('MaGiuCho', $hold->MaGiuCho)
            ->count();

        if ($remaining === 0) {
            $hold->update(['TrangThai' => self::HOLD_EXPIRED]);
        }

        return redirect()->route('cart')->with('success', 'Đã xóa hạng vé khỏi giỏ hàng.');
    }

    public function cancelOrder(Request $request, int $orderId)
    {
        $customerId = $this->customerId($request);
        $order = Order::where('MaDonHang', $orderId)->where('MaKhachHang', $customerId)->first();

        if (!$order || $order->TrangThai === Order::STATUS_PAID) {
            return back()->with('error', 'Khong the huy don nay.');
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

        return back()->with('success', 'Da huy don hang.');
    }

    private function customerId(Request $request): int
    {
        if (auth()->check()) {
            $accountId = auth()->user()->MaTaiKhoan ?? null;
            $customerId = $accountId
                ? DB::table('khach_hang')->where('MaTaiKhoan', $accountId)->value('MaKhachHang')
                : null;

            if ($customerId) {
                session(['MaKhachHang' => (int) $customerId]);
                return (int) $customerId;
            }
        }

        $customerId = (int) session('MaKhachHang', 1);
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

    private function merchandiseCartData(Request $request): array
    {
        $cart = $request->session()->get('merchandise_cart', []);
        $ids = collect($cart)->pluck('MaMerch')->filter()->map(fn ($id) => (int) $id)->values();

        if ($ids->isEmpty()) {
            return ['TongTien' => 0, 'ChiTiet' => collect()];
        }

        $products = DB::table('merchandise')
            ->whereIn('MaMerch', $ids)
            ->get()
            ->keyBy('MaMerch');

        $items = $ids->map(function (int $id) use ($cart, $products) {
            $product = $products->get($id);

            if (!$product) {
                return null;
            }

            $quantity = max(1, (int) ($cart[$id]['SoLuong'] ?? 1));
            $item = clone $product;
            $item->SoLuong = $quantity;
            $item->ThanhTien = (float) $item->GiaBan * $quantity;

            return $item;
        })->filter()->values();

        return ['TongTien' => $items->sum('ThanhTien'), 'ChiTiet' => $items];
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
}