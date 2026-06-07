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
    /** Thời gian giữ đơn do phần đặt vé tạo ra. */
    private const ORDER_HOLD_MINUTES = 5;

    /** Thời gian hiệu lực của một mã QR nội bộ. */
    private const QR_EXPIRE_SECONDS = 60;

    /**
     * Trang thanh toán thật theo luồng QR nội bộ của Echoes.
     */
    public function show(int $orderId)
    {
        $order = DonHang::with(['khachHang.taiKhoan', 'latestPayment'])->findOrFail($orderId);
         if ($order->TrangThai === 'DaThanhToan') {
        return redirect('/my-ticket')
            ->with('success', 'Đơn hàng này đã thanh toán thành công. Vé của bạn nằm trong mục Vé của tôi.');
        }


        $order = $this->expireOrderIfNeeded($order);
        $this->expirePendingPaymentIfNeeded($order->MaDonHang);

        $order->load(['khachHang.taiKhoan', 'latestPayment']);

        $payment = $order->latestPayment;
        $customerAccount = optional($order->khachHang)->taiKhoan;
        $ticketItems = $this->getTicketItems($order->MaDonHang);
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
     * Tạo mã QR nội bộ. Không tạo đơn hàng mới, chỉ tạo/cập nhật giao dịch thanh toán chờ.
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
                ->with('error', 'Đơn hàng đã hết thời gian giữ vé hoặc đã bị hủy. Không thể tạo mã QR thanh toán.');
        }

        if ($order->TrangThai === 'DaThanhToan') {
            return redirect()->route('payment.show', $order->MaDonHang)
                ->with('success', 'Đơn hàng này đã được thanh toán thành công trước đó.');
        }

        $this->expirePendingPaymentIfNeeded($order->MaDonHang);

        $activePayment = ThanhToan::where('MaDonHang', $order->MaDonHang)
            ->where('TrangThai', 'ChoThanhToan')
            ->latest('MaThanhToan')
            ->first();

        if ($activePayment && !$this->isQrExpired($activePayment)) {
            return redirect()->route('payment.show', $order->MaDonHang)
                ->with('success', 'Mã QR thanh toán vẫn còn hiệu lực. Vui lòng hoàn tất thanh toán trong thời gian còn lại.');
        }

        $method = $request->input('payment_method', 'ChuyenKhoanQR');
        $transactionCode = $this->makeTransactionCode($order->MaDonHang);

        ThanhToan::create([
            'MaDonHang' => $order->MaDonHang,
            'PhuongThucThanhToan' => $method,
            'SoTien' => $order->TongTien,
            // Với trạng thái ChoThanhToan, cột này được dùng làm thời điểm tạo mã QR.
            // Khi thanh toán thành công/thất bại, cột này được cập nhật thành thời điểm kết thúc giao dịch.
            'ThoiGianThanhToan' => now(),
            'MaGiaoDich' => $transactionCode,
            'TrangThai' => 'ChoThanhToan',
        ]);

        return redirect()->route('payment.show', $order->MaDonHang)
            ->with('success', 'Echoes đã tạo mã QR thanh toán. Vui lòng hoàn tất trong 01 phút.');
    }

    /**
     * Người dùng xác nhận đã thanh toán QR. Nếu còn hiệu lực 60 giây thì hoàn tất đơn hàng.
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

        // Cập nhật giao dịch thanh toán
        $payment->PhuongThucThanhToan = 'ChuyenKhoanQR';
        $payment->SoTien = $order->TongTien; // Chỉ thanh toán full 100% đơn hàng.
        $payment->ThoiGianThanhToan = now();
        $payment->TrangThai = 'ThanhCong';
        $payment->save();

        // Cập nhật đơn hàng
        $order->TrangThai = 'DaThanhToan';
        $order->save();

        // Cập nhật vé trong đơn hàng sang trạng thái chờ sử dụng
        $order->ve()
            ->where('TrangThai', '!=', 'DaHuy')
            ->update(['TrangThai' => 'ChoSuDung']);

        $paidOrder = DonHang::with(['khachHang.taiKhoan'])->find($order->MaDonHang);
        $paidPayment = $payment;
        $ticketItems = $this->getTicketItems($order->MaDonHang);

        $result = 'success';
    });

    if ($result === 'already_paid') {
        return redirect('/my-ticket')
            ->with('success', 'Đơn hàng này đã được thanh toán thành công trước đó. Vé của bạn nằm trong mục Vé của tôi.');
    }

    if ($result === 'order_expired') {
        return redirect()->route('payment.show', $orderId)
            ->with('error', 'Đơn hàng đã hết thời gian giữ vé 10 phút nên không thể thanh toán.');
    }

    if ($result === 'missing_payment') {
        return redirect()->route('payment.show', $orderId)
            ->with('error', 'Chưa có mã QR thanh toán hợp lệ. Vui lòng tạo mã QR trước khi xác nhận.');
    }

    if ($result === 'qr_expired') {
        return redirect()->route('payment.show', $orderId)
            ->with('error', 'Mã QR đã hết hiệu lực sau 01 phút. Vui lòng tạo mã QR mới để tiếp tục thanh toán.');
    }

    $mailStatus = 'not_sent';

    if ($paidOrder && $paidPayment) {
        $mailStatus = $this->sendPaymentSuccessEmail($paidOrder, $ticketItems, $paidPayment);
    }

    if ($mailStatus !== 'sent') {
        return redirect('/my-ticket')
            ->with('success', 'Thanh toán thành công. Vé của bạn đã được cập nhật trong mục Vé của tôi.')
            ->with('warning', 'Email xác nhận chưa gửi được. Echoes sẽ cố gắng gửi lại email xác nhận trong vài phút tới. Vui lòng kiểm tra hộp thư của bạn sau.');
    }

    return redirect('/my-ticket')
        ->with('success', 'Thanh toán thành công. Echoes đã gửi email xác nhận đặt vé cho khách hàng.');
}
    /**
     * Được gọi bằng JavaScript khi đồng hồ QR hết 60 giây.
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
                'message' => 'Không có giao dịch chờ thanh toán.',
            ]);
        }

        if (!$this->isQrExpired($payment)) {
            return response()->json([
                'status' => 'still_active',
                'message' => 'Mã QR vẫn còn hiệu lực.',
            ]);
        }

        $payment->TrangThai = 'ThatBai';
        $payment->ThoiGianThanhToan = now();
        $payment->save();

        return response()->json([
            'status' => 'expired',
            'message' => 'Mã QR đã hết hiệu lực.',
        ]);
    }

    /**
     * Tương thích với route cũ nếu trong web.php còn route create-pending.
     */
    public function createPending(Request $request, int $orderId): RedirectResponse
    {
        return $this->createQrPayment($request, $orderId);
    }

    /**
     * Tương thích với route cũ nếu trong web.php còn route mock-success.
     */
    public function mockSuccess(Request $request, int $orderId): RedirectResponse
    {
        return $this->confirmQrPayment($request, $orderId);
    }

    /**
     * Tương thích với route cũ nếu trong web.php còn route mock-fail.
     */
    public function mockFail(int $orderId): RedirectResponse
    {
        $this->expirePendingPaymentIfNeeded($orderId, force: true);

        return redirect()->route('payment.show', $orderId)
            ->with('error', 'Giao dịch thanh toán đã được chuyển sang trạng thái thất bại.');
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
            Log::error('Không gửi được email xác nhận thanh toán Echoes', [
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
            'method_label' => env('ECHOES_PAYMENT_METHOD_LABEL', 'Chuyển khoản QR'),
            'qr_image' => env('ECHOES_PAYMENT_QR_IMAGE', 'images/payment/qr-payment.png'),
            'bank_name' => env('ECHOES_PAYMENT_BANK_NAME', 'Ngân hàng thanh toán'),
            'account_name' => env('ECHOES_PAYMENT_ACCOUNT_NAME', 'ECHOES'),
            'account_number' => env('ECHOES_PAYMENT_ACCOUNT_NUMBER', 'Vui lòng cấu hình trong .env'),
            'note_prefix' => env('ECHOES_PAYMENT_NOTE_PREFIX', 'ECHOES'),
        ];
    }
}
