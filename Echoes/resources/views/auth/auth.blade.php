<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Đăng nhập / Đăng ký — Echoes</title>
    <link rel="icon" href="{{ asset('assets/images/index/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/SignUp_LogIn_Form.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/SignUp_login_formRe.css') }}">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        body { background: #f5f5f5; }
        .error-msg { color: #e74c3c; font-size: 13px; margin: 4px 0 0; display: block; }
        .form-box.login .error-msg  { color: #c0392b; }
        .form-box.register .error-msg { color: #ffcccc; }
        .alert-error {
            background: rgba(231,76,60,0.15);
            border: 1px solid #e74c3c;
            color: #c0392b;
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 10px;
            text-align: center;
        }
        .form-box.register .alert-error { color: #ffcccc; border-color: #ffaaaa; background: rgba(255,100,100,0.15); }
    </style>
</head>
<body>

<div class="container {{ $tab === 'register' ? 'active' : '' }}" id="authContainer">

    {{-- ─── ĐĂNG NHẬP ─────────────────────────────────── --}}
    <div class="form-box login" aria-hidden="{{ $tab === 'register' ? 'true' : 'false' }}">
        <form id="loginForm" action="{{ route('login') }}" method="POST" novalidate>
            @csrf
            <h1>Đăng nhập</h1>

            @if(session('error'))
                <div class="alert-error">{{ session('error') }}</div>
            @endif
            @if($errors->has('login'))
                <div class="alert-error">{{ $errors->first('login') }}</div>
            @endif

            <div class="input-box">
                <input type="text" name="login" placeholder="Tên người dùng hoặc Email"
                       value="{{ old('login') }}" required autocomplete="username">
                <i class='bx bxs-user' aria-hidden="true"></i>
            </div>

            <div class="input-box">
                <input type="password" name="password" placeholder="Mật khẩu" required autocomplete="current-password">
                <i class='bx bxs-lock-alt'></i>
                <i class="bx bx-show toggle-password" title="Hiện/ẩn mật khẩu" aria-hidden="true"></i>
            </div>

            <div style="display:flex; justify-content:space-between; align-items:center; margin: -5px 0 15px;">
                <label style="font-size:13px; display:flex; align-items:center; gap:5px; cursor:pointer;">
                    <input type="checkbox" name="remember"> Ghi nhớ đăng nhập
                </label>
                <div class="forgot-link" style="margin:0;">
                    <a href="#" id="forgotLink">Quên mật khẩu?</a>
                </div>
            </div>

            <button type="submit" class="btn">Đăng nhập</button>

            <p>Hoặc đăng nhập bằng</p>
            <div class="social-icons">
                <a href="{{ route('auth.google') }}" aria-label="Đăng nhập bằng Google">
                    <i class='bx bxl-google'></i> Google
                </a>
            </div>
        </form>
    </div>

    {{-- ─── ĐĂNG KÝ ────────────────────────────────────── --}}
    <div class="form-box register" aria-hidden="{{ $tab === 'register' ? 'false' : 'true' }}">
        <form id="registerForm" action="{{ route('register') }}" method="POST" novalidate>
            @csrf
            <h1>Đăng ký</h1>

            @if($errors->has('username') || $errors->has('name') || $errors->has('email') || $errors->has('password'))
                <div class="alert-error">
                    @foreach($errors->all() as $err)
                        <div>{{ $err }}</div>
                    @endforeach
                </div>
            @endif

            <div class="input-box">
                <input type="text" name="username" placeholder="Tên người dùng (không dấu)"
                       value="{{ old('username') }}" required>
                <i class='bx bxs-user' aria-hidden="true"></i>
            </div>

            <div class="input-box">
                <input type="text" name="name" placeholder="Họ và tên"
                       value="{{ old('name') }}" required>
                <i class='bx bxs-id-card' aria-hidden="true"></i>
            </div>

            <div class="input-box">
                <input type="email" name="email" placeholder="Email"
                       value="{{ old('email') }}" required>
                <i class='bx bxs-envelope' aria-hidden="true"></i>
            </div>

            <div class="input-box">
                <input type="password" name="password" placeholder="Mật khẩu" id="regPassword" required autocomplete="new-password">
                <i class='bx bxs-lock-alt' aria-hidden="true"></i>
                <i class="bx bx-show toggle-password" title="Hiện/ẩn mật khẩu" aria-hidden="true"></i>
            </div>

            <div class="input-box">
                <input type="password" name="password_confirmation" placeholder="Xác nhận mật khẩu" required autocomplete="new-password">
                <i class='bx bxs-lock-alt' aria-hidden="true"></i>
                <i class="bx bx-show toggle-password" title="Hiện/ẩn mật khẩu" aria-hidden="true"></i>
            </div>

            <div class="password-validation-box error-message" id="pwdValidation">
                <p class="error-title" id="pwdTitle">❌ Mật khẩu chưa hợp lệ</p>
                <ul>
                    <li data-rule="length"><span class="check-icon">✗</span> Từ 8 - 32 ký tự</li>
                    <li data-rule="lower_digit"><span class="check-icon">✗</span> Bao gồm chữ thường và số</li>
                    <li data-rule="special"><span class="check-icon">✗</span> Bao gồm ký tự đặc biệt (!,@,$,%,...)</li>
                    <li data-rule="upper"><span class="check-icon">✗</span> Có ít nhất 1 ký tự in hoa</li>
                </ul>
            </div>

            <button type="submit" class="btn">Đăng ký</button>

            <p>Hoặc đăng ký bằng</p>
            <div class="social-icons">
                <a href="{{ route('auth.google') }}" aria-label="Đăng ký bằng Google">
                    <i class='bx bxl-google'></i> Google
                </a>
            </div>
        </form>
    </div>

    {{-- ─── TOGGLE PANELS ──────────────────────────────── --}}
    <div class="toggle-box" aria-hidden="true">
        <div class="toggle-panel toggle-left">
            <h1>Chào mừng bạn!</h1>
            <p>Bạn chưa có tài khoản?</p>
            <button class="btn register-btn" type="button">Đăng ký</button>
        </div>
        <div class="toggle-panel toggle-right">
            <h1>Chào mừng bạn trở lại!</h1>
            <p>Bạn đã có tài khoản?</p>
            <button class="btn login-btn" type="button">Đăng nhập</button>
        </div>
    </div>
</div>

{{-- ─── MOBILE NAV ──────────────────────────────────── --}}
<div class="mobile-nav" id="mobileNav">
    <button class="btn mobile-login-btn" type="button">Đăng nhập</button>
    <button class="btn mobile-register-btn" type="button">Đăng ký</button>
</div>

{{-- ─── QUÊN MẬT KHẨU MODAL ────────────────────────── --}}
<div class="forgot-modal" id="forgotModal">
    <div class="form-box forgot-form">
        <button class="close-btn" id="closeForgotBtn" aria-label="Đóng">&times;</button>
        <form id="forgotForm">
            <h1>Quên mật khẩu</h1>
            <p>Nhập email để nhận liên kết đặt lại mật khẩu.</p>
            <div class="input-box">
                <input type="email" name="forgot_email" placeholder="Email" required>
                <i class='bx bxs-envelope'></i>
            </div>
            <button type="submit" class="btn send-email-btn">GỬI</button>
        </form>
        <div id="forgotSuccessMessage" style="display:none; text-align:center; padding: 20px 0;">
            <i class='bx bxs-check-circle' style="font-size:50px;color:#38c172; margin-bottom:10px;display:block;"></i>
            <h2>Hoàn tất!</h2>
            <p>Liên kết đặt lại mật khẩu đã được gửi đến email của bạn.</p>
            <button id="forgotCloseSuccess" class="btn" style="width:150px; margin-top:10px;">Đóng</button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const container      = document.getElementById('authContainer');
    const registerBtn    = document.querySelector('.register-btn');
    const loginBtn       = document.querySelector('.login-btn');
    const mobileLoginBtn = document.querySelector('.mobile-login-btn');
    const mobileRegBtn   = document.querySelector('.mobile-register-btn');

    // Toggle forms
    function openRegister() {
        container.classList.add('active');
        document.querySelector('.form-box.register').setAttribute('aria-hidden','false');
        document.querySelector('.form-box.login').setAttribute('aria-hidden','true');
    }
    function openLogin() {
        container.classList.remove('active');
        document.querySelector('.form-box.register').setAttribute('aria-hidden','true');
        document.querySelector('.form-box.login').setAttribute('aria-hidden','false');
    }

    registerBtn?.addEventListener('click', openRegister);
    loginBtn?.addEventListener('click', openLogin);
    mobileLoginBtn?.addEventListener('click', openLogin);
    mobileRegBtn?.addEventListener('click', openRegister);

    // Toggle password visibility
    document.querySelectorAll('.toggle-password').forEach(icon => {
        icon.addEventListener('click', () => {
            const input = icon.parentElement.querySelector('input');
            if (!input) return;
            input.type = input.type === 'password' ? 'text' : 'password';
            icon.classList.toggle('bx-show');
            icon.classList.toggle('bx-hide');
        });
    });

    // Password validation (register)
    const pwdInput  = document.getElementById('regPassword');
    const pwdBox    = document.getElementById('pwdValidation');
    const pwdTitle  = document.getElementById('pwdTitle');
    const pwdRules  = pwdBox ? pwdBox.querySelectorAll('li') : [];

    const rules = {
        length:     p => p.length >= 8 && p.length <= 32,
        lower_digit: p => /[a-z]/.test(p) && /\d/.test(p),
        special:    p => /[!@#$%^&*_]/.test(p),
        upper:      p => /[A-Z]/.test(p),
    };

    function validatePassword(pwd) {
        let allValid = true;
        pwdRules.forEach(li => {
            const icon = li.querySelector('.check-icon');
            const ok   = rules[li.dataset.rule]?.(pwd);
            if (ok) {
                icon.textContent = '✓'; li.style.color = '#38c172'; icon.style.color = '#38c172';
            } else {
                icon.textContent = '✗'; li.style.color = '#fff'; icon.style.color = '#fff';
                allValid = false;
            }
        });
        if (allValid) {
            pwdBox.classList.add('valid'); pwdBox.classList.remove('error-message');
            pwdTitle.textContent = '✓ Mật khẩu hợp lệ'; pwdTitle.style.color = '#38c172';
        } else {
            pwdBox.classList.remove('valid'); pwdBox.classList.add('error-message');
            pwdTitle.textContent = '❌ Mật khẩu chưa hợp lệ'; pwdTitle.style.color = '#fff';
        }
    }

    pwdInput?.addEventListener('input', () => validatePassword(pwdInput.value));
    validatePassword(pwdInput?.value || '');

    // Forgot password modal
    const forgotModal   = document.getElementById('forgotModal');
    const forgotForm    = document.getElementById('forgotForm');
    const closeForgotBtn= document.getElementById('closeForgotBtn');
    const forgotSuccess = document.getElementById('forgotSuccessMessage');
    const forgotCloseOk = document.getElementById('forgotCloseSuccess');

    document.getElementById('forgotLink')?.addEventListener('click', e => {
        e.preventDefault();
        forgotModal.style.display = 'flex';
    });
    closeForgotBtn?.addEventListener('click', () => { forgotModal.style.display = 'none'; });
    forgotCloseOk?.addEventListener('click',  () => { forgotModal.style.display = 'none'; });

    forgotForm?.addEventListener('submit', e => {
        e.preventDefault();
        const email = new FormData(forgotForm).get('forgot_email');
        if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            alert('Vui lòng nhập email hợp lệ.'); return;
        }

        const btn = forgotForm.querySelector('.send-email-btn');
        btn.disabled = true;
        btn.textContent = 'Đang gửi...';

        fetch('{{ route("password.forgot") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                    || document.querySelector('input[name="_token"]')?.value || ''
            },
            body: JSON.stringify({ forgot_email: email })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                forgotForm.style.display = 'none';
                forgotSuccess.style.display = 'block';
                forgotForm.reset();
            } else {
                btn.disabled = false;
                btn.textContent = 'GỬI';
                alert(data.message || 'Có lỗi xảy ra. Vui lòng thử lại.');
            }
        })
        .catch(() => {
            btn.disabled = false;
            btn.textContent = 'GỬI';
            alert('Không thể kết nối. Vui lòng thử lại.');
        });
    });

    forgotModal?.addEventListener('click', e => {
        if (e.target === forgotModal) forgotModal.style.display = 'none';
    });
});
</script>
</body>
</html>
