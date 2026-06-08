@php
    $money = fn($amount) => number_format((float) $amount, 0, ',', '.') . 'đ';
    $customer = optional($order->khachHang)->taiKhoan;
    $event = $ticketItems->first();
@endphp
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Echoes - Xác nhận đặt vé thành công</title>
</head>
<body style="margin:0;padding:0;background:#f6f4ef;font-family:Arial,Helvetica,sans-serif;color:#1f2937;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f6f4ef;padding:32px 12px;">
        <tr>
            <td align="center">
                <table width="680" cellpadding="0" cellspacing="0" style="max-width:680px;background:#ffffff;border-radius:24px;overflow:hidden;border:1px solid #eadfd3;">
                    <tr>
                        <td style="background:#111827;padding:30px 34px;color:#ffffff;">
                            <div style="font-size:13px;letter-spacing:2px;text-transform:uppercase;color:#fca5a5;font-weight:bold;">Echoes</div>
                            <h1 style="margin:10px 0 0;font-size:30px;line-height:1.2;">Đặt vé thành công</h1>
                            <p style="margin:12px 0 0;color:#d1d5db;line-height:1.6;">
                                Echoes đã xác nhận thanh toán cho đơn hàng của bạn.
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:30px 34px;">
                            <p style="font-size:16px;line-height:1.7;margin:0 0 18px;">
                                Xin chào <strong>{{ $customer->HoTen ?? $customer->TenDangNhap ?? 'bạn' }}</strong>,
                            </p>
                            <p style="font-size:15px;line-height:1.7;margin:0 0 24px;color:#4b5563;">
                                Cảm ơn bạn đã đặt vé tại <strong>Echoes</strong>. Dưới đây là thông tin đơn hàng đã thanh toán.
                            </p>

                            <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:separate;border-spacing:0 10px;">
                                <tr>
                                    <td style="width:38%;color:#6b7280;font-weight:bold;">Mã đơn hàng</td>
                                    <td style="font-weight:bold;text-align:right;">#{{ $order->MaDonHang }}</td>
                                </tr>
                                <tr>
                                    <td style="color:#6b7280;font-weight:bold;">Mã giao dịch</td>
                                    <td style="font-weight:bold;text-align:right;">{{ $payment->MaGiaoDich ?? '---' }}</td>
                                </tr>
                                <tr>
                                    <td style="color:#6b7280;font-weight:bold;">Sự kiện</td>
                                    <td style="font-weight:bold;text-align:right;">{{ $event->TenSuKien ?? 'Echoes Event' }}</td>
                                </tr>
                                <tr>
                                    <td style="color:#6b7280;font-weight:bold;">Phương thức</td>
                                    <td style="font-weight:bold;text-align:right;">Chuyển khoản QR</td>
                                </tr>
                                <tr>
                                    <td style="color:#6b7280;font-weight:bold;">Tổng tiền</td>
                                    <td style="font-size:22px;font-weight:900;color:#b91c1c;text-align:right;">{{ $money($order->TongTien) }}</td>
                                </tr>
                            </table>

                            <div style="height:1px;background:#eadfd3;margin:24px 0;"></div>

                            <h2 style="font-size:20px;margin:0 0 16px;color:#111827;">Danh sách vé</h2>

                            @if($ticketItems->isEmpty())
                                <div style="background:#f9fafb;border:1px dashed #d1d5db;border-radius:14px;padding:16px;color:#6b7280;line-height:1.6;">
                                    Đơn hàng đã thanh toán thành công. Thông tin vé sẽ được cập nhật trong hệ thống Echoes.
                                </div>
                            @else
                                @foreach($ticketItems as $item)
                                    <div style="border:1px solid #eadfd3;border-radius:16px;padding:16px;margin-bottom:12px;background:#fffaf5;">
                                        <div style="font-weight:900;font-size:16px;margin-bottom:8px;">{{ $item->TenHangVe ?? 'Hạng vé' }}</div>
                                        <div style="font-size:14px;line-height:1.7;color:#4b5563;">
                                            Khu vực: <strong>{{ $item->TenKhuVuc ?? 'Không phân khu' }}</strong><br>
                                            Ghế:
                                            <strong>
                                                @if($item->HangGhe || $item->SoGhe)
                                                    {{ trim(($item->HangGhe ?? '') . '-' . ($item->SoGhe ?? ''), '-') }}
                                                @else
                                                    Tự do
                                                @endif
                                            </strong><br>
                                            Mã vé: <strong>{{ $item->MaVeDienTu ?? '---' }}</strong><br>
                                            Giá vé: <strong>{{ $money($item->GiaVe ?? 0) }}</strong>
                                        </div>
                                    </div>
                                @endforeach
                            @endif

                            <div style="height:1px;background:#eadfd3;margin:24px 0;"></div>

                            <p style="font-size:14px;line-height:1.7;color:#6b7280;margin:0;">
                                Vui lòng lưu email này để đối chiếu khi cần hỗ trợ. Cảm ơn bạn đã đồng hành cùng <strong>Echoes</strong>.
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="background:#f9fafb;padding:20px 34px;color:#6b7280;font-size:13px;line-height:1.6;text-align:center;">
                            © {{ date('Y') }} Echoes. Email xác nhận được gửi tự động từ hệ thống đặt vé.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
