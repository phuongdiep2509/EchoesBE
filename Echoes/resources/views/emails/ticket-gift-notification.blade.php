<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Echoes - Vé sự kiện được tặng</title>
</head>
<body style="margin:0;background:#f5f1ea;font-family:Arial,Helvetica,sans-serif;color:#111827;">
    <table width="100%" cellpadding="0" cellspacing="0" style="padding:28px 0;background:#f5f1ea;">
        <tr>
            <td align="center">
                <table width="640" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:20px;overflow:hidden;border:1px solid #e8ddce;">
                    <tr>
                        <td style="background:#111827;color:#fff;padding:30px 34px;">
                            <h1 style="margin:0;font-size:26px;line-height:1.3;">Bạn vừa được tặng một vé Echoes</h1>
                            <p style="margin:9px 0 0;color:#d1d5db;font-size:15px;line-height:1.6;">Một người bạn đã gửi tặng bạn vé tham dự sự kiện.</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:30px 34px;">
                            <p style="font-size:16px;line-height:1.7;margin:0 0 18px;">
                                Chào <strong>{{ $gift->TenNguoiNhan }}</strong>,
                            </p>
                            <p style="font-size:15px;line-height:1.7;margin:0 0 22px;">
                                Bạn nhận được một vé sự kiện từ <strong>{{ optional($gift->nguoiTang)->HoTen ?? 'người tặng' }}</strong> trên hệ thống Echoes.
                            </p>

                            <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;border:1px solid #eee;margin:18px 0;">
                                <tr>
                                    <td style="padding:12px 14px;background:#fafafa;border-bottom:1px solid #eee;width:36%;">Sự kiện</td>
                                    <td style="padding:12px 14px;border-bottom:1px solid #eee;"><strong>{{ $ticket->TenSuKien ?? '—' }}</strong></td>
                                </tr>
                                <tr>
                                    <td style="padding:12px 14px;background:#fafafa;border-bottom:1px solid #eee;">Mã vé</td>
                                    <td style="padding:12px 14px;border-bottom:1px solid #eee;">{{ $ticket->MaVeDienTu ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:12px 14px;background:#fafafa;border-bottom:1px solid #eee;">Hạng vé</td>
                                    <td style="padding:12px 14px;border-bottom:1px solid #eee;">{{ $ticket->TenHangVe ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:12px 14px;background:#fafafa;border-bottom:1px solid #eee;">Khu vực / ghế</td>
                                    <td style="padding:12px 14px;border-bottom:1px solid #eee;">
                                        {{ $ticket->TenKhuVuc ?? '—' }}
                                        @if($ticket && ($ticket->HangGhe || $ticket->SoGhe))
                                            - Ghế {{ trim(($ticket->HangGhe ?? '') . ($ticket->SoGhe ?? '')) }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:12px 14px;background:#fafafa;border-bottom:1px solid #eee;">Thời gian</td>
                                    <td style="padding:12px 14px;border-bottom:1px solid #eee;">
                                        {{ $ticket && $ticket->ThoiGianBatDau ? \Carbon\Carbon::parse($ticket->ThoiGianBatDau)->format('d/m/Y H:i') : '—' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:12px 14px;background:#fafafa;">Địa điểm</td>
                                    <td style="padding:12px 14px;">{{ $ticket->TenDiaDiem ?? '—' }} {{ $ticket && $ticket->ThanhPho ? '- '.$ticket->ThanhPho : '' }}</td>
                                </tr>
                            </table>

                            @if($gift->LoiChuc)
                                <div style="background:#fff8e6;border-left:4px solid #74070d;padding:14px 16px;margin:20px 0;line-height:1.7;">
                                    <strong>Lời nhắn:</strong><br>
                                    {{ $gift->LoiChuc }}
                                </div>
                            @endif

                            <p style="text-align:center;margin:28px 0;">
                                <a href="{{ $receiveUrl }}" style="display:inline-block;background:#74070d;color:#ffffff;text-decoration:none;padding:14px 24px;border-radius:999px;font-weight:bold;">Xác nhận nhận vé</a>
                            </p>

                            <p style="font-size:13px;color:#6b7280;line-height:1.7;">
                                Nếu nút không hoạt động, hãy sao chép liên kết sau và mở trên trình duyệt:<br>
                                <span style="word-break:break-all;color:#74070d;">{{ $receiveUrl }}</span>
                            </p>

                            <p style="font-size:13px;color:#6b7280;line-height:1.6;margin-top:16px;">
                                ⚠️ Mã QR vé sẽ được hiển thị sau khi bạn <strong>xác nhận nhận vé</strong> qua nút trên. Vui lòng không chia sẻ link xác nhận với người khác.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="background:#f9fafb;color:#6b7280;font-size:13px;text-align:center;padding:20px 34px;">
                            Echoes - Hệ thống mua bán vé sự kiện
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
