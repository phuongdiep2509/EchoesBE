<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; background: #f5f5f5; margin: 0; padding: 20px; }
        .card { max-width: 520px; margin: 0 auto; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,.1); }
        .header { background: #74070d; padding: 32px 24px; text-align: center; }
        .header h1 { color: #f3e3b2; margin: 0; font-size: 24px; }
        .body { padding: 32px 24px; }
        .body p { color: #444; line-height: 1.7; margin: 0 0 16px; }
        .btn { display: inline-block; background: #74070d; color: #fff !important; padding: 14px 32px; border-radius: 8px; text-decoration: none; font-weight: bold; font-size: 15px; margin: 8px 0 20px; }
        .note { font-size: 13px; color: #888; border-top: 1px solid #eee; padding-top: 16px; margin-top: 8px; }
        .footer { background: #f9f9f9; padding: 16px 24px; text-align: center; font-size: 12px; color: #aaa; }
    </style>
</head>
<body>
<div class="card">
    <div class="header">
        <h1>🎵 Echoes</h1>
    </div>
    <div class="body">
        <p>Xin chào <strong>{{ $taiKhoan->HoTen }}</strong>,</p>
        <p>Chúng tôi nhận được yêu cầu đặt lại mật khẩu cho tài khoản <strong>{{ $taiKhoan->Email }}</strong>.</p>
        <p>Bấm nút bên dưới để đặt lại mật khẩu:</p>
        <a href="{{ $resetUrl }}" class="btn">Đặt lại mật khẩu</a>
        <p class="note">
            ⏱ Liên kết có hiệu lực trong <strong>{{ $expiry }} phút</strong>.<br>
            Nếu bạn không yêu cầu đặt lại mật khẩu, hãy bỏ qua email này.
        </p>
    </div>
    <div class="footer">© {{ date('Y') }} Echoes. Tất cả quyền được bảo lưu.</div>
</div>
</body>
</html>
