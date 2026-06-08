<?php

namespace App\Http\Controllers;

use App\Mail\OrderPaymentSuccessMail;
use App\Models\DonHang;
use App\Models\ThanhToan;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Throwable;

class PaymentController extends Controller
{
    /** Thá»i gian giá»¯ Ä‘Æ¡n do pháº§n Ä‘áº·t vÃ© táº¡o ra. */
    private const ORDER_HOLD_MINUTES = 5;

    /** Thá»i gian hiá»‡u lá»±c cá»§a má»™t mÃ£ QR ná»™i bá»™. */
    private const QR_EXPIRE_SECONDS = 60;

    /**
     * Trang thanh toÃ¡n tháº­t theo luá»“ng QR ná»™i bá»™ cá»§a Echoes.
     */
    public function show(int $orderId)
    {
        $order = DonHang::with(['khachHang.taiKhoan', 'latestPayment'])->findOrFail($orderId);
         if ($order->TrangThai === 'DaThanhToan') {
        return redirect('/my-ticket')
            ->with('success', 'ÄÆ¡n hÃ ng nÃ y Ä‘Ã£ thanh toÃ¡n thÃ nh cÃ´ng. VÃ© cá»§a báº¡n náº±m trong má»¥c VÃ© cá»§a tÃ´i.');
        }


        $order = $this->expireOrderIfNeeded($order);
        $this->expirePendingPaymentIfNeeded($order->MaDonHang);

        $order->load(['khachHang.taiKhoan', 'latestPayment']);

        $payment = $order->latestPayment;
        $customerAccount = optional($order->khachHang)->taiKhoan;
        $ticketItems = $this->getTicketItems($order->MaDonHang);
        $merchandiseItems = $this->getMerchandiseItems($order->MaDonHang);
        $event = $ticketItems->first();

        $orderDeadlineAt = $this->getOrderDeadline($order);
        $orderRemainingSeconds = $orderDeadlineAt
            ? max(0, $orderDeadlineAt->timestamp - now()->timestamp)
            : 0;

        $qrDeadlineAt = $this->getQrDeadline($payment);
        $qrRemainingSeconds = $qrDeadlineAt
            ? max(0, $qrDeadlineAt->timestamp - now()->timestamp)
            : 0;

        $hasActiveQr = $payment
            && $payment->TrangThai === 'ChoThanhToan'
            && $qrRemainingSeconds > 0;

        $isOrderExpired = $order->TrangThai === 'DaHuy' || $orderRemainingSeconds <= 0;

        $paymentConfig = $this->getPaymentDisplayConfig();

        return view('pages.payment', compact(
            'order',
            'payment',
            'customerAccount',
            'ticketItems',
            'merchandiseItems',
            'event',
            'orderDeadlineAt',
            'orderRemainingSeconds',
            'qrDeadlineAt',
            'qrRemainingSeconds',
            'hasActiveQr',
            'isOrderExpired',
            'paymentConfig'
        ));
    }

    /**
     * Táº¡o mÃ£ QR ná»™i bá»™. KhÃ´ng táº¡o Ä‘Æ¡n hÃ ng má»›i, chá»‰ táº¡o/cáº­p nháº­t giao dá»‹ch thanh toÃ¡n chá».
     */
    public function createQrPayment(Request $request, int $orderId): RedirectResponse
    {
        $request->validate([
            'payment_method' => ['nullable', 'string', 'max:50'],
        ]);

        $order = DonHang::findOrFail($orderId);
        $order = $this->expireOrderIfNeeded($order);

        if ($order->TrangThai === 'DaHuy') {
            return redirect()->route('payment.show', $order->MaDonHang)
                ->with('error', 'ÄÆ¡n hÃ ng Ä‘Ã£ háº¿t thá»i gian giá»¯ vÃ© hoáº·c Ä‘Ã£ bá»‹ há»§y. KhÃ´ng thá»ƒ táº¡o mÃ£ QR thanh toÃ¡n.');
        }

        if ($order->TrangThai === 'DaThanhToan') {
            return redirect()->route('payment.show', $order->MaDonHang)
                ->with('success', 'ÄÆ¡n hÃ ng nÃ y Ä‘Ã£ Ä‘Æ°á»£c thanh toÃ¡n thÃ nh cÃ´ng trÆ°á»›c Ä‘Ã³.');
        }

        $this->expirePendingPaymentIfNeeded($order->MaDonHang);

        $activePayment = ThanhToan::where('MaDonHang', $order->MaDonHang)
            ->where('TrangThai', 'ChoThanhToan')
            ->latest('MaThanhToan')
            ->first();

        if ($activePayment && !$this->isQrExpired($activePayment)) {
            return redirect()->route('payment.show', $order->MaDonHang)
                ->with('success', 'MÃ£ QR thanh toÃ¡n váº«n cÃ²n hiá»‡u lá»±c. Vui lÃ²ng hoÃ n táº¥t thanh toÃ¡n trong thá»i gian cÃ²n láº¡i.');
        }

        $method = $request->input('payment_method', 'ChuyenKhoanQR');
        $transactionCode = $this->makeTransactionCode($order->MaDonHang);

        ThanhToan::create([
            'MaDonHang' => $order->MaDonHang,
            'PhuongThucThanhToan' => $method,
            'SoTien' => $order->TongTien,
            // Vá»›i tráº¡ng thÃ¡i ChoThanhToan, cá»™t nÃ y Ä‘Æ°á»£c dÃ¹ng lÃ m thá»i Ä‘iá»ƒm táº¡o mÃ£ QR.
            // Khi thanh toÃ¡n thÃ nh cÃ´ng/tháº¥t báº¡i, cá»™t nÃ y Ä‘Æ°á»£c cáº­p nháº­t thÃ nh thá»i Ä‘iá»ƒm káº¿t thÃºc giao dá»‹ch.
            'ThoiGianThanhToan' => now(),
            'MaGiaoDich' => $transactionCode,
            'TrangThai' => 'ChoThanhToan',
        ]);

        return redirect()->route('payment.show', $order->MaDonHang)
            ->with('success', 'Echoes Ä‘Ã£ táº¡o mÃ£ QR thanh toÃ¡n. Vui lÃ²ng hoÃ n táº¥t trong 01 phÃºt.');
    }

    /**
     * NgÆ°á»i dÃ¹ng xÃ¡c nháº­n Ä‘Ã£ thanh toÃ¡n QR. Náº¿u cÃ²n hiá»‡u lá»±c 60 giÃ¢y thÃ¬ hoÃ n táº¥t Ä‘Æ¡n hÃ ng.
     */
    public function confirmQrPayment(Request $request, int $orderId): RedirectResponse
{
    $result = 'failed';
    $paidOrder = null;
    $paidPayment = null;
    $ticketItems = collect();

    DB::transaction(function () use ($orderId, &$result, &$paidOrder, &$paidPayment, &$ticketItems) {
        $order = DonHang::lockForUpdate()->findOrFail($orderId);

        if ($order->TrangThai === 'DaThanhToan') {
            $result = 'already_paid';
            return;
        }

        if ($order->TrangThai === 'DaHuy' || $this->shouldExpireOrder($order)) {
            $this->cancelExpiredOrder($order);
            $result = 'order_expired';
            return;
        }

        $payment = ThanhToan::where('MaDonHang', $order->MaDonHang)
            ->where('TrangThai', 'ChoThanhToan')
            ->latest('MaThanhToan')
            ->lockForUpdate()
            ->first();

        if (!$payment) {
            $result = 'missing_payment';
            return;
        }

        if ($this->isQrExpired($payment)) {
            $payment->TrangThai = 'ThatBai';
            $payment->ThoiGianThanhToan = now();
            $payment->save();

            $result = 'qr_expired';
            return;
        }

        // Cáº­p nháº­t giao dá»‹ch thanh toÃ¡n
        $payment->PhuongThucThanhToan = 'ChuyenKhoanQR';
        $payment->SoTien = $order->TongTien; // Chá»‰ thanh toÃ¡n full 100% Ä‘Æ¡n hÃ ng.
        $payment->ThoiGianThanhToan = now();
        $payment->TrangThai = 'ThanhCong';
        $payment->save();

        // Cáº­p nháº­t Ä‘Æ¡n hÃ ng
        $order->TrangThai = 'DaThanhToan';
        $order->save();

        // Cáº­p nháº­t vÃ© trong Ä‘Æ¡n hÃ ng sang tráº¡ng thÃ¡i chá» sá»­ dá»¥ng
        $order->ve()
            ->where('TrangThai', '!=', 'DaHuy')
            ->update(['TrangThai' => 'ChoSuDung']);
        $this->decreaseMerchandiseStock($order->MaDonHang);

        $paidOrder = DonHang::with(['khachHang.taiKhoan'])->find($order->MaDonHang);
        $paidPayment = $payment;
        $ticketItems = $this->getTicketItems($order->MaDonHang);

        $result = 'success';
    });

    if ($result === 'already_paid') {
        return redirect('/my-ticket')
            ->with('success', 'ÄÆ¡n hÃ ng nÃ y Ä‘Ã£ Ä‘Æ°á»£c thanh toÃ¡n thÃ nh cÃ´ng trÆ°á»›c Ä‘Ã³. VÃ© cá»§a báº¡n náº±m trong má»¥c VÃ© cá»§a tÃ´i.');
    }

    if ($result === 'order_expired') {
        return redirect()->route('payment.show', $orderId)
            ->with('error', 'ÄÆ¡n hÃ ng Ä‘Ã£ háº¿t thá»i gian giá»¯ vÃ© 10 phÃºt nÃªn khÃ´ng thá»ƒ thanh toÃ¡n.');
    }

    if ($result === 'missing_payment') {
        return redirect()->route('payment.show', $orderId)
            ->with('error', 'ChÆ°a cÃ³ mÃ£ QR thanh toÃ¡n há»£p lá»‡. Vui lÃ²ng táº¡o mÃ£ QR trÆ°á»›c khi xÃ¡c nháº­n.');
    }

    if ($result === 'qr_expired') {
        return redirect()->route('payment.show', $orderId)
            ->with('error', 'MÃ£ QR Ä‘Ã£ háº¿t hiá»‡u lá»±c sau 01 phÃºt. Vui lÃ²ng táº¡o mÃ£ QR má»›i Ä‘á»ƒ tiáº¿p tá»¥c thanh toÃ¡n.');
    }

    $mailStatus = 'not_sent';

    if ($paidOrder && $paidPayment) {
        $mailStatus = $this->sendPaymentSuccessEmail($paidOrder, $ticketItems, $paidPayment);
    }

    if ($mailStatus !== 'sent') {
        return redirect('/my-ticket')
            ->with('success', 'Thanh toÃ¡n thÃ nh cÃ´ng. VÃ© cá»§a báº¡n Ä‘Ã£ Ä‘Æ°á»£c cáº­p nháº­t trong má»¥c VÃ© cá»§a tÃ´i.')
            ->with('warning', 'Email xÃ¡c nháº­n chÆ°a gá»­i Ä‘Æ°á»£c. Echoes sáº½ cá»‘ gáº¯ng gá»­i láº¡i email xÃ¡c nháº­n trong vÃ i phÃºt tá»›i. Vui lÃ²ng kiá»ƒm tra há»™p thÆ° cá»§a báº¡n sau.');
    }

    return redirect('/my-ticket')
        ->with('success', 'Thanh toÃ¡n thÃ nh cÃ´ng. Echoes Ä‘Ã£ gá»­i email xÃ¡c nháº­n Ä‘áº·t vÃ© cho khÃ¡ch hÃ ng.');
}
    /**
     * ÄÆ°á»£c gá»i báº±ng JavaScript khi Ä‘á»“ng há»“ QR háº¿t 60 giÃ¢y.
     */
    public function expireQrPayment(int $orderId): JsonResponse
    {
        $payment = ThanhToan::where('MaDonHang', $orderId)
            ->where('TrangThai', 'ChoThanhToan')
            ->latest('MaThanhToan')
            ->first();

        if (!$payment) {
            return response()->json([
                'status' => 'no_pending_payment',
                'message' => 'KhÃ´ng cÃ³ giao dá»‹ch chá» thanh toÃ¡n.',
            ]);
        }

        if (!$this->isQrExpired($payment)) {
            return response()->json([
                'status' => 'still_active',
                'message' => 'MÃ£ QR váº«n cÃ²n hiá»‡u lá»±c.',
            ]);
        }

        $payment->TrangThai = 'ThatBai';
        $payment->ThoiGianThanhToan = now();
        $payment->save();

        return response()->json([
            'status' => 'expired',
            'message' => 'MÃ£ QR Ä‘Ã£ háº¿t hiá»‡u lá»±c.',
        ]);
    }

    /**
     * TÆ°Æ¡ng thÃ­ch vá»›i route cÅ© náº¿u trong web.php cÃ²n route create-pending.
     */
    public function createPending(Request $request, int $orderId): RedirectResponse
    {
        return $this->createQrPayment($request, $orderId);
    }

    /**
     * TÆ°Æ¡ng thÃ­ch vá»›i route cÅ© náº¿u trong web.php cÃ²n route mock-success.
     */
    public function mockSuccess(Request $request, int $orderId): RedirectResponse
    {
        return $this->confirmQrPayment($request, $orderId);
    }

    /**
     * TÆ°Æ¡ng thÃ­ch vá»›i route cÅ© náº¿u trong web.php cÃ²n route mock-fail.
     */
    public function mockFail(int $orderId): RedirectResponse
    {
        $this->expirePendingPaymentIfNeeded($orderId, force: true);

        return redirect()->route('payment.show', $orderId)
            ->with('error', 'Giao dá»‹ch thanh toÃ¡n Ä‘Ã£ Ä‘Æ°á»£c chuyá»ƒn sang tráº¡ng thÃ¡i tháº¥t báº¡i.');
    }

    private function getOrderDeadline(DonHang $order): ?Carbon
    {
        if (!$order->NgayDat) {
            return null;
        }

        return Carbon::parse($order->NgayDat)->copy()->addMinutes(self::ORDER_HOLD_MINUTES);
    }

    private function getQrDeadline(?ThanhToan $payment): ?Carbon
    {
        if (!$payment || $payment->TrangThai !== 'ChoThanhToan' || !$payment->ThoiGianThanhToan) {
            return null;
        }

        return Carbon::parse($payment->ThoiGianThanhToan)->copy()->addSeconds(self::QR_EXPIRE_SECONDS);
    }

    private function shouldExpireOrder(DonHang $order): bool
    {
        if ($order->TrangThai !== 'ChoThanhToan') {
            return false;
        }

        $deadlineAt = $this->getOrderDeadline($order);

        return $deadlineAt !== null && now()->greaterThan($deadlineAt);
    }

    private function isQrExpired(ThanhToan $payment): bool
    {
        $deadlineAt = $this->getQrDeadline($payment);

        return $deadlineAt === null || now()->greaterThan($deadlineAt);
    }

    private function expireOrderIfNeeded(DonHang $order): DonHang
    {
        if (!$this->shouldExpireOrder($order)) {
            return $order;
        }

        DB::transaction(function () use ($order) {
            $lockedOrder = DonHang::lockForUpdate()->find($order->MaDonHang);

            if ($lockedOrder && $this->shouldExpireOrder($lockedOrder)) {
                $this->cancelExpiredOrder($lockedOrder);
            }
        });

        return DonHang::with(['khachHang.taiKhoan', 'latestPayment'])->findOrFail($order->MaDonHang);
    }

    private function cancelExpiredOrder(DonHang $order): void
    {
        $order->TrangThai = 'DaHuy';
        $order->save();

        $order->ve()->where('TrangThai', '!=', 'DaSuDung')->update(['TrangThai' => 'DaHuy']);

        ThanhToan::where('MaDonHang', $order->MaDonHang)
            ->where('TrangThai', 'ChoThanhToan')
            ->update([
                'TrangThai' => 'ThatBai',
                'ThoiGianThanhToan' => now(),
                'MaGiaoDich' => DB::raw("COALESCE(MaGiaoDich, CONCAT('EXPIRED-', MaThanhToan))"),
            ]);
    }

    private function expirePendingPaymentIfNeeded(int $orderId, bool $force = false): void
    {
        $payment = ThanhToan::where('MaDonHang', $orderId)
            ->where('TrangThai', 'ChoThanhToan')
            ->latest('MaThanhToan')
            ->first();

        if (!$payment) {
            return;
        }

        if (!$force && !$this->isQrExpired($payment)) {
            return;
        }

        $payment->TrangThai = 'ThatBai';
        $payment->ThoiGianThanhToan = now();
        $payment->save();
    }

    private function getTicketItems(int $orderId): Collection
    {
        return DB::table('ve')
            ->leftJoin('su_kien', 've.MaSuKien', '=', 'su_kien.MaSuKien')
            ->leftJoin('hang_ve', 've.MaHangVe', '=', 'hang_ve.MaHangVe')
            ->leftJoin('khu_vuc_su_kien', 'hang_ve.MaKhuVuc', '=', 'khu_vuc_su_kien.MaKhuVuc')
            ->leftJoin('ghe_ngoi', 've.MaGhe', '=', 'ghe_ngoi.MaGhe')
            ->where('ve.MaDonHang', $orderId)
            ->select([
                've.MaVe',
                've.MaVeDienTu',
                've.MaQR',
                've.TrangThai as TrangThaiVe',
                've.MaGhe',
                'su_kien.MaSuKien',
                'su_kien.TenSuKien',
                'su_kien.AnhBia',
                'su_kien.ThoiGianBatDau',
                'su_kien.ThoiGianKetThuc',
                'su_kien.TrangThai as TrangThaiSuKien',
                'hang_ve.TenHangVe',
                'hang_ve.GiaVe',
                'khu_vuc_su_kien.TenKhuVuc',
                'ghe_ngoi.HangGhe',
                'ghe_ngoi.SoGhe',
            ])
            ->orderBy('ve.MaVe')
            ->get();
    }

    private function getMerchandiseItems(int $orderId): Collection
    {
        return DB::table('ct_don_hang_merchandise as ctm')
            ->join('merchandise as m', 'm.MaMerch', '=', 'ctm.MaMerch')
            ->where('ctm.MaDonHang', $orderId)
            ->select([
                'ctm.MaMerch',
                'ctm.SoLuong',
                'ctm.DonGia',
                'm.TenMerch',
                'm.AnhSanPham',
            ])
            ->orderBy('ctm.MaMerch')
            ->get()
            ->map(function ($item) {
                $item->ThanhTien = (float) $item->DonGia * (int) $item->SoLuong;
                return $item;
            });
    }

    private function decreaseMerchandiseStock(int $orderId): void
    {
        $items = DB::table('ct_don_hang_merchandise')
            ->where('MaDonHang', $orderId)
            ->get();

        foreach ($items as $item) {
            DB::table('merchandise')
                ->where('MaMerch', $item->MaMerch)
                ->update([
                    'SoLuongTon' => DB::raw('GREATEST(SoLuongTon - ' . (int) $item->SoLuong . ', 0)'),
                ]);
        }
    }

    private function sendPaymentSuccessEmail(DonHang $order, Collection $ticketItems, ThanhToan $payment): string
    {
        $email = optional(optional($order->khachHang)->taiKhoan)->Email;

        if (!$email) {
            return 'missing_email';
        }

        try {
            Mail::to($email)->send(new OrderPaymentSuccessMail($order, $ticketItems, $payment));
            return 'sent';
        } catch (Throwable $exception) {
            Log::error('KhÃ´ng gá»­i Ä‘Æ°á»£c email xÃ¡c nháº­n thanh toÃ¡n Echoes', [
                'MaDonHang' => $order->MaDonHang,
                'email' => $email,
                'error' => $exception->getMessage(),
            ]);

            return 'failed';
        }
    }

    private function makeTransactionCode(int $orderId): string
    {
        return 'ECHOES-ORDER-' . $orderId . '-' . now()->format('His') . '-' . Str::upper(Str::random(4));
    }

    private function getPaymentDisplayConfig(): array
    {
        return [
            'brand_name' => env('ECHOES_PAYMENT_BRAND_NAME', 'Echoes'),
            'method_label' => env('ECHOES_PAYMENT_METHOD_LABEL', 'Chuyá»ƒn khoáº£n QR'),
            'qr_image' => env('ECHOES_PAYMENT_QR_IMAGE', 'images/payment/qr-payment.png'),
            'bank_name' => env('ECHOES_PAYMENT_BANK_NAME', 'NgÃ¢n hÃ ng thanh toÃ¡n'),
            'account_name' => env('ECHOES_PAYMENT_ACCOUNT_NAME', 'ECHOES'),
            'account_number' => env('ECHOES_PAYMENT_ACCOUNT_NUMBER', 'Vui lÃ²ng cáº¥u hÃ¬nh trong .env'),
            'note_prefix' => env('ECHOES_PAYMENT_NOTE_PREFIX', 'ECHOES'),
        ];
    }
}