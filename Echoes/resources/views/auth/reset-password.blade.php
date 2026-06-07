<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt lại mật khẩu — Echoes</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }
        .card { background: #74070d; color: #fff; border-radius: 16px; padding: 40px 36px; width: 100%; max-width: 420px; box-shadow: 0 8px 32px rgba(0,0,0,.2); }
        .logo { text-align: center; font-size: 28px; font-weight: bold; color: #f3e3b2; margin-bottom: 8px; }
        h2 { text-align: center; font-size: 22px; margin-bottom: 24px; color: #f3e3b2; }
        .form-group { margin-bottom: 16px; }
        label { display: block; font-size: 13px; margin-bottom: 6px; opacity: .85; }
        .input-wrap { position: relative; }
        input[type=password], input[type=text] { width: 100%; padding: 11px 44px 11px 14px; background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.3); border-radius: 8px; color: #fff; font-size: 15px; outline: none; }
        input::placeholder { color: rgba(255,255,255,.5); }
        input:focus { border-color: #f3e3b2; }
        .eye { position: absolute; right: 14px; top: 50%; transform: translateY(-50%); cursor: pointer; color: rgba(255,255,255,.6); font-size: 18px; }
        .btn { width: 100%; padding: 13px; background: transparent; border: 2px solid #fff; color: #fff; border-radius: 8px; font-size: 15px; font-weight: bold; cursor: pointer; margin-top: 8px; transition: background .2s; }
        .btn:hover { background: rgba(255,255,255,.1); }
        .alert-error { background: rgba(231,76,60,.2); border: 1px solid #e74c3c; color: #ffcccc; padding: 10px 14px; border-radius: 8px; font-size: 13px; margin-bottom: 16px; }
        .pwd-rules { background: transparent; border: 1px solid rgba(255,255,255,.4); border-radius: 8px; padding: 8px 12px; margin-top: 8px; font-size: 12px; }
        .pwd-rules li { display: flex; align-items: center; gap: 6px; margin-bottom: 2px; list-style: none; color: #fff; }
        .pwd-rules.valid { border-color: #38c172; }
        .back-link { text-align: center; margin-top: 16px; }
        .back-link a { color: #f3e3b2; font-size: 13px; text-decoration: none; }
        .back-link a:hover { text-decoration: underline; }
    </style>
</head>
<body>
<div class="card">
    <div class="logo">🎵 Echoes</div>
    <h2>Đặt lại mật khẩu</h2>

    @if($errors->any())
        <div class="alert-error">
            @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
        </div>
    @endif

    <form action="{{ route('password.reset') }}" method="POST">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="form-group">
            <label>Mật khẩu mới</label>
            <div class="input-wrap">
                <input type="password" name="MatKhau" id="newPwd" placeholder="Mật khẩu mới" required>
                <i class="bx bx-show eye" onclick="togglePwd('newPwd', this)"></i>
            </div>
            <ul class="pwd-rules" id="pwdRules">
                <li data-rule="length"><span>✗</span> Từ 8–32 ký tự</li>
                <li data-rule="lower_digit"><span>✗</span> Chữ thường và số</li>
                <li data-rule="special"><span>✗</span> Ký tự đặc biệt (!@$%...)</li>
                <li data-rule="upper"><span>✗</span> Ít nhất 1 chữ in hoa</li>
            </ul>
        </div>

        <div class="form-group">
            <label>Xác nhận mật khẩu</label>
            <div class="input-wrap">
                <input type="password" name="MatKhau_confirmation" id="confirmPwd" placeholder="Nhập lại mật khẩu" required>
                <i class="bx bx-show eye" onclick="togglePwd('confirmPwd', this)"></i>
            </div>
        </div>

        <button type="submit" class="btn">Đặt lại mật khẩu</button>
    </form>

    <div class="back-link">
        <a href="{{ route('home') }}">← Về trang chủ</a>
    </div>
</div>

<script>
function togglePwd(id, icon) {
    const inp = document.getElementById(id);
    inp.type = inp.type === 'password' ? 'text' : 'password';
    icon.classList.toggle('bx-show');
    icon.classList.toggle('bx-hide');
}

const rules = {
    length:      p => p.length >= 8 && p.length <= 32,
    lower_digit: p => /[a-z]/.test(p) && /\d/.test(p),
    special:     p => /[!@#$%^&*_]/.test(p),
    upper:       p => /[A-Z]/.test(p),
};

document.getElementById('newPwd').addEventListener('input', function () {
    const pwd = this.value;
    let allOk = true;
    document.querySelectorAll('#pwdRules li').forEach(li => {
        const ok = rules[li.dataset.rule]?.(pwd);
        li.querySelector('span').textContent = ok ? '✓' : '✗';
        li.style.color = ok ? '#38c172' : '#fff';
        if (!ok) allOk = false;
    });
    document.getElementById('pwdRules').classList.toggle('valid', allOk);
});
</script>
</body>
</html>
